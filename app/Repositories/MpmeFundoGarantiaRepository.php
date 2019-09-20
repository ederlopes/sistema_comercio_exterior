<?php

namespace App\Repositories;

use App\MpmeFundoGarantia;
use Carbon\Carbon;
use DB;
use Auth;

class MpmeFundoGarantiaRepository extends Repository{

  public function __construct()
  {
        $this->setModel(MpmeFundoGarantia::class);
  }

  public static function getMpmeFundoGarantia()
  {
      return MpmeFundoGarantia::all()->where('IN_ATIVO', '=', 'SIM');
  }

}
