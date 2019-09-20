<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeValidaExportador extends Model
{
    protected $table = 'MPME_VALIDA_EXPORTADOR';
    protected $primaryKey  = 'ID_VALIDA_EXPORTADOR';
    public $timestamps = false;
    protected $guarded = array();

}
