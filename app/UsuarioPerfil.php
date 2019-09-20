<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class UsuarioPerfil extends Model
{
    protected $table = 'TB_USUARIO_PERFIL';
    protected $primaryKey  = 'ID_USUARIO_FK';
    public $timestamps = false;
    protected $guarded = array();

}
