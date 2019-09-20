<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeSinistroStatus extends Model
{
    protected $table = 'MPME_SINISTRO_STATUS';
    protected $primaryKey = 'ID_MPME_SINISTRO_STATUS';
    public $timestamps = false;
    protected $guarded = array();
}
