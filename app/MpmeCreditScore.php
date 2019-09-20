<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use MpmeCriterioOperacao;
use App\Repositores\MpmeCriterioOperacaoRepository;
use App\Alcada;
Use App\MpmeRecomendacao;

class MpmeCreditScore extends Model
{
	protected $table = 'MPME_CREDIT_SCORE';
    protected $primaryKey  = 'ID_CREDIT_SCORE';
    public $timestamps = false;
    protected $guarded = array();



    public function Alcada(){
         return $this->hasOne(Alcada::class, 'ID_MPME_ALCADA','ID_MPME_ALCADA');
    }

    public function RecomendacaoAlcada(){
        return $this->hasOne(MpmeRecomendacao::class, 'ID_OPER','ID_OPER');
    }

    public function Arquivo(){
        return $this->hasOne(MpmeArquivo::class, 'ID_MPME_ARQUIVO','ID_MPME_ARQUIVO');
    }

}
