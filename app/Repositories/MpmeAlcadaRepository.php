<?php

namespace App\Repositories;

use App\Alcada;
use App\ImportadoresModel;
use App\MpmeAprovacaoValorAlcada;
use Carbon\Carbon;
use DB;
use Auth;

class MpmeAlcadaRepository extends Repository{

  public function __construct()
  {
        $this->setModel(Alcada::class);
  }

  public function getAlcadasVigente()
  {
      return Alcada::where('IN_ATIVA', '=', 'S')->orderBy('NU_ORDEM')->get();
  }

    public function getAlcada($id_mpme_alcada)
    {
        return Alcada::where('IN_ATIVA', '=', 'S')
                     ->where('ID_MPME_ALCADA', '=', $id_mpme_alcada)
                     ->orderBy('NU_ORDEM')->first(['NO_ALCADA']);
    }



    public function chkAlcadaValor($id_oper)
  {
        $operacao               = ImportadoresModel::find($id_oper);
        $alcadas                = $this->getAlcadasVigente();
        $alcadas_ja_aprovadas   = $this->controleAlcada($id_oper);

        foreach ( $alcadas as $alcada )
        {

            $vl_operacao = $operacao->VL_APROVADO;

            //$vl_operacao = 250000.00;
            //$vl_operacao = 300000.00;
            //$vl_operacao = 2010000.00;

            $in_habilitada = alcadaAtiva($alcada->mpme_alcada_valor->VL_APROVACAO_INICIAL,
                                    $alcada->mpme_alcada_valor->VL_APROVACAO_FINAL,
                                    $vl_operacao,
                                    $alcada->mpme_alcada_valor->IN_DELIBERATIVA
                                    );

            $dados['ALCADA'][]  = [
                        'ID_MPME_ALCADA'        => $alcada->ID_MPME_ALCADA,
                        'NO_ALCADA'             => $alcada->NO_ALCADA,
                        'NO_CLASSE'             => $alcada->NO_CLASSE,
                        'IN_DELIBERATIVA'       => $alcada->mpme_alcada_valor->IN_DELIBERATIVA,
                        'VL_APROVACAO_INICIAL'  => $alcada->mpme_alcada_valor->VL_APROVACAO_INICIAL,
                        'VL_APROVACAO_FINAL'    => $alcada->mpme_alcada_valor->VL_APROVACAO_FINAL,
                        'ALCADA_HABILITADA'     => $in_habilitada,
                        'VL_APROVADO'           => $vl_operacao,
                        'VL_APROVADO_ALCADA'    => isset ( $alcadas_ja_aprovadas['ValoresAprovados'][$alcada->ID_MPME_ALCADA] ) ? $alcadas_ja_aprovadas['ValoresAprovados'][$alcada->ID_MPME_ALCADA] : NULL,
                     ];

            $arrayAlcadas[$alcada->NU_ORDEM] = $alcada->ID_MPME_ALCADA;
        }



        $diferenca          = array_keys(array_diff ($arrayAlcadas, $alcadas_ja_aprovadas['AlcadaAprovada']));


        $dados['CONTROLE_APROVACAO'] = [
                                            'ID_MPME_ALCADA_APROVAR'     => $diferenca[0],
                                            'ID_MPME_ALCADA_PROXIMA'     => (isset($diferenca[1])) ? $diferenca[1] : max($diferenca),
                                        ];


        if ( $dados['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] == $dados['CONTROLE_APROVACAO']['ID_MPME_ALCADA_PROXIMA'])
        {
            $dados['CONTROLE_APROVACAO'] +=  [ 'ULTIMA' => 'SIM' ];
        }else{

            foreach ( $dados['ALCADA'] as $alcada)
            {
                if ( $alcada['ID_MPME_ALCADA'] == $dados['CONTROLE_APROVACAO']['ID_MPME_ALCADA_PROXIMA'] )
                {
                    if ( $alcada['ALCADA_HABILITADA'] == 'disabled')
                    {
                        $dados['CONTROLE_APROVACAO'] +=  [ 'ULTIMA' => 'SIM' ];
                    }else{
                        $dados['CONTROLE_APROVACAO'] +=  [ 'ULTIMA' => 'NAO' ];
                    }
                }
            }

        }


        //dd($dados);
        return $dados;


      //VL_APROVADO
  }


  public function controleAlcada($id_oper)
  {
      $mpme_aprovacao_alcada = new MpmeAprovacaoValorAlcada();
      $mpme_aprovacao_alcada =  $mpme_aprovacao_alcada->where('ID_OPER', '=', $id_oper)
                                                      ->where('IN_DEVOLVIDA', '=', 0)
                                                      ->get(['ID_MPME_ALCADA', 'VL_APROVADO']);

      foreach ($mpme_aprovacao_alcada as $alcadas_aprovadas)
      {
          $arrayAlcadaAprovada[$alcadas_aprovadas->mpme_alcada->NU_ORDEM] = $alcadas_aprovadas->ID_MPME_ALCADA;
          $arrayAlcadaValores[$alcadas_aprovadas->ID_MPME_ALCADA] = $alcadas_aprovadas->VL_APROVADO;
      }



      return [
                'AlcadaAprovada'   =>   $arrayAlcadaAprovada,
                'ValoresAprovados' =>   $arrayAlcadaValores,
             ];
  }



}
