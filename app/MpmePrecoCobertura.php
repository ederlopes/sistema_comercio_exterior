<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;

class MpmePrecoCobertura extends Model
{
    protected $table = 'MPME_PRECO_COBERTURA';
    protected $primaryKey  = 'ID_PRECO_COBERTURA';
    public $timestamps = false;
    protected $guarded = array();
}
