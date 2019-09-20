<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeFormulaPremio extends Model
{
	protected $table = 'MPME_FORMULA_PREMIO';
    protected $primaryKey  = 'ID_MPME_FORMULA_PREMIO';
    public $timestamps = false;
    protected $guarded = array();


    public function getFormulasPremioAtiva()
    {
        return $this->where('IN_ATIVA', '=', 'S')->orderBy('IN_ORDEM')->get();
    }

}
