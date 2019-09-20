<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class MpmeContratoComercial extends Model
{
	protected $table = 'MPME_CONTR_COMERC';
    protected $primaryKey  = 'ID_CONTR_COMERC';
    public $timestamps = false;
    protected $guarded = array();

    const ID_MOEDA_DOLAR = 1;
    const ID_MOEDA_EURO  = 3;


    public function gravarContratoComercial( $arrayDados )
    {
        $id_oper             = $arrayDados['ID_OPER'];

        if ( $id_oper > 0 )
        {
            $contrato_comercial = $this->where("ID_OPER", '=', $id_oper)->first();
            if (!isset($contrato_comercial))
            {
                $contrato_comercial = new MpmeContratoComercial();
            }
        }else{
            $contrato_comercial = $this;
        }


        $contrato_comercial->ID_OPER                     =  $arrayDados['ID_OPER'];
        $contrato_comercial->N_EMBARQUES_ANO             =  $arrayDados['N_EMBARQUES_ANO'];
        $contrato_comercial->PERIODICIDADE_EMB           =  $arrayDados['PERIODICIDADE_EMB'];
        $contrato_comercial->VL_EXP_ANUAL                =  $arrayDados['VL_EXP_ANUAL'];
        $contrato_comercial->PRAZO_OPER_PRE              =  $arrayDados['PRAZO_OPER_PRE'];
        $contrato_comercial->PRAZO_OPER_POS              =  $arrayDados['PRAZO_OPER_POS'];
        $contrato_comercial->CONTRATO_EXPORTACAO         =  $arrayDados['CONTRATO_EXPORTACAO'];
        $contrato_comercial->TX_JUROS                    =  $arrayDados['TX_JUROS'];
        $contrato_comercial->SPREAD                      =  $arrayDados['SPREAD'];

        if (!$contrato_comercial->save())
        {
            return false;
        }

        return true;
    }
}
