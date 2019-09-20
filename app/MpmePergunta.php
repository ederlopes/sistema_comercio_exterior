<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmePergunta extends Model
{
    protected $table = 'MPME_PERGUNTA';
    protected $primaryKey  = 'ID_MPME_PERGUNTA';
    public $timestamps = false;
    protected $guarded = array();

    public function respostas()
    {
        return $this->hasMany(MpmePerguntaResposta::class, 'ID_MPME_PERGUNTA')->orderBy('MPME_PERGUNTA_RESPOSTA.ID_MPME_RESPOSTA');
    }

    public function getPergunta( $in_origem )
    {
        $rs_pergunta =  $this->where('IN_ATIVO', '=', 'S')
                             ->where('IN_ORIGEM', '=', $in_origem)
                             ->get();

        return $rs_pergunta;
    }
}
