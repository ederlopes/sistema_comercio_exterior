<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeFinanc extends Model
{
    protected $table = 'MPME_FINANC';
    protected $primaryKey = 'ID_FINANC';
    public $timestamps = false;
    protected $guarded = array();

    public function Usuario()
    {
        return $this->hasOne('App\User', 'ID_USUARIO', 'ID_USUARIO_FINANCIADOR_FK');
    }

    public function Gecex()
    {
        return $this->hasOne('App\Gecex', 'ID_USUARIO_FK', 'ID_USUARIO_FINANCIADOR_FK');
    }
}
