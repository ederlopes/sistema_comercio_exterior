<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class CreditoConcedidoModel extends Model
{

    protected $table = 'MPME_CREDITO_CONCEDIDO';

    protected $primaryKey = 'ID_CREDITO';

    public $timestamps = false;

    protected $guarded = array();

    public function ImportadoresEOperacoes()
    {
        return $this->belongsToMany(ImportadoresModel::class);
    }
}
