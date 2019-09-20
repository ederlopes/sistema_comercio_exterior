<?php
namespace App\Repositories;
use DB;
use App\MpmeRecomendacao;
use Auth;

class MpmeRecomendacaoRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmeRecomendacao::class);
    }


  public static function salvaRecomendacao($request){

      $recomendacao = (isset($request->ID_RECOMENDACAO) && trim($request->ID_RECOMENDACAO) !="") ? MpmeRecomendacao::where('ID_RECOMENDACAO','=',$request->ID_RECOMENDACAO)->where('ID_MPME_ALCADA','=',$request->ID_MPME_ALCADA)->first() : new MpmeRecomendacao();
      $recomendacao = ($recomendacao != "") ? $recomendacao : new MpmeRecomendacao();
      $recomendacao->ID_OPER = $request->ID_OPER;
      $recomendacao->ID_MPME_ALCADA = $request->ID_MPME_ALCADA;
      $recomendacao->FL_MOMENTO = $request->NO_ALCADA;
      $recomendacao->DS_RECOMENDACAO = $request->ds_recomendacao;
      $recomendacao->DT_RECOMENDACAO = $request->DT_RECOMENDACAO;

      if($recomendacao->save()){
        return true;
      }else{
        return false;
      }


  }

}
