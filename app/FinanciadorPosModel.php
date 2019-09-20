<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class FinanciadorPosModel extends Model
{

    protected $table = 'MPME_FINANC';

    protected $primaryKey = 'ID_FINANC';

    public $timestamps = false;

    protected $guarded = array();
}
