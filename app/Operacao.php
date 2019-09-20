<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operacao extends Model
{
    protected $table = 'MPME_IMPORTADORES';
    protected $primaryKey  = 'ID_IMPORTADOR';
    public $timestamps = false;
    protected $guarded = array();
}
