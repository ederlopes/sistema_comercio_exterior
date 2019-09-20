<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeIndeferimento extends Model
{
	protected $table = 'MPME_INDEFERIMENTO';
    protected $primaryKey  = 'ID_INDEFERIDA';
    public $timestamps = false;
    protected $guarded = array();

}
