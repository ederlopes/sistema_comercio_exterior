<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeControleLimiteCliente extends Model
{

    protected $table = 'MPME_CONTROLE_LIMITE_CLIENTE';

    protected $primaryKey = 'ID_MPME_CONTROLE_LIMITE_CLIENTE';

    public $timestamps = false;

    protected $guarded = array();

}
