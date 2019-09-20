<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeTipoIndeferimento extends Model
{
	protected $table = 'MPME_TIPO_INDEFERIMENTO';
    protected $primaryKey  = 'ID_MPME_TIPO_INDEFERIMENTO';
    public $timestamps = false;
    protected $guarded = array();

}
