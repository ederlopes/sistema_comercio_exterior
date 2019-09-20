<?php
namespace App\Repositories;
use DB;
use App\MpmeTempoValidacao;
use Auth;

class MpmeTempoValidacaoRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmeTempoValidacao::class);
    }


  public static function salvaTempoValidacao($request){

            switch ($request->ID_MPME_ALCADA) {
              case 2:
                $ID_TIPO_VALIDACAO_FK = 6;
                break;
              case 3:
              $ID_TIPO_VALIDACAO_FK = 6;
            }
            $tempoValidacao = (isset($request->ID_CREDIT_SCORE) && trim($request->ID_TEMPO_VALIDACAO) !="") ? MpmeTempoValidacao::find($request->ID_TEMPO_VALIDACAO) : new MpmeTempoValidacao();
            $tempoValidacao->ID_USUARIO_FK                    = Auth::user()->ID_USUARIO;
            $tempoValidacao->ID_OPER_FK                       = $request->ID_OPER;
            $tempoValidacao->ID_TIPO_VALIDACAO_FK             = $request->ID_MPME_ALCADA;
            $tempoValidacao->DT_VALIDACAO                     = date('Y-m-d h:m:s');

            if($tempoValidacao->save()){
              return true;
            }else{
              return false;
            }

  }


}
