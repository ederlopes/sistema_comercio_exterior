<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeFinanceiroExportador extends Model
{
    protected $table = 'MPME_FINANCEIRO_EXPORTADOR';
    protected $primaryKey  = 'ID_MPME_FINANCEIRO_EXPORTADOR';
    public $timestamps = false;
    protected $guarded = array();

}
