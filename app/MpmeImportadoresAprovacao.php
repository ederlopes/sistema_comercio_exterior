<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;

use App\Repositores\MpmeImportadoresAprovacaoRepository;

class MpmeImportadoresAprovacao extends Model
{
		protected $table = 'MPME_IMPORTADORES_APROVACAO';
    protected $primaryKey  = 'ID_TEMPO_VALIDACAO';
    public $timestamps = false;
    protected $guarded = array();


}
