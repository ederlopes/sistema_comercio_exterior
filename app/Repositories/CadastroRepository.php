<?php

namespace App\Repositories;

use App\Agenciabb;
use App\ClienteExportadorModalidadeFinanciamento;
use App\ClienteExportadorRegimeTributario;
use App\Financpos;
use App\Financpre;
use App\Grupos;
use App\GrupoUso;
use App\MpmeCliente;
use App\MpmeClienteExportador;
use App\MpmeFinanceiroExportador;
use App\MpmeQuestionario;
use App\MpmeTipoCliente;
use App\Mpme_Responsav_Assinatura_Cgc;
use App\Notificacoes;
use App\Parametros;
use App\QuadroSocietario;
use App\Repositories\MpmeArquivoRepository;
use App\TempoValidacao;
use App\User;
use App\UsuarioCGCModel;
use Auth;
use DB;
use Illuminate\Support\Carbon;

class CadastroRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(User::class);
    }

    public static function CadastraUsuarioEFazUpload($request, $modalidades, $apenasRecursosProprio)
    {

        DB::beginTransaction();

        $ano_fiscal = (int) $request->calendario_fiscal;
        $data_fechamento = Carbon::create($ano_fiscal + 2, 03, 31);

        $novoUsuario = new User();
        $novoUsuario->CD_LOGIN = mb_strtoupper($request->LOGIN, 'UTF-8');
        $novoUsuario->NM_USUARIO = mb_strtoupper($request->NM_USUARIO, 'UTF-8');
        $novoUsuario->NO_FANTASIA = mb_strtoupper($request->NM_FANTASIA, 'UTF-8');
        $novoUsuario->TIPO_VALIDACAO = 'c';
        $novoUsuario->CD_SENHA = Encripta(mb_strtoupper($request->DS_SENHA, 'UTF-8'));
        $novoUsuario->NU_CNPJ = $request->NU_CNPJ;
        $novoUsuario->NU_INSCR_EST = mb_strtoupper($request->NU_INSCR_ESTADUAL, 'UTF-8');
        $novoUsuario->NU_INSCR_MUNICIPAL = mb_strtoupper($request->NU_INSCR_MUNICIPAL, 'UTF-8');
        $novoUsuario->DE_EMAIL = strtolower(mb_strtoupper($request->DE_EMAIL, 'UTF-8'));
        $novoUsuario->DE_ENDER = mb_strtoupper($request->DE_ENDER, 'UTF-8');
        $novoUsuario->ID_PAIS = '28';
        $novoUsuario->DE_CIDADE = mb_strtoupper($request->DE_CIDADE, 'UTF-8');
        $novoUsuario->CD_UF = $request->CD_UF;
        $novoUsuario->DE_CEP = $request->DE_CEP;
        $novoUsuario->NM_CONTATO = mb_strtoupper($request->NM_CONTATO, 'UTF-8');
        $novoUsuario->DE_CARGO = mb_strtoupper($request->DE_CARGO, 'UTF-8');
        $novoUsuario->NU_DDD = preg_replace('/\(|\)/', '', substr($request->DE_TEL, 0, 4));
        $novoUsuario->DE_TEL = substr($request->DE_TEL, 5, 9);
        $novoUsuario->DE_DDD_FAX = preg_replace('/\(|\)/', '', substr($request->DE_FAX, 0, 4));
        $novoUsuario->DE_FAX = substr($request->DE_FAX, 5, 9);
        $novoUsuario->ID_SETOR = 1;
        $novoUsuario->ID_TEMPO = $request->ID_TEMPO;
        $novoUsuario->FL_ATIVO = 0;
        $novoUsuario->IC_MPME = 1;
        $novoUsuario->ID_MODALIDADE = $modalidades[0];
        $novoUsuario->ID_MOEDA = 1;
        $novoUsuario->TP_USUARIO = 'C';
        $novoUsuario->VL_ANUAL_EXP = 500000.0;
        $novoUsuario->VL_BRUTO_ANUAL = converte_float($request->VL_BRUTO_ANUAL); // Valor da exportação do ano civil anterior
        $novoUsuario->VL_EXP_BRUTA = converte_float($request->FATURAMENTO_BRUTO_ANUAL); // Faturamento Bruto Anual
        $novoUsuario->PROEX_PRE = null;
        $novoUsuario->PROEX_POS = null;
        $novoUsuario->DT_FUNDACAO = ''; //$xDT_FUNDACAO;
        $novoUsuario->DT_INCLUSAO = Carbon::now();
        $novoUsuario->NOME_QUADRO = mb_strtoupper($request->NOME_QUADRO[0], 'UTF-8');
        $novoUsuario->CPF_CNPJ_QUADRO = $request->CPF_QUADRO[0];
        $novoUsuario->PARTICIPACAO_QUADRO = $request->PARTICIPACAO_QUADRO[0];
        $novoUsuario->CAPITAL_QUADRO = $request->CAPITAL_QUADRO;
        $novoUsuario->NU_FUNCIONARIO_EMPRESA = str_replace(',', '', $request->NU_FUNCIONARIO_EMPRESA);
        $novoUsuario->IN_NOVA_OPERACAO = 'S';
        $novoUsuario->DT_VALIDADE_CADASTRO = $data_fechamento;
        $novoUsuario->ID_PERFIL = 9;
        $novoUsuario->DT_ATZX = null;
        if ($novoUsuario->save()) {

            //Caso seja recursos proprios atribue o id do usuario ao exportador
            if ($apenasRecursosProprio == 1) {
                $ID_FINANCIADOR_POS = $novoUsuario->ID_USUARIO;
            } else {
                $ID_FINANCIADOR_POS = $request->ID_FINANCIADOR_POS;
            }

            // Associa o usuário ao grupo para liberar permissões
            $grupos = Parametros::where('CD_PARAM', '=', 'GRUPOCLIENTE')->first(['VL_PARAM'])->VL_PARAM;
            $id_grupo = Grupos::where('NM_GRUPO', '=', $grupos)->first(['ID_GRUPO'])->ID_GRUPO;

            if ($id_grupo > 0) {

                $associarUsuarioAoGrupo = GrupoUso::create(
                    [
                        'ID_GRUPO' => $id_grupo,
                        'ID_USUARIO' => $novoUsuario->ID_USUARIO,
                    ]
                );

                if (!$associarUsuarioAoGrupo) {
                    DB::rollback();
                    // return response()->json([
                    //     'status' => 'erro',
                    //     'message' => 'Ocorreu um erro ao associar o usuario ao grupo de usuario, tente novamente mais tarde!',
                    //     'class_mensagem' => 'error',
                    //     'header' => 'Erro!',
                    // ]);

                    return false;
                }
            } else {
                DB::rollback();
                // return response()->json([
                //     'status' => 'erro',
                //     'message' => 'Ocorreu um erro ao atribuir as permissoes no usuario, tente novamente mais tarde!',
                //     'class_mensagem' => 'error',
                //     'header' => 'Erro!',
                // ]);
                return false;
            }

            // Grava responsavel pela assinatura do cgc
            $responsavelAssinatura = new Mpme_Responsav_Assinatura_Cgc();
            $responsavelAssinatura->ID_USUARIO_RESPONSAVEL = $novoUsuario->ID_USUARIO;
            $responsavelAssinatura->DATA = Carbon::now();
            $responsavelAssinatura->NM_RESPONSAVEL = $request->NM_RESPONSAVEL;
            $responsavelAssinatura->CPF_RESPONSAVEL = $request->CPF_RESPONSAVEL;
            $responsavelAssinatura->EMAIL_RESPONSAVEL = strtolower($request->EMAIL_RESPONSAVEL);

            if (!$responsavelAssinatura->save()) {
                DB::rollback();
                // return response()->json([
                //     'status' => 'erro',
                //     'message' => 'Ocorreu um erro ao salvar o responsavel pela assinatura do cgc, tente novamente mais tarde!',
                //     'class_mensagem' => 'error',
                //     'header' => 'Erro!',
                // ]);
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
                    // return response()->json([
                    //     'status' => 'erro',
                    //     'message' => 'Ocorreu um erro ao salvar o quadro societário, tente novamente mais tarde!',
                    //     'class_mensagem' => 'error',
                    //     'header' => 'Erro!',
                    // ]);
                    return false;

                    break;
                }
            }

            // Salva tempo validacao
            $tempoValidacao = TempoValidacao::create(
                [
                    'ID_USUARIO_FK' => $novoUsuario->ID_USUARIO,
                    'ID_TIPO_VALIDACAO_FK' => 1,
                    'DT_VALIDACAO' => Carbon::now(),
                ]
            );

            if (!$tempoValidacao) {
                DB::rollback();
                // return response()->json([
                //     'status' => 'erro',
                //     'message' => 'Ocorreu um erro ao salvar o tempo validação, tente novamente mais tarde!',
                //     'class_mensagem' => 'error',
                //     'header' => 'Erro!',
                // ]);
                return false;
            }

            // faz upload DRE
            $request->request->add(['no_arquivo' => $request->file('dre')]); //add request
            $request->request->add(['id_mpme_tipo_arquivo' => 20]); //add request
            $request->request->add(['pasta' => 'abgf/exportador/dre/' . $novoUsuario->ID_USUARIO]); //add request
            $request->request->add(['ID_USUARIO' => $novoUsuario->ID_USUARIO]); //add request

            if (!MpmeArquivoRepository::UploadEInsere($request)) {
                DB::rollback();
                // return response()->json([
                //     'status' => 'erro',
                //     'message' => 'Ocorreu um erro ao salvar os dados do arquivo dre, tente novamente mais tarde!',
                //     'class_mensagem' => 'error',
                //     'header' => 'Erro!',
                // ]);
                return false;
            }

            // faz upload Comprovante de exportador
            $request->request->add(['no_arquivo' => $request->file('comprovante_exportacao')]); //add request
            $request->request->add(['id_mpme_tipo_arquivo' => 21]); //add request
            $request->request->add(['pasta' => 'abgf/exportador/comprovante_exportacao/' . $novoUsuario->ID_USUARIO]); //add request
            $request->request->add(['ID_USUARIO' => $novoUsuario->ID_USUARIO]); //add request
            if (!MpmeArquivoRepository::UploadEInsere($request)) {
                DB::rollback();
                // return response()->json([
                //     'status' => 'erro',
                //     'message' => 'Ocorreu um erro ao salvar os dados do arquivo de controle de exportação, tente novamente mais tarde!',
                //     'class_mensagem' => 'error',
                //     'header' => 'Erro!',
                // ]);
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
                    // return response()->json([
                    //     'status' => 'erro',
                    //     'message' => 'Ocorreu um erro ao salvar o cliente exportador, tente novamente mais tarde!',
                    //     'class_mensagem' => 'error',
                    //     'header' => 'Erro!',
                    // ]);
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
                    // return response()->json([
                    //     'status' => 'erro',
                    //     'message' => 'Ocorreu um erro ao salvar o tipo cliente, tente novamente mais tarde!',
                    //     'class_mensagem' => 'error',
                    //     'header' => 'Erro!',
                    // ]);

                    return false;
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
                        // return response()->json([
                        //     'status' => 'erro',
                        //     'message' => 'Ocorreu um erro ao salvar o cliente exportador modalidade financiamento, tente novamente mais tarde!',
                        //     'class_mensagem' => 'error',
                        //     'header' => 'Erro!',
                        // ]);
                        return false;

                        break;
                    }
                }

                // Cadastra o regime tributário do usuário
                $clienteExportadorRegimeTributario = new ClienteExportadorRegimeTributario();
                $clienteExportadorRegimeTributario->ID_MPME_CLIENTE_EXPORTADORES = $idClienteExportador;
                $clienteExportadorRegimeTributario->ID_REGIME_TRIBUTARIO = ($request->simples_nacional == 1) ? 2 : 1;
                $clienteExportadorRegimeTributario->ID_ENQUADRAMENTO_TRIBUTARIO = ($request->simples_nacional == 1) ? $request->ENQUADRAMENTO_TRIBUTARIO : 1;
                $clienteExportadorRegimeTributario->DT_INI_PERIODO = Carbon::now();
                $clienteExportadorRegimeTributario->ID_USUARIO_CAD = $novoUsuario->ID_USUARIO;
                $clienteExportadorRegimeTributario->save();

                if (!$clienteExportadorRegimeTributario->save()) {
                    DB::rollback();
                    return false;
                }

                //Salva as perguntas
                if (isset($request->pergunta) && $request->pergunta != null) {
                    foreach ($request->pergunta as $pe) {
                        $novo_questionario = new MpmeQuestionario();
                        $novo_questionario->ID_MPME_PERGUNTA_RESPOSTA = $pe['IDRESP'];
                        $novo_questionario->ID_MPME_CLIENTE = $mpmeCliente->ID_MPME_CLIENTE;
                        $novo_questionario->IN_QUESTIONARIO_APLICADO = 'CAD_FORNECEDOR';
                        $novo_questionario->DS_OUTRA_RESPOSTA = ($pe['RESP'] != '') ? $pe['RESP'] : '';
                        $novo_questionario->IN_ATIVO = 'S';
                        $novo_questionario->DATA_CADASTRO = Carbon::now();
                        $novo_questionario->ID_USUARIO = $novoUsuario->ID_USUARIO;

                        if (!$novo_questionario->save()) {
                            DB::rollback();
                            // return response()->json([
                            //     'status' => 'erro',
                            //     'message' => 'Ocorreu um erro ao salvar o questionário, tente novamente mais tarde!',
                            //     'class_mensagem' => 'error',
                            //     'header' => 'Erro!',
                            // ]);
                            return false;
                        }
                    }
                }

                //salva financeiro exportador
                $ano_fiscal = (int) $request->calendario_fiscal;
                $data_fechamento = Carbon::create($ano_fiscal + 2, 03, 31);

                $mpme_financeiro_exportador = new MpmeFinanceiroExportador();
                $mpme_financeiro_exportador->ID_MPME_CLIENTE_EXPORTADORES = $idClienteExportador;
                $mpme_financeiro_exportador->VL_EXP_BRUTO_ANUAL = converte_float($request->VL_BRUTO_ANUAL);
                $mpme_financeiro_exportador->VL_FAT_BRUTO_ANUAL = converte_float($request->FATURAMENTO_BRUTO_ANUAL); // Faturamento Bruto Anual
                $mpme_financeiro_exportador->IN_APROVADO = 'N';
                $mpme_financeiro_exportador->IN_ATIVO = 'S';
                $mpme_financeiro_exportador->DT_ANO_FISCAL = $ano_fiscal;
                $mpme_financeiro_exportador->DT_INI_PERIODO = Carbon::now();

                if (!$mpme_financeiro_exportador->save()) {
                    DB::rollback();
                    // return response()->json([
                    //     'status' => 'erro',
                    //     'message' => 'Ocorreu um erro ao salvar os dados do financeiro exportador, tente novamente mais tarde!',
                    //     'class_mensagem' => 'error',
                    //     'header' => 'Erro!',
                    // ]);
                    return false;
                }
            } else { // Caso ocorra algum erro no inser do mpmeCliente
                DB::rollback();
                // return response()->json([
                //     'status' => 'erro',
                //     'message' => 'Ocorreu um erro ao salvar o cliente mpme, tente novamente mais tarde!',
                //     'class_mensagem' => 'error',
                //     'header' => 'Erro!',
                // ]);
                return false;
            }

            //Se ID modalidade for pre ou pos embarque, id_modalidade 1 = Pre-embarque , id_modalidade 2 = pre+pos
            if ($apenasRecursosProprio != 1 & (in_array(1, $request->id_modalidade) || in_array(2, $request->id_modalidade))) {

                if ($request->ID_AGENCIA_PRE == "NULL") {
                    $agencia = new Agenciabb();
                    $agencia->NU_AGENCI = $request->NU_AG_PRE;
                    $agencia->ID_GECEX_FK = $request->ID_FINANCIADOR_PRE;
                    $agencia->DE_AGENCIA = $request->NO_AGENCIA_PRE;
                    $agencia->NU_CNPJ_INSCR = $request->AG_CNPJ_PRE;
                    $agencia->DE_ENDER = $request->AG_ENDERECO_PRE;
                    $agencia->DE_CIDADE = $request->AG_CIDADE_PRE;
                    $agencia->CD_UF = $request->AG_ESTADO_PRE;
                    $agencia->NU_CEP = '-';
                    $agencia->NU_DDD = preg_replace('/\(|\)/', '', substr($request->AG_TEL_PRE, 0, 4));
                    $agencia->NU_TEL = substr($request->AG_TEL_PRE, 5, 9);
                    $agencia->NU_INSCR_EST = $request->AG_INSCR_PRE;
                    $agencia->NO_CONTATO = $request->AG_CONTATO_PRE;
                    $agencia->NU_FAX = substr($request->AG_FAX_PRE, 5, 9);
                    $agencia->DS_EMAIL = strtolower($request->AG_EMAIL_PRE);
                    $agencia->ID_BANCO_FK = $request->ID_FINANCIADOR_PRE;
                    $agencia->IC_ATIVO = 1;

                    if (!$agencia->save()) {
                        DB::rollback();
                        // return response()->json([
                        //     'status' => 'erro',
                        //     'message' => 'Ocorreu um erro ao salvar a agencia bb do pre-embarque, tente novamente mais tarde!',
                        //     'class_mensagem' => 'error',
                        //     'header' => 'Erro!',
                        // ]);
                        return false;
                    }
                }

                $financiadorPre = new Financpre();
                $financiadorPre->ID_USUARIO = $novoUsuario->ID_USUARIO;
                $financiadorPre->ID_USUARIO_FINANCIADOR_FK = ($request->ID_FINANCIADOR_PRE == 16) ? 1086 : $request->ID_FINANCIADOR_PRE;
                $financiadorPre->ID_BANCO = $request->ID_FINANCIADOR_PRE;
                $financiadorPre->ID_AGENCIA = $request->ID_AGENCIA_PRE;
                $financiadorPre->DOMINIO_EMAIL = '@';
                $financiadorPre->NU_CNPJ = $request->AG_CNPJ_PRE;
                $financiadorPre->NU_INSCRICAO = $request->AG_INSCR_PRE;
                $financiadorPre->AG_CC = $request->AG_CC_PRE;
                $financiadorPre->DS_ENDERECO = $request->AG_ENDERECO_PRE;
                $financiadorPre->NO_CIDADE = $request->AG_CIDADE_PRE;
                $financiadorPre->NO_ESTADO = $request->AG_ESTADO_PRE;
                $financiadorPre->NU_CEP = $request->AG_CEP_PRE;
                $financiadorPre->NO_CONTATO = $request->AG_CONTATO_PRE;
                $financiadorPre->NU_DDD_TEL = preg_replace('/\(|\)/', '', substr($request->AG_TEL_PRE, 0, 4));
                $financiadorPre->NU_TEL = substr($request->AG_TEL_PRE, 5, 9);
                $financiadorPre->NO_CARGO = $request->AG_CARGO_PRE;
                $financiadorPre->NU_DDD_FAX = preg_replace('/\(|\)/', '', substr($request->AG_FAX_PRE, 0, 4));
                $financiadorPre->NU_FAX = substr($request->AG_FAX_PRE, 5, 9);
                $financiadorPre->DS_EMAIL = strtolower($request->AG_EMAIL_PRE);
                $financiadorPre->IC_PROEX = 1;
                $financiadorPre->IC_ATIVO = 0;
                $financiadorPre->IC_PROPRIO_EXPORTADOR = 0;
                $financiadorPre->NU_AG_NOVA = $request->NU_AG_PRE;

                if ($financiadorPre->save()) {

                    // Atribui o usuario e financiador a ao cgc
                    $usuarioCGC = new UsuarioCGCModel();
                    $usuarioCGC->ID_USUARIO_FK = $novoUsuario->ID_USUARIO;
                    $usuarioCGC->ID_FINANCIADOR_FK = $financiadorPre->ID_USUARIO_FINANCIADOR_FK;
                    $usuarioCGC->TP_FINANCIADO = 2;
                    $usuarioCGC->IC_ATIVO = 1;

                    if (!$usuarioCGC->save()) {
                        DB::rollback();
                        // return response()->json([
                        //     'status' => 'erro',
                        //     'message' => 'Ocorreu um erro ao salvar o usuario cgc do pre-embarque, tente novamente mais tarde!',
                        //     'class_mensagem' => 'error',
                        //     'header' => 'Erro!',
                        // ]);
                        return false;
                    }
                } else {
                    DB::rollback();
                    return false;
                }
            } // fecha if $apenasRecursosProprio != 1 ...

            /**
             * Se id da agencia existir no post e vir como null, e a modalidade não for pre-embarque, cadastra uma nova agencia
             */

            if ($apenasRecursosProprio != 1 && (isset($request->ID_AGENCIA_POS) && $request->ID_AGENCIA_POS == "NULL") && !in_array(1, $request->id_modalidade)) {

                $agencia = new Agenciabb();
                $agencia->NU_AGENCIA = $request->NU_AG_POS;
                $agencia->ID_GECEX_FK = $ID_FINANCIADOR_POS;
                $agencia->DE_AGENCIA = $request->NO_AGENCIA_POS;
                $agencia->NU_CNPJ_INSCR = $request->AG_CNPJ_POS;
                $agencia->DE_ENDER = $request->AG_ENDERECO_POS;
                $agencia->DE_CIDADE = $request->AG_CIDADE_POS;
                $agencia->CD_UF = $request->AG_ESTADO_POS;
                $agencia->NU_CEP = $request->AG_CEP_POS;
                $agencia->NU_DDD = preg_replace('/\(|\)/', '', substr($request->AG_TEL_POS, 0, 4));
                $agencia->NU_TEL = substr($request->AG_TEL_POS, 5, 9);
                $agencia->NU_INSCR_EST = $request->AG_INSCR_POS;
                $agencia->NO_CONTATO = $request->AG_CONTATO_POS;
                $agencia->NU_FAX = preg_replace('/\(|\)/', '', substr($request->AG_FAX_POS, 0, 4));
                $agencia->DS_EMAIL = strtolower($request->AG_EMAIL_POS);
                $agencia->ID_BANCO_FK = $ID_FINANCIADOR_POS;
                $agencia->IC_ATIVO = 1;

                if (!$agencia->save()) {
                    DB::rollback();
                    // return response()->json([
                    //     'status' => 'erro',
                    //     'message' => 'Ocorreu um erro ao salvar a agencia bb no pos embarque, tente novamente mais tarde!',
                    //     'class_mensagem' => 'error',
                    //     'header' => 'Erro!',
                    // ]);
                    return false;
                }
            } // fecha if de agenciabb para pos-embarque

            /**
             * Se a modalidade for pre+pos embarque ou apenas Pos embarque
             * Cadastra o financiador do pos embarque
             */

            if ($apenasRecursosProprio == 1) { // Se for 10000 e recurso proprio

                $financiadorPos = new Financpos();
                $financiadorPos->ID_USUARIO = $novoUsuario->ID_USUARIO;
                $financiadorPos->ID_USUARIO_FINANCIADOR_FK = $ID_FINANCIADOR_POS; //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086
                $financiadorPos->ID_BANCO = $ID_FINANCIADOR_POS; //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086
                $financiadorPos->ID_AGENCIA = null;
                $financiadorPos->DOMINIO_EMAIL = '@';
                $financiadorPos->NU_CNPJ = '-';
                $financiadorPos->NU_INSCRICAO = '-';
                $financiadorPos->AG_CC = '-';
                $financiadorPos->DS_ENDERECO = '-';
                $financiadorPos->NO_CIDADE = '-';
                $financiadorPos->NO_ESTADO = '-';
                $financiadorPos->NU_CEP = "-";
                $financiadorPos->NO_CONTATO = '-';
                $financiadorPos->NU_DDD_TEL = '-';
                $financiadorPos->NU_TEL = '-';
                $financiadorPos->NO_CARGO = '-';
                $financiadorPos->NU_DDD_FAX = '-';
                $financiadorPos->NU_FAX = '-';
                $financiadorPos->DS_EMAIL = '-';
                $financiadorPos->IC_PROEX = 1;
                $financiadorPos->IC_ATIVO = 0;
                $financiadorPos->IC_PROPRIO_EXPORTADOR = 0;
                $financiadorPos->NU_AG_NOVA = '-';
                $financiadorPos->ID_NOME_BANCO_POS_FK = $ID_FINANCIADOR_POS; //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086;

                if ($financiadorPos->save()) {

                    // Atribui o usuario e financiador a ao cgc
                    $usuarioCGC = new UsuarioCGCModel();
                    $usuarioCGC->ID_USUARIO_FK = $novoUsuario->ID_USUARIO;
                    $usuarioCGC->ID_FINANCIADOR_FK = ($ID_FINANCIADOR_POS == 16) ? 1086 : $ID_FINANCIADOR_POS; //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086;
                    $usuarioCGC->TP_FINANCIADO = 2;
                    $usuarioCGC->IC_ATIVO = 1;

                    if (!$usuarioCGC->save()) {
                        DB::rollback();
                        // return response()->json([
                        //     'status' => 'erro',
                        //     'message' => 'Ocorreu um erro ao salvar usuario cgc, tente novamente mais tarde!',
                        //     'class_mensagem' => 'error',
                        //     'header' => 'Erro!',
                        // ]);
                        return false;
                    }
                }
            }

            if ($apenasRecursosProprio != 1 && (in_array(2, $request->id_modalidade) || in_array(3, $request->id_modalidade))) {

                $financiadorPos = new Financpos();
                $financiadorPos->ID_USUARIO = $novoUsuario->ID_USUARIO;
                $financiadorPos->ID_USUARIO_FINANCIADOR_FK = ($ID_FINANCIADOR_POS == 16) ? 1086 : $ID_FINANCIADOR_POS; //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086
                $financiadorPos->ID_BANCO = ($ID_FINANCIADOR_POS == 16) ? 1086 : $ID_FINANCIADOR_POS; //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086
                $financiadorPos->ID_AGENCIA = $request->ID_AGENCIA_POS;
                $financiadorPos->DOMINIO_EMAIL = '@';
                $financiadorPos->NU_CNPJ = $request->AG_CNPJ_POS;
                $financiadorPos->NU_INSCRICAO = $request->AG_INSCR_POS;
                $financiadorPos->AG_CC = $request->AG_CC_POS;
                $financiadorPos->DS_ENDERECO = $request->AG_ENDERECO_POS;
                $financiadorPos->NO_CIDADE = $request->AG_CIDADE_POS;
                $financiadorPos->NO_ESTADO = $request->AG_ESTADO_POS;
                $financiadorPos->NU_CEP = "-";
                $financiadorPos->NO_CONTATO = $request->AG_CONTATO_POS;
                $financiadorPos->NU_DDD_TEL = preg_replace('/\(|\)/', '', substr($request->AG_TEL_POS, 0, 4));
                $financiadorPos->NU_TEL = substr($request->AG_TEL_POS, 5, 9);
                $financiadorPos->NO_CARGO = $request->AG_CARGO_POS;
                $financiadorPos->NU_DDD_FAX = preg_replace('/\(|\)/', '', substr($request->AG_FAX_POS, 0, 4));
                $financiadorPos->NU_FAX = substr($request->AG_FAX_POS, 5, 9);
                $financiadorPos->DS_EMAIL = strtolower($request->AG_EMAIL_POS);
                $financiadorPos->IC_PROEX = 1;
                $financiadorPos->IC_ATIVO = 0;
                $financiadorPos->IC_PROPRIO_EXPORTADOR = 0;
                $financiadorPos->NU_AG_NOVA = $request->NU_AG_POS;
                $financiadorPos->ID_NOME_BANCO_POS_FK = ($ID_FINANCIADOR_POS == 16) ? 1086 : $ID_FINANCIADOR_POS; //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086;

                if ($financiadorPos->save()) {

                    // Atribui o usuario e financiador a ao cgc
                    $usuarioCGC = new UsuarioCGCModel();
                    $usuarioCGC->ID_USUARIO_FK = $novoUsuario->ID_USUARIO;
                    $usuarioCGC->ID_FINANCIADOR_FK = ($ID_FINANCIADOR_POS == 16) ? 1086 : $ID_FINANCIADOR_POS; //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086;
                    $usuarioCGC->TP_FINANCIADO = 2;
                    $usuarioCGC->IC_ATIVO = 1;

                    if (!$usuarioCGC->save()) {
                        DB::rollback();
                        // return response()->json([
                        //     'status' => 'erro',
                        //     'message' => 'Ocorreu um erro ao salvar usuario cgc, tente novamente mais tarde!',
                        //     'class_mensagem' => 'error',
                        //     'header' => 'Erro!',
                        // ]);
                        return false;
                    }
                } else {
                    DB::rollback();
                    // return response()->json([
                    //     'status' => 'erro',
                    //     'message' => 'Ocorreu um erro ao salvar o financiador do pos-embarque, tente novamente mais tarde!',
                    //     'class_mensagem' => 'error',
                    //     'header' => 'Erro!',
                    // ]);
                    return false;
                } // Fecha if do financidor pos embarque ou pre+pos

            }

            // Caso não seja recurso proprio notifica o banco ?

            //Cria a notificação
            $insereNotificacao = new Notificacoes();
            $insereNotificacao->ID_STATUS_NOTIFICACAO_FK = 14;
            $insereNotificacao->ID_USUARIO_FK = $novoUsuario->ID_USUARIO;
            $insereNotificacao->DE_NOTIFICACAO = " / " . $request->NM_FANTASIA . " / " . $request->NM_USUARIO;
            $insereNotificacao->DS_LINK = "#";
            $insereNotificacao->IC_ATIVO = 1;

            if ($apenasRecursosProprio == 1) {
                $insereNotificacao->TIPO_VALIDACAO = 'A'; // Se for apenas recursos proprio, envia direto para o analista
            }

            // $notificacaoExp = new Notificacoes();
            // $notificacaoExp->ID_STATUS_NOTIFICACAO_FK = 41;
            // $notificacaoExp->ID_USUARIO_FK = $novoUsuario->ID_USUARIO;
            // $notificacaoExp->DE_NOTIFICACAO = "UM NOVO EXPORTADOR FOI CADASTRADO PELO SITE - " . $request->NM_USUARIO;
            // $notificacaoExp->DS_LINK = "#";
            // $notificacaoExp->IC_ATIVO = 1;

            if (!$insereNotificacao->save()) {
                DB::rollback();
                // return response()->json([
                //     'status' => 'erro',
                //     'message' => 'Ocorreu um erro ao salvar a notificação, tente novamente mais tarde!',
                //     'class_mensagem' => 'error',
                //     'header' => 'Erro!',
                // ]);
                return false;
            }

            // Se todos os inserts ocorrerem bem faz o commit e retorna true;
            DB::commit();
            return true;
        } else { // Caso ocorra algum erro no inser do Usuario
            DB::rollback();

            // return response()->json([
            //     'status' => 'erro',
            //     'message' => 'Ocorreu um erro ao salvar o usuario, tente novamente mais tarde!',
            //     'class_mensagem' => 'error',
            //     'header' => 'Erro!',
            // ]);
            return false;
        }
    }

    public static function AtualizaCadastroExportador($request, $modalidades, $apenasRecursosProprio)
    {
        DB::beginTransaction();

        if ($apenasRecursosProprio != 1 && (in_array(2, $request->id_modalidade) || in_array(3, $request->id_modalidade))) {

            $financiadorPos = Financpos::updateOrCreate(
                ['ID_USUARIO' => Auth::User()->ID_USUARIO],
                [
                    'ID_USUARIO' => Auth::User()->ID_USUARIO,
                    'ID_USUARIO_FINANCIADOR_FK' => ($request->ID_FINANCIADOR == 16) ? 1086 : $request->ID_FINANCIADOR,
                    'ID_BANCO' => ($request->ID_FINANCIADOR == 16) ? 1086 : $request->ID_FINANCIADOR,
                    'ID_AGENCIA' => $request->ID_AGENCIA_POS,
                    'DOMINIO_EMAIL' => '@',
                    'NU_CNPJ' => $request->AG_CNPJ_POS,
                    'NU_INSCRICAO' => $request->AG_INSCR_POS,
                    'AG_CC' => $request->AG_CC_POS,
                    'DS_ENDERECO' => $request->AG_ENDERECO_POS,
                    'NO_CIDADE' => $request->AG_CIDADE_POS,
                    'NO_ESTADO' => $request->AG_ESTADO_POS,
                    'NU_CEP' => $request->AG_CEP_POS,
                    'NO_CONTATO' => $request->AG_CONTATO_POS,
                    'NU_DDD_TEL' => preg_replace('/\(|\)/', '', substr($request->AG_TEL_POS, 0, 4)),
                    'NU_TEL' => substr($request->AG_TEL_POS, 5, 9),
                    'NO_CARGO' => $request->AG_CARGO_POS,
                    'NU_DDD_FAX' => preg_replace('/\(|\)/', '', substr($request->AG_FAX_POS, 0, 4)),
                    'NU_FAX' => substr($request->AG_FAX_POS, 5, 9),
                    'DS_EMAIL' => strtolower($request->AG_EMAIL_POS),
                    'IC_PROEX' => 1,
                    'IC_ATIVO' => 0,
                    'IC_PROPRIO_EXPORTADOR' => 0,
                    'NU_AG_NOVA' => $request->NU_AG_POS,
                    'ID_NOME_BANCO_POS_FK' => ($request->ID_FINANCIADOR == 16) ? 1086 : $request->ID_FINANCIADOR, //Caso seja financiador 16 é banco do brasil, então atribue a gecex 1086;
                ]
            );

            if ($financiadorPos) {

                // Atribui o usuario e financiador a ao cgc
                $usuarioCGC = UsuarioCGCModel::where('ID_USUARIO_FK', Auth::User()->ID_USUARIO)->first();
                $usuarioCGC->ID_USUARIO_FK = Auth::User()->ID_USUARIO;
                $usuarioCGC->ID_FINANCIADOR_FK = $financiadorPos->ID_USUARIO_FINANCIADOR_FK;
                $usuarioCGC->TP_FINANCIADO = 2;
                $usuarioCGC->IC_ATIVO = 1;

                if (!$usuarioCGC->save()) {
                    DB::rollback();
                    return false;
                }
            }
        } else {
            DB::rollback();
            return false;
        }

        if ($apenasRecursosProprio != 1 && (in_array(1, $request->id_modalidade) || in_array(2, $request->id_modalidade))) {

            $financiadorPre = Financpre::updateOrCreate(
                ['ID_USUARIO' => Auth::User()->ID_USUARIO],
                [
                    'ID_USUARIO' => Auth::User()->ID_USUARIO,
                    'ID_USUARIO_FINANCIADOR_FK' => ($request->ID_FINANCIADOR == 16) ? 1086 : $request->ID_FINANCIADOR,
                    'ID_BANCO' => ($request->ID_FINANCIADOR == 16) ? 1086 : $request->ID_FINANCIADOR,
                    'ID_AGENCIA' => $request->NO_AGENCIA_PRE,
                    'DOMINIO_EMAIL' => '@',
                    'NU_CNPJ' => $request->AG_CNPJ_PRE,
                    'NU_INSCRICAO' => $request->AG_INSCR_PRE,
                    'AG_CC' => $request->AG_CC_PRE,
                    'DS_ENDERECO' => $request->AG_ENDERECO_PRE,
                    'NO_CIDADE' => $request->AG_CIDADE_PRE,
                    'NO_ESTADO' => $request->AG_ESTADO_PRE,
                    'NU_CEP' => $request->AG_CEP_PRE,
                    'NO_CONTATO' => $request->AG_CONTATO_PRE,
                    'NU_DDD_TEL' => preg_replace('/\(|\)/', '', substr($request->AG_TEL_PRE, 0, 4)),
                    'NU_TEL' => substr($request->AG_TEL_PRE, 5, 9),
                    'NO_CARGO' => $request->AG_CARGO_PRE,
                    'NU_DDD_FAX' => preg_replace('/\(|\)/', '', substr($request->AG_FAX_PRE, 0, 4)),
                    'NU_FAX' => substr($request->AG_FAX_PRE, 5, 9),
                    'DS_EMAIL' => strtolower($request->AG_EMAIL_PRE),
                    'IC_PROEX' => 1,
                    'IC_ATIVO' => 0,
                    'IC_PROPRIO_EXPORTADOR' => 0,
                    'NU_AG_NOVA' => $request->NU_AG_PRE,
                ]
            );

            if ($financiadorPre) {
                // Atribui o usuario e financiador a ao cgc
                $usuarioCGC = UsuarioCGCModel::where('ID_USUARIO_FK', Auth::User()->ID_USUARIO)->first();
                $usuarioCGC->ID_USUARIO_FK = Auth::User()->ID_USUARIO;
                $usuarioCGC->ID_FINANCIADOR_FK = $financiadorPre->ID_USUARIO_FINANCIADOR_FK;
                $usuarioCGC->TP_FINANCIADO = 2;
                $usuarioCGC->IC_ATIVO = 1;

                if (!$usuarioCGC->save()) {
                    DB::rollback();
                    return false;
                }
            } else {
                DB::rollback();
                return false;
            }
        }

        //Cria a notificação
        $insereNotificacao = Notificacoes::updateOrCreate(['ID_USUARIO_FK' => Auth::User()->ID_USUARIO], [
            'ID_STATUS_NOTIFICACAO_FK' => 14,
            'ID_USUARIO_FK' => Auth::User()->ID_USUARIO,
            'DE_NOTIFICACAO' => "ATUALIZACAO_CADASTRAL",
            'DS_LINK' => "#",
            'IC_ATIVO' => 1,
            'TIPO_VALIDACAO' => 'C',
        ]);

        if (!$insereNotificacao) {
            DB::rollback();
            return false;
        }

        //Insere cliente exportador modalidade financiamento

        $modalidadesUsuario = ClienteExportadorModalidadeFinanciamento::where('ID_USUARIO_CAD', Auth::User()->ID_USUARIO)->pluck('ID_MODALIDADE_FINANCIAMENTO')->toArray();

        if (!$modalidadesUsuario) {
            DB::rollback();
            return false;
        }
        foreach ($request->id_modalidade as $modalid) {
            $modalidade = explode("#", $modalid);
            if (!in_array($modalidade[2], $modalidadesUsuario)) {
                $clienteExportadorFinanciamento = new ClienteExportadorModalidadeFinanciamento();
                $clienteExportadorFinanciamento->ID_MODALIDADE_FINANCIAMENTO = $modalidade[2];
                $clienteExportadorFinanciamento->ID_MPME_CLIENTE_EXPORTADORES = retornaClienteExportadorPeloIdUsuario(Auth::User()->ID_USUARIO);
                $clienteExportadorFinanciamento->ID_USUARIO_CAD = Auth::User()->ID_USUARIO;
                $clienteExportadorFinanciamento->DT_INI_PERIODO = Carbon::now();
                $clienteExportadorFinanciamento->IN_REGISTRO_ATIVO = 'N';

                if (!$clienteExportadorFinanciamento->save()) {
                    DB::rollback();

                    return false;

                    break;
                }
            }
        }

        DB::commit();
        return true;
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
             * Caso nao retorne nenhum resultado na agencia financiador eh porque o importador do financiador
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
            $cadastraAgencia->gecex_idgecex = $request->ID_FINANCIADOR_POS;

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
