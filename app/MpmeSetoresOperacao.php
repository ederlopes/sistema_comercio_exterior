<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeSetoresOperacao extends Model
{

    protected $table = 'MPME_SETORES_OPERACAO';

    protected $primaryKey = 'ID_MPME_SETORES_OPERACAO';

    public $timestamps = false;

    protected $guarded = array();

    public function operacoes()
    {
        return $this->belongsTo(ImportadoresModel::class, 'ID_OPER', 'ID_OPER');
    }

    public function setor()
    {
        return $this->belongsTo(TbSetores::class, 'ID_SETOR', 'ID_SETOR');
    }
}
