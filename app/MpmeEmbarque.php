<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeEmbarque extends Model
{
	protected $table = 'MPME_EMBARQUE';
    protected $primaryKey  = 'ID_MPME_EMBARQUE';
    public $timestamps = false;
    protected $guarded = array();

    public function tipo_embarque(){
        return $this->belongsTo(MpmeTipoEmbarque::class, 'ID_MPME_TIPO_EMBARQUE', 'ID_MPME_TIPO_EMBARQUE');
    }

    public function status(){
        return $this->belongsTo(MpmeStatus::class, 'ID_MPME_STATUS', 'ID_MPME_STATUS');
    }

    public function proposta(){
        return $this->belongsTo(MpmeProposta::class, 'ID_MPME_PROPOSTA', 'ID_MPME_PROPOSTA');
    }

    public function mercadorias(){
        return $this->hasMany(MpmeMercadoriaEmbarque::class, 'ID_MPME_EMBARQUE', 'ID_MPME_EMBARQUE');
    }

}
