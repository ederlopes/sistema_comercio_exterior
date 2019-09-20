<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeDesembolso extends Model
{
    protected $table = 'MPME_DESEMBOLSO';
    protected $primaryKey  = 'ID_MPME_DESEMBOLSO';
    public $timestamps = false;
    protected $guarded = array();

    public function status(){
        return $this->belongsTo(MpmeStatus::class, 'ID_MPME_STATUS', 'ID_MPME_STATUS');
    }

}
