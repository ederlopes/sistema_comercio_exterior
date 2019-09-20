<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Agenciabb;
use App\Gecex;
use DB;

class Financpos extends Model
{

    protected $table = 'MPME_FINANC';

    protected $primaryKey = 'ID_FINANC';

    public $timestamps = false;

    protected $guarded = array();

    public function RetornaDadosAgencia($idFinanciador)
    {
        $agencia = Agenciabb::where('ID_AGENCIA', '=', $idFinanciador)->first();

        return $agencia;
    }

    public function RetornaDadosGecex($idFinanciador)
    {
        $gecex = Gecex::where('ID_USUARIO_FK', '=', $idFinanciador)->first();

        return $gecex;
    }

    public function RetornaConfirmaDadosExportador($idUsuario, $idFinanciador)
    {
        $confim = DB::table('MPME_CONFIRMA_DADOS_EXPORTADOR')->where('ID_USUARIO_FK', '=', $idUsuario)
            ->where('NU_TELA', '=', 3)
            ->first([
            'IC_STATUS'
        ]);

        return $confim;
    }

    public function RetornaInforAdicionalExportador($idUsuario, $idFinanciador)
    {
        $InforAdicional = DB::table('MPME_INF_ADICIONAL_EXPORTADOR')->where('ID_USUARIO_FK', '=', $idUsuario)
            ->where('ID_FINANCIADOR_FK', '=', $idFinanciador)
            ->first([
            'DS_RESP1',
            'DS_RESP2',
            'DS_RESP3',
            'DS_RESP4'
        ]);

        return $InforAdicional;
    }

    public function RetornaDivergenciaExportador($idUsuario, $idFinanciador)
    {
        $InforAdicional = DB::table('MPME_DIVERGENCIA_EXPORTADOR')->where('ID_USUARIO_FK', '=', $idUsuario)
            ->where('ID_FINANCIADOR_FK', '=', $idFinanciador)
            ->first([
            'DS_DIVERGENCIA'
        ]);

        return $InforAdicional;
    }

    public function Gecex()
    {
        return $this->hasOne('App\Gecex', 'ID_USUARIO_FK', 'ID_USUARIO_FINANCIADOR_FK');
    }
}
