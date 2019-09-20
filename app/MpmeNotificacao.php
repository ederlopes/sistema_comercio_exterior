<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeNotificacao extends Model
{
	protected $table = 'MPME_NOTIFICACAO';
    protected $primaryKey  = 'ID_NOTIFICACAO';
    public $timestamps = false;
    protected $guarded = array();

}
