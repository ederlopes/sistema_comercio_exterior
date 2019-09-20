<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class PermissoesAdmin extends Model
{
    protected $table = 'permissoesadmin';
    protected $primaryKey  = 'idpermissoesadmin';
    public $timestamps = false;
    protected $guarded = array();



}
