<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TbSetores extends Model
{
	  protected $table = 'TB_SETORES';
    protected $primaryKey  = 'ID_SETOR';
    public $timestamps = false;
    protected $guarded = array();

}
