<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RecuperacaoSinistro extends Model
{
    protected $table = 'RECUPERACAO_SINISTRO';
    protected $primaryKey = 'ID_RECUPERACAO_SINISTRO';
    public $timestamps = false;
    protected $guarded = array();
}
