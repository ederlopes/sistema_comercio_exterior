<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeResposta extends Model
{
    protected $table = 'MPME_RESPOSTA';
    protected $primaryKey  = 'ID_MPME_RESPOSTA';
    public $timestamps = false;
    protected $guarded = array();

    public function perguntas()
    {
        return $this->belongsToMany(MpmePerguntas::class, 'MPME_PERGUNTA_RESPOSTA', 'ID_MPME_PERGUNTA', 'ID_MPME_PERGUNTA');
    }
}
