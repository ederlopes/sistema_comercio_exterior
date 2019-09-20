<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notificacoes extends Model
{
    protected $table = 'MPME_NOTIFICACAO';
    protected $primaryKey = 'ID_NOTIFICACAO';
    public $timestamps = false;
    protected $guarded = array();

    public function RetornaTotalNotificacaoAtivas()
    {

        $notificacoes = Notificacoes::where('IC_ATIVO', '=', 1)
            ->whereNotNull('ID_AREA_FK')
            ->count();

        return $notificacoes;
    }

    public function Exportador()
    {
        return $this->hasOne('App\User', 'ID_USUARIO', 'ID_USUARIO_FK');
    }

    public function Banco()
    {
        return $this->hasOne('App\MpmeFinanc', 'ID_USUARIO', 'ID_USUARIO_FK')
            ->with('Gecex');
    }

    public function BancoPre()
    {
        return $this->hasOne('App\Financpre', 'ID_USUARIO', 'ID_USUARIO_FK')
            ->with('Gecex');
    }

    public function ClienteExportador()
    {
        return $this->hasOne('App\MpmeClienteExportador', 'ID_USUARIO', 'ID_USUARIO_FK')
            ->with('ModalidadeFinanciamento');
    }

    public function InfoAdicionalExportador()
    {
        return $this->hasOne('App\MpmeInfAdicionalExportador', 'ID_USUARIO_FK', 'ID_USUARIO_FK');
    }

}
