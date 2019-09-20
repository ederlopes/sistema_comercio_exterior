<?php
namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Log extends Model
{

    protected $table = 'LOG';

    protected $primaryKey = 'ID_LOG';

    public $timestamps = false;

    protected $guarded = array();

    public function usuario()
    {
        return $this->hasMany(User::class, 'ID_USUARIO', 'ID_USUARIO');
    }
}
