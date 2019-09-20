<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MoedaModel extends Model
{
	protected $table = 'MOEDA';
    protected $primaryKey  = 'MOEDA_ID';
    public $timestamps = false;
    protected $guarded = array();

    const ID_MOEDA_DOLAR = 1;
    const ID_MOEDA_EURO  = 3;

    public function Usuario(){
    	return $this->belongsToMany(User::class);
    }


    public function getMoeda()
    {
        $rs_moedas   = $this->where("LIBERADA_PARA_OPERACAO", '=', 'S')
                            ->whereIn('MOEDA_ID', [$this::ID_MOEDA_DOLAR, $this::ID_MOEDA_EURO])
                            ->get();
        return $rs_moedas;
    }
}
