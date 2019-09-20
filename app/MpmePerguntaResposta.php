<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmePerguntaResposta extends Model
{
    protected $table = 'MPME_PERGUNTA_RESPOSTA';
    protected $primaryKey  = 'ID_MPME_PERGUNTA_RESPOSTA';
    public $timestamps = false;
    protected $guarded = array();

    public function resposta()
    {
        return $this->belongsTo(MpmeResposta::class, 'ID_MPME_RESPOSTA');
    }

    public function pergunta()
    {
        return
            $this->belongsTo(MpmePergunta::class, 'ID_MPME_PERGUNTA');
    }
}
