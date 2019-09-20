<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;

class MpmeQuestionario extends Model
{
    protected $table = 'MPME_QUESTIONARIO';
    protected $primaryKey  = 'MPME_QUESTIONARIO';
    public $timestamps = false;
    protected $guarded = array();


    public function gravarQuestionario( $arrayDados )
    {

        $questionario = $this;

        $questionario->ID_OPER                                            =  $arrayDados['ID_OPER'];
        $questionario->ID_MPME_PERGUNTA_RESPOSTA                          =  $arrayDados['ID_MPME_PERGUNTA_RESPOSTA'];
        $questionario->ID_MPME_CLIENTE                                    =  $arrayDados['ID_MPME_CLIENTE'];
        $questionario->IN_QUESTIONARIO_APLICADO                           =  $arrayDados['IN_QUESTIONARIO_APLICADO'];
        $questionario->DS_OUTRA_RESPOSTA                                  =  $arrayDados['DS_OUTRA_RESPOSTA'];
        $questionario->IN_ATIVO                                           =  $arrayDados['IN_ATIVO'];
        $questionario->ID_USUARIO                                         =  $arrayDados['ID_USUARIO'];
        $questionario->DATA_CADASTRO                                      =  $arrayDados['DATA_CADASTRO'];

        if (!$questionario->save())
        {
            return false;
        }

        return true;

    }

}
