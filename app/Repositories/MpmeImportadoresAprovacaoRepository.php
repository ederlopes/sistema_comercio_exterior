<?php
namespace App\Repositories;
use DB;
use App\MpmeImportadoresAprovacao;
use Auth;
use App\MpmeNotificacao;

class MpmeImportadoresAprovacaoRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmeImportadoresAprovacao::class);
    }


  public static function salvaImportadoresAprovacao($request){

            $impAprov = (isset($request->ID_IMPORTADORES_APROVACAO) && trim($request->ID_IMPORTADORES_APROVACAO) !="") ? MpmeImportadoresAprovacao::find($request->ID_IMPORTADORES_APROVACAO) : new MpmeImportadoresAprovacao();
            $impAprov->ID_OPER_FK                       = $request->ID_OPER;
            $impAprov->ID_MPME_ALCADA                   = (isset($request->IC_DEVOLVEU_ALCADA_ANTERIOR) && $request->IC_DEVOLVEU_ALCADA_ANTERIOR != 0) ? $request->ID_MPME_ALCADA - 1 : $request->ID_MPME_ALCADA;
            $impAprov->FL_MOMENTO                       = 'ANA';
            $impAprov->IC_INDEFERIDA                    = (isset($request->DS_RECOMENDACAO) && $request->DS_RECOMENDACAO == 2) ? 1 : 0;
            $impAprov->IC_DEVOLVEU_ALCADA_ANTERIOR      = (isset($request->IC_DEVOLVEU_ALCADA_ANTERIOR) && $request->IC_DEVOLVEU_ALCADA_ANTERIOR != 0) ? $request->IC_DEVOLVEU_ALCADA_ANTERIOR : 0;
            $impAprov->DT_APROVACAO = date('Y-m-d h:m:s');
            $impAprov->DE_MOTIVO_DEVOLUCAO = (isset($request->DE_MOTIVO_DEVOLUCAO) && $request->DE_MOTIVO_DEVOLUCAO != "") ? $request->DE_MOTIVO_DEVOLUCAO : '';
            if($impAprov->save()){
              return true;
            }else{
              return false;
            }

  }

    public static function aprovaLimite($request){

        $impAprov =  new MpmeImportadoresAprovacao();
        $impAprov->ID_OPER_FK                       = $request->ID_OPER;
        $impAprov->ID_MPME_ALCADA                   = $request->ID_MPME_ALCADA;
        $impAprov->FL_MOMENTO                       = 'APV';
        $impAprov->IC_INDEFERIDA                    = 0;
        $impAprov->IC_DEVOLVEU_ALCADA_ANTERIOR      = 0;
        $impAprov->DT_APROVACAO = date('Y-m-d h:m:s');

        if($impAprov->save()){
            return true;
        }else{
            return false;
        }

    }


}
