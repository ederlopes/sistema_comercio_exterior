<?php
namespace App\Repositories;
use App\MpmeInfAdicionalExportador;
use DB;
use Auth;


class MpmeInfAdicionalExportadorRepository extends Repository{

    public function __construct()
    {
        $this->setModel(MpmeInfAdicionalExportador::class);
    }

    public static function salvaInfAdicionalExportador($request)
    {
        $info                    = (isset($request->ID_INF_ADICIONAL) && 
                                    trim($request->ID_INF_ADICIONAL) !="") ? 
                                    MpmeInfAdicionalExportador::find($request->ID_INF_ADICIONAL) : 
                                    new MpmeInfAdicionalExportador();

        $info->ID_USUARIO_FK     = $request->ID_USUARIO;
        $info->ID_FINANCIADOR_FK = Auth::User()->ID_USUARIO;
        $info->DS_RESP1          = $request->respa ?? '';
        $info->DS_RESP2          = $request->respb ?? '';
        $info->DS_RESP3          = $request->respc ?? '';
        $info->DS_RESP4          = $request->respd ?? '';
        $info->DT_REGISTRO       = date('Y-m-d'); 

        if($info->save()) {
            return true;
          }else {
            return false;
          }
    }
    
}
