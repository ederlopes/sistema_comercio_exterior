<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MpmeSinistro extends Model
{
    protected $table = 'MPME_SINISTRO';
    protected $primaryKey = 'ID_MPME_SINISTRO';
    public $timestamps = false;
    protected $guarded = array();


    public function RetornaPagamentoEmAtraso(){
        return $this->hasMany('App\PagamentoEmAtrasoSinistro', 'ID_MPME_SINISTRO', 'ID_MPME_SINISTRO');
    }

    public function ValorRecuperadoSinistro(){
        return $this->hasMany('App\ValorRecuperadoSinistro', 'ID_MPME_SINISTRO', 'ID_MPME_SINISTRO');
    }

    public function RetornaRecuperacaoSinistro(){
        return $this->hasMany('App\RecuperacaoSinistro', 'ID_MPME_SINISTRO', 'ID_MPME_SINISTRO');
    }

    public function RetornaValorRecuperado($ID_SINISTRO){
        $vl = RecuperacaoSinistro::where('ID_MPME_SINISTRO', '=',$ID_SINISTRO)->get();
        $vlRecuperadoSinistro = ValorRecuperadoSinistro::where('ID_MPME_SINISTRO', '=',$ID_SINISTRO)->get();
        $pg_atraso = PagamentoEmAtrasoSinistro::where('ID_MPME_SINISTRO', '=',$ID_SINISTRO)->get();


        $sum = 0;
        foreach ($vl as $valor){
            $sum += $valor['VA_PAGO_RECUPERACAO_SINISTRO'];
        }

        foreach ($pg_atraso as $valorAtraso){
            $sum += $valorAtraso['VA_PAGAMENTO_EM_ATRASO_SINISTRO'];
        }

        foreach ($vlRecuperadoSinistro as $valor){
            $sum += $valor['VA_VALOR_RECUPERADO_SINISTRO'];
        }

        return number_format($sum, 2, ',', '.');
    }

    public function RetornaSaldoDevedor($ID_SINISTRO,$vlARecuperar){
        $vl = RecuperacaoSinistro::where('ID_MPME_SINISTRO', '=',$ID_SINISTRO)->get();

        $vlRecuperadoSinistro = ValorRecuperadoSinistro::where('ID_MPME_SINISTRO', '=',$ID_SINISTRO)->get();

        $pg_atraso = PagamentoEmAtrasoSinistro::where('ID_MPME_SINISTRO', '=',$ID_SINISTRO)->get();


        $sum = 0;

        foreach ($vl as $valor){
            $sum += $valor['VA_PAGO_RECUPERACAO_SINISTRO'];
        }

        foreach ($pg_atraso as $valorAtraso){
            $sum += $valorAtraso['VA_PAGAMENTO_EM_ATRASO_SINISTRO'];
        }

        foreach ($vlRecuperadoSinistro as $valor){
            $sum += $valor['VA_VALOR_RECUPERADO_SINISTRO'];
        }


        $sum = $vlARecuperar - $sum;

        return number_format($sum, 2, ',', '.');

    }

    public function Operacao(){
        return $this->hasOne('App\ImportadoresModel','ID_OPER','ID_OPER')->with('propostas');
    }

    public function Status(){
        return $this->hasOne('App\MpmeSinistroStatus','ID_MPME_SINISTRO_STATUS','ID_MPME_SINISTRO_STATUS');
    }


    public function Embarque(){
        return $this->hasOne('App\MpmeEmbarque','ID_MPME_PROPOSTA','ID_MPME_PROPOSTA');
    }

}
