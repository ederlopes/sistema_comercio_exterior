<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Parametros extends Model
{
    protected $table = 'PARAMETROS';
    protected $primaryKey  = 'ID_PARAMETROS';
    public $timestamps = false;
    protected $guarded = array();


}
