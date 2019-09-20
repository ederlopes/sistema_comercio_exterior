<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModalidadeModel extends Model
{
	protected $table = 'MODALIDADE';
    protected $primaryKey  = 'ID_MODALIDADE';
    public $timestamps = false;
    protected $guarded = array();

}
