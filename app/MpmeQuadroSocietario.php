<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;
use DB;

class MpmeQuadroSocietario extends Model
{
    protected $table = 'MPME_QUADRO_SOCIETARIO';
    protected $primaryKey  = 'ID_MPME_QUADRO_SOCIETARIO';
    public $timestamps = false;
    protected $guarded = array();


}
