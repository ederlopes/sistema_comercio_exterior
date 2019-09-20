<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ClienteExportadorRegimeTributario extends Model
{

    protected $table = 'CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO';

    protected $primaryKey = 'ID_CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO';

    public $timestamps = false;

    protected $guarded = array();
}
