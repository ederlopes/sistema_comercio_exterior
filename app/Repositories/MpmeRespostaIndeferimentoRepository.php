<?php
namespace App\Repositories;
use DB;
use App\MpmeRespostaIndeferimento;
use Auth;

class MpmeRespostaIndeferimentoRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmeRespostaIndeferimento::class);
    }


  public static function salvaRespostaIndeferimento($request){
      DB::beginTransaction();

      //Deleta todas as respostas anteriores caso haja
      MpmeRespostaIndeferimento::where('ID_MPME_ALCADA','=',$request->ID_MPME_ALCADA)->where('ID_OPER','=',$request->ID_OPER)->delete();

      foreach($request->motivo_indeferimento as $motivoIndeferimento){
        $respIndeferimento = new MpmeRespostaIndeferimento();
        $respIndeferimento->ID_MPME_TIPO_INDEFERIMENTO = $motivoIndeferimento;
        $respIndeferimento->ID_OPER = $request->ID_OPER;
        $respIndeferimento->ID_MPME_ALCADA = $request->ID_MPME_ALCADA;
        $respIndeferimento->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
        $respIndeferimento->DATA_CADASTRO = date('Y-m-d');
        $respIndeferimento->ID_MODALIDADE = $request->modalidade;

        if(!$respIndeferimento->save()){
            DB::rollback();
            return false;
            break;
        }
      }

      DB::commit();
      return true;

  }



}
