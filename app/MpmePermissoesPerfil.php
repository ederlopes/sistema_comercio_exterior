<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use MpmeCriterioOperacao;
use App\Repositores\MpmeCriterioOperacaoRepository;
use App\Alcada;
Use App\MpmeRecomendacao;

class MpmePermissoesPerfil extends Model
{
	protected $table = 'MPME_PERMISSOES_PERFIL';
    protected $primaryKey  = 'ID_MPME_PERMISSOES_PERFIL';
    public $timestamps = false;
    protected $guarded = array();

    public function perfil(){
        return $this->belongsTo(MpmePerfil::class, 'ID_PERFIL','ID_PERFIL');
    }
}
