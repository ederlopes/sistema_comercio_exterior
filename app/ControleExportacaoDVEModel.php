<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ControleExportacaoDVEModel extends Model
{

    protected $table = 'MPME_DVE';

    protected $primaryKey = 'ID_DVE';

    public $timestamps = false;

    protected $guarded = array();

    public function ImportadoresEOperacoes()
    {
        return $this->belongsToMany(ImportadoresModel::class);
    }
}
