<?php

namespace App\Http\Controllers;

use App\Cotacao;
use App\ImportadoresModel;
use App\MpmeAprovacaoValorAlcada;
use App\MpmeArquivo;
use App\MpmeFormulaPremio;
use App\MpmeHistProposta;
use App\MpmePergunta;
use App\MpmeProposta;
use App\MpmeQuestionario;
use App\MpmeStatus;
use App\MpmeStatusProposta;
use App\Repositories\MpmeAlcadaRepository;
use App\Repositories\MpmeArquivoRepository;
use App\Repositories\MpmeEmbarqueRepository;
use App\Repositories\MpmeFundoGarantiaRepository;
use App\Repositories\MpmeImportadoresRepository;
use App\Repositories\MpmeNotificacaoUsuarioRepository;
use App\Repositories\MpmePropostaAprovacaoRepository;
use App\Repositories\MpmePropostaRepository;
use App\Repositories\MpmeRestricaoRepository;
use App\TbSetores;
use App\User;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Exception;
use Session;

class PropostaController extends Controller
{

    const ID_MPME_TIPO_ARQUIVO_BOLETO = 8;

    const ID_MPME_TIPO_ARQUIVO_CG_ASSINADO = 13; // Certificado Assinado

    const ID_MPME_TIPO_ARQUIVO_APOLICE_ASSINADA = 16; //AGUARDANDO UPLOAD DA APÓLICE ASSINADA

    const ID_MPME_TIPO_ARQUIVO_COMP_BOLETO = 9;

    const ID_MPME_STATUS_PROPOSTA_ANALISTA = 2;

    const ID_MPME_STATUS_CG_ANALISTA = 13; //AGUARDANDO UPLOAD DO CG ASSINADO

    const ID_MPME_STATUS_APOLICE_ANALISTA = 11; //AGUARDANDO UPLOAD DA APÓLICE ASSINADA

    const ID_MPME_STATUS_EXCLUIDA = 17;

    public function index(Request $request, MpmePropostaRepository $propostaRepository, MpmeAprovacaoValorAlcada $mpmeAprovacaoValorAlcada, MpmePropostaAprovacaoRepository $propostaAprovacaoRepository, MpmeFormulaPremio $mpmeFormulaPremio, MpmeStatusProposta $mpmeStatusProposta)
    {
        $operacao = MpmeImportadoresRepository::getOperacao($request->id_oper);

        $where = [
            'id_usuario' => Auth::user()->ID_USUARIO,
            'id_oper' => $request->id_oper,
            'cod_unico_operacao' => $request->cod_unico_operacao,
            'id_mpme_proposta' => $request->id_mpme_proposta,
            'id_mpme_status_proposta' => $request->id_mpme_status_proposta,
            'not_id_mpme_status_proposta' => [$this::ID_MPME_STATUS_EXCLUIDA],
            'total_paginacao' => $request->total_paginacao,
        ];

        $mpmePropostaRepository = new mpmePropostaRepository();
        $rs_proposta = $mpmePropostaRepository->filtrarPropostas($where);

        //$rs_proposta            = $propostaRepository->getProposta($request->id_oper)->whereNotIn('ID_MPME_STATUS_PROPOSTA', [17]);
        $rs_valores = $mpmeAprovacaoValorAlcada->getValorAprovadoPorAlcada($request->id_oper);
        $vl_aprovado_operacao = $mpmeAprovacaoValorAlcada->getValorAprovado($request->id_oper);
        $valor_prostas = $propostaAprovacaoRepository->getSumProposta($request->id_oper);
        $saldo = formatar_valor_sem_moeda($vl_aprovado_operacao - $valor_prostas);
        $token = str_random(30);
        $rs_status_proposta = $mpmeStatusProposta->where('IN_VISUALIZA_CLIENTE', '=', 'S')->get();

        $compact_args = array(
            'request' => $request,
            'token' => $token,
            'class' => $this,
            'rs_proposta' => $rs_proposta,
            'vl_aprovado_operacao' => $vl_aprovado_operacao,
            'saldo' => $saldo,
            'operacao' => $operacao,
            'rs_status_proposta' => $rs_status_proposta,
            'disabled_operacao' => false,
        );

        return view('proposta.index_proposta', $compact_args);
    }

