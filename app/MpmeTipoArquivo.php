<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeTipoArquivo extends Model
{
	protected $table = 'MPME_TIPO_ARQUIVO';
    protected $primaryKey  = 'ID_MPME_TIPO_ARQUIVO';
    public $timestamps = false;
    protected $guarded = array();

}
