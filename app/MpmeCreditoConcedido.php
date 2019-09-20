<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Auth;
use DB;
use App\Repositories\MpmeCreditoConcedidoRepository;

class MpmeCreditoConcedido extends Model
{
    protected $table = 'MPME_CREDITO_CONCEDIDO';
    protected $primaryKey  = 'ID_CREDITO';
    public $timestamps = false;
    protected $guarded = array();


}
