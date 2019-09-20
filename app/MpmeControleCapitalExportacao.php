<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeControleCapitalExportacao extends Model
{

    protected $table = 'MPME_CONTROLE_CAPITAL_EXPORTACAO';

    protected $primaryKey = 'ID_MPME_CONTROLE_CAPITAL_EXPORTACAO';

    public $timestamps = false;

    protected $guarded = array();

}
