<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoRevogacaoSinistro extends Model
{
    protected $table = 'MOTIVO_REVOGACAO_SINISTRO';
    protected $primaryKey = 'ID_MOTIVO_REVOGACAO_SINISTRO';
    public $timestamps = false;
    protected $guarded = array();
}