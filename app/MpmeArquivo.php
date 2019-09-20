<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class MpmeArquivo extends Model
{
    protected $table = 'MPME_ARQUIVO';
    protected $primaryKey  = 'ID_MPME_ARQUIVO';
    public $timestamps = false;
    protected $guarded = array();


}
