<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeStatus extends Model
{
    protected $table = 'MPME_STATUS';
    protected $primaryKey  = 'ID_MPME_STATUS';
    public $timestamps = false;
    protected $guarded = array();

}
