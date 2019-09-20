<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class AlcadaValor extends Model
{

    protected $table = 'MPME_ALCADA_VALOR';

    protected $primaryKey = 'ID_MPME_ALCADA_VALOR';

    public $timestamps = false;

    protected $guarded = array();
}
