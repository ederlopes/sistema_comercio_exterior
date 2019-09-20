<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResetarSenha extends Model
{
    protected $table = 'RESETAR_SENHAS';
    protected $primaryKey = 'ID_RESETAR_SENHAS';
    public $timestamps = false;
    protected $guarded = array();
}
