<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeStatusProposta extends Model
{
	protected $table = 'MPME_STATUS_PROPOSTA';
    protected $primaryKey  = 'ID_MPME_STATUS_PROPOSTA';
    public $timestamps = false;
    protected $guarded = array();


    public function mpme_proposta()
    {
        return $this->hasOne(MpmeProposta::class, 'ID_MPME_STATUS_PROPOSTA', 'ID_MPME_STATUS_PROPOSTA');
    }

}
