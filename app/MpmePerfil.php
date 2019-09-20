<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmePerfil extends Model
{
    protected $table = 'MPME_PERFIL';
    protected $primaryKey  = 'ID_PERFIL';
    public $timestamps = false;
    protected $guarded = array();
}
