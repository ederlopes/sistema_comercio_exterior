<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class RegimeTributario extends Model
{
    protected $table = 'REGIME_TRIBUTARIO';
    protected $primaryKey  = 'ID_REGIME_TRIBUTARIO';
    public $timestamps = false;
    protected $guarded = array();

}
