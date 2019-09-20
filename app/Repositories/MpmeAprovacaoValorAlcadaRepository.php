<?php
namespace App\Repositories;
use DB;
use App\MpmeAprovacaoValorAlcada;
use Auth;

class MpmeAprovacaoValorAlcadaRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmeAprovacaoValorAlcada::class);
    }


  public static function devolveAlcada($request){


      $vlApAlcada = MpmeAprovacaoValorAlcada::where('ID_OPER','=',$request->ID_OPER)->whereIn('ID_MPME_ALCADA',[$request->ID_MPME_ALCADA,($request->ID_MPME_ALCADA - 1)])->first();
      $vlApAlcada->IN_DEVOLVIDA = 1;
      $vlApAlcada->ID_MPME_ALCADA = $request->ID_MPME_ALCADA - 1;
      $vlApAlcada->TX_OBSERVACAO = $request->DE_MOTIVO_DEVOLUCAO;

      if($vlApAlcada->save()){
          return true;
      }else{
          return false;
      }


  }



}
