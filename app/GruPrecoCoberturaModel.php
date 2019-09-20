<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class GruPrecoCoberturaModel extends Model
{

    protected $table = 'MPME_GRU_PRECO_COBERTURA';

    protected $primaryKey = 'ID_MPME_GRU_PRECO_COBERTURA';

    public $timestamps = false;

    protected $guarded = array();

    public function ImportadoresEOperacoes()
    {
        return $this->belongsToMany(ImportadoresModel::class);
    }
}
