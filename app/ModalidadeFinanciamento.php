<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModalidadeFinanciamento extends Model
{
    protected $table = 'MODALIDADE_FINANCIAMENTO';
    protected $primaryKey  = 'ID_MODALIDADE_FINANCIAMENTO';
    public $timestamps = false;
    protected $guarded = array();
    
    
   public function enquadramento(){
       
       
        return $this->hasOne(EnquadramentoFinanceiroCliente::class, 'ID_MODALIDADE', 'ID_MODALIDADE');
       
   } 

   public function Modalidade(){

        return $this->belongsTo(ModalidadeModel::class, 'ID_MODALIDADE', 'ID_MODALIDADE');   

   }
   
}
