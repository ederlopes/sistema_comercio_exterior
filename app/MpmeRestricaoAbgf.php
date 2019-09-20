<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MpmeRestricaoAbgf extends Model
{
    protected   $table       = 'MPME_RESTRICAO_ABGF';
    protected   $primaryKey  = 'ID_MPME_RESTRICAO_ABGF';
    public      $timestamps  = false;
    protected   $guarded     = array();


    public function paises(){
        return $this->belongsTo(Pais::class, 'ID_PAIS', 'ID_PAIS');
    }

    public function setores(){
        return $this->belongsTo(TbSetores::class, 'ID_SETOR', 'ID_SETOR');
    }

}
