<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class RespostaModel extends Model
{
    protected $table = 'MPME_RESPOSTA';
    protected $primaryKey  = 'ID_MPME_RESPOSTA';
    public $timestamps = false;
    protected $guarded = array();


}
