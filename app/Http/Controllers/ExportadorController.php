<?php

namespace App\Http\Controllers;

use App\MpmeArquivo;
use App\Notificacoes;
use App\Repositories\UsersRepository;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use App\Repositories\MpmeArquivoRepository;

class ExportadorController extends Controller
{

    public function convertUtf8($value)
    {
        return mb_detect_encoding($value, mb_detect_order(), true) === 'UTF-8' ? $value : mb_convert_encoding($value, 'UTF-8');
    }

    public function index(Request $request)
    {
        $notificacoes = Notificacoes::with('Banco', 'ClienteExportador', 'Exportador')
            ->where('TIPO_VALIDACAO', 'A')
            ->where('ID_STATUS_NOTIFICACAO_FK', '=', 14);

    
        if ($request->nm_usuario == "" && $request->fl_ativo == "" && $request->nu_cnpj == "") {
            $notificacoes->where('IC_ATIVO', 1);
        } else {

            if ($request->nm_usuario != "") {
                $notificacoes->whereHas('Exportador', function ($query) use ($request) {
                    $query->where('NM_USUARIO', 'like', '%' . mb_convert_case($request->nm_usuario, MB_CASE_UPPER, "UTF-8") . '%');
                });
            }

            if ($request->fl_ativo != "") {
                $notificacoes->whereHas('Exportador', function ($query) use ($request) {
                    $query->where('FL_ATIVO', $request->fl_ativo);
                });
            }

            if ($request->nu_cnpj != "") {
                $notificacoes->whereHas('Exportador', function ($query) use ($request) {
                    $query->where('NU_CNPJ', $request->nu_cnpj);
                });
            }
        }


        $notificacoes = $notificacoes->orderByDesc('ID_USUARIO_FK')->get();

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'notificacoes' => $notificacoes,
        );

