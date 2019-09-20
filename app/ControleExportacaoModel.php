<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ControleExportacaoModel extends Model
{

    protected $table = 'MPME_CONTROLE_EXPORTACAO';

    protected $primaryKey = 'ID_MPME_CONTROLE_EXPORTACAO';

    public $timestamps = false;

    protected $guarded = array();

    public function ImportadoresEOperacoes()
    {
        return $this->belongsToMany(ImportadoresModel::class);
    }
}
