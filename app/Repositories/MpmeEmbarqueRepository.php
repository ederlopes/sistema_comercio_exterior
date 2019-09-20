<?php

namespace App\Repositories;

use App\MpmeEmbarque;
use App\MpmeHistEmbarque;
use App\MpmeMercadoriaEmbarque;
use App\MpmeProposta;
use Auth;
use Carbon\Carbon;
use DB;

class MpmeEmbarqueRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(MpmeEmbarque::class);
    }

    public function salvar_embarque($request)
    {
        $embarque = (isset($request->id_mpme_embarque)) ? MpmeEmbarque::find($request->id_mpme_embarque) : new MpmeEmbarque();

        DB::beginTransaction();

        $embarque->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
        $embarque->ID_MPME_TIPO_EMBARQUE = $request->id_mpme_tipo_embarque;
        $embarque->ID_MPME_STATUS = $request->id_mpme_status;
        $embarque->VL_EMBARQUE = converte_float($request->vl_embarque);
        $embarque->VL_FINANCIAMENTO = converte_float($request->vl_financiamento);
        $embarque->NU_FATURA = $request->nu_fatura;
        $embarque->NU_DUE = $request->nu_due;
        $embarque->NU_RVS = $request->nu_rvs;

        $embarque->DT_EMBARQUE = formatar_data_sql($request->dt_embarque);
        $embarque->DT_VENCIMENTO = formatar_data_sql($request->dt_vencimento);
        $embarque->DT_CADASTRO = Carbon::now();
        $embarque->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        if (!$embarque->save()) {
            DB::rollback();
            return false;
        }

        $mercadorias = $request->mercadoria['ncm'];
        $nm_mercadoria = $request->mercadoria['nm_mercadoria'];
        $in_aceite = $request->mercadoria['in_aceite'];
        $vl_mercadoria = $request->mercadoria['vl_mercadoria'];
        $no_observacao = $request->mercadoria['no_observacao'];

        foreach ($mercadorias as $key => $valor) {

            $nova_mercadoria_embarque = new MpmeMercadoriaEmbarque();
            $nova_mercadoria_embarque->ID_MPME_EMBARQUE = $embarque->ID_MPME_EMBARQUE;
            $nova_mercadoria_embarque->NU_NCM_NBS = $valor;
            $nova_mercadoria_embarque->NO_MERCADORIA = $nm_mercadoria[$key];
            $nova_mercadoria_embarque->IN_ACEITE = $in_aceite[$key];
            $nova_mercadoria_embarque->VL_MERCADORIA = converte_float($vl_mercadoria[$key]);
            $nova_mercadoria_embarque->DS_OBSERVACAO = $no_observacao[$key];
            $nova_mercadoria_embarque->DT_CADASTRO = Carbon::now();
            $nova_mercadoria_embarque->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

            if (!$nova_mercadoria_embarque->save()) {
                DB::rollback();
                return false;
            }

        }

        $mpme_hist_embarque = new MpmeHistEmbarque();

        $msg = (isset($request->id_mpme_embarque) && $request->id_mpme_embarque != "") ? 'DADOS ALTERADOS COM SUCESSO' : 'DADOS CADASTRADOS COM SUCESSO';

        $mpme_hist_embarque->ID_MPME_EMBARQUE = $embarque->ID_MPME_EMBARQUE;
        $mpme_hist_embarque->ID_MPME_STATUS = $embarque->ID_MPME_STATUS;
        $mpme_hist_embarque->DS_OBSERVACAO = $msg;
        $mpme_hist_embarque->DT_CADASTRO = Carbon::now();
        $mpme_hist_embarque->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        if (!$mpme_hist_embarque->save()) {
            DB::rollback();
            return false;
        }

        DB::commit();

        return $embarque;
    }

    public function listarEmbarque($id_mpme_proposta, $id_mpme_embarque = null)
    {
        $lista_embarque = MpmeEmbarque::where('ID_MPME_PROPOSTA', '=', $id_mpme_proposta);

        if ($id_mpme_embarque != null) {
            $lista_embarque = $lista_embarque->where('ID_MPME_EMBARQUE', '=', $id_mpme_embarque);
        }

        if (Auth::User()->PermissoesConferenciaValidador->ativConferente ?? '' == 1 && Auth::User()->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin ?? '' == 1) {
            $lista_embarque = $lista_embarque->whereIn('ID_MPME_STATUS', [4, 11]);
        }

        if (Auth::User()->PermissoesConferenciaValidador->ativValidador ?? '' == 2 && Auth::User()->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin ?? '' == 1) {
            $lista_embarque = $lista_embarque->where('ID_MPME_STATUS', '=', 5);
        }

        $lista_embarque = $lista_embarque->get();

        return $lista_embarque;
    }

    public static function AprovaEmbarque($request)
    {

        DB::beginTransaction();

        $mpme_hist_embarque = new MpmeHistEmbarque();

        $status = '';
        $parecer = $request->parecer;

        if (isset($request->ehrecursoproprio) && $request->ehrecursoproprio == 1) {

            $status = 13;
            $parecer = $request->parecer;

        } else {
            switch (Auth::User()->TipoPermissao()) {
                case 'C':
                    $status = 5;
                    break;
                case 'V':
                    $status = 6;
                    $parecer = 'Embarque Aprovado';
            }
        }

        $mpme_hist_embarque->ID_MPME_EMBARQUE = $request->ID_MPME_EMBARQUE;
        $mpme_hist_embarque->ID_MPME_STATUS = $status; //aprovado pelo conferente/validador
        $mpme_hist_embarque->DS_OBSERVACAO = $parecer;
        $mpme_hist_embarque->DT_CADASTRO = Carbon::now();
        $mpme_hist_embarque->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        $atualizaEmbarque = MpmeEmbarque::find($request->ID_MPME_EMBARQUE);
        $atualizaEmbarque->ID_MPME_STATUS = $status; //aprovado pelo conferente
        $atualizaEmbarque->PARECER = $request->parecer;

        $atualizaProposta = MpmeProposta::where("ID_MPME_PROPOSTA", '=', $atualizaEmbarque->ID_MPME_PROPOSTA )->first();
        $atualizaProposta->DT_EMBARQUE = $atualizaEmbarque->DT_EMBARQUE;
        $atualizaProposta->IN_EMBARQUE_CONFIRMADO = 1;


        if (($status == '') || !$mpme_hist_embarque->save() || !$atualizaEmbarque->save() || !$atualizaProposta->save()) {
            DB::rollback();
            return false;
        }

        DB::commit();

    }

    public static function DevolveConferente($request)
    {

        DB::beginTransaction();

        $mpme_hist_embarque = new MpmeHistEmbarque();

        /*
         *
        Caso o devolve exportador seja 1 será devolvido ao exportador

        9  =  AGUARDANDO CORREÇÃO PELO EXPORTADOR
        11 =  AGUARDANDO CORREÇÃO PELO CONFERENTE
         *
         */

        $status = ($request->devolve_exportador == 1) ? 9 : 11;
        $parecer = $request->parecer;

        $msg = 'Operação Devolvido para o conferente com o seguinte parecer: ' . $parecer;
        $msgExp = 'Operação devolvida para o exportador com o seguinte parecer:' . $parecer;

        $mpme_hist_embarque->ID_MPME_EMBARQUE = $request->ID_MPME_EMBARQUE;
        $mpme_hist_embarque->ID_MPME_STATUS = $status;
        $mpme_hist_embarque->DS_OBSERVACAO = ($request->devolve_exportador == 1) ? $msgExp : $msg;
        $mpme_hist_embarque->DT_CADASTRO = Carbon::now();
        $mpme_hist_embarque->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        $atualizaEmbarque = MpmeEmbarque::find($request->ID_MPME_EMBARQUE);
        $atualizaEmbarque->ID_MPME_STATUS = $status; //status da operacao
        $atualizaEmbarque->PARECER = $request->parecer;

        if (($status == '') || !$mpme_hist_embarque->save() || !$atualizaEmbarque->save()) {
            DB::rollback();
            return false;
        }

        DB::commit();

    }

    public static function DevolveEmbarque($request)
    {

        DB::beginTransaction();

        $mpme_hist_embarque = new MpmeHistEmbarque();

        /*
         *
        Caso o devolve exportador seja 1 será devolvido ao exportador

        9  =  AGUARDANDO CORREÇÃO PELO EXPORTADOR
        11 =  AGUARDANDO CORREÇÃO PELO CONFERENTE
         *
         */

        $status = ($request->devolve_exportador == 1) ? 9 : 11;
        $parecer = $request->parecer;

        $msg = 'Operação Devolvido para o conferente com o seguinte parecer: ' . $parecer;
        $msgExp = 'Operação devolvida para o exportador com o seguinte parecer:' . $parecer;

        $mpme_hist_embarque->ID_MPME_EMBARQUE = $request->ID_MPME_EMBARQUE;
        $mpme_hist_embarque->ID_MPME_STATUS = $status;
        $mpme_hist_embarque->DS_OBSERVACAO = ($request->devolve_exportador == 1) ? $msgExp : $msg;
        $mpme_hist_embarque->DT_CADASTRO = Carbon::now();
        $mpme_hist_embarque->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        $atualizaEmbarque = MpmeEmbarque::find($request->ID_MPME_EMBARQUE);
        $atualizaEmbarque->ID_MPME_STATUS = $status; //status da operacao
        $atualizaEmbarque->PARECER = $request->parecer;

        if (($status == '') || !$mpme_hist_embarque->save() || !$atualizaEmbarque->save()) {
            DB::rollback();
            return false;
        }

        DB::commit();

    }

    public function listarEmbarqueAnalista($where)
    {

        $filtro_proposta = MpmeEmbarque::join('MPME_PROPOSTA', 'MPME_EMBARQUE.ID_MPME_PROPOSTA', '=', 'MPME_PROPOSTA.ID_MPME_PROPOSTA');

        if (!is_null($where['id_mpme_status'])) {
            $filtro_proposta->whereIn('MPME_EMBARQUE.ID_MPME_STATUS', $where['id_mpme_status']);
        }

        if (!is_null($where['nu_proposta'])) {
            $filtro_proposta->where('MPME_PROPOSTA.NU_PROPOSTA', '=',$where['nu_proposta']);
        }

        $embarque = $filtro_proposta->get();

        return $embarque;
    }

}
