<?php

namespace App\Repositories;

use App\Alcada;
use App\ImportadoresModel;
use App\MpmeAprovacaoValorAlcada;
use App\MpmeHistDesembolso;
use Carbon\Carbon;
use DB;
use Auth;

class MpmeHistoricoDesembolsoRepository extends Repository{

  public function __construct()
  {
        $this->setModel(MpmeHistDesembolso::class);
  }

    public function getAprovacao($id_mpme_desembolso)
    {
        return $this->where('MPME_HIST_DESEMBOLSO.ID_MPME_DESEMBOLSO', '=', $id_mpme_desembolso)
            ->orderByDesc('MPME_HIST_DESEMBOLSO.ID_MPME_HIST_DESEMBOLSO')
            ->get();
    }



}
