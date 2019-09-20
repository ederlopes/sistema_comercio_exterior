<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use MpmeCriterioOperacao;
use App\Repositores\MpmeCriterioOperacaoRepository;
use App\Alcada;
Use App\MpmeRecomendacao;

class MpmePermissoes extends Model
{
	protected $table = 'MPME_PERMISSOES';
    protected $primaryKey  = 'ID_MPME_PERMISSOES';
    public $timestamps = false;
    protected $guarded = array();

    public function permisoes_perfil(){
         return $this->hasMany(MpmePermissoesPerfil::class, 'ID_MPME_PERMISSOES','ID_MPME_PERMISSOES')
                     ->with('perfil');
    }


}
