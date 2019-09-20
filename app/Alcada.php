<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Alcada extends Model
{

    protected $table = 'MPME_ALCADA';

    protected $primaryKey = 'ID_MPME_ALCADA';

    public $timestamps = false;

    protected $guarded = array();

    public function mpme_alcada_valor()
    {
        return $this->hasOne(AlcadaValor::class, 'ID_MPME_ALCADA', 'ID_MPME_ALCADA')->whereNull('DT_FIM_VIGENCIA');
    }
}
