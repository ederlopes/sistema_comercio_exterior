<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsuarioCGCVigenciaModel extends Model
{
	protected $table = 'MPME_CGC_VIGENCIA';
    protected $primaryKey  = 'ID_MPME_CGC_VIGENCIA';
    public $timestamps = false;
    protected $guarded = array();

    public function Usuario(){
    	return $this->belongsToMany(User::class);
    }
}
