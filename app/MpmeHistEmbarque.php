<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeHistEmbarque extends Model
{
	protected $table = 'MPME_HIST_EMBARQUE';
    protected $primaryKey  = 'ID_MPME_HIST_EMBARQUE';
    public $timestamps = false;
    protected $guarded = array();

    public function embarque(){
        return $this->belongsTo(MpmeEmbarque::class, 'ID_MPME_EMBARQUE', 'ID_MPME_EMBARQUE');
    }

    public function status(){
        return $this->belongsTo(MpmeStatus::class, 'ID_MPME_STATUS', 'ID_MPME_STATUS');
    }



}
