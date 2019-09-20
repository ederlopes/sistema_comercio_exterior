<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeTipoEmbarque extends Model
{
	protected $table = 'MPME_TIPO_EMBARQUE';
    protected $primaryKey  = 'ID_MPME_TIPO_EMBARQUE';
    public $timestamps = false;
    protected $guarded = array();

    public function embarque(){
        return $this->hasOne(MpmeEmbarque::class, 'ID_MPME_TIPO_EMBARQUE', 'ID_MPME_TIPO_EMBARQUE');
    }
}
