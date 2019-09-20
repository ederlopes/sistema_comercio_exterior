<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Pais extends Model
{
    protected $table = 'PAISES';
    protected $primaryKey  = 'ID_PAIS';
    public $timestamps = false;
    protected $guarded = array();

    public function ImportadoresEOperacoes(){
    	return $this->belongsToMany(ImportadoresModel::class);
    }


    public function getPais()
    {
        $rs_pais   = $this->where("REGISTRO_ATIVO", '=', 'S')
                          ->get();
        return $rs_pais;
    }

    public function getPaisRisco()
    {
        $rs_pais   = $this->where("REGISTRO_ATIVO", '=', 'S')
                          ->where('PAISES.ID_PAIS', '<>', 28)
                          ->whereNull('DT_FIM_VIG')
                          ->join('PAISES_VAL', 'PAISES_VAL.ID_PAIS', '=', 'PAISES.ID_PAIS')
                          ->orderBy('NM_ORDER')
                          ->get([
                              'PAISES.ID_PAIS',
                              'NM_PAIS',
                              'CD_RISCO',
                          ]);
        return $rs_pais;
    }

    public function getRiscoPaisBrasil()
    {
        $rs_pais   = $this->where("REGISTRO_ATIVO", '=', 'S')
                          ->where('PAISES.ID_PAIS', '=', 28)
                          ->whereNull('DT_FIM_VIG')
                          ->join('PAISES_VAL', 'PAISES_VAL.ID_PAIS', '=', 'PAISES.ID_PAIS')
                          ->orderBy('NM_ORDER')
                          ->get([
                                'PAISES.ID_PAIS',
                                'NM_PAIS',
                                'CD_RISCO',
                          ])[0];
        return $rs_pais;
    }

    public function getValorRelatorio($id_pais=null)
    {
        $rs_pais   = $this->where("REGISTRO_ATIVO", '=', 'S')
                          ->join('MPME_PRODUTO_PRECO_PAISES', 'MPME_PRODUTO_PRECO_PAISES.ID_PAIS', '=', 'PAISES.ID_PAIS')
                          ->join('MOEDA', 'MOEDA.MOEDA_ID', '=', 'MPME_PRODUTO_PRECO_PAISES.MOEDA_ID');

        if(isset($id_pais))
        {
            $rs_pais->whereIn('PAISES.ID_PAIS', $id_pais);
        }

        $rs_pais = $rs_pais->orderBy('NM_ORDER')->get(['PAISES.ID_PAIS',
                                                   'SIGLA_MOEDA',
                                                   'NM_PAIS',
                                                   'VALOR_PRODUTO',
                                                ]);

        return $rs_pais;
    }

    public function RiscoPais() {
        return $this->hasOne(PaisVal::class,'ID_PAIS','ID_PAIS');
    }

}
