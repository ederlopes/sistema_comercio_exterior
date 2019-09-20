<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Grupos extends Model
{

    protected $table = 'GRUPOS';

    protected $primaryKey = 'ID_GRUPOS';

    public $timestamps = false;

    protected $guarded = array();
}
