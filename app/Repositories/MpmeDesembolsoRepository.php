<?php

namespace App\Repositories;

use App\Alcada;
use App\ImportadoresModel;
use App\MpmeAprovacaoValorAlcada;
use App\MpmeDesembolso;
use App\MpmeHistDesembolso;
use Carbon\Carbon;
use DB;
use Auth;
use http\Env\Request;
use App\MpmeProposta;

class MpmeDesembolsoRepository extends Repository{

  public function __construct()
  {
        $this->setModel(MpmeDesembolso::class);
  }


    public function salvar_desembolso($request)
    {
        $desembolso           = null;
        $id_mpme_desembolso   = ( isset($request->id_mpme_desembolso)) ? $request->id_mpme_desembolso : NULL;

        if ( $id_mpme_desembolso > 0 )
        {
            $desembolso = MpmeDesembolso::find($id_mpme_desembolso);
        }

        if (!isset($desembolso))
        {
            $desembolso = new MpmeDesembolso();
        }


        DB::beginTransaction();

        $desembolso->ID_MPME_PROPOSTA        = $request->id_mpme_proposta;
        $desembolso->ID_MPME_STATUS          = $request->id_mpme_status;
        $desembolso->VL_DESEMBOLSO           = converte_float($request->vl_desembolso);
        $desembolso->DT_DESEMBOLSO           = formatar_data_sql($request->dt_desembolso);
        $desembolso->DT_VENCIMENTO           = formatar_data_sql($request->dt_vencimento);
        $desembolso->DT_CADASTRO             = Carbon::now();
        $desembolso->ID_USUARIO_CAD          = Auth::user()->ID_USUARIO;

        if(!$desembolso->save())
        {
            DB::rollback();
            return false;
        }

        $mpme_hist_desembolso = new MpmeHistDesembolso();

        $msg = ($id_mpme_desembolso != "") ? 'DADOS ALTERADOS COM SUCESSO PELO CONFERENTE' : 'DADOS CADASTRADOS COM SUCESSO PELO CONFERENTE';

        $mpme_hist_desembolso->ID_MPME_DESEMBOLSO       = $desembolso->ID_MPME_DESEMBOLSO;
        $mpme_hist_desembolso->ID_MPME_STATUS           = $desembolso->ID_MPME_STATUS;
        $mpme_hist_desembolso->DS_OBSERVACAO            = $msg;
        $mpme_hist_desembolso->DT_CADASTRO              = Carbon::now();
        $mpme_hist_desembolso->ID_USUARIO_CAD           = Auth::user()->ID_USUARIO;

        if(!$mpme_hist_desembolso->save())
        {
            DB::rollback();
            return false;
        }

        DB::commit();
        return $desembolso;

    }

    public function recusar_desembolso($request)
    {
        $id_mpme_desembolso                 = ( isset($request->id_mpme_desembolso)) ? $request->id_mpme_desembolso : NULL;
        $desembolso                         = MpmeDesembolso::find($id_mpme_desembolso);

        DB::beginTransaction();

        $desembolso->ID_MPME_STATUS         = $request->id_mpme_status;

        if(!$desembolso->save())
        {
            DB::rollback();
            return false;
        }

        $mpme_hist_desembolso                           = new MpmeHistDesembolso();
        $mpme_hist_desembolso->ID_MPME_DESEMBOLSO       = $desembolso->ID_MPME_DESEMBOLSO;
        $mpme_hist_desembolso->ID_MPME_STATUS           = $desembolso->ID_MPME_STATUS;
        $mpme_hist_desembolso->DS_OBSERVACAO            = 'DESEMBOLSO RECUSADO PELO VALIDADOR';
        $mpme_hist_desembolso->DT_CADASTRO              = Carbon::now();
        $mpme_hist_desembolso->ID_USUARIO_CAD           = Auth::user()->ID_USUARIO;

        if(!$mpme_hist_desembolso->save())
        {
            DB::rollback();
            return false;
        }

        DB::commit();
        return $desembolso;

    }


    public function aprovar_desembolso($request)
    {
        $id_mpme_desembolso                 = ( isset($request->id_mpme_desembolso)) ? $request->id_mpme_desembolso : NULL;
        $desembolso                         = MpmeDesembolso::find($id_mpme_desembolso);

        $modalidadeProposta                 = retornaModalidadeFromIdProposta($request->id_proposta);

        DB::beginTransaction();

        if($modalidadeProposta == 1 || $modalidadeProposta == 2){

            $proposta = MpmeProposta::find($request->id_proposta);
            $proposta->ID_MPME_STATUS_PROPOSTA = 14; // Concretizada;
            $proposta->save();

            if(!$proposta){
                DB::rollback();
                return false; 
            }

        }


        

        $desembolso->ID_MPME_STATUS         = $request->id_mpme_status;

        if(!$desembolso->save())
        {
            DB::rollback();
            return false;
        }

        $mpme_hist_desembolso                           = new MpmeHistDesembolso();
        $mpme_hist_desembolso->ID_MPME_DESEMBOLSO       = $desembolso->ID_MPME_DESEMBOLSO;
        $mpme_hist_desembolso->ID_MPME_STATUS           = $desembolso->ID_MPME_STATUS;
        $mpme_hist_desembolso->DS_OBSERVACAO            = 'DESEMBOLSO APROVADO PELO VALIDADOR';
        $mpme_hist_desembolso->DT_CADASTRO              = Carbon::now();
        $mpme_hist_desembolso->ID_USUARIO_CAD           = Auth::user()->ID_USUARIO;

        if(!$mpme_hist_desembolso->save())
        {
            DB::rollback();
            return false;
        }

        DB::commit();
        return $desembolso;

    }




    public function listarDesembolso($id_mpme_proposta, $id_mpme_desembolso=null)
    {
        $lista_desembolso = MpmeDesembolso::where('ID_MPME_PROPOSTA', '=', $id_mpme_proposta);

        if ($id_mpme_desembolso!=null)
        {
            $lista_desembolso = $lista_desembolso->where('ID_MPME_DESEMBOLSO', '=', $id_mpme_desembolso);
        }

        $lista_desembolso = $lista_desembolso->get();

        return $lista_desembolso;
    }


}
