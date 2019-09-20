<?php

namespace App\Repositories;


use App\MpmeControleCapital;
use Carbon\Carbon;
use DB;
use Auth;

class MpmeControleCapitalRepository extends Repository{

  public function __construct()
  {
        $this->setModel(MpmeControleCapital::class);
  }

  public function getValorFundo($id_mpme_fundo_garantia)
  {
    return $this::where('ID_MPME_FUNDO_GARANTIA', '=', $id_mpme_fundo_garantia)->get(['VL_FATURAMENTO_ATUAL']);
  }




}
