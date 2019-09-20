<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioCGCModel extends Model
{
    protected $table = 'MPME_USUARIO_CGC';
    protected $primaryKey = 'ID_USUARIO_CGC';
    public $timestamps = false;
    protected $guarded = array();

    public function Usuario()
    {
        return $this->belongsToMany(User::class);
    }
}
