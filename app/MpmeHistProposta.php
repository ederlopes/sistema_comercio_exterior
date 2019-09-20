<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MpmeHistProposta extends Model
{
    protected $table = 'MPME_HIST_PROPOSTA';
    protected $primaryKey  = 'ID_MPME_HIST_PROPOSTA';
    public $timestamps = false;
    protected $guarded = array();

}
