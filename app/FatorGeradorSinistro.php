<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class FatorGeradorSinistro extends Model
{

    protected $table = 'FATO_GERADOR_SINISTRO';

    protected $primaryKey = 'ID_FATO_GERADOR_SINISTRO';

    public $timestamps = false;

    protected $guarded = array();
}
