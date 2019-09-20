<?php

namespace App\Repositories;

use App\Alcada;
use App\ImportadoresModel;
use App\MpmeAprovacaoValorAlcada;
use App\MpmeMovimentacaoControleCapital;
use Carbon\Carbon;
use DB;
use Auth;

class MpmeMovimentacaoControleCapitalRepository extends Repository{

  public function __construct()
  {
        $this->setModel(MpmeMovimentacaoControleCapital::class);
  }


  public function getMovimentacaoControleCapital($id_movimentacao_controle_capital = null, $id_oper = null)
  {
      if (isset($id_movimentacao_controle_capital)){
          return MpmeMovimentacaoControleCapital::find($id_movimentacao_controle_capital);
      }elseif (isset($id_oper)){
          return MpmeMovimentacaoControleCapital::where('ID_OPER', '=', $id_oper)->get();
      }else{
          return MpmeMovimentacaoControleCapital::all();
      }

  }



  
  public function movimentacao_controle_capital($dados)
  {         

        $id_oper = isset($dados) ? (array_key_exists('id_oper', $dados)) ? $dados['id_oper'] : $dados['ID_OPER'] : $dados;
   
        //existe uma triguer no banco TGR_CONTROLE_CAPITAL que faz o controle do saldo na tabela MPME_CONTROLE_CAPITAL
        if ( $id_oper == "" || $id_oper == "0" )
        {
            return response()->json([
                'message' => 'Parametros inválidos!',
                'class_mensagem' => 'error',
                'header' => 'Parametros inválidos!'
            ]);
        }


        $id_mpme_alcada = (array_key_exists('id_oper', $dados)) ? $dados['id_mpme_alacada'] : $dados['ID_MPME_ALCADA'];
         
        if($id_mpme_alcada != 7){




           
            $novo_lancamento                            = new MpmeMovimentacaoControleCapital();
            $novo_lancamento->ID_MPME_CONTROLE_CAPITAL  = $dados['id_mpme_fundo_garantia'];
            $novo_lancamento->ID_OPER                   = (array_key_exists('id_oper', $dados)) ? $dados['id_oper'] : $dados['ID_OPER'];
            $novo_lancamento->ID_MPME_FUNDO_PRINCIPAL   = (array_key_exists('id_mpme_fundo_garantia_principal', $dados)) ? $dados['id_mpme_fundo_garantia_principal'] : $dados['id_mpme_fundo_garantia_operacao'];
            $novo_lancamento->ID_MPME_FUNDO_GARANTIA    = $dados['id_mpme_fundo_garantia'];
            $novo_lancamento->ID_MPME_ALCADA            = $id_mpme_alcada;
            $novo_lancamento->ID_MOEDA                  = $dados['id_moeda'];
            $novo_lancamento->VL_MOVIVENTACAO           = converte_float((array_key_exists('vl_movimentacao', $dados)) ? converte_float($dados['vl_movimentacao']) : converte_float($dados['vl_cred_concedido']));
            $novo_lancamento->VL_TAXA_CAMBIO            = (array_key_exists('vl_taxa_cambio', $dados)) ? $dados['vl_taxa_cambio'] : $dados['tx_cotacao'];
            $novo_lancamento->VL_PERC_FUNDO             = $dados['vl_perc_fundo'];
            $novo_lancamento->VL_TOTAL_REAIS            = (array_key_exists('vl_total_reais', $dados)) ? converte_float($dados['vl_total_reais']) : converte_float($dados['vl_total_real']);
            $novo_lancamento->TP_MOVIMENTACAO           = 'EXTORNO';
            $novo_lancamento->IN_SALDO_SUFICIENTE       = strtoupper((array_key_exists('in_saldo_insuficiente', $dados)) ? $dados['in_saldo_insuficiente'] : $dados['in_saldo_suficiente'][0]);
            $novo_lancamento->DT_CADASTRO               = Carbon::now();
            $novo_lancamento->ID_USUARIO_CAD            = Auth::User()->ID_USUARIO;
    
            if (!$novo_lancamento->save()){
              return response()->json([
                  'message' => 'Erro ao fazer lançamento!',
                  'class_mensagem' => 'error',
                  'header' => 'Erro ao fazer lançamento!'
              ]);
            }

        }   
        
        $novo_lancamento                            = new MpmeMovimentacaoControleCapital();
        $novo_lancamento->ID_MPME_CONTROLE_CAPITAL  = $dados['id_mpme_fundo_garantia'];
        $novo_lancamento->ID_OPER                   = (array_key_exists('id_oper', $dados)) ? $dados['id_oper'] : $dados['ID_OPER'];
        $novo_lancamento->ID_MPME_FUNDO_PRINCIPAL   = (array_key_exists('id_mpme_fundo_garantia_principal', $dados)) ? $dados['id_mpme_fundo_garantia_principal'] : $dados['id_mpme_fundo_garantia_operacao'];
        $novo_lancamento->ID_MPME_FUNDO_GARANTIA    = $dados['id_mpme_fundo_garantia'];
        $novo_lancamento->ID_MPME_ALCADA            = $id_mpme_alcada;
        $novo_lancamento->ID_MOEDA                  = $dados['id_moeda'];
        $novo_lancamento->VL_MOVIVENTACAO           = converte_float((array_key_exists('vl_movimentacao', $dados)) ? $dados['vl_movimentacao'] : $dados['vl_cred_concedido']);
        $novo_lancamento->VL_TAXA_CAMBIO            = (array_key_exists('vl_taxa_cambio', $dados)) ? $dados['vl_taxa_cambio'] : $dados['tx_cotacao'];
        $novo_lancamento->VL_PERC_FUNDO             = $dados['vl_perc_fundo'];
        $novo_lancamento->VL_TOTAL_REAIS            = (array_key_exists('vl_total_reais', $dados)) ? $dados['vl_total_reais'] : $dados['vl_total_real'];
        $novo_lancamento->TP_MOVIMENTACAO           = strtoupper($dados['tipo_movimentacao']);
        $novo_lancamento->IN_SALDO_SUFICIENTE       = strtoupper((array_key_exists('in_saldo_insuficiente', $dados)) ? $dados['in_saldo_insuficiente'] : $dados['in_saldo_suficiente']);
        $novo_lancamento->DT_CADASTRO               = Carbon::now();
        $novo_lancamento->ID_USUARIO_CAD            = Auth::User()->ID_USUARIO;

        if (!$novo_lancamento->save()){
          return response()->json([
              'message' => 'Erro ao fazer lançamento!',
              'class_mensagem' => 'error',
              'header' => 'Erro ao fazer lançamento!'
          ]);
        }


        return $novo_lancamento;

  }






}
