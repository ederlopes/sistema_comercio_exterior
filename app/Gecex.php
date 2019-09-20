<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Gecex extends Model
{

    protected $table = 'MPME_GECEX_BB';

    protected $primaryKey = 'ID_GECEX_BB';

    public $timestamps = false;

    protected $guarded = array();
}
