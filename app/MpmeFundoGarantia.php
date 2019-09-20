<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class MpmeFundoGarantia extends Model
{
    protected   $table       = 'MPME_FUNDO_GARANTIA';
    protected   $primaryKey  = 'ID_MPME_FUNDO_GARANTIA';
    public      $timestamps  = false;
    protected   $guarded     = array();

}
