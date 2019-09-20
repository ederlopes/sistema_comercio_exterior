<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class ClienteExportadoresModalidadeFinanciamento extends Model
{

    protected $table = 'CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO';

    protected $primaryKey = 'ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO';

    public $timestamps = false;

    protected $guarded = array();
}
