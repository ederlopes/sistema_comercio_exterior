<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoCancelamentoDas extends Model
{
    protected $table = 'MOTIVO_CANCELAMENTO_DAS';
    protected $primaryKey  = 'ID_MOTIVO_CANCELAMENTO_DAS';
    public $timestamps = false;
    protected $guarded = array();

}
