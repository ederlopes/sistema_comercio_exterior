<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PaisVal extends Model
{
    protected $table = 'PAISES_VAL';
    protected $primaryKey = 'ID_PAIS';
    public $timestamps = false;
    protected $guarded = array();
}
