<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeTipoNotificacaoUsuario extends Model
{

    protected $table = 'MPME_TIPO_NOTIFICACAO_USUARIO';

    protected $primaryKey = 'ID_MPME_TIPO_NOTIFICACAO_USUARIO';

    public $timestamps = false;

    protected $guarded = array();

}
