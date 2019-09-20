<?php
namespace App\Repositories;
use App\MpmeRestricaoAbgf;
use DB;
use App\MpmeTempoValidacao;
use Auth;
use phpDocumentor\Reflection\Types\Self_;

class MpmeRestricaoRepository extends Repository{

    public function __construct()
    {
        $this->setModel(MpmeRestricaoAbgf::class);
    }


    public function getRestricoesAbgfPais($request)
    {
        return $this->whereNull('DT_VIGENCIA_FIM')->whereNull('ID_SETOR')->get();
    }

    public function getRestricoesAbgfSetores($request)
    {
        return $this->whereNull('DT_VIGENCIA_FIM')->whereNull('ID_PAIS')->get();
    }

}
