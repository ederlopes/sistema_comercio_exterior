<?php

namespace App\Repositories;

use App\MpmeFinanceiroExportador;
use App\Repositories\MpmeArquivoRepository;
use App\MpmeRecomendacaoExp;
use App\MpmeTempoValidacao;
use App\MpmeValidaExportador;
use App\Notificacoes;
use App\MpmeCliente;
use App\QuadroSocietario;
use App\User;
use App\MpmeClienteExportador;
use Auth;
use DB;
use App\MpmeTipoCliente;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Validator;
use App\ClienteExportadorModalidadeFinanciamento;
use App\Log;

class UsersRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(User::class);
    }

    public static function salvaAlteracoes($request)
    {

        $mensagemPersonalizada = [
            'NM_USUARIO.required' => 'O campo <b>Nome</b> do exportador não '
                . 'pode ser vazio',
            'NU_CNPJ.required' => 'O campo <b>CNPJ</b> do exportador  não '
                . 'pode ser vazio',
            'DE_ENDER.required' => 'O campo <b>Endereço</b> do exportador não '
                . 'pode ser vazio',
            'DE_CEP.required' => 'O campo <b>CEP</b> do exportador não pode '
                . 'ser vazio',
            'DE_CARGO.required' => 'O campo <b>Cargo</b> do exportador não pode'
                . ' ser vazio',
            'DE_TEL.required' => 'O campo <b>Telefone</b> do exportador não '
                . 'pode ser vazio',
            'DE_EMAIL.required' => 'O campo <b>E-mail</b> do exportador não '
                . 'pode ser vazio',
            'VL_BRUTO_ANUAL.required' => 'O campo <b>Faturamento</b> do '
                . 'exportador não pode ser vazio',
            'VL_EXP_BRUTA.required' => 'O campo <b>Faturamento civil '
                . 'anterior</b> do exportador não pode ser vazio',
            'DE_CIDADE.required' => 'O campo <b>Cidade</b> do exportador não '
                . 'pode ser vazio',
            'CD_UF.required' => 'O campo <b>Estado</b> do exportador não pode '
                . 'ser vazio',
            'ID_MOEDA.required' => 'O campo <b>Moeda</b> do exportador não '
                . 'pode ser vazio',

        ];

        $validacao = Validator::make($request->all(), [
            'NM_USUARIO' => 'required',
            'NU_CNPJ' => 'required',
            'DE_ENDER' => 'required',
            'DE_CEP' => 'required',
            'DE_CARGO' => 'required',
            'DE_TEL' => 'required',
            'DE_EMAIL' => 'required',
            'VL_BRUTO_ANUAL' => 'required',
            'DE_CIDADE' => 'required',
            'CD_UF' => 'required',
            'ID_MOEDA' => 'required',
        ], $mensagemPersonalizada);

        if ($validacao->passes()) {

            DB::beginTransaction();
            $usuario = User::find($request->ID_USUARIO);
            $usuario->NM_USUARIO = $request->NM_USUARIO;
            $usuario->NU_CNPJ = $request->NU_CNPJ;
            $usuario->DE_ENDER = $request->DE_ENDER;
            $usuario->DE_CEP = $request->DE_CEP;
            $usuario->DE_CARGO = $request->DE_CARGO;
            $usuario->DE_TEL = $request->DE_TEL;
            $usuario->DE_EMAIL = $request->DE_EMAIL;
            $usuario->VL_BRUTO_ANUAL = converte_float($request->VL_BRUTO_ANUAL);
            $usuario->VL_EXP_BRUTA = converte_float($request->VL_EXP_BRUTA);
            $usuario->NU_INSCR_EST = $request->NU_INSCR_EST;
            $usuario->NU_INSCR_MUNICIPAL = $request->NU_INSCR_MUNICIPAL;
            $usuario->DE_CIDADE = remove_caracteres($request->DE_CIDADE);
            $usuario->CD_UF = $request->CD_UF;
            $usuario->ID_MOEDA = $request->ID_MOEDA;
            $usuario->DE_FAX = $request->DE_FAX;
            $usuario->ID_TEMPO = $request->ID_TEMPO;

            if (!$usuario->save()) {
                DB::rollback(); // se der erro faz o roolback
                return false;
            }
            $mpme_financeiro_exportador = MpmeFinanceiroExportador::where('ID_MPME_CLIENTE_EXPORTADORES', $usuario->exportador->ID_MPME_CLIENTE_EXPORTADORES)->first();
            $mpme_financeiro_exportador->ID_MPME_CLIENTE_EXPORTADORES = $usuario->exportador->ID_MPME_CLIENTE_EXPORTADORES;
            $mpme_financeiro_exportador->VL_EXP_BRUTO_ANUAL = converte_float($request->VL_BRUTO_ANUAL);
            $mpme_financeiro_exportador->VL_FAT_BRUTO_ANUAL = converte_float($request->VL_EXP_BRUTA); // Faturamento Bruto Anual


            if (!$mpme_financeiro_exportador->save()) {
                DB::rollback(); // se der erro faz o roolback
                return false;
            }

            $log = new Log();
            $log->ID_USUARIO = Auth::User()->ID_USUARIO;
            $log->DT_LOG = Carbon::now();
            $log->CD_FUNCAO = 'ATUALIZA USU. : ' . $request->ID_USUARIO;
            $log->TABELA = 'USUARIO';
            $log->DE_SQL = 'ATUALIZA USUARIOS COM OS CAMPOS ' . remove_caracteres(implode('#', $request->all()));
            $log->DATA_CADASTRO = Carbon::now();
            if (!$log->save()) {
                DB::rollback(); // se der erro faz o roolback
                return false;
            }


            DB::commit(); // se todos os inserts no foreach der certo, comita
            return true;
        } else {
            return back()->withErrors($validacao);
        }
    }

    public static function salvaListaRarefas($request)
    {

        // Inicia a Transação para previnir error no foreach
        DB::beginTransaction();
        //Deleta todas as checks para depois adicionar as novas
        MpmeValidaExportador::where('ID_USUARIO', $request->ID_USUARIO)->delete();
        if (isset($request->id_check)) {
            foreach ($request->id_check as $numero) {
                $lista = new MpmeValidaExportador();
                $lista->ID_USUARIO = $request->ID_USUARIO;
                $lista->NU_CHECK = $numero;
                $lista->VL_CHECK = 1;
                if (!$lista->save()) {
                    DB::rollback(); // se der erro faz o roolback
                    return false;
                }
            }
        }
        DB::commit(); // se todos os inserts no foreach der certo, comita
        return true;
    }

    public static function fichaCadastralLiberacaoCadastro($request)
    {
        // Inicia a Transação para previnir error no foreach

        DB::beginTransaction();

        if ($request->ds_recomendacao == 1) { //LIBERA CADASTRO
            $liberaUsuario = User::find($request->ID_USUARIO);
            $liberaUsuario->FL_ATIVO = '1';
            $liberaUsuario->DT_ATZX = \Carbon\Carbon::now();
            if (!$liberaUsuario->save()) {
                DB::rollback(); // se der erro faz o roolback
                return back()->withErrors('Erro ao Aprovar usuário');
            }
        } else if ($request->ds_recomendacao == 2) {
            $liberaUsuario = User::find($request->ID_USUARIO);
            $liberaUsuario->FL_ATIVO = 0;
            if (!$liberaUsuario->save()) {
                DB::rollback(); // se der erro faz o roolback
                return back()->withErrors('Erro ao recusar usuário');
            }
        }

        $notificacao = Notificacoes::find($request->ID_NOTIFICACAO);
        $notificacao->IC_ATIVO = 0;

        if (!$notificacao->save()) {

            DB::rollback(); // se der erro faz o roolback
            return back()->withErrors('Erro ao atualizar notificacao');
        }

        $mpmeFinanceiro = MpmeFinanceiroExportador::where(
            'ID_MPME_CLIENTE_EXPORTADORES',
            '=',
            $request->ID_MPME_CLIENTE_EXPORTADORES
        )
            ->update(['IN_APROVADO' => 'S']);

        if (!$mpmeFinanceiro) {
            DB::rollback(); // se der erro faz o roolback
            return back()->withErrors('Erro ao atualizar financeiro exp.');
        }

        $tempoValidacao = new MpmeTempoValidacao();
        $tempoValidacao->ID_USUARIO_FK = $request->ID_USUARIO;
        $tempoValidacao->ID_TIPO_VALIDACAO_FK = 3;
        $tempoValidacao->DT_VALIDACAO = \Carbon\Carbon::createFromFormat('d/m/Y', $request->data_recomendacao)->format('Y-m-d');
        if (!$tempoValidacao->save()) {
            DB::rollback(); // se der erro faz o roolback
            return back()->withErrors('Erro ao criar tempo validacao');
        }

        $recomendacaoExp = new MpmeRecomendacaoExp();
        $recomendacaoExp->ID_USUARIO_FK = $request->ID_USUARIO;
        $recomendacaoExp->FL_MOMENTO = 'ANA';
        $recomendacaoExp->DS_RECOMENDACAO_EXP = $request->ds_parecer;
        $recomendacaoExp->DT_RECOMENDACAO_EXP = \Carbon\Carbon::createFromFormat('d/m/Y', $request->data_recomendacao)->format('Y-m-d');

        if (!$recomendacaoExp->save()) {
            DB::rollback(); // se der erro faz o roolback
            return back()->withErrors('Erro ao criar recomendação exp.');
        }

        if ($request->ds_recomendacao == 1) {
            $liberaUsuario->notify(new \App\Notifications\AprovaUsuario($liberaUsuario));
        } else {
            $liberaUsuario->notify(new \App\Notifications\ReprovaUsuario());
        }

        DB::commit(); // se todos os inserts no foreach der certo, comita
        return true;
    }

    public function alterarSenha($request)
    {
        if ($request->no_nova_senha == "" || $request->no_nova_senha == "" || $request->no_repetir_senha == "") {
            return [
                'sucesso' => false,
                'msg' => 'Parametros invalidos',
            ];
        }

        $usuario = User::find(Auth::user()->ID_USUARIO);

        if ($usuario->CD_SENHA == Encripta(strtoupper(substr($request->no_senha_atual, 0, 10)))) {
            $usuario->CD_SENHA = Encripta(strtoupper(substr($request->no_nova_senha, 0, 10)));

            if (!$usuario->save()) {
                return [
                    'sucesso' => false,
                    'msg' => 'Erro ao atualizar dados!',
                ];
            }
            return [
                'sucesso' => true,
                'msg' => 'Dados alterados com suceso!',
            ];
        } else {
            return [
                'sucesso' => false,
                'msg' => 'Senha atual errada!',
            ];
        }
    }

    public static function CadastraUsuarioEFazUpload($request, $modalidades)
    {

        DB::beginTransaction();

        $ano_fiscal = (int) $request->calendario_fiscal;
        $data_fechamento = Carbon::create($ano_fiscal + 2, 03, 31);

        $novoUsuario = new User();
        $novoUsuario->CD_LOGIN = strtoupper($request->LOGIN);
        $novoUsuario->NM_USUARIO = $request->NM_USUARIO;
        $novoUsuario->NO_FANTASIA = $request->NM_FANTASIA;
        $novoUsuario->TIPO_VALIDACAO = 'c';
        $novoUsuario->CD_SENHA = Encripta($request->DS_SENHA);
        $novoUsuario->NU_CNPJ = $request->NU_CNPJ;
        $novoUsuario->NU_INSCR_EST = $request->NU_INSCR_ESTADUAL;
        $novoUsuario->NU_INSCR_MUNICIPAL = $request->NU_INSCR_MUNICIPAL;
        $novoUsuario->DE_EMAIL = strtolower($request->DE_EMAIL);
        $novoUsuario->DE_ENDER = $request->DE_ENDER;
        $novoUsuario->ID_PAIS = '28';
        $novoUsuario->DE_CIDADE = $request->DE_CIDADE;
        $novoUsuario->CD_UF = $request->CD_UF;
        $novoUsuario->DE_CEP = $request->DE_CEP;
        $novoUsuario->NM_CONTATO = $request->NM_CONTATO;
        $novoUsuario->DE_CARGO = $request->DE_CARGO;
        $novoUsuario->NU_DDD = preg_replace('/\(|\)/', '', substr($request->DE_TEL, 0, 4));
        $novoUsuario->DE_TEL = substr($request->DE_TEL, 5, 9);
        $novoUsuario->DE_DDD_FAX = preg_replace('/\(|\)/', '', substr($request->DE_FAX, 0, 4));
        $novoUsuario->DE_FAX = substr($request->DE_FAX, 5, 9);
        $novoUsuario->ID_SETOR = 1;
        $novoUsuario->ID_TEMPO = $request->ID_TEMPO;
        $novoUsuario->FL_ATIVO = 0;
        $novoUsuario->IC_MPME = 1;
        $novoUsuario->ID_MODALIDADE = $modalidades[0];
        $novoUsuario->ID_MOEDA = $request->ID_MOEDA;
        $novoUsuario->TP_USUARIO = 'C';
        $novoUsuario->VL_ANUAL_EXP = '500.000,00';
        $novoUsuario->VL_BRUTO_ANUAL = converte_float($request->VL_BRUTO_ANUAL); // Valor da exportação do ano civil anterior
        $novoUsuario->VL_EXP_BRUTA = converte_float($request->FATURAMENTO_BRUTO_ANUAL); // Faturamento Bruto Anual
        $novoUsuario->PROEX_PRE = null;
        $novoUsuario->PROEX_POS = null;
        $novoUsuario->DT_FUNDACAO = ''; //$xDT_FUNDACAO;
        $novoUsuario->DT_INCLUSAO = Carbon::now();
        $novoUsuario->NOME_QUADRO = $request->NOME_QUADRO[0];
        $novoUsuario->CPF_CNPJ_QUADRO = $request->CPF_QUADRO[0];
        $novoUsuario->PARTICIPACAO_QUADRO = $request->PARTICIPACAO_QUADRO[0];
        $novoUsuario->CAPITAL_QUADRO = $request->CAPITAL_QUADRO;
        $novoUsuario->NU_FUNCIONARIO_EMPRESA = $request->NU_FUNCIONARIO_EMPRESA;
        $novoUsuario->IN_NOVA_OPERACAO = 'S';
        $novoUsuario->DT_VALIDADE_CADASTRO = $data_fechamento;
        $novoUsuario->ID_PERFIL = 9;
        $novoUsuario->DT_ATZX = null;

        if ($novoUsuario->save()) {

            // faz upload DRE
            $request->request->add(['no_arquivo' => $request->file('dre')]); //add request
            $request->request->add(['id_mpme_tipo_arquivo' => 20]); //add request
            $request->request->add(['pasta' => '/abgf/exportador/dre/' . $novoUsuario->ID_USUARIO]); //add request
            $request->request->add(['ID_USUARIO' => $novoUsuario->ID_USUARIO]); //add request

            if (!MpmeArquivoRepository::UploadEInsere($request)) {
                DB::rollback();
                return false;
            }

            // faz upload Comprovante de exportador
            $request->request->add(['no_arquivo' => $request->file('comprovante_exportacao')]); //add request
            $request->request->add(['id_mpme_tipo_arquivo' => 21]); //add request
            $request->request->add(['pasta' => '/abgf/exportador/comprovante_exportacao/' . $novoUsuario->ID_USUARIO]); //add request
            $request->request->add(['ID_USUARIO' => $novoUsuario->ID_USUARIO]); //add request
            if (!MpmeArquivoRepository::UploadEInsere($request)) {
                DB::rollback();
                return false;
            }

            // Cadastra Cliente Mpme
            $mpmeCliente = new MpmeCliente();
            $mpmeCliente->NOME_CLIENTE = $request->NM_USUARIO;
            $mpmeCliente->NOME_FANTASIA = $request->NM_FANTASIA;
            $mpmeCliente->ID_PAIS = 28;
            $mpmeCliente->NOME_CIDADE = $request->DE_CIDADE;
            $mpmeCliente->NUMERO_DOC_IDENTIFICACAO = limpaCPF_CNPJ($request->NU_CNPJ);
            $mpmeCliente->NUMERO_CNPJ = limpaCPF_CNPJ($request->NU_CNPJ);

            if ($mpmeCliente->save()) {

                // Retorna cliente exportador -- Nova Estrutura
                $clienteExportador = new MpmeClienteExportador();
                $clienteExportador->ID_MPME_CLIENTE = $mpmeCliente->ID_MPME_CLIENTE;
                $clienteExportador->ID_USUARIO = $novoUsuario->ID_USUARIO;

                if (!$clienteExportador->save()) {
                    DB::rollback();
                    return false;
                }

                $idClienteExportador = $clienteExportador->ID_MPME_CLIENTE_EXPORTADORES; // Pega o ID do CLiente Exportador Inserido.

                //cadastra tipo cliente
                $mpmeTipoCliente = new MpmeTipoCliente();
                $mpmeTipoCliente->ID_MPME_CLIENTE = $mpmeCliente->ID_MPME_CLIENTE;
                $mpmeTipoCliente->ID_TIPO_CLIENTE = 1;
                $mpmeTipoCliente->DATA_CADASTRO = Carbon::now();

                if (!$mpmeTipoCliente->save()) {
                    DB::rollback();
                    return false;
                }

                // Cria os socios com base na lista de socios relacionados na tela de cadastro
                foreach ($request->NOME_QUADRO as $key => $nomeQuadro) {

                    $insereQuadro = QuadroSocietario::create(
                        [
                            'ID_USUARIO' => $novoUsuario->ID_USUARIO,
                            'NOME_SOCIO' => $nomeQuadro,
                            'NU_CPF_CNPJ' => limpaCPF_CNPJ($request->CPF_QUADRO[$key]),
                            'PC_PARTICIPACAO' => converte_float($request->PARTICIPACAO_QUADRO[$key]),
                        ]
                    );

                    if (!$insereQuadro) {
                        DB::rollback();
                        return false;
                        break;
                    }
                }

                //Insere cliente exportador modalidade financiamento 

                foreach ($request->id_modalidade as $modalid) {
                    $modalidade = explode("#", $modalid);
                    $clienteExportadorFinanciamento = new ClienteExportadorModalidadeFinanciamento();
                    $clienteExportadorFinanciamento->ID_MODALIDADE_FINANCIAMENTO = $modalidade[2];
                    $clienteExportadorFinanciamento->ID_MPME_CLIENTE_EXPORTADORES = $clienteExportador->ID_MPME_CLIENTE_EXPORTADORES;
                    $clienteExportadorFinanciamento->ID_USUARIO_CAD = $novoUsuario->ID_USUARIO;
                    $clienteExportadorFinanciamento->DT_INI_PERIODO = Carbon::now();
                    $clienteExportadorFinanciamento->IN_REGISTRO_ATIVO = 'N';

                    if (!$clienteExportadorFinanciamento->save()) {
                        DB::rollback();
                        return false;
                        break;
                    }
                }
            } else { // Caso ocorra algum erro no inser do mpmeCliente
                DB::rollback();
                return false;
            }

            DB::commit();
            return true;
        } else { // Caso ocorra algum erro no inser do Usuario
            DB::rollback();
            return false;
        }
    }

    public static function CadastraOuRetornaFinanciador($request)
    {

        DB::beginTransaction();

        $usuarioexiste = null;

        $agenciaFinanciador = User::where('ID_USUARIO', '=', $request->ID_FINANCIADOR_POS)->first();

        //  pesquisa se existe o login da agencia no sistema, login agencia eh ID_USUARIO.Nome Agencia
        if (isset($agenciaFinanciador) && $agenciaFinanciador != null) {
            $loginAgencia = $agenciaFinanciador->ID_USUARIO . $request->NO_AGENCIA_POS;

            $usuarioexiste = User::where('CD_LOGIN', '=', $loginAgencia)->first();
        } else {
            /**
             * Caso nao retorne nenhum resultado na agencia financiador eh porque o impostador do financiador
             * não esta em nosso banco de dados, portando eh um erro
             */

            return false;
        }

        // se existir usuario com login gerado acima, ele seta o ID do usuario a variavel;
        if ($usuarioexiste != null) {

            return $usuarioexiste->ID_USUARIO;
        } else { // Caso contrario cria o usuario da agencia.

            $cadastraAgencia = new user();
            $cadastraAgencia->CD_LOGIN = $loginAgencia;
            $cadastraAgencia->NM_USUARIO = $agenciaFinanciador->NM_USUARIO;
            $cadastraAgencia->CD_SENHA = $agenciaFinanciador->CD_SENHA;
            $cadastraAgencia->TP_USUARIO = 'B';
            $cadastraAgencia->FL_ATIVO = 1;
            $cadastraAgencia->DE_ENDER = $request->AG_ENDERECO_POS;
            $cadastraAgencia->DE_CIDADE = $request->AG_CIDADE_POS;
            $cadastraAgencia->CD_UF = $request->AG_ESTADO_POS;
            $cadastraAgencia->ID_PAIS = 1;
            $cadastraAgencia->DE_CEP = '-';
            $cadastraAgencia->NM_CONTATO = $request->AG_CONTATO_POS;
            $cadastraAgencia->DE_TEL = preg_replace('/\(|\)/', '', substr($request->AG_TEL_POS, 0, 4));
            $cadastraAgencia->DE_EMAIL = strtolower($request->AG_EMAIL_POS);
            $cadastraAgencia->DE_HMPAGE = '';
            $cadastraAgencia->DE_TITULO = '';
            $cadastraAgencia->DE_DEPTO = '';
            $cadastraAgencia->DE_AREA = '';
            $cadastraAgencia->DE_SEGMENTO = '';
            $cadastraAgencia->DE_DETALHE = '';
            $cadastraAgencia->CD_LINGUA = 'P';
            $cadastraAgencia->DE_COOKIE = '';
            $cadastraAgencia->NU_CNPJ = $request->AG_CNPJ_POS;
            $cadastraAgencia->ID_SETOR = 1;
            $cadastraAgencia->DE_CARGO = $request->AG_CARGO_POS;
            $cadastraAgencia->NU_DDD = preg_replace('/\(|\)/', '', substr($request->AG_FAX_POS, 0, 4));
            $cadastraAgencia->ID_TEMPO = 2;
            $cadastraAgencia->IC_MPME = 1;
            $cadastraAgencia->ID_MODALIDADE = 0;
            $cadastraAgencia->ID_MOEDA = 1;
            $cadastraAgencia->DE_DDD_FAX = preg_replace('/\(|\)/', '', substr($request->AG_FAX_POS, 0, 4));
            $cadastraAgencia->DE_FAX = substr($request->AG_FAX_POS, 5, 9);
            $cadastraAgencia->VL_BRUTO_ANUAL = null;
            $cadastraAgencia->VL_ANUAL_EXP = null;
            $cadastraAgencia->VL_LIMITE_MPME = null;
            $cadastraAgencia->VL_EXP_BRUTA = null;
            $cadastraAgencia->VL_ESTIMADO = null;
            $cadastraAgencia->DE_EMAIL_CONTATO = null;
            $cadastraAgencia->NU_INSCR_EST = null;
            $cadastraAgencia->NU_INSCR_MUNICIPAL = null;
            $cadastraAgencia->PROEX_PRE = null;
            $cadastraAgencia->PROEX_POS = null;
            $cadastraAgencia->IC_VALIDADO_BANCO = 1;
            $cadastraAgencia->ID_PERFIL = 6;
            $cadastraAgencia->DT_RECUSA = null;
            $cadastraAgencia->DS_MOTIVO_RECUSA = null;
            $cadastraAgencia->DT_FUNDACAO = null;
            $cadastraAgencia->DT_INCLUSAO = Carbon::now();
            $cadastraAgencia->DS_DIVERGENCIA = null;
            $cadastraAgencia->ID_ANALISTA_FK = null;
            $cadastraAgencia->NOME_QUADRO = null;
            $cadastraAgencia->CPF_CNPJ_QUADRO = null;
            $cadastraAgencia->PARTICIPACAO_QUADRO = null;
            $cadastraAgencia->CAPITAL_QUADRO = null;
            $cadastraAgencia->IC_MOMENTO = 'ANA';
            $cadastraAgencia->ST_USUARIO = null;
            $cadastraAgencia->DT_ATZX = null;
            $cadastraAgencia->NO_FANTASIA = null;

            if ($cadastraAgencia->save()) {

                $colunasPerfil = array(
                    array('ID_USUARIO_FK' => $cadastraAgencia->ID_USUARIO, 'NU_CHECK' => 28),
                    array('ID_USUARIO_FK' => $cadastraAgencia->ID_USUARIO, 'NU_CHECK' => 29),
                    array('ID_USUARIO_FK' => $cadastraAgencia->ID_USUARIO, 'NU_CHECK' => 30),
                    array('ID_USUARIO_FK' => $cadastraAgencia->ID_USUARIO, 'NU_CHECK' => 40),
                );

                DB::table('TB_USUARIO_PERFIL')->insert($colunasPerfil);

                DB::commit();

                return $cadastraAgencia->ID_USUARIO; // retorna
            } else {
                DB::rollback();
                return false;
            }
        }
    }
}
