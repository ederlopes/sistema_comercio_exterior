<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MpmeRespostaIndeferimento extends Model
{
    protected $table = 'MPME_RESPOSTA_INDEFERIMENTO';
    protected $primaryKey  = 'ID_MPME_INDEFERIMENTO';
    public $timestamps = false;
    protected $guarded = array();

}
