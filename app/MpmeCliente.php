<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeCliente extends Model
{
	protected $table = 'MPME_CLIENTE';
    protected $primaryKey  = 'ID_MPME_CLIENTE';
    public $timestamps = false;
    protected $guarded = array();

}
