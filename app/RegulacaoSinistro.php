<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegulacaoSinistro extends Model
{
    protected $table = 'REGULACAO_SINISTRO';
    protected $primaryKey = 'ID_REGULACAO_SINISTRO';
    public $timestamps = false;
    protected $guarded = array();

}
