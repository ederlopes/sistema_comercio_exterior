<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeClienteImportador extends Model
{
    protected $table = 'MPME_CLIENTE_IMPORTADORES';
    protected $primaryKey  = 'ID_MPME_CLIENTE_IMPORTADORES';
    public $timestamps = false;
    protected $guarded = array();

}
