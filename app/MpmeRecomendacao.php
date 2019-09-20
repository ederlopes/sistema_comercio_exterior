<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MpmeRecomendacao extends Model
{
    protected $table = 'MPME_RECOMENDACAO';
    protected $primaryKey  = 'ID_RECOMENDACAO';
    public $timestamps = false;
    protected $guarded = array();

}
