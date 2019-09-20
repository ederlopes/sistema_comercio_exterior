<?php

namespace App;

use App\Repositories\MpmeMovimentacaoControleCapitalRepository;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;
use DB;
use Carbon\Carbon;

class MpmeAprovacaoValorAlcada extends Model
{
    protected $table = 'MPME_APROVACAO_VALOR_ALCADA';
    protected $primaryKey  = 'ID_APROVACAO_VALOR_ALCADA';
    public $timestamps = false;
    protected $guarded = array();

    CONST PRAZO_VALIDADE_CG         = 90;
    CONST PRAZO_VALIDADE_APOLICE    = 180;
    CONST ALCADA_ANALISTA           = 2;


    public function mpme_alcada()
    {
        return $this->belongsTo(Alcada::class, 'ID_MPME_ALCADA', 'ID_MPME_ALCADA');
    }

    public static function gravarAprovacaoAlcada( $arrayDados )
    {

        $id_oper              = $arrayDados['ID_OPER'];
        $id_mpme_alcada       = $arrayDados['ID_MPME_ALCADA'];

        if ( $id_oper > 0 )
        {
            $aprovar_alcada = MpmeAprovacaoValorAlcada::where("ID_OPER", '=', $id_oper)
                                   ->where("ID_MPME_ALCADA", '=',$id_mpme_alcada)
                                   ->where("IN_DECISAO", '=',1)
                                    ->first();


            if (!isset($aprovar_alcada))
            {
                $aprovar_alcada = new MpmeAprovacaoValorAlcada();
            }

        }else{
            $aprovar_alcada = new MpmeAprovacaoValorAlcada();
        }

        DB::beginTransaction();

        $aprovar_alcada->ID_OPER                    = $id_oper;
        $aprovar_alcada->ID_MPME_ALCADA             = $id_mpme_alcada;
        $aprovar_alcada->IN_DECISAO                 = $arrayDados['IN_DECISAO'];
        $aprovar_alcada->VL_APROVADO                = converte_float($arrayDados['VL_APROVADO']);
        $aprovar_alcada->TX_OBSERVACAO              = (isset($arrayDados['TX_OBSERVACAO'])) ? $arrayDados['TX_OBSERVACAO']     : NULL;
        $aprovar_alcada->DT_DELIBERACAO             = (isset($arrayDados['DT_DELIBERACAO'])) ? $arrayDados['DT_DELIBERACAO']     : NULL;
        $aprovar_alcada->NU_DELIBERACAO             = (isset($arrayDados['NU_DELIBERACAO'])) ? $arrayDados['NU_DELIBERACAO']     : NULL;
        $aprovar_alcada->ID_MPME_FUNDO_GARANTIA     = (isset($arrayDados['id_mpme_fundo_garantia'])) ? $arrayDados['id_mpme_fundo_garantia']     : NULL;
        $aprovar_alcada->DT_CADASTRO                = Carbon::now();
        $aprovar_alcada->IN_DEVOLVIDA               = 0;
        $aprovar_alcada->ID_USUARIO_CAD             = Auth::User()->ID_USUARIO;

        if (!$aprovar_alcada->save())
        {
            DB::rollback();
            return false;
        }

        //atualizando valor MPME_IMPORTADORES
        $importadores = new ImportadoresModel();
        $importadores->where('ID_OPER', '=', $id_oper )
                        ->update([
                                    'VL_APROVADO'           => converte_float($arrayDados['VL_APROVADO']),
                                    'DT_VALIDADE_OPERACAO'  => Carbon::now()->addDay(self::PRAZO_VALIDADE_CG)
                                ]);

        //atualizando valor MERCADORIA
        $mercadorias = new MercadoriasModel();
        $mercadorias->where('ID_OPER', '=', $id_oper )
                        ->where('NU_DOCUMENTO', '=', '9999999' )
                        ->where('TIPO_VALIDACAO', '=', 'PI' )
                        ->update([
                            'VL_TOTAL' => converte_float($arrayDados['VL_APROVADO'])
                        ]);

        /*
         * COMO FOI FEITO UM APROVISIONAMENO DE SALDO NO INICIO DA ANALISE E CADA ALÇADA TEM A POSSIBILIDADE DE
         * DETERMINAR UM NOVO VALOR, O SISTEMA IRA EXTORNAR O VALOR INICIAL E LANCAR O NOVO VALOR
         */

        if ($arrayDados['IN_DECISAO'] == 1) // = Aprovar
        {
            //CONTROLANDO SALDO DO CAPITAL DA ABGF
            $mpmeMovimentacaoControleCapital        = new MpmeMovimentacaoControleCapitalRepository();
            $rsSaldoExtorno                         = $mpmeMovimentacaoControleCapital
                                                      ->movimentacao_controle_capital($importadores->ID_MPME_FUNDO_GARANTIA, $id_oper,
                                                      $arrayDados['VL_APROVADO'],'EXTORNO');

            if (!$rsSaldoExtorno)
            {
                DB::rollback();
                return false;
            }

            //CONTROLANDO SALDO DO CAPITAL DA ABGF
            $mpmeMovimentacaoControleCapital = new MpmeMovimentacaoControleCapitalRepository();
            $rsSaldo                         = $mpmeMovimentacaoControleCapital
                                               ->movimentacao_controle_capital($importadores->ID_MPME_FUNDO_GARANTIA, $id_oper,
                                                                               $arrayDados['VL_APROVADO'],'DEBITO');

            if (!$rsSaldo)
            {
                DB::rollback();
                return false;
            }
        }



        DB::commit();

        return true;

    }


    public function getValorAprovadoPorAlcada( $id_oper )
    {
        if ( $id_oper =="" )
        {
            return false;
        }

        $recordSetValores = MpmeAprovacaoValorAlcada::select('MPME_APROVACAO_VALOR_ALCADA.*')
                                                    ->where('ID_OPER', '=', $id_oper)
                                                    ->where('IN_DEVOLVIDA', '=', 0)
                                                    ->where('IN_DECISAO', '=', 1)
                                                    ->get();

        foreach ( $recordSetValores as $valores)
        {
            $dados[$valores->ID_MPME_ALCADA] =  [
                                                'NM_ALCADA'     => $valores->mpme_alcada->NO_ALCADA,
                                                'IN_DECISAO'     => $valores->IN_DECISAO,
                                                'VL_APROVADO'    => $valores->VL_APROVADO,
                                                'IN_DELIBERACAO' => $valores->IN_DELIBERACAO,
                                                'DT_DELIBERACAO' => $valores->DT_DELIBERACAO,
                                                'NU_DELIBERACAO' => $valores->NU_DELIBERACAO,
                                                'TX_OBSERVACAO'  => $valores->TX_OBSERVACAO,
                                            ];
        }

        return $dados;

    }


    public function getValorAprovado( $id_oper )
    {
        if ( $id_oper =="" )
        {
            return false;
        }

        $recordSetValores = MpmeAprovacaoValorAlcada::select('MPME_APROVACAO_VALOR_ALCADA.*')
            ->where('ID_OPER', '=', $id_oper)
            ->where('IN_DEVOLVIDA', '=', 0)
            ->where('IN_DECISAO', '=', 1)
            ->orderByDesc('ID_APROVACAO_VALOR_ALCADA')
            ->first();



        return $recordSetValores->VL_APROVADO;

    }


}
