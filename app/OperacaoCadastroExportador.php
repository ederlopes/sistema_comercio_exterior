<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use DB;
use App\Modalidade;

class OperacaoCadastroExportador extends Model
{
    protected $table = 'OPERACAO_CADASTRO_EXPORTADOR';
    protected $primaryKey  = 'ID_OPERACAO_CADASTRO_EXPORTADOR';
    public $timestamps = false;
    protected $guarded = array();


    public function gravarOperacaoCadastroExportador( $arrayDados )
    {
        $id_oper             = $arrayDados['ID_OPER'];

        if ( $id_oper > 0 )
        {
            $operacao_exportador = $this->where("ID_OPER", '=', $id_oper)->first();
            if (!isset($operacao_exportador))
            {
                $operacao_exportador = new OperacaoCadastroExportador();
            }
        }else{
            $operacao_exportador = $this;
        }


        $operacao_exportador->ID_OPER                                            =  $arrayDados['ID_OPER'];
        $operacao_exportador->ID_MPME_CLIENTE_EXPORTADORES                       =  $arrayDados['ID_MPME_CLIENTE_EXPORTADORES'];
        $operacao_exportador->ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO   =  $arrayDados['ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO'];
        $operacao_exportador->ID_CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO          =  $arrayDados['ID_CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO'];
        $operacao_exportador->ID_REGIME_TRIBUTARIO                               =  $arrayDados['ID_REGIME_TRIBUTARIO'];
        $operacao_exportador->ID_MODALIDADE                                      =  $arrayDados['ID_MODALIDADE'];
        $operacao_exportador->ID_FINANCIAMENTO                                   =  $arrayDados['ID_FINANCIAMENTO'];
        $operacao_exportador->ID_ENQUADRAMENTO_TRIBUTARIO                        =  $arrayDados['ID_ENQUADRAMENTO_TRIBUTARIO'];
        $operacao_exportador->ID_PAIS                                            =  $arrayDados['ID_PAIS'];
        $operacao_exportador->MOEDA_ID                                           =  $arrayDados['MOEDA_ID'];
        $operacao_exportador->ID_USUARIO_CAD                                     =  $arrayDados['ID_USUARIO_CAD'];
        $operacao_exportador->DATA_CADASTRO                                      =  $arrayDados['DATA_CADASTRO'];
        $operacao_exportador->IN_ACEITE_RESTRICOES                               =  $arrayDados['IN_ACEITE_RESTRICOES'];

        if (!$operacao_exportador->save())
        {
            DB::rollback();
            return false;
        }

        return true;

    }


    public function modalidade(){
        return $this->belongsTo('App\ModalidadeModel', 'ID_MODALIDADE', 'ID_MODALIDADE');
    }

    public function financiamento(){
        return $this->belongsTo('App\FinanciamentoModel', 'ID_FINANCIAMENTO', 'ID_FINANCIAMENTO');
    }


}
