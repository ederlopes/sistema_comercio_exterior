<?php
namespace App\Http\Controllers;

use App\Cotacao;
use App\ImportadoresModel;
use App\ModalidadeModel;
use App\MpmeAprovacaoValorAlcada;
use App\MpmeArquivo;
use App\MpmeControleLimiteCliente;
use App\MpmeCreditScore;
use App\MpmeMovimentacaoControleCapital;
use App\MpmePergunta;
use App\MpmeQuestionario;
use App\MpmeTipoIndeferimento;
use App\OperacaoCadastroExportador;
use App\Repositories\MpmeAlcadaRepository;
use App\Repositories\MpmeAprovacaoValorAlcadaRepository;
use App\Repositories\MpmeCreditoConcedidoRepository;
use App\Repositories\MpmeCreditScoreExportadorRepository;
use App\Repositories\MpmeCreditScoreRepository;
use App\Repositories\MpmeCriterioOperacaoRepository;
use App\Repositories\MpmeFundoGarantiaRepository;
use App\Repositories\MpmeImportadoresAprovacaoRepository;
use App\Repositories\MpmeImportadoresRepository;
use App\Repositories\MpmeIndeferimentoRepository;
use App\Repositories\MpmeMovimentacaoControleCapitalRepository;
use App\Repositories\MpmeNotificacaoRepository;
use App\Repositories\MpmeNotificacaoUsuarioRepository;
use App\Repositories\MpmeRecomendacaoRepository;
use App\Repositories\MpmeRespostaIndeferimentoRepository;
use App\Repositories\MpmeRestricaoRepository;
use App\Repositories\MpmeTempoValidacaoRepository;
use App\StatusOper;
use App\User;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AbgfAnalisaLimiteController extends Controller
{
    const AGUARDANDO_COMPROVANTE_PAGAMENTO_RELATORIO = 13;
    const STATUSOPER_ANALISE = 3;
    const STATUSOPER_AGUARDANDO_BOLETO = 12;
    const STATUSOPER_ANALISE_LIMITE = 20;

    public function index(Request $request, ImportadoresModel $importadores, StatusOper $statusOper, ModalidadeModel $modalidadeModel)
    {
        $importadores->menuImportadores();

        $rs_modalidade = $modalidadeModel::all();
        $rs_status_operacao = $statusOper->where('IN_VISUALIZA_INTERNO', '=', 'S')->orderBy('NM_OPER')->get();

        if (!is_null($request->st_oper)) {
            if ($request->st_oper == "0") {
                $status = null;
            } else {
                $status = [$request->st_oper];
            }
        } else {
            $status = [$this::STATUSOPER_ANALISE, $this::STATUSOPER_AGUARDANDO_BOLETO, $this::STATUSOPER_ANALISE_LIMITE];
        }

        $where = [
            'ID_OPER' => $request->id_oper,
            'ID_MODALIDADE' => $request->id_modalidade,
            'COD_UNICO_OPERACAO' => $request->cod_unico_operacao,
            'ST_OPER' => $status,
            'total_paginacao' => $request->total_paginacao,
        ];

        $importadores = $importadores->getQuestionarioOperacao($where);

        if (!Auth::user()->isSuperAdmin()) {

            if (!Auth::user()->can('LISTAR_OPERACAO_APROCACAO')) {

                if ($request->id_oper == "" && $request->id_modalidade == "" && $request->cod_unico_operacao == "") {
                    $importadores = $importadores->join('MPME_NOTIFICACAO', function ($join) {
                        $join->on('MPME_NOTIFICACAO.ID_OPER', '=', 'MPME_IMPORTADORES.ID_OPER')
                            ->where('MPME_NOTIFICACAO.IC_ATIVO', '=', 1)
                            ->where('MPME_NOTIFICACAO.ID_MPME_ALCADA', '=', @Auth::user()->usuario_alcada_principal->ID_MPME_ALCADA);
                    });
                }
            }
        }

        $rsImportadores = $importadores->orderByDesc('MPME_IMPORTADORES.ID_OPER')
            ->paginate(($where['total_paginacao']) ? $where['total_paginacao'] : 10);

        $rsImportadores->appends(Request::capture()->except('table_proposta', '_token'));

        $token = str_random(30);

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rsImportadores' => $rsImportadores,
            'rs_modalidade' => $rs_modalidade,
            'rs_status_operacao' => $rs_status_operacao,
            'token' => $token,
        );

        return view('abgf.exportador.limite.index_questionario_aprovacao', $compact_args);
    }

    public function listarQuestionario(Request $request, Cotacao $cotacao, MpmeAlcadaRepository $mpmeAlcadaRepository, ImportadoresModel $importadoresModel, MpmeRestricaoRepository $mpmeRestricaoRepository, MpmePergunta $pergunta, MpmeQuestionario $questionario)
    {
        $dadosFundoGarantia = MpmeFundoGarantiaRepository::getMpmeFundoGarantia();

        /*$dt = Carbon::now();
        $nova_data = $dt->subWeekday()->format('Y-m-d 00:00:00');*/


        $where = [
            'ID_OPER' => $request->idoper,
        ];

        $creditScoreImportador = $importadoresModel->MpmeCreditScore($request->idoper);
        $creditScoreExportador = $importadoresModel->MpmeCreditScoreExportador($request->idoper);

        $operacao = $importadoresModel->getQuestionarioOperacao($where)->first();

        $cotacao = $cotacao->where('MOEDA_ID', '=', $operacao->ID_MOEDA)->orderBy('DATA', 'desc')->first(['TAXA_VENDA'])->TAXA_VENDA;

        $cotacao = str_replace(",", ".", $cotacao);
        $cotacao = (float)$cotacao;

        $exportador = User::find($request->id_exportador);

        $importador = ImportadoresModel::where('ID_OPER', $request->idoper)->with('UltimaAlcadaMovimentacao', 'creditScoreImportador', 'CreditScoreExportador')->first();

        $crontroleAlcadas = $mpmeAlcadaRepository->chkAlcadaValor($request->idoper);

        $dadosFundoGarantia = MpmeFundoGarantiaRepository::getMpmeFundoGarantia();

        $dadosCreditScore = $importadoresModel->MpmeCreditScore($request->idoper);
        $dadosCreditScorePre = $importadoresModel->MpmeCreditScoreExportador($request->idoper);
        $dadosAProvValorAlc = $importadoresModel->MpmeAprovacaoValorAlcada($request->idoper);

        $tipoIndeferimentos = MpmeTipoIndeferimento::all();

        //$motivoAlteracaoCreditScore =

        $listaRestricoesPais = $mpmeRestricaoRepository->getRestricoesAbgfPais($request);
        $listaRestricoesSetores = $mpmeRestricaoRepository->getRestricoesAbgfSetores($request);

        $rs_pergunta = $pergunta->getPergunta('O');

        $rs_questionario = $questionario->where('ID_OPER', $request->idoper)
            ->get()
            ->pluck('ID_MPME_PERGUNTA_RESPOSTA')
            ->toArray();

        $arquivos = MpmeArquivo::where('ID_USUARIO_CAD', $operacao->ID_USUARIO)->whereIn('ID_MPME_TIPO_ARQUIVO', [20, 21])->get();

        return view('abgf.exportador.limite.analisa_limite', compact(
            'exportador',
            'importador',
            'tipoIndeferimentos',
            'crontroleAlcadas',
            'dadosCreditScore',
            'dadosCreditScorePre',
            'dadosAProvValorAlc',
            'dadosFundoGarantia',
            'listaRestricoesPais',
            'listaRestricoesSetores',
            'cotacao',
            'operacao',
            'rs_pergunta',
            'rs_questionario',
            'creditScoreImportador',
            'creditScoreExportador',
            'arquivos'
        ));
    }

    public function analistaAprovaLimite(Request $request, MpmeMovimentacaoControleCapitalRepository $MpmeMovimentacaoControleCapitalRepository)
    {
        $campos = (object)$request->all();

        // Verifica se foi cadastrado o importador unico ou não
        if ($request->CODIGO_UNICO_IMPORTADOR == 0 || empty($request->CODIGO_UNICO_IMPORTADOR)) {
            return response()->json(array(
                'header' => 'Importador único ?',
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Você deve informar se o importador é unico ou não',
            ));
        }


        // verifica upload
        if (!VerificaSeuploadFoifeito('upload_calculo_limite_credito', $request->ID_OPER)) {
            return response()->json([
                'status' => 'erro',
                'message' => 'Faça os Uploads do cálculo de limite de crédito para continuar.',
                'class_mensagem' => 'error',
                'header' => 'Faça o upload!',
            ]);
        }

        if (!VerificaSeuploadFoifeito('comprovante_pg_relatorio', $request->ID_OPER) && $request->modalidade != 3) {
            return response()->json([
                'status' => 'erro',
                'message' => 'Faça os Uploads Necessários para continuar.',
                'class_mensagem' => 'error',
                'header' => 'Faça o upload!',
            ]);
        }
        if (!VerificaSeuploadFoifeito('relatorio_internacional', $request->ID_OPER)) {
            return response()->json([
                'status' => 'erro',
                'message' => 'Faça os Uploads Necessários para continuar.',
                'class_mensagem' => 'error',
                'header' => 'Faça o upload!',
            ]);
        }

        if ($request->ID_OPER == "" || $request->id_mpme_fundo_garantia == 0 || $request->vl_cred_concedido == "") {
            return response()->json(array(
                'header' => 'Parâmentros insuficientes!',
                'status' => 'erro',
                'recarrega' => 'false',
                'message' => 'Parâmentros insuficientes!',
            ));
        }



        /*AGUARDAR LIBERACAO DE PADRAO
        $mpmeControleCapitalRepository = new MpmeControleCapitalRepository();

        $valor_requerido    = converte_float($request->vl_cred_concedido);
        $valor_fundo        = $mpmeControleCapitalRepository->getValorFundo($request->id_mpme_fundo_garantia)[0]['VL_FATURAMENTO_ATUAL'];

        if ($valor_requerido > $valor_fundo)
        {
        return response()->json(array(
        'header' => 'ATENÇÃO!',
        'status' => 'erro',
        'class_mensagem' => 'error',
        'recarrega' => 'false',
        'message' => 'Saldo do fundo insuficiente para aprovar esta operação!'
        ));
        }*/

        $inseriuCreditScore = 0;
        DB::beginTransaction(); // Inicia uma transação no banco para garantir que todos os inserts sejam realizados.
        ($request->motivo_indeferimento != "" && !empty(array_filter($request->motivo_indeferimento))) ? MpmeRespostaIndeferimentoRepository::salvaRespostaIndeferimento($campos) : '';

        switch ($request->modalidade) {
            case 1:
                $creditScoreExportador = MpmeCreditScoreExportadorRepository::salvaCreditStoreExportador($request); // Salva ou atualiza o creditScore do exportador
                if ($creditScoreExportador) {
                    $inseriuCreditScore = 1;
                } else {
                    $inseriuCreditScore = 0;
                }
                break;

            case 2:
                $creditScoreExportador = MpmeCreditScoreExportadorRepository::salvaCreditStoreExportador($request); // Salva ou atualiza o creditScore do exportador
                $creditScore = MpmeCreditScoreRepository::salvaCreditStore($request); // Salva ou atualiza o creditScore
                if ($creditScoreExportador && $creditScore) {
                    $inseriuCreditScore = 1;
                } else {
                    $inseriuCreditScore = 0;
                }
                break;

            case 3:
                $creditScore = MpmeCreditScoreRepository::salvaCreditStore($request); // Salva ou atualiza o creditScore
                if ($creditScore) {
                    $inseriuCreditScore = 1;
                } else {
                    $inseriuCreditScore = 0;
                }
                break;
        }

        $arrayFundo = $request->id_mpme_fundo_garantia_negocio;
        $arrayPercFundo = $request->vl_perc_fundo;
        $arrayValorReal = $request->vl_total_real;
        $arrayInSaldo = $request->in_saldo_suficiente;

        $deleta = MpmeMovimentacaoControleCapital::where('ID_OPER', $request->ID_OPER)->where('ID_MPME_ALCADA', $request->ID_MPME_ALCADA)->delete();

        foreach ($arrayFundo as $key => $value) {
            $dados = [
                'id_mpme_fundo_garantia_principal' => $request->id_mpme_fundo_garantia_operacao,
                'id_oper' => $request->ID_OPER,
                'id_mpme_fundo_garantia' => $value,
                'id_mpme_alacada' => $request->ID_MPME_ALCADA,
                'id_moeda' => $request->id_moeda,
                'vl_movimentacao' => converte_float($request->vl_cred_concedido),
                'vl_taxa_cambio' => $request->tx_cotacao,
                'vl_perc_fundo' => $arrayPercFundo[$key],
                'vl_total_reais' => converte_float($arrayValorReal[$key]),
                'tipo_movimentacao' => 'DEBITO',
                'in_saldo_insuficiente' => $arrayInSaldo[$key],
            ];

            $movimentacaoControleCapitalRepository = new MpmeMovimentacaoControleCapitalRepository();

            if (!$movimentacaoControleCapitalRepository->movimentacao_controle_capital($dados)) {
                DB::rollback();
                return response()->json(array(
                    'message' => 'Ocorreu um erro ao salvar a movimentação!',
                    'class_mensagem' => 'error',
                    'status' => 'erro',
                    'header' => 'Ocorreu um erro ao salvar!',
                ));
            };
        }

        $criterio = MpmeCriterioOperacaoRepository::salvaCriterio($campos); // Salva ou atualiza o criterio caso haja ID_CRITERIO;
        $recomendacao = MpmeRecomendacaoRepository::salvaRecomendacao($campos); // Salva ou atualiza a recomendacao
        $creditoConcedido = MpmeCreditoConcedidoRepository::salvaCreditoConcedido($campos); // Salva ou atualiza o credito concedido

        $mpmeImportadoresRepository = new MpmeImportadoresRepository();

        $dados = [
            'ID_OPER' => $request->ID_OPER,
            'ST_OPER' => 3,
            'DS_OBSERVACAO' => 'REGISTRO SALVO COM SUCESSO POR: ' . $request->NO_ALCADA,
        ];

        $inseriuLog = $mpmeImportadoresRepository->registarLogMovimentacaoQuestionario($dados);

        $numeroUnicoOperacao = OperacaoCadastroExportador::where('ID_OPER', $request->ID_OPER)->first();

        if ($numeroUnicoOperacao != "") {
            $numeroUnicoOperacao->COD_UNICO_OPERACAO = $request->COD_UNICO_OPERACAO;
            if (!$numeroUnicoOperacao->save()) {
                DB::rollback(); // faz um rollback no banco
                return response()->json([
                    'status' => 'erro',
                    'message' => 'Ocorreu um erro ao salvar o numero único da operação!',
                    'class_mensagem' => 'error',
                    'header' => 'Ocorreu um erro ao salvar!',
                ]);
            }
        }

        if ($inseriuCreditScore == 1 && $criterio && $recomendacao && $creditoConcedido && $inseriuLog) {
            DB::commit(); // Faz o commit dos inserts
            return response()->json([
                'status' => 'sucesso',
                'message' => 'Operação relizada com sucesso!',
                'class_mensagem' => 'success',
                'header' => 'Salvo!',
            ]);
        } else {
            DB::rollback(); // faz um rollback no banco
            return response()->json([
                'status' => 'erro',
                'message' => 'Ocorreu um erro ao salvar o credit-score e o parecer!',
                'class_mensagem' => 'error',
                'header' => 'Ocorreu um erro ao salvar!',
            ]);
        }
    }

    public function encaminhar(Request $request)
    {
        $campos = (object)$request->all();

        DB::beginTransaction();
        $mpmeImportadoresAprovacao = MpmeImportadoresAprovacaoRepository::salvaImportadoresAprovacao($campos);
        $mpmeTempoValidacao = MpmeTempoValidacaoRepository::salvaTempoValidacao($campos);
        $mpmeAprovacaoValorAlcada = MpmeAprovacaoValorAlcada::gravarAprovacaoAlcada($request->all());
        $mpmeNotifAlcada = MpmeNotificacaoRepository::desativaNotificacaoPorAlcada($campos, $request->ID_MPME_ALCADA);
        $mpmeNotif = MpmeNotificacaoRepository::NotificaProximaAlcada($campos);

        $mpmeImportadoresRepository = new MpmeImportadoresRepository();

        $dados = [
            'ID_OPER' => $request->ID_OPER,
            'ST_OPER' => 18,
            'DS_OBSERVACAO' => 'ANALISE ENCAMINHADA POR: ' . $request->NO_ALCADA,
        ];

        $inseriuLog = $mpmeImportadoresRepository->registarLogMovimentacaoQuestionario($dados);

        if ($mpmeImportadoresAprovacao && $mpmeTempoValidacao && $mpmeAprovacaoValorAlcada && $mpmeNotifAlcada && $mpmeNotif && $inseriuLog) {
            DB::commit(); // Faz o commit dos inserts
            $usuario = Auth::User();

            //Notification::send(Auth::User(), new \App\Notifications\AnaliseAprovada());

            return response()->json([
                'message' => 'Analise encaminhada!',
                'class_mensagem' => 'success',
                'header' => 'Encaminhado!',
            ]);
        } else {
            DB::rollback(); // faz um rollback no banco
            return response()->json([
                'message' => 'Ocorreu um erro ao encaminhar!',
                'class_mensagem' => 'error',
                'header' => 'Ocorreu um erro ao encaminhar!',
            ]);
        }
    }

    public function devolver(Request $request)
    {
        DB::beginTransaction();
        $campos = (object)$request->all();
        $mpmeAprovacaoValorAlcada = MpmeAprovacaoValorAlcadaRepository::devolveAlcada($campos);
        $dvmpmeImportadoresAprovacao = MpmeImportadoresAprovacaoRepository::salvaImportadoresAprovacao($campos);
        $mpmeNotifAlcada = MpmeNotificacaoRepository::desativaNotificacaoPorAlcada($campos, $request->ID_MPME_ALCADA);
        $mpmeNotif = MpmeNotificacaoRepository::salvaNotificacao($campos);
        $mpmeImport = MpmeImportadoresRepository::devolveAlcada($campos);

        $mpmeImportadoresRepository = new MpmeImportadoresRepository();

        $dados = [
            'ID_OPER' => $request->ID_OPER,
            'ST_OPER' => 19,
            'DS_OBSERVACAO' => 'ANALISE DEVOLVIDA POR: ' .
                retornaNomeAlcada($request->ID_MPME_ALCADA) .
                ' <br /> Texto: ' . $request->DE_MOTIVO_DEVOLUCAO,
        ];

        $inseriuLog = $mpmeImportadoresRepository->registarLogMovimentacaoQuestionario($dados);

        if ($mpmeAprovacaoValorAlcada && $dvmpmeImportadoresAprovacao && $mpmeNotif && $mpmeImport && $mpmeNotifAlcada && $inseriuLog) {
            DB::commit(); // Faz o commit dos inserts
            return response()->json([
                'message' => 'Analise devolvida!',
                'class_mensagem' => 'success',
                'header' => 'Devolvido!',
            ]);
        } else {
            DB::rollback(); // faz um rollback no banco
            return response()->json([
                'message' => 'Ocorreu um erro ao devolver!',
                'class_mensagem' => 'error',
                'header' => 'Ocorreu um erro ao devolver!',
            ]);
        }
    }

    public function indeferir(Request $request)
    {
        DB::beginTransaction();
        $campos = (object)$request->all();
        $mpmeAprovacaoValorAlcada = MpmeAprovacaoValorAlcada::gravarAprovacaoAlcada($request->all());
        $dvmpmeImportadoresAprovacao = MpmeImportadoresAprovacaoRepository::salvaImportadoresAprovacao($campos);
        $mudaStatusMpmeImportadores = MpmeImportadoresRepository::indeferir($campos);
        $mpmeIndeferimento = MpmeIndeferimentoRepository::indeferir($campos);
        $mpmeNotifDesativa = MpmeNotificacaoRepository::desativaNotificacao($campos);
        $mpmeNotif = MpmeNotificacaoRepository::indeferir($campos);

        $mpmeImportadoresRepository = new MpmeImportadoresRepository();

        $dados = [
            'ID_OPER' => $request->ID_OPER,
            'ST_OPER' => 9,
            'DS_OBSERVACAO' => 'ANALISE INDEFERIDA POR: ' .
                retornaNomeAlcada($request->ID_MPME_ALCADA) .
                ' <br /> Texto: ' . $request->DS_RECOMENDACAO,
        ];

        $inseriuLog = $mpmeImportadoresRepository->registarLogMovimentacaoQuestionario($dados);

        if ($mpmeAprovacaoValorAlcada && $dvmpmeImportadoresAprovacao && $mpmeNotifDesativa && $mpmeNotif && $mpmeIndeferimento) {
            DB::commit(); // Faz o commit dos inserts
            return response()->json([
                'message' => 'Operação indeferida!',
                'class_mensagem' => 'success',
                'header' => 'Indeferida!',
            ]);
        } else {
            DB::rollback(); // faz um rollback no banco
            return response()->json([
                'message' => 'Ocorreu um erro indeferida!',
                'class_mensagem' => 'error',
                'header' => 'Ocorreu um erro indeferida!',
            ]);
        }
    }

    public function concluir(Request $request)
    {
        DB::beginTransaction();
        $campos = (object)$request->all();
        $mpmeAprovacaoValorAlcada = MpmeAprovacaoValorAlcada::gravarAprovacaoAlcada($request->all());
        $dvmpmeImportadoresAprovacao = MpmeImportadoresAprovacaoRepository::aprovaLimite($campos);
        $dvmpmeImportadores = MpmeImportadoresRepository::aprovaLimite($campos);
        $mpmeNotif = MpmeNotificacaoRepository::desativaNotificacao($campos);
        $mpmeNotif = MpmeNotificacaoRepository::salvaNotificacao($campos);

        $mpmeImportadoresRepository = new MpmeImportadoresRepository();

        $dados = [
            'ID_OPER' => $request->ID_OPER,
            'ST_OPER' => 4,
            'DS_OBSERVACAO' => 'ANALISE CONCLUIDA POR: ' . $request->NO_ALCADA,
        ];

        $inseriuLog = $mpmeImportadoresRepository->registarLogMovimentacaoQuestionario($dados);

        $id_modalidade = retornaModalidade($request->ID_OPER);
        $idMpmeClienteExportador = retornaClienteExportadorPelaOperacao($request->ID_OPER);
        $idMpmeClienteImportador = retornaClienteImportadorPelaOperacao($request->ID_OPER);

        switch ($id_modalidade) {
            case 1:

                $limite_clienteExp = new MpmeControleLimiteCliente();
                $limite_clienteExp->ID_MODALIDADE = $id_modalidade;
                $limite_clienteExp->ID_MPME_CLIENTE = $idMpmeClienteExportador;
                $limite_clienteExp->VL_APROVADO = converte_float($request->VL_APROVADO);
                $limite_clienteExp->DT_CADASTRO = Carbon::now();
                $limite_clienteExp->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                if (!$limite_clienteExp->save()) {
                    DB::rollback();
                    return response()->json([
                        'message' => 'Ocorreu ao salvar o controle de limite do cliente',
                        'class_mensagem' => 'error',
                        'header' => 'Ocorreu um erro ao aprovar o limite!',
                    ]);
                }

                break;

            case 2:

                $limite_clienteExp = new MpmeControleLimiteCliente();
                $limite_clienteExp->ID_MODALIDADE = $id_modalidade;
                $limite_clienteExp->ID_MPME_CLIENTE = $idMpmeClienteExportador;
                $limite_clienteExp->VL_APROVADO = converte_float($request->VL_APROVADO);
                $limite_clienteExp->DT_CADASTRO = Carbon::now();
                $limite_clienteExp->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                if (!$limite_clienteExp->save()) {
                    DB::rollback();
                    return response()->json([
                        'message' => 'Ocorreu ao salvar o controle de limite do cliente',
                        'class_mensagem' => 'error',
                        'header' => 'Ocorreu um erro ao aprovar o limite!',
                    ]);
                }

                $limite_cliente = new MpmeControleLimiteCliente();
                $limite_cliente->ID_MODALIDADE = $id_modalidade;
                $limite_cliente->ID_MPME_CLIENTE = $idMpmeClienteImportador;
                $limite_cliente->VL_APROVADO = converte_float($request->VL_APROVADO);
                $limite_cliente->DT_CADASTRO = Carbon::now();
                $limite_cliente->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                if (!$limite_cliente->save()) {
                    DB::rollback();
                    return response()->json([
                        'message' => 'Ocorreu ao salvar o controle de limite do cliente',
                        'class_mensagem' => 'error',
                        'header' => 'Ocorreu um erro ao aprovar o limite!',
                    ]);
                }

                break;

            case 3:

                $limite_cliente = new MpmeControleLimiteCliente();
                $limite_cliente->ID_MODALIDADE = $id_modalidade;
                $limite_cliente->ID_MPME_CLIENTE = $idMpmeClienteImportador;
                $limite_cliente->VL_APROVADO = converte_float($request->VL_APROVADO);
                $limite_cliente->DT_CADASTRO = Carbon::now();
                $limite_cliente->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                if (!$limite_cliente->save()) {
                    DB::rollback();
                    return response()->json([
                        'message' => 'Ocorreu ao salvar o controle de limite do cliente',
                        'class_mensagem' => 'error',
                        'header' => 'Ocorreu um erro ao aprovar o limite!',
                    ]);
                }

                break;
        }

        if ($mpmeAprovacaoValorAlcada && $dvmpmeImportadoresAprovacao && $mpmeNotif && $dvmpmeImportadores && $inseriuLog) {
            DB::commit(); // Faz o commit dos inserts
            return response()->json([
                'message' => 'Limite Aprovado!',
                'class_mensagem' => 'success',
                'header' => 'Limite aprovado!',
            ]);
        } else {
            DB::rollback(); // faz um rollback no banco
            return response()->json([
                'message' => 'Ocorreu um erro ao aprovar o limite!',
                'class_mensagem' => 'error',
                'header' => 'Ocorreu um erro ao aprovar o limite!',
            ]);
        }
    }

    public function inserir_boleto_relatorio(Request $request, ImportadoresModel $importadoresModel)
    {
        //operacao com arquivo

        if ($request->session()->has('datapasta_' . $request->token)) {
            $arquivos = $request->session()->get('datapasta_' . $request->token);

            $novo_arquivo = new ArquivoController();
            $destino = '/boleto_relatorio/' . $arquivos['id_oper'];

            DB::beginTransaction();

            if ($novo_arquivo->insere_arquivo($arquivos, $destino)) {
                $importador_selecionado = $importadoresModel->where('ID_OPER', '=', $arquivos['id_oper'])->first();
                $importador_selecionado->ST_OPER = $this::AGUARDANDO_COMPROVANTE_PAGAMENTO_RELATORIO;

                if (!$importador_selecionado->save()) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                    ));
                };

                $dados = [
                    'ID_OPER' => $arquivos['id_oper'],
                    'ST_OPER' => 16,
                    'DS_OBSERVACAO' => 'UPLOAD DE RELATORIO DE DOCUMENTO FEITO COM SUCESSO',
                ];

                $mpmeImportadoresRepository = new MpmeImportadoresRepository();

                if (!$mpmeImportadoresRepository->registarLogMovimentacaoQuestionario($dados)) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao processar registro',
                    ));
                };

                //novo sistema de notificacao
                $notificacao = new MpmeNotificacaoUsuarioRepository();

                $notificacao->registrar_notificacao([
                    'id_mpme_tipo_notificacao' => 2,
                    'id_oper' => $arquivos['id_oper'],
                ]);

                DB::commit();
                return response()->json(array(
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'msg' => 'Upload realizado com sucesso.',
                ));
            } else {
                DB::rollback();
                return response()->json(array(
                    'status' => 'erro',
                    'recarrega' => 'true',
                    'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                ));
            }
        } else {
            throw new Exception('A sessão de arquivos não foi localizada.');
        }
    }

    public function limite_operacional(Request $request, ImportadoresModel $importadoresModel, Cotacao $cotacao)
    {
        $where = [
            'ID_OPER' => $request->id_oper,
        ];

        $operacao = $importadoresModel->getQuestionarioOperacao($where)->first();

        $exportador = User::find($operacao->OperacaoCadastroExportador->ID_USUARIO_CAD);
        $importador = ImportadoresModel::find($request->id_oper);

        $creditScoreImportador = $importadoresModel->MpmeCreditScore($request->id_oper);
        $creditScoreExportador = $importadoresModel->MpmeCreditScoreExportador($request->id_oper);

        $dadosFundoGarantia = MpmeFundoGarantiaRepository::getMpmeFundoGarantia();

        $cotacao = $cotacao->where('MOEDA_ID', '=', $operacao->ID_MOEDA)->orderBy('DATA', 'desc')->first(['TAXA_VENDA'])->TAXA_VENDA;

        if ($cotacao == "") {
            echo '<div class="alert alert-danger" role="alert">Não foi encontrada taxa de cotação para esta operação. Favor entrar em contato com o Administrador do sistema</div>';
            exit();
        } else {
            $cotacao = str_replace(",", ".", $cotacao);
            $cotacao = (float)$cotacao;
        }

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'operacao' => $operacao,
            'exportador' => $exportador,
            'importador' => $importador,
            'creditScoreImportador' => $creditScoreImportador,
            'creditScoreExportador' => $creditScoreExportador,
            'dadosFundoGarantia' => $dadosFundoGarantia,
            'cotacao' => $cotacao,

        );

        return view('abgf.exportador.limite.analise_limite_operacional', $compact_args);
    }
}
