<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeTipoCliente extends Model
{
	protected $table = 'MPME_CLIENTE_TIPO_CLIENTE';
    protected $primaryKey  = 'ID_MPME_CLIENTE_TIPO_CLIENTE';
    public $timestamps = false;
    protected $guarded = array();

}
