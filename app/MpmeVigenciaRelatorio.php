<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeVigenciaRelatorio extends Model
{

    protected $table = 'MPME_VIGENCIA_RELATORIO';

    protected $primaryKey = 'ID_MPME_VIGENCIA_RELATORIO';

    public $timestamps = false;

    protected $guarded = array();


}
