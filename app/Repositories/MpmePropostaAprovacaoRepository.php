<?php

namespace App\Repositories;
use App\MpmePrecoCobertura;
use App\MpmeProposta;
use App\Repositories\MpmePropostaRepository;
use App\MpmePropostaAprovacao;
use Carbon\Carbon;
use DB;
use Auth;
use Session;

class MpmePropostaAprovacaoRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmePropostaAprovacao::class);
    }


  public function salvaPropostaAprovacao($request, $objProposta = NULL)
  {

      $proposta_aprovacao           = null;
      $id_mpme_proposta             = (isset($request->id_mpme_proposta)) ? $request->id_mpme_proposta: $objProposta->ID_MPME_PROPOSTA ;
      $ID_MPME_ALCADA               = $request->id_mpme_alcada;

      if ( $id_mpme_proposta > 0 ){
          $proposta_aprovacao = MpmePropostaAprovacao::where('ID_MPME_PROPOSTA', '=', $id_mpme_proposta)
                                                     ->where('ID_MPME_ALCADA', '=', $ID_MPME_ALCADA)
                                                     ->first();
      }

      if (!isset($proposta_aprovacao))
      {
          $proposta_aprovacao = new MpmePropostaAprovacao();
      }

      $proposta_aprovacao->ID_MPME_PROPOSTA                = $id_mpme_proposta;
      $proposta_aprovacao->ID_MPME_ALCADA                  = (isset($request->id_mpme_alcada))  ? $request->id_mpme_alcada  : NULL;
      $proposta_aprovacao->IN_DECISAO                      = (isset($request->in_decisao)) ? $request->in_decisao : NULL;
      $proposta_aprovacao->VL_PROPOSTA                     = converte_float($request->vl_proposta);
      $proposta_aprovacao->VL_PERC_DOWPAYMENT              = converte_float($request->va_percentual_dw_payment);
      $proposta_aprovacao->NU_PRAZO_PRE                    = $request->nu_prazo_pre;
      $proposta_aprovacao->NU_PRAZO_POS                    = $request->nu_prazo_pos;
      $proposta_aprovacao->IN_ACEITE                       = (isset($request->in_aceite)) ? $request->in_aceite : 0;
      $proposta_aprovacao->DT_CADASTRO                     = Carbon::now();
      $proposta_aprovacao->ID_USUARIO_CAD                  = Auth::user()->ID_USUARIO;

      if(!$proposta_aprovacao->save()){
          return false;
      }

      return $proposta_aprovacao;
  }

  public function salvarProcessoProposta($request)
  {

      $campos = (object) $request->all();


      DB::beginTransaction();

      $propostaRepository   = new MpmePropostaRepository();
      $rs_proposta          = $propostaRepository->salvaProposta($campos);

      if (!$rs_proposta)
      {
          DB::rollback();
          return false;
      }

      $proposta_aprovacao = $this->salvaPropostaAprovacao($campos, $rs_proposta);


      if (!$proposta_aprovacao)
      {
          DB::rollback();
          return false;
      }

      $dados =  [
                    'ID_MPME_PROPOSTA'      => $proposta_aprovacao->ID_MPME_PROPOSTA,
                    'FL_DESCISAO'           => $proposta_aprovacao->FL_DESCISAO,
                    'VL_PROPOSTA'           => $proposta_aprovacao->VL_PROPOSTA,
                    'VL_PERC_DOWPAYMENT'    => $proposta_aprovacao->VL_PERC_DOWPAYMENT,
                    'NU_PRAZO_PRE'          => $proposta_aprovacao->NU_PRAZO_PRE,
                    'NU_PRAZO_POS'          => $proposta_aprovacao->NU_PRAZO_POS,
                ];

      $rs_atualiza_proposta     = new MpmePropostaRepository();

      if(!$rs_atualiza_proposta->atualizar_dados_aprovacao($dados)){
          DB::rollback();
          return false;
      }


      if ($request->session()->has('resposta_calculadora'))
      {
         //dados alimentados na simulacao da calculadora
         $dadosAlimentadosCalculadora                       = $request->session()->get('resposta_calculadora');
         $dadosAlimentadosCalculadora['ID_MPME_PROPOSTA']   = $rs_proposta->ID_MPME_PROPOSTA;

         $precificacao                = new PrecificacaoRepository();

          if (!$precificacao::salvarPrecoCobertura($dadosAlimentadosCalculadora))
          {
              DB::rollback();
              return false;
          }
      }else{

          DB::rollback();
          return false;
      }


      DB::commit();
      $request->session()->forget('resposta_calculadora');

      return $proposta_aprovacao->ID_MPME_PROPOSTA;
  }

  public function getSumProposta( $id_oper )
  {
      $vl_total = 0;

      $proposta = new MpmeProposta();

      $retorno = $proposta->where('ID_OPER', '=', $id_oper)
                          ->where('MPME_PROPOSTA.IN_ACEITE', '=', 'SIM')
                          ->whereNotIn('MPME_PROPOSTA.ID_MPME_STATUS_PROPOSTA', [6,7,17])
                          ->select(DB::raw('SUM ( (  MPME_PROPOSTA.VL_PROPOSTA - 
                                   ( 	MPME_PROPOSTA.VL_PROPOSTA * (MPME_PROPOSTA.VL_PERC_DOWPAYMENT/100) ) ) ) AS VL_PROPOSTA'))
                          ->get();

     foreach ($retorno as $valor )
     {
         $vl_total += $valor->VL_PROPOSTA;
     }

     return $vl_total;

  }

  public function getAprovacao($id_oper, $id_mpme_proposta)
  {
      return $this->join('MPME_PROPOSTA', 'MPME_PROPOSTA.ID_MPME_PROPOSTA', 'MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA')
                  ->where('ID_OPER', '=', $id_oper)
                  ->where('MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA', '=', $id_mpme_proposta)
                  ->orderByDesc('ID_MPME_PROPOSTA_APROVACAO')
                  ->get([
                      'MPME_PROPOSTA_APROVACAO.*'
                  ]);
  }

}
