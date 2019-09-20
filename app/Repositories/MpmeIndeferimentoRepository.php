<?php
namespace App\Repositories;
use DB;
use App\MpmeIndeferimento;
use Auth;
use App\MpmeFundoGarantia;
use App\MpmeMovimentacaoControleCapital;
use Carbon\Carbon;
class MpmeIndeferimentoRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmeIndeferimento::class);
    }


  public static function indeferir($request){

    DB::beginTransaction();

    $importadores = new MpmeIndeferimento();
    $importadores->OPER_MPME   = $request->OPER_MPME;
    $importadores->GARANTIDO   = $request->NM_USUARIO;
    $importadores->ID_OPER   = $request->ID_OPER;
    $importadores->IMPORTADOR   = $request->RAZAO_SOCIAL;
    $importadores->VL_CRED_CONCEDIDO   = converte_float($request->VL_CRED_CONCEDIDO);
    $importadores->DT_RECOMENDACAO   = $request->DT_RECOMENDACAO;
    $importadores->DS_RECOMENDACAO   = $request->DS_RECOMENDACAO;
    $importadores->FL_MOMENTO   = $request->FL_MOMENTO;
    $importadores->DS_PARECER   = $request->DS_PARECER;

    if($importadores->save())
    {
        //CONTROLANDO SALDO DO CAPITAL DA ABGF

        $fundos = MpmeFundoGarantia::where('IN_ATIVO', '=', 'SIM')->get(['ID_MPME_FUNDO_GARANTIA']);

        $movimentacao = MpmeMovimentacaoControleCapital::where('ID_OPER', $request->ID_OPER)->orderByDesc('ID_MPME_FUNDO_GARANTIA')->first();

        foreach($fundos as $fundo) {
            $rsSaldo = new MpmeMovimentacaoControleCapital();
            $rsSaldo->ID_MPME_FUNDO_GARANTIA = $fundo->ID_MPME_FUNDO_GARANTIA;
            $rsSaldo->ID_MPME_CONTROLE_CAPITAL = $movimentacao->ID_MPME_CONTROLE_CAPITAL;
            $rsSaldo->ID_MPME_FUNDO_PRINCIPAL = $movimentacao->ID_MPME_FUNDO_PRINCIPAL;
            $rsSaldo->ID_MOEDA = $movimentacao->ID_MOEDA;
            $rsSaldo->VL_TAXA_CAMBIO = $movimentacao->VL_TAXA_CAMBIO;
            $rsSaldo->VL_PERC_FUNDO = $movimentacao->VL_PERC_FUNDO;
            $rsSaldo->VL_TOTAL_REAIS = $movimentacao->VL_TOTAL_REAIS;
            $rsSaldo->IN_SALDO_SUFICIENTE = $movimentacao->IN_SALDO_SUFICIENTE;
            $rsSaldo->ID_MPME_ALCADA = $request->ID_MPME_ALCADA;
            $rsSaldo->ID_OPER = $request->ID_OPER;
            $rsSaldo->VL_MOVIVENTACAO = converte_float($request->VL_CRED_CONCEDIDO);
            $rsSaldo->TP_MOVIMENTACAO = 'EXTORNO';
            $rsSaldo->DT_CADASTRO = Carbon::now();
            $rsSaldo->ID_USUARIO_CAD = Auth::User()->ID_USUARIO;



            if (!$rsSaldo->save()) {
                DB::rollback();
                return false;
            }

            DB::commit();

        }

      return true;
    }else{
      return false;
    }

  }


}
