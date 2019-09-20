<?php
namespace App\Repositories;
use DB;
use App\Financpos;
use Auth;

class MpmeFinancPosRepository extends Repository{

  public function __construct()
    {
        $this->setModel(Financpos::class);
    }

  public static function atualizaDadosFinanciador($request)
  {
      $financPre = Financpos::find($request->ID_FINANC);
      $financPre->DS_ENDERECO    = $request->DS_ENDERECO;
      $financPre->ID_AGENCIA     = $request->ID_AGENCIA;
      $financPre->ID_BANCO       = $request->nu_ag_pos;
      $financPre->NO_CIDADE      = $request->NO_CIDADE;
      $financPre->NO_ESTADO      = $request->NO_ESTADO;
      $financPre->NU_CEP         = $request->NU_CEP;
      $financPre->NO_CONTATO     = $request->NO_CONTATO;
      $financPre->NU_TEL         = $request->NU_TEL;
      $financPre->DS_EMAIL       = $request->DS_EMAIL;
      $financPre->NO_CARGO       = $request->NO_CARGO;
      $financPre->NU_CNPJ        = $request->NU_CNPJ;
      $financPre->NU_INSCRICAO   = $request->NU_INSCRICAO;
      if($financPre->save()){
          return true;
      }else{
          return false;
      }
  }




}
