<?php

namespace App\Repositories;

use App\MpmeControleCapitalExportacao;
use Carbon\Carbon;
use DB;
use Auth;

class MpmeControelCapitalExportacaoRepository extends Repository{

  public function __construct()
  {
        $this->setModel(MpmeControleCapitalExportacao::class);
  }

  public function salvarControleCapitalExportacao($request)
  {
      $novo_controle                            = new MpmeControleCapitalExportacao();

      $novo_controle->ID_OPER                    = $request->id_oper;
      $novo_controle->ID_MPME_FUNDO_PRINCIPAL    = $request->id_mpme_fundo_garantia_operacao;
      $novo_controle->ID_MOEDA                   = $request->id_moeda_exp;
      $novo_controle->ID_PAIS                    = $request->id_pais_exp;
      $novo_controle->VL_MOVIVENTACAO            = converte_float($request->vl_cred_concedido_exp);
      $novo_controle->VL_TAXA_CAMBIO             = $request->tx_cotacao_exp;
      $novo_controle->VL_TOTAL_REAIS             = converte_float($request->vl_total_real_exp);;
      $novo_controle->IN_SALDO_SUFICIENTE        = $request->in_saldo_suficiente_exp;
      $novo_controle->DS_PARECER                 = $request->parecer_exp;
      $novo_controle->DT_CADASTRO                = Carbon::now();
      $novo_controle->ID_USUARIO_CAD             = Auth::User()->ID_USUARIO;

      if (!$novo_controle->save()){
          return false;
      }else{
          return true;
      }
  }

}
