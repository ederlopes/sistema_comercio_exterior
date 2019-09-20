<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class TempoValidacao extends Model
{
    protected $table = 'MPME_TEMPO_VALIDACAO';
    protected $primaryKey  = 'ID_MPME_TEMPO_VALIDACAO';
    protected $guarded = array();
    public $timestamps = false;


}
