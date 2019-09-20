<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Mpme_Responsav_Assinatura_Cgc extends Model
{
    protected $table = 'MPME_RESPONS_ASSINATURA_CGC';
    protected $primaryKey  = 'ID_MPME_RESPONS_ASSINATURA_CGC';
    public $timestamps = false;
    protected $guarded = array();
}
