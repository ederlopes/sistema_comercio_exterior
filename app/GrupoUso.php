<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class GrupoUso extends Model
{

    protected $table = 'GRUPOUSU';

    protected $primaryKey = 'ID_GRUPO';

    public $timestamps = false;

    protected $guarded = array();
}
