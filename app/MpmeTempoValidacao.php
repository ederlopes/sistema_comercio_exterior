<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MpmeTempoValidacao extends Model
{
    protected $table = 'MPME_TEMPO_VALIDACAO';
    protected $primaryKey  = 'ID_TEMPO_VALIDACAO';
    public $timestamps = false;
    protected $guarded = array();



}
