<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MpmeRecomendacaoExp extends Model
{
    protected $table = 'MPME_RECOMENDACAO_EXP';
    protected $primaryKey  = 'ID_RECOMENDACAO_EXP';
    public $timestamps = false;
    protected $guarded = array();

}
