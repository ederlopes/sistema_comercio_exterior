<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MotivoNaoDeferimentoSinistro extends Model
{
    protected $table = 'MOTIVO_NAO_DEFERIMENTO_SINISTRO';
    protected $primaryKey  = 'ID_MOTIVO_NAO_DEFERIMENTO_SINISTRO';
    public $timestamps = false;
    protected $guarded = array();

}
