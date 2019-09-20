<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use DB;

class MercadoriasModel extends Model
{
 	protected $table = 'MPME_MERCADORIAS';
    protected $primaryKey  = 'ID_MERCADORIA';
    public $timestamps = false;
    protected $guarded = array();

    public function ImportadoresEOperacoes(){
    	return $this->belongsToMany(ImportadoresModel::class);
    }


    public function gravarMercadoria( $arrayDados )
    {
        $id_oper             = $arrayDados['ID_OPER'];

        if ( $id_oper > 0 )
        {
            $mecadoria = $this->where("ID_OPER", '=', $id_oper)->first();
            if (!isset($mecadoria))
            {
                $mecadoria = new MercadoriasModel();
            }
        }else{
            $mecadoria = $this;
        }


        $mecadoria->ID_OPER                      =  $arrayDados['ID_OPER'];
        $mecadoria->NCM                          =  $arrayDados['NCM'];
        $mecadoria->NM_MERCADORIA                =  $arrayDados['NM_MERCADORIA'];
        $mecadoria->PC_ANTECIPADO                =  $arrayDados['PC_ANTECIPADO'];
        $mecadoria->VL_TOTAL                     =  $arrayDados['VL_TOTAL'];
        $mecadoria->PRAZO                        =  $arrayDados['PRAZO'];
        $mecadoria->PZ_PAGTO                     =  $arrayDados['PZ_PAGTO'];
        $mecadoria->DS_DOCUMENTO                 =  $arrayDados['DS_DOCUMENTO'];
        $mecadoria->TIPO_VALIDACAO               =  $arrayDados['TIPO_VALIDACAO'];
        $mecadoria->NU_DOCUMENTO                 =  $arrayDados['NU_DOCUMENTO'];
        $mecadoria->DS_DOCUMENTO                 =  $arrayDados['DS_DOCUMENTO'];
        $mecadoria->DT_CADASTRO                  =  $arrayDados['DT_CADASTRO'];
        $mecadoria->DIVERGENCIA                  =  $arrayDados['DIVERGENCIA'];
        $mecadoria->OCULTO                       =  $arrayDados['OCULTO'];
        $mecadoria->DATA_CADASTRO                =  $arrayDados['DATA_CADASTRO'];
        $mecadoria->DATA_ULTIMA_ALTERACAO        =  $arrayDados['DATA_ULTIMA_ALTERACAO'];
        $mecadoria->ACEITE_IMPORTADOR            =  $arrayDados['ACEITE_IMPORTADOR'];

        if (!$mecadoria->save())
        {
            return false;
        }

        return true;
    }


    
}