    public function nova(Request $request, User $user, MpmeAprovacaoValorAlcada $mpmeAprovacaoValorAlcada, MpmePropostaAprovacaoRepository $propostaAprovacaoRepository, TbSetores $setores)
    {
        $operacao = MpmeImportadoresRepository::getOperacao($request->id_oper);

        $rs_modalidade_financiamento = $user->RetornaModalidadeFinancimento(Auth::user()->ID_USUARIO);

        if ($operacao->ID_MODALIDADE != 2) {
            $rs_modalidade_financiamento = $rs_modalidade_financiamento->where('ID_MODALIDADE', '=', $operacao->ID_MODALIDADE);
        }

        $vl_aprovado_operacao = $mpmeAprovacaoValorAlcada->getValorAprovado($request->id_oper);
        $valor_prostas = $propostaAprovacaoRepository->getSumProposta($request->id_oper);
        $saldo = formatar_valor_sem_moeda($vl_aprovado_operacao - $valor_prostas);

        // regra de validade de proposta
        $is_validade = dataMaiorQueOutra(Carbon::now(), $operacao->DT_VALIDADE_OPERACAO);

        $setoresOperacao = $operacao->setoresOperacao->pluck('ID_SETOR')->toArray();

        $setores = $setores->whereIn('ID_SETOR', $setoresOperacao)->get();

        $mpmeRestricaoRepository = new MpmeRestricaoRepository();

        $lista_restricoes = $mpmeRestricaoRepository->getRestricoesAbgfSetores($request)->pluck('ID_SETOR')->toArray();

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rs_modalidade_financiamento' => $rs_modalidade_financiamento,
            'vl_aprovado_operacao' => $vl_aprovado_operacao,
            'saldo' => $saldo,
            'is_validade' => $is_validade,
            'setores' => $setores,
            'lista_restricoes' => $lista_restricoes,
            'operacao' => $operacao,
        );

