<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MpmeInfAdicionalExportador extends Model
{
    protected $table = 'MPME_INF_ADICIONAL_EXPORTADOR';
    protected $primaryKey  = 'ID_INF_ADICIONAL';
    public $timestamps = false;
    protected $guarded = array();



}
