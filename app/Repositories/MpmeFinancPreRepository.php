<?php
namespace App\Repositories;
use DB;
use App\Financpre;
use Auth;

class MpmeFinancPreRepository extends Repository{

  public function __construct()
    {
        $this->setModel(Financpre::class);
    }

  public static function atualizaDadosFinanciadorPre($request)
  {
      $financPre = Financpre::find($request->ID_FINANC_PRE);
      $financPre->DS_ENDERECO    = $request->DS_ENDERECO_PRE;
      $financPre->ID_AGENCIA     = $request->ID_AGENCIA_PRE;
      $financPre->ID_BANCO       = $request->nu_ag;
      $financPre->NO_CIDADE      = $request->NO_CIDADE_PRE;
      $financPre->NO_ESTADO      = $request->NO_ESTADO_PRE;
      $financPre->NU_CEP         = $request->NU_CEP_PRE;
      $financPre->NO_CONTATO     = $request->NO_CONTATO_PRE;
      $financPre->NU_TEL         = $request->NU_TEL_PRE;
      $financPre->DS_EMAIL       = $request->DS_EMAIL_PRE;
      $financPre->NO_CARGO       = $request->NO_CARGO_PRE;
      $financPre->NU_CNPJ        = $request->NU_CNPJ_PRE;
      $financPre->NU_INSCRICAO   = $request->NU_INSCRICAO_PRE;
      if($financPre->save()){
          return true;
      }else{
          return false;
      }
  }




}
