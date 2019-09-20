<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmePropostaAprovacao extends Model
{
	protected $table = 'MPME_PROPOSTA_APROVACAO';
    protected $primaryKey  = 'ID_MPME_PROPOSTA_APROVACAO';
    public $timestamps = false;
    protected $guarded = array();


    public function mpme_proposta()
    {
        return $this->belongsTo(MpmeProposta::class, 'ID_MPME_PROPOSTA', 'ID_MPME_PROPOSTA');
    }

    public function mpme_alcada()
    {
        return $this->belongsTo(Alcada::class, 'ID_MPME_ALCADA', 'ID_MPME_ALCADA');
    }

}
