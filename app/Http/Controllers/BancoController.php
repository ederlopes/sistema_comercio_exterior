<?php
namespace App\Http\Controllers;

use App\Notificacoes;
use App\Repositories\MpmeFinancPosRepository;
use App\Repositories\MpmeFinancPreRepository;
use App\Repositories\MpmeInfAdicionalExportadorRepository;
use App\Repositories\MpmeNotificacaoRepository;
use App\Repositories\MpmePropostaRepository;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BancoController extends Controller
{

    public function index(Request $request)
    {
        $tipoPermissao = 'C';

        // ativValidador = 1 = Conferente, 2 = Validador,
        // tipoPermissaoAdmin_idtipoPermissaoAdmin = permissao para acessar menu de validar exportador

        if (Auth::User()->PermissoesConferenciaValidador->ativValidador == 1 && Auth::User()->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin == 1) {
            $tipoPermissao = 'C';
        }

        if (Auth::User()->PermissoesConferenciaValidador->ativValidador == 2 && Auth::User()->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin == 1) {
            $tipoPermissao = 'V';
        }

        // Pega todas as notificacoes de novos cadastros
        $notificacoes = Notificacoes::where('ID_STATUS_NOTIFICACAO_FK', '=', 14)->where('MPME_NOTIFICACAO.IC_ATIVO', '=', 1)
            ->where('MPME_NOTIFICACAO.TIPO_VALIDACAO', '=', $tipoPermissao)
            ->orderByDesc('MPME_NOTIFICACAO.ID_USUARIO_FK')
            ->with('Exportador', 'ClienteExportador', 'Banco')
            ->whereHas('Banco.Gecex', function ($query) {
                $query->where('ID_USUARIO_FK', Auth::User()->gecex_idgecex);
            })
            ->get();

        return view('banco.index_banco', compact('notificacoes'));
    }

    public function analisaExportador(Request $request)
    {
        $tipoPermissao = 'C';
        if (Auth::User()->PermissoesConferenciaValidador->ativValidador == 1 && Auth::User()->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin == 1) {
            $tipoPermissao = 'C';
        }

        if (Auth::User()->PermissoesConferenciaValidador->ativValidador == 2 && Auth::User()->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin == 1) {
            $tipoPermissao = 'V';
        }

        $dadosExportador = User::where('TP_USUARIO', 'C')->where('ID_USUARIO', '=', $request->ID_USUARIO)
            ->with('Banco', 'ClienteExportador', 'InfoAdicionalExportador', 'QuadroSocietarioExportador')
            ->first();

        $idNotificacao = $request->ID_NOTIFICACAO;
        $notificacao = Notificacoes::find($request->ID_NOTIFICACAO);

        return view('banco.exportador.dados_exportador', compact('dadosExportador', 'idNotificacao', 'notificacao', 'tipoPermissao'));
    }

    public function atualizaFinancPre(Request $request)
    {
        $mensagemPersonalizada = [
            'DS_ENDERECO_PRE.required' => 'O campo <b>endereço</b> do pré-embarque não pode ser vazio',
            'ID_AGENCIA_PRE.required' => 'O campo <b>agencia</b> do pré-embarque não pode ser vazio',
            'NO_CIDADE_PRE.required' => 'O campo <b>cidade</b> do pré-embarque não pode ser vazio',
            'NO_ESTADO_PRE.required' => 'O campo <b>estado</b> do pré-embarque não pode ser vazio',
            'NU_CEP_PRE.required' => 'O campo <b>CEP</b> do pré-embarque não pode ser vazio',
            'NO_CONTATO_PRE.required' => 'O campo <b>contato</b> do pré-embarque não pode ser vazio',
            'NU_TEL_PRE.required' => 'O campo <b>telefone</b> do pré-embarque não pode ser vazio',
            'DS_EMAIL_PRE.required' => 'O campo <b>e-mail</b> do pré-embarque não pode ser vazio',
            'NO_CARGO_PRE.required' => 'O campo <b>cargo</b> do pré-embarque não pode ser vazio',
            'NU_CNPJ_PRE.required' => 'O campo <b>CNPJ</b> do pré-embarque não pode ser vazio',
            'NU_INSCRICAO_PRE.required' => 'O campo <b>Inscrição estadual</b> do pré-embarque não pode ser vazio',
        ];

        $validacao = Validator::make($request->all(), [
            'DS_ENDERECO_PRE' => 'required',
            'ID_AGENCIA_PRE' => 'required',
            'NO_CIDADE_PRE' => 'required',
            'NO_ESTADO_PRE' => 'required',
            'NU_CEP_PRE' => 'required',
            'NO_CONTATO_PRE' => 'required',
            'NU_TEL_PRE' => 'required',
            'DS_EMAIL_PRE' => 'required',
            'NO_CARGO_PRE' => 'required',
            'NU_CNPJ_PRE' => 'required',
            'NU_INSCRICAO_PRE' => 'required',
        ], $mensagemPersonalizada);

        if ($validacao->passes()) {

            DB::beginTransaction();
            $campos = (object) $request->all();
            $atualizaFinancPre = MpmeFinancPreRepository::atualizaDadosFinanciadorPre($campos);

            if ($atualizaFinancPre) {
                DB::commit(); // Faz o commit dos inserts
                return back()->with('success', 'Dados atualizados com sucesso!');
            } else {
                DB::rollback(); // faz um rollback no banco
                return back()->with('error', 'Ocorreu um erro ao atualizar os dados do financiador pre!');
            }
        } else {
            return back()->withErrors($validacao);
        }
    }

    public function atualizaFinancPos(Request $request)
    {
        $mensagemPersonalizada = [
            'DS_ENDERECO.required' => 'O campo <b>endereço</b> do pós-embarque não pode ser vazio',
            'ID_AGENCIA.required' => 'O campo <b>agencia</b> do pós-embarque não pode ser vazio',
            'NO_CIDADE.required' => 'O campo <b>cidade</b> do pós-embarque não pode ser vazio',
            'NO_ESTADO.required' => 'O campo <b>estado</b> do pós-embarque não pode ser vazio',
            'NU_CEP.required' => 'O campo <b>CEP</b> do pós-embarque não pode ser vazio',
            'NO_CONTATO.required' => 'O campo <b>contato</b> do pós-embarque não pode ser vazio',
            'NU_TEL.required' => 'O campo <b>telefone</b> do pós-embarque não pode ser vazio',
            'DS_EMAIL.required' => 'O campo <b>e-mail</b> do pós-embarque não pode ser vazio',
            'NO_CARGO.required' => 'O campo <b>cargo</b> do pós-embarque não pode ser vazio',
            'NU_CNPJ.required' => 'O campo <b>CNPJ</b> do pós-embarque não pode ser vazio',
            'NU_INSCRICAO.required' => 'O campo <b>Inscrição estadual</b> do pós-embarque não pode ser vazio',
        ];

        $validacao = Validator::make($request->all(), [
            'DS_ENDERECO' => 'required',
            'ID_AGENCIA' => 'required',
            'NO_CIDADE' => 'required',
            'NO_ESTADO' => 'required',
            'NU_CEP' => 'required',
            'NO_CONTATO' => 'required',
            'NU_TEL' => 'required',
            'DS_EMAIL' => 'required',
            'NO_CARGO' => 'required',
            'NU_CNPJ' => 'required',
            'NU_INSCRICAO' => 'required',
        ], $mensagemPersonalizada);

        if ($validacao->passes()) {

            DB::beginTransaction();
            $campos = (object) $request->all();
            $atualizaFinancPos = MpmeFinancPosRepository::atualizaDadosFinanciador($campos);

            if ($atualizaFinancPos) {
                DB::commit(); // Faz o commit dos inserts
                return back()->with('success', 'Dados atualizados com sucesso!');
            } else {
                DB::rollback(); // faz um rollback no banco
                return back()->with('error', 'Ocorreu um erro ao atualizar os dados do financiador pre!');
            }
        } else {
            return back()->withErrors($validacao);
        }
    }

    public function atualizaInfoAddExportador(Request $request)
    {
        DB::beginTransaction();
        $campos = (object) $request->all();
        $salvaInfAdicionaisExp = MpmeInfAdicionalExportadorRepository::salvaInfAdicionalExportador($campos);
        $notificacao = MpmeNotificacaoRepository::atualizaNotificacaoDadosExportador($campos);

        if ($salvaInfAdicionaisExp && $notificacao) {
            DB::commit(); // Faz o commit dos inserts
            return redirect('/banco')->with('success', 'Dados salvos com sucesso!');
        } else {
            DB::rollback(); // faz um rollback no banco
            return back()->with('error', 'Ocorreu um erro ao salvar as informações adicionais do exportador!');
        }
    }

    public function devolverValidador(Request $request)
    {
        $campos = (object) $request->all();
        $devolve = MpmeNotificacaoRepository::devolveParaValidador($campos);
        if ($devolve) {
            return response()->json([
                'message' => 'Operação relizada com sucesso!',
                'class_mensagem' => 'success',
                'header' => 'Salvo!',
            ]);
        }
    }

    public function listar_propostas_aprovacao(Request $request, MpmePropostaRepository $propostaRepository)
    {
        $id_status = [14,18]; // concretizado ou aguardando desembolso

        $where = [
            'id_oper' => $request->ID_OPER,
            'total_paginacao' => 10,
        ];

        $rs_proposta = $propostaRepository->getPropostasPorAlcada($id_status, $where);
        $token = str_random(30);
        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rs_proposta' => $rs_proposta,
            'token' => $token,
        );

        return view('banco.proposta.listar_proposta_aprovacao', $compact_args);
    }

    public function salvar_divergencia(Request $request, User $usuario)
    {

        if ($request->id_usuario == "" || $request->ds_divergencia == "") {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Parametros inválidos',
            ));
        }

        $atualizar_usuario = $usuario->where("ID_USUARIO", '=', $request->id_usuario)->first();

        $atualizar_usuario->DS_DIVERGENCIA = strtoupper($request->ds_divergencia);

        if (!$atualizar_usuario->save()) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Erro ao cadastrar',
            ));
        }

        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
            'msg' => 'Divergência cadastrada com sucesso',
        ));
    }

}
