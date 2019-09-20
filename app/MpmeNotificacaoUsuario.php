<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeNotificacaoUsuario extends Model
{

    protected $table = 'MPME_NOTIFICACAO_USUARIO';

    protected $primaryKey = 'ID_MPME_NOTIFICACAO_USUARIO';

    public $timestamps = false;

    protected $guarded = array();

    public function tipo_notificacao()
    {
       return  $this->belongsTo(MpmeTipoNotificacao::class, 'ID_MPME_TIPO_NOTIFICACAO', 'ID_MPME_TIPO_NOTIFICACAO');
    }

}