        return view('proposta.nova_proposta', $compact_args);
    }

    public function salvar(Request $request, MpmePropostaAprovacaoRepository $propostaAprovacaoRepository)
    {

        $ID_MPME_PROPOSTA = $propostaAprovacaoRepository->salvarProcessoProposta($request);

        $dt_embarque = Carbon::createFromFormat('d/m/Y', $request->dt_embarque)->toDateTimeString();  // Data do embarque
        $dt_max_embarque = Carbon::now()->addDays(14)->toDateTimeString(); // Data maxima do embarque
        $dt_max_embarque_formatada = Carbon::now()->addDays(14)->format('d/m/Y'); // Data maxima do embarque


        if ($dt_embarque > $dt_max_embarque) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'url' => 'proposta/' . $request->id_oper,
                'id_oper' => $request->id_oper,
                'id_mpme_proposta' => $ID_MPME_PROPOSTA,
                'msg' => 'A data de embarque não pode ser maior que ' . $dt_max_embarque_formatada,
            ));
        }


        if (!$ID_MPME_PROPOSTA) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'url',
                'url' => 'proposta/' . $request->id_oper,
                'id_oper' => $request->id_oper,
                'id_mpme_proposta' => $ID_MPME_PROPOSTA,
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº ',
            ));
        }

        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'url',
            'url' => 'proposta/' . $request->id_oper,
            'id_oper' => $request->id_oper,
            'id_mpme_proposta' => $ID_MPME_PROPOSTA,
            'msg' => 'A proposta foi inserida com sucesso',
        ));
    }

    public function listar_propostas_aprovacao(Request $request, MpmePropostaRepository $propostaRepository, MpmeAprovacaoValorAlcada $mpmeAprovacaoValorAlcada, MpmePropostaAprovacaoRepository $propostaAprovacaoRepository, MpmeArquivoRepository $mpmeArquivoRepository)
    {
        if (Auth::user()->isSuperAdmin()) {
            $id_status = null;
        } elseif (Auth::user()->can('LISTAR_PROPOSTA_APROCACAO')) {
            if (in_array(Auth::user()->ID_PERFIL, [1, 2, 3, 7])) { // VERIFICA SE O PERFIL É ANALISTA, GERENTE, SUPER CASO SEJA LIMITA A VISUALIZAÇÃO AOS STATUS ABAIXO

                if ($request->ID_MPME_STATUS_PROPOSTA) {
                    $id_status = [$request->ID_MPME_STATUS_PROPOSTA];
                } else {

                    if ($request->ID_MPME_PROPOSTA == "" && $request->cod_unico_operacao == "" && $request->ID_MPME_STATUS_PROPOSTA == "") {
                        $id_status = [
                            2, // AGUARDANDO APROVAÇÃO ANALISTA
                            7, // RECUSADA
                            8, // AGUARDANDO BOLETO DO PRÊMIO
                            10, // AGUARDANDO GERAÇÃO DA APÓLICE
                            12, // AGUARDANDO GERAÇÃO DO CG
                        ];
                    } else {
                        $id_status = null;
                    }
                }
            } else {
                $id_status = [
                    retornaStatusPerfilProposta(Auth::user()->ID_PERFIL),
                    7,
                ];
            }
        }

        $where = [
            'id_oper' => $request->ID_OPER,
            'cod_unico_operacao' => $request->cod_unico_operacao,
            'id_mpme_proposta' => $request->ID_MPME_PROPOSTA,
            'id_mpme_status_proposta' => $request->ID_MPME_STATUS_PROPOSTA,
            'not_id_mpme_status_proposta' => [$this::ID_MPME_STATUS_EXCLUIDA],
            'total_paginacao' => $request->total_paginacao,
        ];

        $rs_proposta = $propostaRepository->getPropostasPorAlcada($id_status, $where);
        $status_proposta = MpmeStatusProposta::all();
        $token = str_random(30);
        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rs_proposta' => $rs_proposta,
            'token' => $token,
            'status_proposta' => $status_proposta,
        );

        return view('proposta.listar_proposta_aprovacao', $compact_args);
    }

    public function filtrarPropostas(Request $request, MpmePropostaRepository $propostaRepository, MpmeAprovacaoValorAlcada $mpmeAprovacaoValorAlcada, MpmePropostaAprovacaoRepository $propostaAprovacaoRepository, MpmeArquivoRepository $mpmeArquivoRepository)
    {
        if (Auth::user()->isSuperAdmin() || Auth::user()->can('LISTAR_PROPOSTA_APROCACAO')) {
            $id_status = null;
        } else {
            if (in_array(Auth::user()->ID_PERFIL, [1, 3])) { // VERIFICA SE O PERFIL É ANALISTA, CASO SEJA LIMITA A VISUALIZAÇÃO AOS STATUS ABAIXO
                $id_status = [
                    2, // AGUARDANDO APROVAÇÃO ANALISTA
                    7, // RECUSADA
                    8, // AGUARDANDO BOLETO DO PRÊMIO
                    10, // AGUARDANDO GERAÇÃO DA APÓLICE
                    12, // AGUARDANDO GERAÇÃO DO CG
                ];
            } else {
                $id_status = [
                    retornaStatusPerfilProposta(Auth::user()->ID_PERFIL),
                    7,
                ];
            }
        }

        $where = [
            'ID_OPER' => $request->ID_OPER,
            'ID_MPME_PROPOSTA' => $request->ID_MPME_PROPOSTA,
            'ID_MPME_STATUS_PROPOSTA' => $request->ID_MPME_STATUS_PROPOSTA,
            'total_paginacao' => $request->total_paginacao,
            'dias_restantes' => $request->dias_restantes,
        ];

        $rs_proposta = $propostaRepository->filtrarPropostaAbgf($where);
        $status_proposta = MpmeStatusProposta::all();

        $token = str_random(30);
        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rs_proposta' => $rs_proposta,
            'token' => $token,
            'status_proposta' => $status_proposta,
        );

        return view('proposta.listar_proposta_aprovacao', $compact_args);
    }

    public function historico_proposta(Request $request, MpmePropostaAprovacaoRepository $mpmePropostaAprovacaoRepository)
    {
        $rsHistoricoAprovacao = $mpmePropostaAprovacaoRepository->getAprovacao($request->id_oper, $request->id_mpme_proposta);

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rsHistoricoAprovacao' => $rsHistoricoAprovacao,
        );

        return view('proposta.historico_aprovacao', $compact_args);
    }

    public function inserir_boleto_premio(Request $request)
    {
        DB::beginTransaction();

        try {
            // operacao com arquivo
            if ($request->session()->has('datapasta_' . $request->token)) {
                $arquivos = $request->session()->get('datapasta_' . $request->token);

                $novo_arquivo = new ArquivoController();
                $destino = $arquivos['id_oper'] . '/boleto/' . $request->id_mpme_proposta . '/';
                $proposta = MpmeProposta::find($request->id_mpme_proposta);
                $proposta->ID_MPME_STATUS_PROPOSTA = $this::ID_MPME_TIPO_ARQUIVO_COMP_BOLETO;

                $historico_proposta = new MpmeHistProposta();
                $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
                $historico_proposta->ID_MPME_STATUS_PROPOSTA = $this::ID_MPME_TIPO_ARQUIVO_COMP_BOLETO; // aguardando comprovante
                $historico_proposta->DT_CADASTRO = Carbon::now();
                $historico_proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                $historico_proposta->DS_OBSERVACAO = 'Boleto enviado, aguardando upload do comprovante';

                if ($novo_arquivo->insere_arquivo($arquivos, $destino) && $proposta->save() && $historico_proposta->save()) {

                    //criar nova notificacao - analisar operacao
                    $notificacao = new MpmeNotificacaoUsuarioRepository();
                    $notificacao->registrar_notificacao([
                        'id_mpme_tipo_notificacao' => 5,
                        'id_oper' => $arquivos['id_oper'],
                        'id_mpme_proposta' => $request->id_mpme_proposta,
                    ]);

                    DB::commit();
                    return response()->json(array(
                        'status' => 'sucesso',
                        'recarrega' => 'true',
                        'msg' => 'Upload realizado com sucesso.',
                    ));
                } else {
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                    ));
                }
            } else {
                throw new Exception('A sessão de arquivos não foi localizada.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => $e->getMessage(),
            ));
        }
    }

    public function inserir_cg(Request $request)
    {
        DB::beginTransaction();

        try {
            // operacao com arquivo
            if ($request->session()->has('datapasta_' . $request->token)) {
                $arquivos = $request->session()->get('datapasta_' . $request->token);

                $novo_arquivo = new ArquivoController();
                $destino = '/cg/' . $arquivos['id_oper'];
                $proposta = MpmeProposta::find($request->id_mpme_proposta);
                $proposta->ID_MPME_STATUS_PROPOSTA = $this::ID_MPME_STATUS_CG_ANALISTA; // aguardando upload do cg assinado

                $historico_proposta = new MpmeHistProposta();
                $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
                $historico_proposta->ID_MPME_STATUS_PROPOSTA = $this::ID_MPME_STATUS_CG_ANALISTA; // aguardando upload do cg assinado
                $historico_proposta->DT_CADASTRO = Carbon::now();
                $historico_proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                $historico_proposta->DS_OBSERVACAO = 'CG gerada e enviada, aguardando upload da CG assinada';

                if ($novo_arquivo->insere_arquivo($arquivos, $destino) && $proposta->save() && $historico_proposta->save()) {

                    //criar nova notificacao
                    $notificacao = new MpmeNotificacaoUsuarioRepository();
                    $notificacao->registrar_notificacao([
                        'id_mpme_tipo_notificacao' => 9,
                        'id_oper' => $arquivos['id_oper'],
                        'id_mpme_proposta' => $request->id_mpme_proposta,
                    ]);

                    //marcar como lida a visualizacao
                    $notificacaoMarcarComoLida = new MpmeNotificacaoUsuarioRepository();
                    $dados = (object) [
                        'id_mpme_tipo_notificacao' => 7,
                        'id_oper' => $arquivos['id_oper'],
                        'id_mpme_proposta' => $request->id_mpme_proposta,
                    ];

                    $notificacaoMarcarComoLida->visualizarNotificacao($dados);

                    DB::commit();
                    return response()->json(array(
                        'status' => 'sucesso',
                        'recarrega' => 'true',
                        'msg' => 'Upload realizado com sucesso.',
                    ));
                } else {
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                    ));
                }
            } else {
                throw new Exception('A sessão de arquivos não foi localizada.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => $e->getMessage(),
            ));
        }
    }

    public function inserir_cg_assinado(Request $request)
    {
        DB::beginTransaction();

        try {
            // operacao com arquivo
            if ($request->session()->has('datapasta_' . $request->token)) {
                $arquivos = $request->session()->get('datapasta_' . $request->token);

                $novo_arquivo = new ArquivoController();
                $destino = '/cg/' . $arquivos['id_oper'];

                $proposta = MpmeProposta::find($request->id_mpme_proposta);

                $proposta->ID_MPME_STATUS_PROPOSTA = 14; // Operação aprovada, pois ja foi assinado

                $historico_proposta = new MpmeHistProposta();
                $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
                $historico_proposta->ID_MPME_STATUS_PROPOSTA = 14; // Operação concretizada, pois ja foi assinado
                $historico_proposta->DT_CADASTRO = Carbon::now();
                $historico_proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                $historico_proposta->DS_OBSERVACAO = 'Certificado assinado, operação concretizada';

                if ($novo_arquivo->insere_arquivo($arquivos, $destino) && $proposta->save() && $historico_proposta->save()) {

                    DB::commit();
                    return response()->json(array(
                        'status' => 'sucesso',
                        'recarrega' => 'true',
                        'msg' => 'Upload realizado com sucesso.',
                    ));
                } else {
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                    ));
                }
            } else {
                throw new Exception('A sessão de arquivos não foi localizada.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => $e->getMessage(),
            ));
        }
    }

    public function inserir_apolice(Request $request)
    {

        $arquivo = $request->arquivo_apolice;
        $nome_arquivo = strtolower(remove_caracteres($arquivo->getClientOriginalName()));

        $arquivos = [
            'id_mpme_tipo_arquivo' => 15,
            'id_flex' => $request->id_mpme_proposta,
            'id_oper' => $request->id_oper,
            'no_arquivo' => $nome_arquivo,
            'arquivo' => $arquivo,
        ];

        $destino = $arquivos['id_oper'] . '/' . $request->id_mpme_proposta . '/apolice/';

        $proposta = MpmeProposta::find($request->id_mpme_proposta);
        $proposta->ID_MPME_STATUS_PROPOSTA = $this::ID_MPME_STATUS_APOLICE_ANALISTA; // aguardando upload do cg assinado
        $proposta->NU_APOLICE = $request->nu_apolice;

        if (!$proposta->save()) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => 'Erro ao atualizar proposta',
            ));
        }

        $historico_proposta = new MpmeHistProposta();
        $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
        $historico_proposta->ID_MPME_STATUS_PROPOSTA = $this::ID_MPME_STATUS_APOLICE_ANALISTA; // aguardando upload do cg assinado
        $historico_proposta->DT_CADASTRO = Carbon::now();
        $historico_proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
        $historico_proposta->DS_OBSERVACAO = 'Apolice gerada e enviada, aguardando upload da Apolice assinada';

        if (!$historico_proposta->save()) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => 'Erro ao lancar historico',
            ));
        }

        if (isset($request->arquivo_apolice)) {
            // Define o valor default para a variável que contém o nome da imagem
            $nameFile = null;

            // Recupera a extensão do arquivo
            $extension = $arquivo->extension();


            $arquivos['no_local_arquivo'] = $destino;
            $arquivos['no_extensao'] = $extension;

            $dadosArquivo = $arquivos;


            $uploadArquivos = new ArquivoController();
            $uploadArquivos->insere_arquivo_sem_sessao($dadosArquivo, $destino, $arquivo);

            /*  // Faz o upload:
            $upload = $arquivo->storeAs('public/' . $destino, $nome_arquivo);
            
            if (!$upload) {
                DB::rollback();
                return response()->json(array(
                    'status' => 'erro',
                    'recarrega' => 'true',
                    'msg' => 'Erro no arquivo',
                ));
            }*/


            //marcar como lida a visualizacao
            $notificacaoMarcarComoLida = new MpmeNotificacaoUsuarioRepository();
            $dados = (object) ['id_mpme_tipo_notificacao' => 6, 'id_oper' => $arquivos['id_oper'], 'id_mpme_proposta' => $request->id_mpme_proposta];
            $notificacaoMarcarComoLida->visualizarNotificacao($dados);

            //criar nova notificacao
            $notificacao = new MpmeNotificacaoUsuarioRepository();
            $notificacao->registrar_notificacao([
                'id_mpme_tipo_notificacao' => 8,
                'id_oper' => $arquivos['id_oper'],
                'id_mpme_proposta' => $request->id_mpme_proposta,
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
                'msg' => 'Erro no arquivo',
            ));
        }
    }

    public function inserir_apolice_assinada(Request $request)
    {
        DB::beginTransaction();

        try {
            // operacao com arquivo
            if ($request->session()->has('datapasta_' . $request->token)) {
                $arquivos = $request->session()->get('datapasta_' . $request->token);

                $novo_arquivo = new ArquivoController();
                $destino = $arquivos['id_oper'] . '/boleto/' . $request->id_mpme_proposta . '/apolice/assinada/';

                $proposta = MpmeProposta::find($request->id_mpme_proposta);

                $id_modalidade = retornaModalidade($arquivos['id_oper']);

                if ($id_modalidade == 1 || $id_modalidade == 2) {
                    $proposta->ID_MPME_STATUS_PROPOSTA = 18; // Aguardando Desembolso
                } else {
                    $proposta->ID_MPME_STATUS_PROPOSTA = 14; // Operação aprovada, pois ja foi assinado
                }


                $proposta->DT_ASSINATURA_APOLICE = Carbon::now();

                if (!$proposta->save()) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao atualizar proposta',
                    ));
                }

                $historico_proposta = new MpmeHistProposta();
                $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
                $historico_proposta->ID_MPME_STATUS_PROPOSTA = 14; // Operação concretizada, pois ja foi assinado
                $historico_proposta->DT_CADASTRO = Carbon::now();
                $historico_proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                $historico_proposta->DS_OBSERVACAO = 'Apolice assinada, operação concretizada';

                if (!$historico_proposta->save()) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao lancar historico',
                    ));
                }

                if ($novo_arquivo->insere_arquivo($arquivos, $destino)) {
                    DB::commit();

                    //criar nova notificacao
                    $notificacao = new MpmeNotificacaoUsuarioRepository();
                    $notificacao->registrar_notificacao([
                        'id_mpme_tipo_notificacao' => 10,
                        'id_oper' => $arquivos['id_oper'],
                        'id_mpme_proposta' => $request->id_mpme_proposta,
                    ]);

                    return response()->json(array(
                        'status' => 'sucesso',
                        'recarrega' => 'true',
                        'msg' => 'Upload realizado com sucesso.',
                    ));
                } else {
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                    ));
                }
            } else {
                throw new Exception('A sessão de arquivos não foi localizada.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => $e->getMessage(),
            ));
        }
    }

    public function inserir_comprovante_boleto_premio(Request $request)
    {
        DB::beginTransaction();
        try {
            // operacao com arquivo
            if ($request->session()->has('datapasta_' . $request->token)) {
                $arquivos = $request->session()->get('datapasta_' . $request->token);

                $novo_arquivo = new ArquivoController();
                $destino = $arquivos['id_oper'] . '/boleto/comprovante/' . $request->id_mpme_proposta . '/';

                $proposta = MpmeProposta::find($request->id_mpme_proposta);
                $proposta->ID_MPME_STATUS_PROPOSTA = (retornaTipoFundoProposta($arquivos['id_oper']) == 1) ? 10/* Status aguardando geração da apolice */ : 12; /* Status aguardando geração do cg*/ //$this::ID_MPME_STATUS_PROPOSTA_ANALISTA;

                if (!$proposta->save()) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                    ));
                }

                $historico_proposta = new MpmeHistProposta();
                $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
                $historico_proposta->ID_MPME_STATUS_PROPOSTA = $proposta->ID_MPME_STATUS_PROPOSTA;
                $historico_proposta->DT_CADASTRO = Carbon::now();
                $historico_proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                $historico_proposta->DS_OBSERVACAO = 'Comprovante enviado, ';

                if (!$historico_proposta->save()) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao salvar histórico',
                    ));
                }

                $novo_arquivo = $novo_arquivo->insere_arquivo($arquivos, $destino);

                if (!$novo_arquivo) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro ao realizar Upload. Tente novamente mais tarde',
                    ));
                }

                //criar notificacao COSUP
                $notificacao_cosup = new MpmeNotificacaoUsuarioRepository();
                $notificacao_cosup->registrar_notificacao([
                    'id_mpme_tipo_notificacao' => 11,
                    'id_mpme_proposta' => $request->id_mpme_proposta,
                    'id_oper' => $arquivos['id_oper'],
                    'id_mpme_arquivo' => $novo_arquivo->ID_MPME_ARQUIVO,
                ]);

                //criar nova notificacao - analisar operacao
                $notificacao = new MpmeNotificacaoUsuarioRepository();

                $id_mpme_tipo_notificacao = (retornaTipoFundoProposta($arquivos['id_oper']) == 1) ? 6/* Status aguardando geração da apolice */ : 7; /* Status aguardando geração do cg*/ //$this::ID_MPME_STATUS_PROPOSTA_ANALISTA;,

                $notificacao->registrar_notificacao([
                    'id_mpme_tipo_notificacao' => $id_mpme_tipo_notificacao,
                    'id_mpme_proposta' => $request->id_mpme_proposta,
                    'id_oper' => $arquivos['id_oper'],

                ]);

                DB::commit();
                return response()->json(array(
                    'status' => 'sucesso',
                    'recarrega' => 'true',
                    'msg' => 'Upload realizado com sucesso.',
                ));
            } else {
                throw new Exception('A sessão de arquivos não foi localizada.');
            }
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'true',
                'msg' => $e->getMessage(),
            ));
        }
    }

    public function dados_questionario(Request $request, Cotacao $cotacao, MpmeAlcadaRepository $mpmeAlcadaRepository, ImportadoresModel $importadoresModel, MpmeRestricaoRepository $mpmeRestricaoRepository, MpmePergunta $pergunta, MpmeQuestionario $questionario)
    {

        $dadosFundoGarantia = MpmeFundoGarantiaRepository::getMpmeFundoGarantia();

        $where = [
            'ID_OPER' => $request->id_oper,
        ];

        $operacao = $importadoresModel->getQuestionarioOperacao($where)->first();

        $exportador = User::find($operacao->ID_USUARIO);

        $importador = ImportadoresModel::where('ID_OPER', $request->id_oper)->with('UltimaAlcadaMovimentacao', 'creditScoreImportador', 'CreditScoreExportador')->first();

        $crontroleAlcadas = $mpmeAlcadaRepository->chkAlcadaValor($request->id_oper);

        $dadosFundoGarantia = MpmeFundoGarantiaRepository::getMpmeFundoGarantia();

        $rs_pergunta = $pergunta->getPergunta('O');

        $rs_questionario = $questionario->where('ID_OPER', $request->id_oper)
            ->get()
            ->pluck('ID_MPME_PERGUNTA_RESPOSTA')
            ->toArray();

        $creditScoreImportador = $importadoresModel->MpmeCreditScore($request->id_oper);
        $creditScoreExportador = $importadoresModel->MpmeCreditScoreExportador($request->id_oper);

        $arquivos = MpmeArquivo::where('ID_USUARIO_CAD', $operacao->ID_USUARIO)->whereIn('ID_MPME_TIPO_ARQUIVO', [20, 21])->get();

        $cotacao = $cotacao->where('MOEDA_ID', '=', $operacao->ID_MOEDA)->orderBy('DATA', 'desc')->first(['TAXA_VENDA'])->TAXA_VENDA;

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'operacao' => $operacao,
            'exportador' => $exportador,
            'importador' => $importador,
            'creditScoreImportador' => $creditScoreImportador,
            'creditScoreExportador' => $creditScoreExportador,
            'crontroleAlcadas' => $crontroleAlcadas,
            'dadosFundoGarantia' => $dadosFundoGarantia,
            'rs_pergunta' => $rs_pergunta,
            'rs_questionario' => $rs_questionario,
            'dadosFundoGarantia' => $dadosFundoGarantia,
            'arquivos' => $arquivos,
            'cotacao' => $cotacao,
        );

        return view('abgf.exportador.limite.dados_quastionario_visualizacao', $compact_args);
    }

    public function excluir(Request $request, MpmePropostaRepository $mpmePropostaRepository)
    {
        DB::beginTransaction();

        if (!$mpmePropostaRepository->excluirProposta($request)) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Erro ao realizar Exclusão da proposta.. Tente novamente mais tarde',
            ));
        }

        $historico_proposta = new MpmeHistProposta();
        $historico_proposta->ID_MPME_PROPOSTA = $request->id_mpme_proposta;
        $historico_proposta->ID_MPME_STATUS_PROPOSTA = $request->id_mpme_status_proposta;
        $historico_proposta->DT_CADASTRO = Carbon::now();
        $historico_proposta->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
        $historico_proposta->DS_OBSERVACAO = strtoupper($request->ds_motivo);

        if (!$historico_proposta->save()) {
            DB::rollback();
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Erro ao realizar Exclusão da proposta. Tente novamente mais tarde',
            ));
        }

        DB::commit();
        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
            'msg' => 'Registro excluído com sucesso.',
        ));
    }

    public function enviar(Request $request, MpmePropostaRepository $mpmePropostaRepository)
    {
        if (!$mpmePropostaRepository->enviarProposta($request)) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Erro ao realizar Enviar da proposta. Tente novamente mais tarde',
            ));
        }

        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
            'msg' => 'Registro enviado com sucesso.',
        ));
    }

    public function aprovar_proposta(Request $request, MpmePropostaRepository $mpmePropostaRepository)
    {

        if (!$mpmePropostaRepository->aprovarProposta($request)) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Erro ao realizar Enviar da proposta. Tente novamente mais tarde',
            ));
        }

        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
            'msg' => 'Registro aprovado com sucesso.',
        ));
    }

    public function dados_proposta(Request $request, MpmePropostaRepository $propostaRepository)
    {

        $proposta = $propostaRepository->getDadosProposta($request->id_oper, $request->id_mpme_proposta);
        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'proposta' => $proposta,

        );

        return view('proposta.dados_proposta', $compact_args);
    }

    public function listar_propostas_usuario(Request $request, MpmePropostaRepository $mpmePropostaRepository, MpmeStatusProposta $mpmeStatusProposta)
    {

        $where = [
            'id_usuario' => Auth::user()->ID_USUARIO,
            'id_oper' => $request->id_oper,
            'cod_unico_operacao' => $request->cod_unico_operacao,
            'id_mpme_proposta' => $request->id_mpme_proposta,
            'id_mpme_status_proposta' => $request->id_mpme_status_proposta,
            'not_id_mpme_status_proposta' => [$this::ID_MPME_STATUS_EXCLUIDA],
            'total_paginacao' => $request->total_paginacao,
        ];

        $rs_proposta = $mpmePropostaRepository->filtrarPropostas($where);

        $rs_status_proposta = $mpmeStatusProposta->where('IN_VISUALIZA_CLIENTE', '=', 'S')->get();
        $token = str_random(30);

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rs_proposta' => $rs_proposta,
            'rs_status_proposta' => $rs_status_proposta,
            'token' => $token,
            'disabled_operacao' => true,
        );

        return view('proposta.listar_proposta_usuario', $compact_args);
    }

    public function listar_propostas_embarque(Request $request, MpmeEmbarqueRepository $mpmebarquerepository)
    {

        $embarque = new MpmeStatus();
        $status_embarque = $embarque->where("NO_ORIGEM_STATUS", '=', 'EMBARQUE')->get();

        $where = [
            'id_mpme_status' => ($request->ID_MPME_STATUS == 0) ?  [8] : [$request->ID_MPME_STATUS],
            'nu_proposta' => $request->NU_PROPOSTA
        ];

        $listarEmbarque = $mpmebarquerepository->listarEmbarqueAnalista($where);

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'listarEmbarque' => $listarEmbarque,
            'status_embarque' => $status_embarque,
        );

        return view('proposta.listar_proposta_embarque', $compact_args);
    }

    public function embarque_proposta(Request $request)
    {
        $mpmeProposta = mpmeProposta::find($request->ID_PROPOSTA);
        return view('proposta.embarque_proposta', compact('mpmeProposta'));
    }

    public function salvar_embarque_proposta(Request $request)
    {


        if ($request->confirm_embarque == "") {
            redirect('proposta/lista-proposta-usuario')->with('error', 'Você precisa confirmar se efetuou o embarque ou não');
        }

        $mpmeProposta = mpmeProposta::find($request->id_proposta);

        if ($mpmeProposta->IN_EMBARQUE_CONFIRMADO != "") {
            redirect('proposta/lista-proposta-usuario')->with('error', 'O embarque ja foi definido anteriormente!');
        }



        $data1 = Carbon::createFromDate($mpmeProposta->DT_ENVIO)->startOfDay();
        $dt_embarque = Carbon::createFromFormat('d/m/Y', $request->dt_embarque);


        $data_limite = $data1->diffInDays($dt_embarque);

        if ($data_limite >= 15) {
            return redirect('proposta/lista-proposta-usuario')->with('error', 'Data superior a 15 dias do cadastro da proposta, se a exportação/embarque ocorrer em data superior a 15 dias, é necessário cancelar essa proposta e cadastrar uma Nova Proposta de Seguro!');
        }

        $mpmeProposta->IN_EMBARQUE_CONFIRMADO = $request->confirm_embarque;
        $mpmeProposta->DT_EMBARQUE = $dt_embarque;
        if ($mpmeProposta->save()) {
            if ($request->confirm_embarque == 'S') {

                return redirect('proposta/lista-proposta-usuario')->with('success', 'Embarque confirmado, obrigado!');
            }

            if ($request->confirm_embarque == 'N') {

                $proposta = mpmeProposta::find($request->id_proposta);

                $notificacao                = new MpmeNotificacaoUsuarioRepository();
                $notificacao->registrar_notificacao([
                    'id_mpme_tipo_notificacao' => 17,
                    'id_oper' => $proposta->ID_OPER,
                    'id_mpme_proposta' => $proposta->ID_MPME_PROPOSTA,

                ]);

                $mpmePropostaRepository = new MpmePropostaRepository();

                $request  =  [
                    'in_aceite'                 => 'N',
                    'id_mpme_proposta'          => $proposta->ID_MPME_PROPOSTA,
                    'ds_motivo'                 => 'CANCELADO AUTOMATICAMENTE PELO SISTEMA',
                    'id_mpme_status_proposta'   => 6,
                    'id_mpme_alcada'            => 1,
                    'nu_proposta'               => null,
                    'DT_CANCELAMENTO'           => Carbon::now(),
                    'id_oper'                   => $proposta->ID_OPER,
                ];

                $request = (object) $request;

                $mpmePropostaRepository->cancelarProposta($request);

                return redirect('proposta/lista-proposta-usuario')->with('info', 'Proposta Cancelada!');
            }
        } else {
            return back()->with('error', 'Ocorreu um erro ao confirmar o embarque, entre em contato com a ABGF!');
        }
    }
}
