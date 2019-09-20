<?php
namespace App\Http\Controllers;

use App\ImportadoresModel;
use App\ModalidadeModel;
use App\MoedaModel;
use App\MpmePergunta;
use App\MpmeVigenciaRelatorio;
use App\Pais;
use App\Repositories\MpmeControelCapitalExportacaoRepository;
use App\Repositories\MpmeImportadoresRepository;
use App\Repositories\MpmeMovimentacaoControleCapitalRepository;
use App\Repositories\MpmeNotificacaoUsuarioRepository;
use App\Repositories\MpmeRestricaoRepository;
use App\StatusOper;
use App\TbSetores;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionarioOperacaoController extends Controller
{

    const AGUARDANDO_COMPROVANTE_PAGAMENTO_RELATORIO = 13;
    const STOPER_EM_ANALISE = 3;
    const STOPER_INSERCAO_COMPROVANTE = 15;
    const STOPER_EXCLUIDA = 14;

    public function index(Request $request, ImportadoresModel $importadores, StatusOper $statusOper, ModalidadeModel $modalidadeModel)
    {
        $rs_modalidade = $modalidadeModel::all();
        $rs_status_operacao = $statusOper->where('IN_VISUALIZA_CLIENTE', '=', 'S')->orderBy('NM_OPER')->get();

        if (!is_null($request->st_oper)) {
            if ($request->st_oper == "0") {
                $status = null;
            } else {
                $status = [$request->st_oper];
            }
        } else {
            $status = null;
        }

        $where = [
            'ID_USUARIO' => Auth::user()->ID_USUARIO,
            'ID_OPER' => $request->id_oper,
            'COD_UNICO_OPERACAO' => $request->cod_unico_operacao,
            'ID_MODALIDADE' => $request->id_modalidade,
            'ST_OPER' => $status,
            'NOT_ST_OPER' => [$this::STOPER_EXCLUIDA],
            'total_paginacao' => $request->total_paginacao,
        ];

        $rsImportadores = $importadores->getQuestionarioOperacao($where);
        $rsImportadores = $rsImportadores->select([
            'MPME_IMPORTADORES.ID_OPER',
            'OPERACAO_CADASTRO_EXPORTADOR.COD_UNICO_OPERACAO',
            'MPME_IMPORTADORES.ID_USUARIO',
            'MPME_IMPORTADORES.RAZAO_SOCIAL',
            'MPME_IMPORTADORES.DATA_CADASTRO',
            'MPME_IMPORTADORES.IC_ENVIADO',
            'PAISES.NM_PAIS',
            'PAISES_VAL.CD_RISCO',
            'TB_SETORES.NM_SETOR',
            'FINANCIAMENTO.NO_FINANCIAMENTO',
            'MODALIDADE.NO_MODALIDADE',
            'MPME_MERCADORIAS.VL_TOTAL',
            'STATUSOPER.NM_OPER',
            'STATUSOPER.ST_OPER',
            'MOEDA.SIGLA_MOEDA',
        ])

            ->orderByDesc('MPME_IMPORTADORES.ID_OPER')
            ->paginate(($where['total_paginacao']) ? $where['total_paginacao'] : 10);

        $rsImportadores->appends(Request::capture()->except('table_proposta', '_token'));

        $token = str_random(30);

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rsImportadores' => $rsImportadores,
            'rs_status_operacao' => $rs_status_operacao,
            'rs_modalidade' => $rs_modalidade,
            'token' => $token,

        );

        return view('questionario_operacao.index_questionario', $compact_args);
    }

    public function novo(Request $request, User $user, TbSetores $setores, MoedaModel $moeda, Pais $pais, MpmePergunta $pergunta, MpmeRestricaoRepository $mpmeRestricaoRepository)
    {
        $rs_pergunta = $pergunta->getPergunta('O');

        $listaRestricoesPais = $mpmeRestricaoRepository->getRestricoesAbgfPais($request);
        $listaRestricoesSetores = $mpmeRestricaoRepository->getRestricoesAbgfSetores($request)->pluck('ID_SETOR')->toArray();

        $rs_modalidade_financiamento = $user->RetornaModalidadeFinancimento(Auth::user()->ID_USUARIO);
        $rs_setores = $setores->where('IN_VISUALIZA_CLIENTE', '=', 'S')->get();
        $rs_moeda = $moeda->getMoeda();
        $rs_pais = $pais->getPaisRisco();

        foreach ($listaRestricoesPais as $pais) {
            $arrayPais[$pais->ID_PAIS] = $pais->ID_PAIS;
        }

        $compact_args = array(
            'rs_moeda' => $rs_moeda,
            'rs_pais' => $rs_pais,
            'rs_modalidade_financiamento' => $rs_modalidade_financiamento,
            'rs_setores' => $rs_setores,
            'rs_pergunta' => $rs_pergunta,
            'listaRestricoesPais' => $listaRestricoesPais,
            'listaRestricoesSetores' => $listaRestricoesSetores,
            'arrayPais' => $arrayPais,
        );

        return view('questionario_operacao.novo_questionario', $compact_args);
    }

    public function salvar(Request $request, ImportadoresModel $importadoresModel)
    {
        $dados = [
            "ID_USUARIO" => Auth::user()->ID_USUARIO,
            "codigo_unico_importador" => $request->codigo_unico_importador,
            "id_cliente_mpme" => $request->id_cliente_mpme,
            "ID_OPER" => $request->id_oper,
            "ID_CLIENTE_EXPORTADORES_MODALIDADE" => $request->id_cliente_exportadores_modalidade,
            "RAZAO_SOCIAL" => $request->no_razao_social,
            "NAT_JURIDICA" => $request->id_nat_jur,
            "NAT_RISCO" => $request->id_nat_risco,
            "CNPJ" => $request->cnpj,
            "ENDERECO" => $request->endereco,
            "CIDADE" => $request->cidade,
            "CEP" => $request->cep,
            "ID_PAIS" => $request->id_pais,
            "CONTATO" => $request->contato,
            "TELEFONE" => $request->telefone,
            "FAX" => $request->fax,
            "E_MAIL" => $request->e_mail,
            "ID_SETOR" => 29, // $request->id_setor,
            "ID_MOEDA" => $request->id_moeda,
            "CODIGO_UNICO_IMPORTADOR" => $request->codigo_unico_importador,
            "FL_ATIVO" => '1',
            "FL_MOMENTO" => 'ANA',
            "ST_OPER" => '1',
            "IC_VALIDADO" => '0',
            "IC_ATIVO" => '1',
            "IC_ENVIADO" => '0',
            "CHECK_ENVIO" => $request->in_documentacao,
            "DATA_CADASTRO" => Carbon::now(),
            "ID_QUALIDADE_PRODUTO" => 2,
            "TP_COBERTURA_INDENIZACAO" => 'RP',
            "PERGUNTA" => $request->pergunta,
            "VL_PROPOSTA" => $request->vl_proposta,
            "ID_MPME_ALCADA" => 1,
            "IN_ACEITE_RESTRICOES" => $request->in_aceite_restricoes,
            "ARRAY_SETORES_ATIVIDADES" => $request->id_setor_atividade,
        ];

        if (!$importadoresModel->gravarQuestionarioOperacao($dados)) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº ',
            ));
        }

        return response()->json(array('status' => 'sucesso',
            'recarrega' => 'url',
            'url' => 'questionario_operacao/',
            'msg' => 'Cadastrado com sucesso. Atenção você precisa enviar a operação para que a ABGF possa iniciar o processo de análise',
        ));
    }

    public function editar(Request $request, User $user, TbSetores $setores, MoedaModel $moeda, Pais $pais,
        MpmePergunta $pergunta, ImportadoresModel $importadores, MpmeRestricaoRepository $mpmeRestricaoRepository
    ) {
        $rs_buscar_dados = $importadores->find($request->id_oper);
        $listaRestricoesSetores = $mpmeRestricaoRepository->getRestricoesAbgfSetores($request)->pluck('ID_SETOR')->toArray();
        $rs_setores = $setores->where('IN_VISUALIZA_CLIENTE', '=', 'S')->get();
        $rs_pergunta = $pergunta->getPergunta('O');

        $rs_modalidade_financiamento = $user->RetornaModalidadeFinancimento(Auth::user()->ID_USUARIO);
        $rs_moeda = $moeda->getMoeda();
        $rs_pais = $pais->getPais();
        $arraySetoresOperacao = $rs_buscar_dados->setoresOperacao->pluck('ID_SETOR')->toArray();

        $compact_args = array(
            'rs_moeda' => $rs_moeda,
            'rs_pais' => $rs_pais,
            'rs_modalidade_financiamento' => $rs_modalidade_financiamento,
            'rs_setores' => $rs_setores,
            'listaRestricoesSetores' => $listaRestricoesSetores,
            'rs_pergunta' => $rs_pergunta,
            'rs_buscar_dados' => $rs_buscar_dados,
            'arraySetoresOperacao' => $arraySetoresOperacao,
        );

        return view('questionario_operacao.editar_questionario', $compact_args);
    }

    public function inserir_comprovante_boleto_relatorio(Request $request, ImportadoresModel $importadoresModel)
    {

        if ($request->session()->has('datapasta_' . $request->token)) {
            $arquivos = $request->session()->get('datapasta_' . $request->token);

            $novo_arquivo = new ArquivoController();
            $destino = '/boleto_relatorio/' . $arquivos['id_oper'] . '/comprovante/';

            DB::beginTransaction();

            if ($novo_arquivo->insere_arquivo($arquivos, $destino)) {

                /*
                 *
                 * busca a modalidade e cliente exportador pela operação
                 *
                 *
                 *
                 */

                $id_modalidade = retornaModalidade($arquivos['id_oper']);

                $idMpmeClienteExportador = retornaClienteExportadorPelaOperacao($arquivos['id_oper']);

                $vigerenciaRelatorio = MpmeVigenciaRelatorio::where('ID_MPME_CLIENTE_EXPORTADORES', $idMpmeClienteExportador)->where('ID_MODALIDADE', $id_modalidade)->first();

                if ($vigerenciaRelatorio == null) {

                    $insereVigencia = new MpmeVigenciaRelatorio();
                    $insereVigencia->ID_MODALIDADE = $id_modalidade;
                    $insereVigencia->DT_INI_VIGENCIA = Carbon::now();
                    $insereVigencia->DT_FIM_VIGENCIA = Carbon::now()->addYear();
                    $insereVigencia->DT_CADASTRO = Carbon::now();
                    $insereVigencia->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                    $insereVigencia->DS_OBSERVACAO = 'INICIO DA VIGENCIA';
                    $insereVigencia->ID_MPME_CLIENTE_EXPORTADORES = $idMpmeClienteExportador;

                    if (!$insereVigencia->save()) {
                        DB::rollback();
                        return false;
                    }

                }

                $importador_selecionado = $importadoresModel->where('ID_OPER', '=', $arquivos['id_oper'])->first();
                $importador_selecionado->ST_OPER = $this::STOPER_EM_ANALISE;
                $importador_selecionado->save();

                /*
                 * INSERIR LOG DE MOVIMENTACAO DO QUESTIONÁRIO
                 */

                $mpme_movimentacao_questionario = new MpmeImportadoresRepository();

                $dados = [
                    'ID_OPER' => $arquivos['id_oper'],
                    'ST_OPER' => $this::STOPER_INSERCAO_COMPROVANTE,
                    'DS_OBSERVACAO' => 'COMPROVANTE DE BOLETO REGISTRADO COM SUCESSO',
                ];

                if (!$mpme_movimentacao_questionario->registarLogMovimentacaoQuestionario($dados)) {
                    DB::rollback();
                    return false;
                };

                //marcar como lida a visualizacao
                $notificacaoMarcarComoLida = new MpmeNotificacaoUsuarioRepository();
                $dados = (object) ['id_mpme_tipo_notificacao' => 2, 'id_oper' => $arquivos['id_oper']];
                $notificacaoMarcarComoLida->visualizarNotificacao($dados);

                //criar nova notificacao - analisar operacao
                $notificacao = new MpmeNotificacaoUsuarioRepository();
                $notificacao->registrar_notificacao([
                    'id_mpme_tipo_notificacao' => 3,
                    'id_oper' => $arquivos['id_oper'],
                ]);

                DB::commit();

                return response()->json(array('status' => 'sucesso',
                    'recarrega' => 'true',
                    'msg' => 'Upload realizado com sucesso.',
                ));
            } else {
                return response()->json(array('status' => 'erro',
                    'recarrega' => 'true',
                    'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                ));
            }

        } else {
            throw new Exception('A sessão de arquivos não foi localizada.');
        }
    }

    public function excluir(Request $request, MpmeImportadoresRepository $mpmeImportadoresRepository)
    {
        if (!$request->id_oper) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº ',
            ));
        }

        DB::beginTransaction();

        if (!$mpmeImportadoresRepository->excluir($request->id_oper)) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => 'Erro ao processar registro',
            ));
        };

        $dados = [
            'ID_OPER' => $request->id_oper,
            'ST_OPER' => 14,
            'DS_OBSERVACAO' => $request->ds_motivo,
        ];

        if (!$mpmeImportadoresRepository->registarLogMovimentacaoQuestionario($dados)) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => 'Erro ao processar registro',
            ));
        };

        DB::commit();

        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
            'msg' => 'Registro processado com sucesso.',
        ));
    }

    public function analise_operacional(Request $request, MpmeImportadoresRepository $mpmeImportadoresRepository)
    {
        if (!$request->id_oper) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Erro parametros insuficientes ',
            ));
        }


        DB::beginTransaction();


        if (!$mpmeImportadoresRepository->controle_operacional($request)) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº ',
            ));
        };

        $arrayFundo = $request->id_mpme_fundo_garantia;
        $arrayPercFundo = $request->vl_perc_fundo;
        $arrayValorReal = $request->vl_total_real;
        $arrayInSaldo = $request->in_saldo_suficiente;

        foreach ($arrayFundo as $key => $value) {
            $dados = [
                'id_mpme_fundo_garantia_principal' => $value,
                'id_oper' => $request->id_oper,
                'id_mpme_fundo_garantia_principal' => $request->id_mpme_fundo_garantia_operacao,
                'id_mpme_fundo_garantia' => $value,
                'id_mpme_alacada' => $request->id_alcada,
                'id_moeda' => $request->id_moeda,
                'vl_movimentacao' => $request->vl_cred_concedido,
                'vl_taxa_cambio' => $request->tx_cotacao,
                'vl_perc_fundo' => $arrayPercFundo[$key],
                'vl_total_reais' => $arrayValorReal[$key],
                'tipo_movimentacao' => 'DEBITO',
                'in_saldo_insuficiente' => $arrayInSaldo[$key],
            ];

            $movimentacaoControleCapitalRepository = new MpmeMovimentacaoControleCapitalRepository();

            if (!$movimentacaoControleCapitalRepository->movimentacao_controle_capital($dados)) {
                DB::rollback();
                return response()->json(array(
                    'status' => 'erro',
                    'recarrega' => 'false',
                    'msg' => 'Por favor, tente novamente mais tarde. Erro nº ',
                ));
            };
        }

        $controle_exportacao = new MpmeControelCapitalExportacaoRepository();

        if (!$controle_exportacao->salvarControleCapitalExportacao($request)) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº ',
            ));
        };

        $notificacaoMarcarComoLida = new MpmeNotificacaoUsuarioRepository();
        $dados = (object) ['id_mpme_tipo_notificacao' => 1, 'id_oper' => $request->id_oper];
        $notificacaoMarcarComoLida->visualizarNotificacao($dados);


        if (!$mpmeImportadoresRepository->negar_limite_operacional($request)) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº ',
            ));
        };

        DB::commit();

        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
            'msg' => 'Registro processado com sucesso.',
        ));
    }

}
