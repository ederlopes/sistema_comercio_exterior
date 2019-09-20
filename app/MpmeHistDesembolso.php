<?php

namespace App;

use App\Repositories\UsersRepository;
use Illuminate\Database\Eloquent\Model;

class MpmeHistDesembolso extends Model
{
    protected $table = 'MPME_HIST_DESEMBOLSO';
    protected $primaryKey  = 'ID_MPME_HIST_DESEMBOLSO';
    public $timestamps = false;
    protected $guarded = array();

    public function status(){
        return $this->belongsTo(MpmeStatus::class, 'ID_MPME_STATUS', 'ID_MPME_STATUS');
    }

    public function usuario(){
        return $this->belongsTo(User::class, 'ID_USUARIO_CAD', 'ID_USUARIO');
    }
    
}
