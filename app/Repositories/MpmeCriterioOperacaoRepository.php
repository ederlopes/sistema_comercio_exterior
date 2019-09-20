<?php
namespace App\Repositories;
use App\MpmeCriterioOperacao;
use DB;

class MpmeCriterioOperacaoRepository extends Repository{

    public function __construct()
      {
          $this->setModel(MpmeCriterioOperacao::class);
      }

      public static function salvaCriterio($request){
          $criterio = (isset($request->ID_CRITERIO_OPERACAO) && trim($request->ID_CRITERIO_OPERACAO) !="") ? MpmeCriterioOperacao::find($request->ID_CRITERIO_OPERACAO) : new MpmeCriterioOperacao();
          $criterio->ID_OPER 			= $request->ID_OPER;
          $criterio->ID_CRITERIO 	= (isset($request->ID_CRITERIO) && trim($request->ID_CRITERIO) != "") ? $request->ID_CRITERIO : 0;
          $criterio->ID_MPME_ALCADA 	= $request->ID_MPME_ALCADA;
          $criterio->DS_RESULTADO = '';

          if($criterio->save()){
              return true;
          }else{
              return false;
          }
      }

      public static function deletaCriterioOperacao($request){
  				$criterio = MpmeCriterioOperacao::where('ID_OPER','=', $request->ID_OPER)->where('ID_MPME_ALCADA','=', $reques->ID_MPME_ALCADA);
  				if($criterio->delete()){
  						return true;
  				}else{
  						return false;
  				}
  		}



}
