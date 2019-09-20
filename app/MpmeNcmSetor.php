<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeNcmSetor extends Model
{
	  protected $table = 'MPME_NCM_SETOR';
    protected $primaryKey  = 'ID_NCM_SETOR';
    public $timestamps = false;
    protected $guarded = array();

}
