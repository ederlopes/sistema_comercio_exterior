<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class StatusOper extends Model
{
    protected $table = 'STATUSOPER';
    protected $primaryKey  = 'ST_OPER';
    public $timestamps = false;
    protected $guarded = array();

    public function ImportadoresEOperacoes(){
    	return $this->hasOne(ImportadoresModel::class);
    }


    public function getStatusOper()
    {
        $rs_status   = $this->get();
        return $rs_status;
    }
}
