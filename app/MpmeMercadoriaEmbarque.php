<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeMercadoriaEmbarque extends Model
{
	protected $table = 'MPME_MERCADORIA_EMBARQUE';
    protected $primaryKey  = 'ID_MPME_MERCADORIA_EMBARQUE';
    public $timestamps = false;
    protected $guarded = array();

    public function embarque(){
        return $this->belongsTo(MpmeEmbarque::class, 'ID_MPME_EMBARQUE', 'ID_MPME_EMBARQUE');
    }


}
