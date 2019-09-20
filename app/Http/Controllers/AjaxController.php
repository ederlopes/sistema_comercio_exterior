<?php

namespace App\Http\Controllers;

use App\ClienteExportadorModalidadeFinanciamento;
use App\ImportadoresModel;
use App\MpmeAprovacaoValorAlcada;
use App\MpmeCliente;
use App\MpmeClienteExportador;
use App\MpmeClienteImportador;
use App\MpmeHistEmbarque;
use App\MpmeNcmSetor;
use App\MpmeProposta;
use App\MpmePropostaAprovacao;
use App\MpmeTipoCliente;
use App\Notificacoes;
use App\OperacaoCadastroExportador;
use App\Repositories\MpmeAlcadaRepository;
use App\Repositories\MpmeControleCapitalRepository;
use App\Repositories\MpmeImportadoresRepository;
use App\Repositories\MpmeNotificacaoRepository;
use App\Repositories\MpmeNotificacaoUsuarioRepository;
use Auth;
use Carbon\Carbon;
use DB;
use File;
use Illuminate\Http\Request;

class AjaxController extends Controller
{

    public function buscarncmnbs(Request $request)
    {

        $valor_encontrado = null;

        if ($request->id_mpme_tipo_embarque == "" || $request->codigoncm == "") {
            return response()->json(array(
                'status' => 'alerta',
                'msg' => 'Dados insuficientes para processar solicitação',
                'recarrega' => 'false',
            ));
        }

        if ($request->id_mpme_tipo_embarque == 1) {
            $ncm = MpmeNcmSetor::join('TB_SETORES', 'TB_SETORES.ID_SETOR', '=', 'MPME_NCM_SETOR.ID_SETOR_FK')
                ->whereRaw('(IC_ATIVO = 1) and (' . $request->codigoncm . ' BETWEEN NU_NCM_INI AND NU_NCM_FIM) AND ID_SETOR_FK <> 28 group by NM_SETOR')
                ->first([
                    'NM_SETOR',
                ]);

            if (isset($ncm)) {
                $valor_encontrado = $ncm->NM_SETOR;
            }

        } else {
            $nbs = MpmeNcmSetor::whereRaw('(IC_ATIVO = 1) and (' . $request->codigoncm . ' BETWEEN NU_NCM_INI AND NU_NCM_FIM) AND ID_SETOR_FK = 28')
                ->first(['DS_NCM']);

            if (isset($nbs)) {
                $valor_encontrado = $nbs->DS_NCM;
            }

        }

        if (!isset($valor_encontrado)) {
            return response()->json(array(
                'status' => 'erro',
                'msg' => 'Dados não encontrados',
                'value' => $valor_encontrado,
                'recarrega' => 'false',
            ));
        }

        return response()->json(array(
            'status' => 'sucesso',
            'msg' => 'Registro encontrado com sucesso',
            'value' => $valor_encontrado,
            'recarrega' => 'false',
        ));

        return $ncm->NM_SETOR;
    }

    public function buscarImportadoresPorPais(Request $request)
    {
        $rs_cliente_importador = MpmeCliente::join('MPME_CLIENTE_IMPORTADORES', 'MPME_CLIENTE_IMPORTADORES.ID_MPME_CLIENTE', '=', 'MPME_CLIENTE.ID_MPME_CLIENTE')
            ->join('MPME_CLIENTE_TIPO_CLIENTE', 'MPME_CLIENTE_TIPO_CLIENTE.ID_MPME_CLIENTE', '=', 'MPME_CLIENTE.ID_MPME_CLIENTE')
            ->where('ID_TIPO_CLIENTE', '=', '2')
            ->where('ID_PAIS', '=', $request->id_pais)
            ->orderBy('NOME_CLIENTE')
            ->groupBy([
                'NOME_CLIENTE',
                'MPME_CLIENTE.ID_MPME_CLIENTE',
                'CODIGO_UNICO_IMPORTADOR',
            ])
            ->get([
                'NOME_CLIENTE',
                'MPME_CLIENTE.ID_MPME_CLIENTE',
                'CODIGO_UNICO_IMPORTADOR',
            ]);

        return response()->json($rs_cliente_importador);
    }

