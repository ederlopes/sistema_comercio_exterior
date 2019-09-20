<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Agenciabb;
use App\Gecex;

class Financpre extends Model
{

    protected $table = 'MPME_FINANC_PRE';

    protected $primaryKey = 'ID_FINANC_PRE';

    public $timestamps = false;

    protected $guarded = array();

    public function RetornaDadosAgencia($idFinanciador)
    {
        $agencia = Agenciabb::where('ID_AGENCIA', '=', $idFinanciador)->first();

        return $agencia;
    }

    public function RetornaDadosGecex($idFinanciador)
    {

        // Corrigindo bug com gecex 16 = BB
        $idFinanciador = ($idFinanciador == 16) ? $idFinanciador = 1086 : $idFinanciador;
        $gecex = Gecex::where('ID_USUARIO_FK', '=', $idFinanciador)->first();

        return $gecex;
    }

    public function Gecex()
    {
        return $this->hasOne('App\Gecex', 'ID_USUARIO_FK', 'ID_USUARIO_FINANCIADOR_FK');
    }
}
