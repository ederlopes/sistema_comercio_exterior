<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PerguntaModel extends Model
{
    protected $table = 'MPME_PERGUNTA';
    protected $primaryKey  = 'ID_MPME_PERGUNTA';
    public $timestamps = false;
    protected $guarded = array();


}
