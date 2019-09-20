<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MpmeUsuarioAlcada extends Model
{
    protected   $table       = 'MPME_USUARIO_ALCADA';
    protected   $primaryKey  = 'ID_MPME_USUARIO_ALCADA';
    public      $timestamps  = false;
    protected   $guarded     = array();

    public function usuario()
    {
        return $this->belongsTo(User::class, 'ID_USUARIO', 'ID_USUARIO');
    }


}
