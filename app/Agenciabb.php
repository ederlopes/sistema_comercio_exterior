<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Agenciabb extends Model
{

    protected $table = 'MPME_AGENCIAS_BB';

    protected $primaryKey = 'ID_AGENCIA';

    public $timestamps = false;

    protected $guarded = array();
}