    public function buscarImportador(Request $request)
    {
        $rs_cliente_importador = MpmeCliente::join('MPME_CLIENTE_IMPORTADORES', 'MPME_CLIENTE_IMPORTADORES.ID_MPME_CLIENTE', '=', 'MPME_CLIENTE.ID_MPME_CLIENTE')->join('MPME_CLIENTE_TIPO_CLIENTE', 'MPME_CLIENTE_TIPO_CLIENTE.ID_MPME_CLIENTE', '=', 'MPME_CLIENTE.ID_MPME_CLIENTE')
            ->leftjoin('MPME_IMPORTADORES', 'MPME_IMPORTADORES.CODIGO_UNICO_IMPORTADOR', '=', 'MPME_CLIENTE_IMPORTADORES.CODIGO_UNICO_IMPORTADOR')
            ->where('MPME_IMPORTADORES.CODIGO_UNICO_IMPORTADOR', '=', $request->codigo_unico_importador)
            ->select('MPME_IMPORTADORES.*')
            ->orderByDesc('MPME_IMPORTADORES.ID_OPER')
            ->limit(1)
            ->get();

        return response()->json($rs_cliente_importador);
    }

    public function buscarDadosExportadorLogado()
    {
        $rs_cliente_importador = MpmeCliente::join('MPME_CLIENTE_EXPORTADORES', 'MPME_CLIENTE_EXPORTADORES.ID_MPME_CLIENTE', '=', 'MPME_CLIENTE.ID_MPME_CLIENTE')
            ->join('MPME_CLIENTE_TIPO_CLIENTE', 'MPME_CLIENTE_TIPO_CLIENTE.ID_MPME_CLIENTE', '=', 'MPME_CLIENTE.ID_MPME_CLIENTE')
            ->join('USUARIOS', 'USUARIOS.ID_USUARIO', '=', 'MPME_CLIENTE_EXPORTADORES.ID_USUARIO')
            ->where('MPME_CLIENTE_EXPORTADORES.ID_USUARIO', '=', Auth::user()->ID_USUARIO)
            ->select(
                'MPME_CLIENTE_EXPORTADORES.ID_MPME_CLIENTE',
                'USUARIOS.ID_PAIS',
                'DE_EMAIL',
                'DE_ENDER',
                'DE_CIDADE',
                'CPF_CNPJ_QUADRO',
                'NM_CONTATO',
                'DE_CEP',
                'DE_TEL',
                'DE_FAX',
                'NOME_FANTASIA'
            )->first();

        return response()->json($rs_cliente_importador);
    }

