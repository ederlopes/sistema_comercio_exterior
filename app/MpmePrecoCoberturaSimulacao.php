<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;

class MpmePrecoCoberturaSimulacao extends Model
{
    protected $table = 'MPME_PRECO_COBERTURA_SIMULACOES';
    protected $primaryKey  = 'ID_PRECO_COBERTURA_SIMULACOES';
    public $timestamps = false;
    protected $guarded = array();
}
