<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagamentoEmAtrasoSinistro extends Model
{
    protected $table = 'PAGAMENTO_EM_ATRASO_SINISTRO';
    protected $primaryKey = 'ID_PAGAMENTO_EM_ATRASO_SINISTRO';
    public $timestamps = false;
    protected $guarded = array();
}
