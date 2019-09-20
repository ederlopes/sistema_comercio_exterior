<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\MpmeClienteExportador;
class ClienteExportadorModalidadeFinanciamento extends Model
{

    protected $table = 'CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO';

    protected $primaryKey = 'ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO';

    public $timestamps = false;

    protected $guarded = array();

    public function ModalidadeFinanciamento()
    {
        return $this->hasOne('App\ModalidadeFinanciamento', 'ID_MODALIDADE_FINANCIAMENTO', 'ID_MODALIDADE_FINANCIAMENTO')->with('enquadramento','Modalidade');
    }
    
    public function MpmeClienteExportador()
    {
        return $this->hasOne('App\MpmeClienteExportador',
                'ID_MPME_CLIENTE_EXPORTADORES','ID_MPME_CLIENTE_EXPORTADORES')
                ->with('Usuario');
    }
    
   
    
}
