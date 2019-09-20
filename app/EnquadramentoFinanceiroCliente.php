<?php
namespace App;

use Illuminate\Database\Eloquent\Model;
use App\User;

class EnquadramentoFinanceiroCliente extends Model
{

    protected $table = 'ENQUADRAMENTO_FINANCEIRO_CLIENTE';

    protected $primaryKey = 'ID_ENQUADRAMENTO_FINANCEIRO_CLIENTE';

    public $timestamps = false;

    protected $guarded = array();
}