    public function mudaStatusQuestionarioOperacao(Request $request)
    {
        if (!isset($request->id_oper)) {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
            ));
        }

        DB::beginTransaction();

        $questionario_selecionado = ImportadoresModel::find($request->id_oper);

        $questionario_selecionado->ST_OPER = $request->st_oper;

        if (isset($request->ic_enviado)) {
            $questionario_selecionado->IC_ENVIADO = $request->ic_enviado;
        }

        switch ($request->st_oper) {
            case 12:
            case 20:
                $ds_motivo = 'ENVIO DO FORMULÁRIO PARA ABGF';
                $st_oper_log = 17;
                break;
            default:
                $ds_motivo = 'LOG PADRÃO';
                $st_oper_log = $request->st_oper;
                break;
        }

        $dados = [
            'ID_OPER' => $request->id_oper,
            'ST_OPER' => $st_oper_log,
            'DS_OBSERVACAO' => $ds_motivo,
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
            'id_mpme_tipo_notificacao' => 1,
            'id_oper' => $request->id_oper,
        ]);

        if ($request->in_notificacao = 'S') {
            // INSERINDO NOTIFICACAO
            $dados = [
                'ID_STATUS_NOTIFICACAO_FK' => 500,
                'ID_MPME_ALCADA' => 2,
                'ID_OPER' => $request->id_oper,
                'DE_NOTIFICACAO' => 1,
                'NM_USUARIO' => Auth::user()->NM_USUARIO,
                'RAZAO_SOCIAL' => $questionario_selecionado->RAZAO_SOCIAL,
                'DS_LINK' => '',
                'IC_ATIVO' => 1,
            ];

            $notificacao = MpmeNotificacaoRepository::salvaNotificacao((object) $dados);

            if (!$notificacao) {
                DB::rollback();
                return false;
            }

            // notificando usuario por email
            switch ($notificacao->ID_STATUS_NOTIFICACAO_FK) {
                case '500':
                case '501':
                case '502':
                case '503':
                    //MpmeNotificacaoRepository::enviarEmailNotificacao($request, $notificacao);
                    break;

                default: // notificar usuario

                    break;
            }
        }

        if (!$questionario_selecionado->save()) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
            ));
        }

        DB::commit();
        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
        ));
    }

    public function historico_aprovacao_operacao(Request $request)
    {
        if (!isset($request->id_oper)) {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
            ));
        }

        $mpme_aprovacao_valor_alcada = MpmeAprovacaoValorAlcada::where('ID_OPER', '=', $request->id_oper)->orderByDesc('ID_APROVACAO_VALOR_ALCADA')->get();

        $crontroleAlcadas = new MpmeAlcadaRepository();
        $crontroleAlcadas = $crontroleAlcadas->chkAlcadaValor($request->id_oper);

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'mpme_aprovacao_valor_alcada' => $mpme_aprovacao_valor_alcada,
            'crontroleAlcadas' => $crontroleAlcadas,
        );

        if ($mpme_aprovacao_valor_alcada) {
            return view('questionario_operacao.historico_aprovacao_questionario', $compact_args);
        }
    }

    public function recusar_proposta(Request $request, MpmePropostaAprovacao $mpmePropostaAprovacao)
    {
        $mpme_proposta_aprovacao_selecionado = $mpmePropostaAprovacao->where('ID_MPME_PROPOSTA', '=', $request->id_mpme_proposta)
            ->orderByDesc('ID_MPME_PROPOSTA_APROVACAO')
            ->first();

        DB::beginTransaction();

        if (count($mpme_proposta_aprovacao_selecionado) > 0) {
            $mpme_nova_proposta_aprovacao = new MpmePropostaAprovacao();
            $mpme_nova_proposta_aprovacao->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
            $mpme_nova_proposta_aprovacao->DS_MOTIVO = $request->ds_motivo;
            $mpme_nova_proposta_aprovacao->ID_MPME_ALCADA = (isset($request->id_mpme_alcada)) ? $request->id_mpme_alcada : retornaStatusPerfilAlcada(Auth::user()->ID_PERFIL);
            $mpme_nova_proposta_aprovacao->IN_DECISAO = (isset($request->in_decisao)) ? $request->in_decisao : null;
            $mpme_nova_proposta_aprovacao->VL_PROPOSTA = $mpme_proposta_aprovacao_selecionado->VL_PROPOSTA;
            $mpme_nova_proposta_aprovacao->VL_PERC_DOWPAYMENT = $mpme_proposta_aprovacao_selecionado->VA_PERCENTUAL_DW_PAYMENT;
            $mpme_nova_proposta_aprovacao->NU_PRAZO_PRE = $mpme_proposta_aprovacao_selecionado->NU_PRAZO_PRE;
            $mpme_nova_proposta_aprovacao->NU_PRAZO_POS = $mpme_proposta_aprovacao_selecionado->NU_PRAZO_POS;
            $mpme_nova_proposta_aprovacao->IN_ACEITE = $request->in_aceite;
            $mpme_nova_proposta_aprovacao->DT_CADASTRO = Carbon::now();
            $mpme_nova_proposta_aprovacao->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
        }

        if (!$mpme_nova_proposta_aprovacao->save()) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
            ));
        }

        $atualizar_proposta = new MpmeProposta();
        $atualizar_proposta_selecionar = $atualizar_proposta->where('ID_OPER', '=', $request->id_oper)
            ->where('ID_MPME_PROPOSTA', '=', $request->id_mpme_proposta)
            ->first();
        $atualizar_proposta_selecionar->ID_MPME_STATUS_PROPOSTA = 7;

        if (!$atualizar_proposta_selecionar->save()) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
            ));
        }

        DB::commit();
        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
        ));
    }

    public function enquadramentoAprovaModalidade(Request $request)
    {

        $ID_MOME_CLIENTE_EXPORTADORES = $request->ID_MOME_CLIENTE_EXPORTADORES;
        $ID_MODALIDADE = $request->ID_MODALIDADE;

        $updadeClienteExportadorModalidadeFinanciamento = ClienteExportadorModalidadeFinanciamento::where('ID_MPME_CLIENTE_EXPORTADORES', $request->ID_MOME_CLIENTE_EXPORTADORES)
            ->with('ModalidadeFinanciamento');

        if ($ID_MODALIDADE != 2) {
            $updadeClienteExportadorModalidadeFinanciamento = $updadeClienteExportadorModalidadeFinanciamento
                ->whereHas('ModalidadeFinanciamento', function ($query) use ($request) {
                    $query->where('ID_MODALIDADE', $request->ID_MODALIDADE);
                });
        }

        $updadeClienteExportadorModalidadeFinanciamento = $updadeClienteExportadorModalidadeFinanciamento->update(['IN_REGISTRO_ATIVO' => 'S']);
        if ($updadeClienteExportadorModalidadeFinanciamento) {
            echo "1";
        } else {
            echo 'erro';
        }
    }

    public function calcular_Data(Request $request)
    {
        if (!isset($request->dt_inicial)) {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
                'msg' => 'Paramentros insuficientes!',
            ));
        }

        $arrayData = explode("/", $request->dt_inicial);

        $dt = Carbon::create($arrayData[2], $arrayData[1], $arrayData[0], 0, 0, 0, 0);

        return $dt->addDays($request->nu_prazo)->format('d/m/Y');

    }

    public function novoImportador(Request $request)
    {

        if (!isset($request->id_oper)) {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
                'msg' => 'Paramentros insuficientes!',
            ));
        }

        /*
        Inicia uma transação no banco para
        garantir que todos os inserts sejam realizados.
         */
        DB::beginTransaction(); //

        try {

            //Busca o importador pelo id da operação
            $importador = ImportadoresModel::find($request->id_oper);

            //instancia o mpme cliente para salvar um novo cliente
            $mpmeCliente = new MpmeCliente();
            $mpmeCliente->NOME_CLIENTE = $importador->RAZAO_SOCIAL;
            $mpmeCliente->NOME_FANTASIA = $importador->RAZAO_SOCIAL;
            $mpmeCliente->ID_PAIS = $importador->ID_PAIS;
            $mpmeCliente->NOME_CIDADE = $importador->CIDADE;
            $mpmeCliente->REGISTRO_ATIVO = 'S';
            $mpmeCliente->NUMERO_DOC_IDENTIFICACAO = $importador->CNPJ;
            $mpmeCliente->NUMERO_CNPJ = $importador->CNPJ;
            $mpmeCliente->DATA_CADASTRO = Carbon::now();
            $mpmeCliente->save();

            //Instancia o tipo do cliente relaciona com o cliente
            $mpmeTipoCliente = new MpmeTipoCliente();
            $mpmeTipoCliente->ID_MPME_CLIENTE = $mpmeCliente->ID_MPME_CLIENTE;
            $mpmeTipoCliente->ID_TIPO_CLIENTE = 2; // TIPO IMPORTADOR
            $mpmeTipoCliente->DATA_CADASTRO = Carbon::now();
            $mpmeTipoCliente->save();

            //Instancia cliente importador
            $mpmeClienteImportador = new MpmeClienteImportador();
            $mpmeClienteImportador->ID_MPME_CLIENTE = $mpmeCliente->ID_MPME_CLIENTE;
            $mpmeClienteImportador->ID_OPER = $request->id_oper;
            $mpmeClienteImportador->DATA_CADASTRO = Carbon::now();
            $mpmeClienteImportador->CODIGO_UNICO_IMPORTADOR =
            MpmeClienteImportador::max('CODIGO_UNICO_IMPORTADOR') + 1;
            $mpmeClienteImportador->save();

            //Atualiza o codigo unico do importador
            $importador->CODIGO_UNICO_IMPORTADOR = $mpmeClienteImportador->CODIGO_UNICO_IMPORTADOR;
            $importador->save();

            if ($mpmeCliente && $mpmeTipoCliente && $mpmeClienteImportador && $importador) {
                DB::commit(); // Faz o commit dos inserts
                return response()->json(array(
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'msg' => 'Registro salvo com sucesso',
                ));
            }

        } catch (\Exception $e) {
            DB::rollback(); // desfaz os inserts
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => $e->getMessage() . " Na Linha - " . $e->getLine(),
            ));
        }

    }

    public function atualizaImportadorUnico(Request $request)
    {

        if (!isset($request->id_oper) || !isset($request->codigo_unico_importador) || !isset($request->id_mpme_cliente)) {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
                'msg' => 'Paramentros insuficientes!',
            ));
        }

        /*
        Inicia uma transação no banco para
        garantir que todos os inserts sejam realizados.
         */
        DB::beginTransaction(); //

        try {

            //Busca o importador pelo id da operação
            $importador = ImportadoresModel::find($request->id_oper);

            //Instancia cliente importador
            $mpmeClienteImportador = new MpmeClienteImportador();
            $mpmeClienteImportador->ID_MPME_CLIENTE = $request->id_mpme_cliente;
            $mpmeClienteImportador->ID_OPER = $request->id_oper;
            $mpmeClienteImportador->DATA_CADASTRO = Carbon::now();
            $mpmeClienteImportador->CODIGO_UNICO_IMPORTADOR = $request->codigo_unico_importador;
            $mpmeClienteImportador->save();

            //Atualiza o codigo unico do importador
            $importador->CODIGO_UNICO_IMPORTADOR = $request->codigo_unico_importador;
            $importador->save();

            if ($mpmeClienteImportador && $importador) {
                DB::commit(); // Faz o commit dos inserts
                return response()->json(array(
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'msg' => 'Registro salvo com sucesso',
                ));
            }

        } catch (\Exception $e) {
            DB::rollback(); // desfaz os inserts
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => $e->getMessage() . " Na Linha - " . $e->getLine(),
            ));
        }

    }

    public function buscarImportadorUnico(Request $request)
    {
        $rs_cliente_importador = MpmeCliente::join('MPME_CLIENTE_IMPORTADORES', 'MPME_CLIENTE_IMPORTADORES.ID_MPME_CLIENTE', '=', 'MPME_CLIENTE.ID_MPME_CLIENTE')
            ->join('MPME_CLIENTE_TIPO_CLIENTE', 'MPME_CLIENTE_TIPO_CLIENTE.ID_MPME_CLIENTE', '=', 'MPME_CLIENTE.ID_MPME_CLIENTE')
            ->join('PAISES', 'PAISES.ID_PAIS', 'MPME_CLIENTE.ID_PAIS')
            ->where('ID_TIPO_CLIENTE', '=', '2')
            ->where('MPME_CLIENTE.ID_PAIS', '=', $request->id_pais)
            ->orderBy('NOME_CLIENTE')
            ->groupBy([
                'NOME_CLIENTE',
                'MPME_CLIENTE.ID_MPME_CLIENTE',
                'CODIGO_UNICO_IMPORTADOR',
                'PAISES.CD_SIGLA',
            ])
            ->get([
                'NOME_CLIENTE',
                'MPME_CLIENTE.ID_MPME_CLIENTE',
                'CODIGO_UNICO_IMPORTADOR',
                'PAISES.CD_SIGLA',
            ]);

        return response()->json($rs_cliente_importador);
    }

    public function verificar_saldo(Request $request, MpmeControleCapitalRepository $mpmeControleCapitalRepository)
    {
        if ($request->id_oper == "" || $request->id_mpme_fundo_garantia == 0 || $request->vl_cred_concedido == "") {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
                'msg' => 'Parâmentros insuficientes!',
            ));
        }

        $valor_requerido = converte_float($request->vl_cred_concedido);
        $valor_fundo = $mpmeControleCapitalRepository->getValorFundo($request->id_mpme_fundo_garantia)[0]['VL_FATURAMENTO_ATUAL'];

        if ($valor_requerido > $valor_fundo) {
            return response()->json(array(
                'status' => 'saldo_insuficiente',
                'recarrega' => 'false',
                'msg' => 'Saldo do fundo insuficiente para aprovar esta operação!',
            ));
        }

        return response()->json(array(
            'status' => 'saldo_ok',
            'recarrega' => 'false',
            'msg' => 'Saldo do fundo ok!',
        ));

    }

    public function retornaOperacoes(Request $request)
    {
        $operacoes = ImportadoresModel::where('ID_USUARIO', $request->id_usuario)->get();
        return $operacoes;
    }

    public function historico_aprovacao_embarque(Request $request)
    {
        if (!isset($request->id_mpme_embarque)) {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
            ));
        }

        $historico_embarque = MpmeHistEmbarque::where('ID_MPME_EMBARQUE', $request->id_mpme_embarque)->get();

        if ($historico_embarque) {
            return view('embarque.historico_aprovacao', compact('historico_embarque'));
        }
    }

    public function ConsultaCodigoUnico(Request $request)
    {
        // Procura o codigo único quando o ID_OPER for diferente do atual.
        $operacao = OperacaoCadastroExportador::where('ID_OPER', '!=', $request->id_oper)->where('COD_UNICO_OPERACAO', $request->codUnico)->first();

        if (isset($operacao) && $operacao != "") {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
                'msg' => 'Código único da operação ja utilizado!',
            ));
        } else {
            return response()->json(array(
                'status' => 'info',
                'recarrega' => 'false',
                'msg' => 'numero válido',
            ));
        }
    }

    public function ConsultaEnquadramentoUsuario(Request $request)
    {
        // Verifica se o foi realizado o enquadramento ou não

        $enquadramento = MpmeClienteExportador::where('ID_USUARIO', $request->ID_USUARIO)
            ->with('ClienteExportadoresModalidadeFinanciamento')->first();

        $enquadrado = 0;

        if (isset($enquadramento) && $enquadramento != "") {

            foreach ($enquadramento->ClienteExportadoresModalidadeFinanciamento as $enquadradamento) {
                if ($enquadradamento->IN_REGISTRO_ATIVO == 'S') {
                    $enquadrado = 1;
                }
            }
        }

        echo $enquadrado;
    }

    public function checa_envio_delacarao_compromisso(Request $request, ImportadoresModel $importador)
    {
        $importador = $importador->where('ID_USUARIO', '=', Auth::user()->ID_USUARIO)->orderBy('ID_OPER')->first();
        $pathDeclaracaoAntiCorrupcao = public_path('/docs/anti-corrupcao/') . Auth::User()->ID_USUARIO . '/' . Auth::User()->ID_USUARIO . '.pdf';

        $dt_cadastro_primeira_operacao = $importador->DATA_CADASTRO;
        $arrayData = explode("-", $dt_cadastro_primeira_operacao);
        $ano = $arrayData[0];
        $mes = $arrayData[1];
        $dia = substr($arrayData[2], 0, 2);

        $dt_cadastro_primeira_operacao = Carbon::create($ano, $mes, $dia, 0, 0, 0, 0);

        if ($importador->count() == 0) {
            return response()->json(array(
                'status' => 'sucesso',
                'recarrega' => 'false',
                'msg' => 'Nenhuma operação existente!',
            ));
        }

        if (File::exists($pathDeclaracaoAntiCorrupcao) == true) {
            return response()->json(array(
                'status' => 'sucesso',
                'recarrega' => 'false',
                'msg' => 'Arquivo já importado!',
            ));
        }

        $data_verificacao = $dt_cadastro_primeira_operacao->addDays(15)->format('d/m/Y');
        $dt_now = Carbon::now()->format('d/m/Y');

        if ($dt_now <= $data_verificacao) {
            return response()->json(array(
                'status' => 'sucesso',
                'recarrega' => 'false',
                'msg' => 'Ainda esta no prazo!',
            ));
        } else {
            return response()->json(array(
                'status' => 'alerta',
                'recarrega' => 'false',
                'msg' => 'Você precisa fazer o upload da Declaração de compromisso antes de enviar uma nova Operação!',
            ));
        }
    }

    public function concluirAtualizacao(Request $request)
    {

        $notificacao = Notificacoes::find($request->ID_NOTIFICACAO);
        $notificacao->IC_ATIVO = 0;
        if ($notificacao->save()) {
            return response()->json(array(
                'status' => 'success',
                'recarrega' => 'true',
                'msg' => 'Atualização concluida!',
            ));
        } else {
            return response()->json(array(
                'status' => 'error',
                'recarrega' => 'false',
                'msg' => 'Ocorreu um erro na Atualização!',
            ));
        }

    }

}
