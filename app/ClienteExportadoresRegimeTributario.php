<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use Illuminate\Support\Facades\Auth;
use DB;

class ClienteExportadoresRegimeTributario extends Model
{

    protected $table = 'CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO';

    protected $primaryKey = 'ID_CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO';

    public $timestamps = false;

    protected $guarded = array();

    public static function getRegimeTributarioFornecedor()
    {
        return self::where('IN_REGISTRO_ATIVO', '=', 'S')->where('ID_MPME_CLIENTE_EXPORTADORES', '=', Auth::user()->exportador->ID_MPME_CLIENTE_EXPORTADORES)->get([
            'ID_CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO as ID_CLI_EXP_REG_TRIB',
            'ID_REGIME_TRIBUTARIO',
            'ID_ENQUADRAMENTO_TRIBUTARIO'
        ]);
    }
}
