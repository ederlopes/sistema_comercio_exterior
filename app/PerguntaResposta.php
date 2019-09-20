<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PerguntaResposta extends Model
{
    protected $table = 'MPME_PERGUNTA_RESPOSTA';
    protected $primaryKey  = 'ID_MPME_PERGUNTA_RESPOSTA';
    public $timestamps = false;
    protected $guarded = array();

}
