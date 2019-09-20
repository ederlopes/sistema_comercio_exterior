<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeHistQuestionario extends Model
{

    protected $table = 'MPME_HIST_QUESTIONARIO';

    protected $primaryKey = 'ID_MPME_HIST_QUESTIONARIO';

    public $timestamps = false;

    protected $guarded = array();

}
