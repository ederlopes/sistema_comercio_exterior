<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeCalculadora extends Model
{
	protected $table = 'MPME_CALCULADORA';
    protected $primaryKey  = 'ID_MPME_CALCULADORA';
    public $timestamps = false;
    protected $guarded = array();


    public static function getCalculadoraVigente()
    {
        $calculadora_vigente = self::whereNull('DT_FIM_PERIODO')->get(['ID_MPME_CALCULADORA']);
        return $calculadora_vigente[0]->ID_MPME_CALCULADORA;
    }

}
