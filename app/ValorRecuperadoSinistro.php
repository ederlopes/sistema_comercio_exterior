<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValorRecuperadoSinistro extends Model
{
    protected $table = 'VALOR_RECUPERADO_SINISTRO';
    protected $primaryKey = 'ID_VALOR_RECUPERADO_SINISTRO';
    public $timestamps = false;
    protected $guarded = array();
}
