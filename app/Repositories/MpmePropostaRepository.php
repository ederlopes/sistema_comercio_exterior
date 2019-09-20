<?php

namespace App\Repositories;

use App\MpmeHistProposta;
use App\MpmeProposta;
use App\MpmePropostaAprovacao;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;

class MpmePropostaRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(MpmeProposta::class);
    }

    public function salvaProposta($request)
    {
        $proposta = null;
        $id_mpme_proposta = (isset($request->id_mpme_proposta)) ? $request->id_mpme_proposta : null;

        if ($id_mpme_proposta > 0) {
            $proposta = MpmeProposta::find($id_mpme_proposta);
        }

        if (!isset($proposta)) {
            $proposta = new MpmeProposta();
        }

        $dadosFinanceiro = explode("#", $request->id_cliente_exportadores_modalidade);

        $dt_embarque = Carbon::createFromFormat('d/m/Y', $request->dt_embarque)->toDateTimeString();  // Data do embarque

        $proposta->ID_OPER = $request->id_oper;
        $proposta->ID_SETOR = $request->id_setor;
        $proposta->IN_ACEITE = $request->in_aceite;
        $proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta;
        $proposta->ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO = $dadosFinanceiro[0];
        $proposta->VL_PROPOSTA = converte_float($request->vl_proposta);
        $proposta->VL_PERC_DOWPAYMENT = converte_float($request->va_percentual_dw_payment);
        $proposta->NU_PRAZO_PRE = $request->nu_prazo_pre;
        $proposta->NU_PRAZO_POS = $request->nu_prazo_pos;
        $proposta->DT_CADASTRO = Carbon::now();
        $proposta->DT_EMBARQUE = $dt_embarque;
        $proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        if (!$proposta->save()) {
            return false;
        }

        return $proposta;
    }

    public function atualizar_dados_aprovacao($dados)
    {
        $proposta = $this->find($dados['ID_MPME_PROPOSTA']);
        $proposta->VL_PROPOSTA = $dados['VL_PROPOSTA'];
        $proposta->VL_PERC_DOWPAYMENT = $dados['VL_PERC_DOWPAYMENT'];
        $proposta->NU_PRAZO_PRE = $dados['NU_PRAZO_PRE'];
        $proposta->NU_PRAZO_POS = $dados['NU_PRAZO_POS'];

        if (!$proposta->save()) {
            return false;
        }

        return $proposta;
    }

    public function getProposta($id_oper, $id_mpme_proposta = null)
    {
        $proposta = MpmeProposta::where('ID_USUARIO_CAD', '=', Auth::user()->ID_USUARIO)
            ->where('ID_OPER', '=', $id_oper);

        if ($id_mpme_proposta != null) {
            $proposta = $proposta->where('ID_MPME_PROPOSTA', '=', $id_mpme_proposta);
        }

        $proposta = $proposta->orderByDesc('ID_MPME_PROPOSTA')
            ->get();

        return $proposta;
    }

    public function getDadosProposta($id_oper, $id_mpme_proposta = null)
    {
        $proposta = MpmeProposta::join('OPERACAO_CADASTRO_EXPORTADOR', 'OPERACAO_CADASTRO_EXPORTADOR.ID_OPER', '=', 'MPME_PROPOSTA.ID_OPER')
            ->where('MPME_PROPOSTA.ID_OPER', '=', $id_oper);

        if ($id_mpme_proposta != null) {
            $proposta = $proposta->where('ID_MPME_PROPOSTA', '=', $id_mpme_proposta);
        }

        $proposta = $proposta->orderByDesc('ID_MPME_PROPOSTA')
            ->first();

        return $proposta;
    }

    public function getPropostasPorAlcada($id_mpme_status_proposta, $where, $is_paginacao = true)
    {

        $filtro_proposta = MpmeProposta::select(
            'MPME_PROPOSTA.*',
            'OPERACAO_CADASTRO_EXPORTADOR.*',
            DB::raw('(15 - DATEDIFF ( day , DT_ENVIO ,  GETDATE()))  as SALDO_DIAS'),
            DB::raw('(DATEDIFF ( day , GETDATE() ,  DT_EMBARQUE))  as SALDO_DIAS_EMBARQUE')
        )
            ->join('OPERACAO_CADASTRO_EXPORTADOR', 'OPERACAO_CADASTRO_EXPORTADOR.ID_OPER', '=', 'MPME_PROPOSTA.ID_OPER');


        if (array_key_exists('id_oper', $where)) {
            if (!is_null($where['id_oper'])) {
                $filtro_proposta->where('MPME_PROPOSTA.ID_OPER', '=', $where['id_oper']);
            }
        }

        if (array_key_exists('cod_unico_operacao', $where)) {
            if (!is_null($where['cod_unico_operacao'])) {
                $filtro_proposta->where('OPERACAO_CADASTRO_EXPORTADOR.COD_UNICO_OPERACAO', '=', $where['cod_unico_operacao']);
            }
        }

        if (array_key_exists('id_mpme_proposta', $where)) {
            if (!is_null($where['id_mpme_proposta'])) {
                $filtro_proposta->where('MPME_PROPOSTA.ID_MPME_PROPOSTA', '=', $where['id_mpme_proposta']);
            }
        }

        if (isset($id_mpme_status_proposta)) {
            $filtro_proposta->whereIn('MPME_PROPOSTA.ID_MPME_STATUS_PROPOSTA', $id_mpme_status_proposta);
        }

        if (array_key_exists('id_mpme_proposta', $where)) {
            if ($where['not_id_mpme_status_proposta'] > 0) {
                $filtro_proposta->whereNotIn('MPME_PROPOSTA.ID_MPME_STATUS_PROPOSTA', $where['not_id_mpme_status_proposta']);
            }
        }






        if ($is_paginacao) {
            $filtro_proposta = $filtro_proposta->orderByDesc('MPME_PROPOSTA.ID_MPME_PROPOSTA')->paginate(($where['total_paginacao']) ? $where['total_paginacao'] : 10);
        } else {
            $filtro_proposta = $filtro_proposta;
        }


        return $filtro_proposta;
    }

    public function filtrarPropostaAbgf($where)
    {
        $filtro_proposta = MpmeProposta::whereNotNull('ID_MPME_STATUS_PROPOSTA');

        if (!is_null($where['ID_OPER'])) {
            $filtro_proposta->where('ID_OPER', '=', $where['ID_OPER']);
        }

        if (!is_null($where['ID_MPME_PROPOSTA'])) {
            $filtro_proposta->where('ID_MPME_PROPOSTA', '=', $where['ID_MPME_PROPOSTA']);
        }

        if ($where['ID_MPME_STATUS_PROPOSTA'] > 0) {
            $filtro_proposta->where('ID_MPME_STATUS_PROPOSTA', '=', $where['ID_MPME_STATUS_PROPOSTA']);
        }

        if ($where['dias_restantes'] > 0) {
            $data_restante = Carbon::now();
            $data_restante->subDays($where['dias_restantes']);
            $dias = $where['dias_restantes'];
            $filtro_proposta->where('DT_ENVIO', '<=', $data_restante->format('Y-m-d'));
        }

        $filtro_proposta = $filtro_proposta->orderByDesc('ID_MPME_PROPOSTA')->paginate(($where['total_paginacao']) ? $where['total_paginacao'] : 10);

        $filtro_proposta->appends(Request::capture()->except('table_proposta', '_token'));

        return $filtro_proposta;
    }

    public function filtrarPropostas($where)
    {
        $filtro_proposta = MpmeProposta::join("OPERACAO_CADASTRO_EXPORTADOR", "OPERACAO_CADASTRO_EXPORTADOR.ID_OPER", "=", "MPME_PROPOSTA.ID_OPER")
            ->join('CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO', 'CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO.ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO', 'MPME_PROPOSTA.ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO')
            ->join('MODALIDADE_FINANCIAMENTO', 'MODALIDADE_FINANCIAMENTO.ID_MODALIDADE_FINANCIAMENTO', 'CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO.ID_MODALIDADE_FINANCIAMENTO')
            ->where('MPME_PROPOSTA.ID_USUARIO_CAD', '=', $where['id_usuario']);

        if (!is_null($where['id_usuario'])) {
            $filtro_proposta->where('MPME_PROPOSTA.ID_USUARIO_CAD', '=', $where['id_usuario']);
        }

        if (!is_null($where['id_oper'])) {
            $filtro_proposta->where('MPME_PROPOSTA.ID_OPER', '=', $where['id_oper']);
        }

        if (!is_null($where['cod_unico_operacao'])) {
            $filtro_proposta->where('OPERACAO_CADASTRO_EXPORTADOR.COD_UNICO_OPERACAO', '=', $where['cod_unico_operacao']);
        }

        if (!is_null($where['id_mpme_proposta'])) {
            $filtro_proposta->where('MPME_PROPOSTA.ID_MPME_PROPOSTA', '=', $where['id_mpme_proposta']);
        }

        if ($where['id_mpme_status_proposta'] > 0) {
            $filtro_proposta->where('MPME_PROPOSTA.ID_MPME_STATUS_PROPOSTA', '=', $where['id_mpme_status_proposta']);
        }

        if ($where['not_id_mpme_status_proposta'] > 0) {
            $filtro_proposta->whereNotIn('MPME_PROPOSTA.ID_MPME_STATUS_PROPOSTA', $where['not_id_mpme_status_proposta']);
        }

        $filtro_proposta = $filtro_proposta->orderByDesc('MPME_PROPOSTA.ID_MPME_PROPOSTA')->paginate(($where['total_paginacao']) ? $where['total_paginacao'] : 10);

        $filtro_proposta->appends(Request::capture()->except('table_proposta', '_token'));

        return $filtro_proposta;
    }

    public function validarPropostaOperacao($id_oper, $id_mpme_proposta)
    {
        return MpmeProposta::where('ID_OPER', '=', $id_oper)
            ->where('ID_MPME_PROPOSTA', '=', $id_mpme_proposta)
            ->orderByDesc('ID_MPME_PROPOSTA')
            ->count();
    }

    public function excluirProposta($request)
    {

        if ($request->id_mpme_proposta == "" || $request->id_mpme_status_proposta == "") {
            return false;
        }

        $proposta = MpmeProposta::find($request->id_mpme_proposta);

        $proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta;

        if ($proposta->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function enviarProposta($request)
    {

        if ($request->id_mpme_proposta == "" || $request->id_mpme_status_proposta == "") {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Parametros inválidos',
            ));
        }

        $proposta = MpmeProposta::find($request->id_mpme_proposta);

        DB::beginTransaction();

        $proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta;
        $proposta->DT_ENVIO = Carbon::now();

        if (!$proposta->save()) {
            DB::rollback();
            return false;
        }

        $historico_proposta = new MpmeHistProposta();
        $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
        $historico_proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta;
        $historico_proposta->DT_CADASTRO = Carbon::now();
        $historico_proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
        $historico_proposta->DS_OBSERVACAO = 'PROPOSTA ENVIADA COM SUCESSO';

        if (!$historico_proposta->save()) {
            DB::rollback();
            return false;
        }

        //criar nova notificacao - analisar operacao
        $notificacao = new MpmeNotificacaoUsuarioRepository();
        $notificacao->registrar_notificacao([
            'id_mpme_tipo_notificacao' => 4,
            'id_oper' => $request->id_oper,
            'id_mpme_proposta' => $request->id_mpme_proposta,
        ]);

        DB::commit();
        return true;
    }

    public function aprovarProposta($request)
    {
        if ($request->id_mpme_proposta == "" || $request->id_mpme_status_proposta == "") {
            return false;
        }

        $mpme_proposta_aprovacao_selecionado = MpmePropostaAprovacao::where('ID_MPME_PROPOSTA', '=', $request->id_mpme_proposta)
            ->orderByDesc('ID_MPME_PROPOSTA_APROVACAO')
            ->first();

        DB::beginTransaction();

        if (@count($mpme_proposta_aprovacao_selecionado) > 0) {
            $mpme_nova_proposta_aprovacao = new MpmePropostaAprovacao();
            $mpme_nova_proposta_aprovacao->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
            $mpme_nova_proposta_aprovacao->DS_MOTIVO = $request->ds_motivo;

            $mpme_nova_proposta_aprovacao->ID_MPME_ALCADA = (isset($request->id_mpme_alcada)) ? $request->id_mpme_alcada : retornaStatusPerfilAlcada(Auth::user()->ID_PERFIL);
            $mpme_nova_proposta_aprovacao->IN_DECISAO = (isset($request->in_decisao)) ? $request->in_decisao : null;
            $mpme_nova_proposta_aprovacao->VL_PROPOSTA = $mpme_proposta_aprovacao_selecionado->VL_PROPOSTA;
            $mpme_nova_proposta_aprovacao->VL_PERC_DOWPAYMENT = $mpme_proposta_aprovacao_selecionado->VL_PERC_DOWPAYMENT;
            $mpme_nova_proposta_aprovacao->NU_PRAZO_PRE = $mpme_proposta_aprovacao_selecionado->NU_PRAZO_PRE;
            $mpme_nova_proposta_aprovacao->NU_PRAZO_POS = $mpme_proposta_aprovacao_selecionado->NU_PRAZO_POS;
            $mpme_nova_proposta_aprovacao->IN_ACEITE = $request->in_aceite;
            $mpme_nova_proposta_aprovacao->DT_CADASTRO = Carbon::now();
            $mpme_nova_proposta_aprovacao->ID_USUARIO_CAD = (isset(Auth::user()->ID_USUARIO)) ? Auth::user()->ID_USUARIO : 1;
        }

        if (!$mpme_nova_proposta_aprovacao->save()) {
            DB::rollback();
            return false;
        }

        $proposta = MpmeProposta::find($request->id_mpme_proposta);
        $proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta;
        $proposta->NU_PROPOSTA = $request->nu_proposta;
        $proposta->DT_APROVACAO = Carbon::now();

        if (!$proposta->save()) {
            DB::rollback();
            return false;
        }

        $historico_proposta = new MpmeHistProposta();
        $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
        $historico_proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta; // aguardando upload do boleto
        $historico_proposta->DT_CADASTRO = Carbon::now();
        $historico_proposta->ID_USUARIO_CAD = (isset(Auth::user()->ID_USUARIO)) ? Auth::user()->ID_USUARIO : 1;
        $historico_proposta->DS_OBSERVACAO = 'Operação aprovada pelo analista, aguardando envio do boleto';

        if (!$historico_proposta->save()) {
            DB::rollback();
            return false;
        }

        if ($historico_proposta->ID_USUARIO_CAD != 1) //se nao for o sisetema inserindo automaticamente
        {
            $notificacaoMarcarComoLida = new MpmeNotificacaoUsuarioRepository();
            $dados = (object) [
                'id_mpme_tipo_notificacao' => 4,
                'id_oper' => $request->id_oper,
                'id_mpme_proposta' => $request->id_mpme_proposta,
            ];

            $notificacaoMarcarComoLida->visualizarNotificacao($dados);
        }


        DB::commit();
        return true;
    }

    public function cancelarProposta($request)
    {
        if ($request->id_mpme_proposta == "" || $request->id_mpme_status_proposta == "") {
            return false;
        }

        $mpme_proposta_aprovacao_selecionado = MpmePropostaAprovacao::where('ID_MPME_PROPOSTA', '=', $request->id_mpme_proposta)
            ->orderByDesc('ID_MPME_PROPOSTA_APROVACAO')
            ->first();

        DB::beginTransaction();

        if (@count($mpme_proposta_aprovacao_selecionado) > 0) {
            $mpme_nova_proposta_aprovacao = new MpmePropostaAprovacao();
            $mpme_nova_proposta_aprovacao->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
            $mpme_nova_proposta_aprovacao->DS_MOTIVO = $request->ds_motivo;

            $mpme_nova_proposta_aprovacao->ID_MPME_ALCADA = (isset($request->id_mpme_alcada)) ? $request->id_mpme_alcada : retornaStatusPerfilAlcada(Auth::user()->ID_PERFIL);
            $mpme_nova_proposta_aprovacao->IN_DECISAO = (isset($request->in_decisao)) ? $request->in_decisao : null;
            $mpme_nova_proposta_aprovacao->VL_PROPOSTA = $mpme_proposta_aprovacao_selecionado->VL_PROPOSTA;
            $mpme_nova_proposta_aprovacao->VL_PERC_DOWPAYMENT = $mpme_proposta_aprovacao_selecionado->VL_PERC_DOWPAYMENT;
            $mpme_nova_proposta_aprovacao->NU_PRAZO_PRE = $mpme_proposta_aprovacao_selecionado->NU_PRAZO_PRE;
            $mpme_nova_proposta_aprovacao->NU_PRAZO_POS = $mpme_proposta_aprovacao_selecionado->NU_PRAZO_POS;
            $mpme_nova_proposta_aprovacao->IN_ACEITE = $request->in_aceite;
            $mpme_nova_proposta_aprovacao->DT_CADASTRO = Carbon::now();
            $mpme_nova_proposta_aprovacao->ID_USUARIO_CAD = (isset(Auth::user()->ID_USUARIO)) ? Auth::user()->ID_USUARIO : 1;
        }

        if (!$mpme_nova_proposta_aprovacao->save()) {
            DB::rollback();
            return false;
        }

        $proposta = MpmeProposta::find($request->id_mpme_proposta);
        $proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta;
        $proposta->NU_PROPOSTA = $request->nu_proposta;
        $proposta->DT_CANCELAMENTO = Carbon::now();

        if (!$proposta->save()) {
            DB::rollback();
            return false;
        }

        $historico_proposta = new MpmeHistProposta();
        $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
        $historico_proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta; // aguardando upload do boleto
        $historico_proposta->ID_USUARIO_CAD = (isset(Auth::user()->ID_USUARIO)) ? Auth::user()->ID_USUARIO : 1;
        $historico_proposta->DS_OBSERVACAO = 'Proposta cancelada automaticamente pelo sistema';

        if (!$historico_proposta->save()) {
            DB::rollback();
            return false;
        }

        if ($historico_proposta->ID_USUARIO_CAD != 1) //se nao for o sisetema inserindo automaticamente
        {
            $notificacaoMarcarComoLida = new MpmeNotificacaoUsuarioRepository();
            $dados = (object) [
                'id_mpme_tipo_notificacao' => 17,
                'id_oper' => $request->id_oper,
                'id_mpme_proposta' => $request->id_mpme_proposta,
            ];

            $notificacaoMarcarComoLida->visualizarNotificacao($dados);
        }


        DB::commit();
        return true;
    }
}
