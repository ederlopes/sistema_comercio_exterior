<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeTipoNotificacao extends Model
{

    protected $table = 'MPME_TIPO_NOTIFICACAO';

    protected $primaryKey = 'ID_MPME_TIPO_NOTIFICACAO';

    public $timestamps = false;

    protected $guarded = array();

}
