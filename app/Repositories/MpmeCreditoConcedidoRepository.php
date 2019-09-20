<?php
namespace App\Repositories;
use DB;
use App\MpmeCreditoConcedido;
use Auth;

class MpmeCreditoConcedidoRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmeCreditoConcedido::class);
    }


  public static function salvaCreditoConcedido($request){

      $credito = (isset($request->ID_CREDITO) && trim($request->ID_CREDITO) !="") ? MpmeCreditoConcedido::find($request->ID_CREDITO) : new MpmeCreditoConcedido();
      $credito->ID_OPER = $request->ID_OPER;
      $credito->VL_CRED_SOLICITADO = converte_float($request->VL_SOLICITA_USUARIO);
      $credito->VL_CRED_CONCEDIDO = converte_float($request->vl_cred_concedido);
      $credito->DT_VALIDADE = date('Y-m-d');
      $credito->ID_MPME_ALCADA = $request->ID_MPME_ALCADA;
      $credito->ID_MPME_FUNDO_GARANTIA = $request->id_mpme_fundo_garantia;

      if($credito->save()){
        return true;
      }else{
        return false;
      }


  }

}