        return view('abgf.exportador.index_exportador', $compact_args);
    }

    public function atualizacaoCadastral(Request $request)
    {

        $notificacoes = Notificacoes::where('ID_STATUS_NOTIFICACAO_FK', '=', 14)->where('TIPO_VALIDACAO', 'U')->where('IC_ATIVO', 1)
            ->with('Banco', 'ClienteExportador', 'Exportador');

        if ($request->nm_usuario != "") {
            $notificacoes->whereHas('Exportador', function ($query) use ($request) {
                $query->where('NM_USUARIO', 'like', '%' . $request->nm_usuario . '%');
            });
        }

        if ($request->fl_ativo != "") {
            $notificacoes->whereHas('Exportador', function ($query) use ($request) {
                $query->where('FL_ATIVO', $request->fl_ativo);
            });
        }

        if ($request->nu_cnpj != "") {
            $notificacoes->whereHas('Exportador', function ($query) use ($request) {
                $query->where('NU_CNPJ', $request->nu_cnpj);
            });
        }

        $notificacoes = $notificacoes->orderByDesc('ID_USUARIO_FK')->get();

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'notificacoes' => $notificacoes,
        );

        return view('abgf.exportador.index_exportador', $compact_args);
    }

    public function validaExportador(Request $request)
    {

        $dadosExportador = User::where('ID_USUARIO', '=', $request->idmpme)->with('ClienteExportador', 'FinanciadorPre', 'FinanciadorPos', 'InfoAdicionalExportador', 'listaTarefasAnalista')->first();
        $idExportador = $request->idmpme;
        $listaTarefas = $dadosExportador->listaTarefasAnalista->pluck('NU_CHECK')->toArray();
        $dadosFinanceirosdoExportador = $dadosExportador->ClienteExportador->FinanceiroExportador;

        foreach ($dadosExportador->ClienteExportador->ModalidadeFinanciamento as $clienteModalidadeFinanciamentos) :
            $dados[$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE] = enquadrarModalidade($dadosFinanceirosdoExportador, $clienteModalidadeFinanciamentos);
        endforeach;

        $i = 0;
        foreach ($dados as $fornecedor) {
            if (isset($fornecedor['modalidade'])) {
                if ($fornecedor['enquaradrado'] == 'NAO') {
                    $i++;
                }
            }
        }

        //checando se mais de uma modalidade foi aprovada como sim, oara que pos+pre funcione tenho que ter pelo menos 2
        if ($i > 0) {
            $dados[2] = enquadradado('NAO', 2, $dadosFinanceirosdoExportador->ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
        } else {
            $dados[2] = enquadradado('SIM', 2, $dadosFinanceirosdoExportador->ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
        }

        $arquivos = MpmeArquivo::where('ID_USUARIO_CAD', $request->idmpme)->whereIn('ID_MPME_TIPO_ARQUIVO', [20, 21])->get();
        $idNotificacao = $request->idNotificacao;
        $notificacao = Notificacoes::find($request->idNotificacao);
        return view('abgf.exportador.dados_exportador', compact('dadosExportador', 'idExportador', 'listaTarefas', 'idNotificacao', 'dados', 'arquivos', 'notificacao'));
    }

    public function salvaAlteracoesExportador(Request $request)
    {

        $exportador = UsersRepository::salvaAlteracoes($request);
        if ($exportador) {
            return back()->with('success', 'Dados salvos com sucesso!');
        } else {
            return back()->with('error', 'Ocorreu um erro ao salvar as informações adicionais do exportador!');
        }
    }

    public function salvaListaTarefas(Request $request)
    {
        $campos = (object) $request->all();
        $tarefas = UsersRepository::salvaListaRarefas($campos);
        if ($tarefas) {
            return back()->with('success', 'Dados salvos com sucesso!');
        } else {
            return back()->with('error', 'Ocorreu um erro ao salvar as tarefas realizadas!');
        }
    }

    public function fichaCadastral(Request $request)
    {
        $campos = (object) $request->all();

        $mensagemPersonalizada = [
            'data_recomendacao.required' => 'O campo <b>Data Recomendação</b> '
                . 'do exportador não pode ser vazio',
            'ds_recomendacao.required' => 'O campo <b>Recomendação</b> do '
                . 'exportador não pode ser vazio',
            'ds_parecer.required' => 'O campo <b>Parecer Técnico</b> do '
                . 'exportador não pode ser vazio',
        ];
        $validacao = Validator::make($request->all(), [
            'data_recomendacao' => 'required',
            'ds_recomendacao' => 'required',
            'ds_parecer' => 'required',

        ], $mensagemPersonalizada);

        if ($validacao->passes()) {

            //Valida se o analista liberou o cadastro sem aprovar nenhum enquadramento
            $exportador = User::with('ClienteExportador')->where('ID_USUARIO', $request->ID_USUARIO)->first()->toArray();
            $rsEnquadrado = $exportador['cliente_exportador']['modalidade_financiamento'];
            $enquadrado = Arr::pluck($rsEnquadrado, 'IN_REGISTRO_ATIVO');

            if (!in_array('S', $enquadrado)) {
                return back()->with('error', 'Você deve aprovar ao menos 1 modalidade');
            }

            $ficha = UsersRepository::fichaCadastralLiberacaoCadastro($campos);

            if ($ficha) {
                return redirect('lista-proposta-usuario')->route('abgf.exportador.index')->with('success', 'Dados salvos com sucesso!');
            } else {
                return back()->with('error', 'Ocorreu um erro ao salvar a ficha cadastral do exportador!');
            }
        } else {

            return back()->withErrors($validacao);
        }
    }


    public function substituirArquivos(Request $request)
    {

        if (isset($request->dre)) {
            // faz upload DRE

            $request->request->add(['no_arquivo' => $request->file('dre')]); //add request
            $request->request->add(['id_mpme_tipo_arquivo' => 20]); //add request
            $request->request->add(['pasta' => 'abgf/exportador/dre/' . $request->ID_USUARIO_EXP]); //add request
            $request->request->add(['ID_USUARIO' => $request->ID_USUARIO_EXP]); //add request
            $request->request->add(['atualizar' => 1]); //add request
            $request->request->add(['ID_MPME_ARQUIVO' => $request->dre_sub_cad]); //add request

            if (!MpmeArquivoRepository::UploadEInsere($request)) {
                DB::rollback();

                return back()->with('error', 'Ocorreu um erro ao enviar o arquivo da dre');
            }
        }


        if (isset($request->comprovante_exportacao)) {
            // faz upload Comprovante de exportador
            $request->request->add(['no_arquivo' => $request->file('comprovante_exportacao')]); //add request
            $request->request->add(['id_mpme_tipo_arquivo' => 21]); //add request
            $request->request->add(['pasta' => 'abgf/exportador/comprovante_exportacao/' . $request->ID_USUARIO_EXP]); //add request
            $request->request->add(['ID_USUARIO' => $request->ID_USUARIO_EXP]); //add request
            $request->request->add(['atualizar' => 1]); //add request
            $request->request->add(['ID_MPME_ARQUIVO' => $request->comprovante_exportacao_sub_cad]); //add request
            if (!MpmeArquivoRepository::UploadEInsere($request)) {
                DB::rollback();

                return back()->with('error', 'Ocorreu um erro ao enviar o comprovante da exportacao');
            }
        }

        return back()->with('success', 'Arquivos do upload substituidos');
    }
}
