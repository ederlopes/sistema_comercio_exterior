<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeClienteExportador extends Model
{
    protected $table = 'MPME_CLIENTE_EXPORTADORES';
    protected $primaryKey = 'ID_MPME_CLIENTE_EXPORTADORES';
    public $timestamps = false;
    protected $guarded = array();
//
    //    public function usuario()
    //    {
    //        return $this->belongsTo(Usuario::class, 'ID_USUARIO');
    //    }

    public function ModalidadeFinanciamento()
    {
        return $this->hasMany('App\ClienteExportadorModalidadeFinanciamento',
            'ID_MPME_CLIENTE_EXPORTADORES', 'ID_MPME_CLIENTE_EXPORTADORES')
            ->with('ModalidadeFinanciamento');
    }

    public function Usuario()
    {
        return $this->hasOne('App\User', 'ID_USUARIO', 'ID_USUARIO')->with('Banco');
    }

    public function FinanceiroExportador()
    {
        return $this->hasOne(MpmeFinanceiroExportador::class,
            'ID_MPME_CLIENTE_EXPORTADORES', 'ID_MPME_CLIENTE_EXPORTADORES')
            ->where('IN_ATIVO', 'S');

    }

    public function ClienteExportadoresModalidadeFinanciamento()
    {
        return $this->hasMany(ClienteExportadoresModalidadeFinanciamento::class, 'ID_MPME_CLIENTE_EXPORTADORES', 'ID_MPME_CLIENTE_EXPORTADORES');
    }

}
