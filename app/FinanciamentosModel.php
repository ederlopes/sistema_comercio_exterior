<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class FinanciamentosModel extends Model
{

    protected $table = 'FINANCIAMENTO';

    protected $primaryKey = 'ID_FINANCIAMENTO';

    public $timestamps = false;

    protected $guarded = array();
}
