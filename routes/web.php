<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

Auth::routes();

//ROTAS SEM NECESSIDADE DE LOGIN
Route::get('/', function () {
    return redirect('/login');
})->middleware('guest');

Route::get('/logout', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout');

//PRECIFICACAO SITE
Route::group(['prefix' => '/precificacao/', 'as' => 'precificacao.'], function () {
    Route::get('/simulacaoPrecificacaoSite', 'PrecificacaoController@nova_simulacao_site')->name('nova_simulacao_site');;
    Route::post('/precificarValorSimulacaoSite', 'PrecificacaoController@precificarValorSimulacaoSite');
});

// Alteração de Senha
Route::post('/resetar_senha', 'AlterarSenhaController@resetarSenha')->name('resetar_senha');
Route::get('resetar-senha/token/{token}', 'AlterarSenhaController@resetar')->name('resetar_senha_token');
Route::post('/resetar-senha', 'AlterarSenhaController@token')->name('resetar.token');
Route::post('resetar', 'AlterarSenhaController@reset');

Route::get('/testeCalculadora', 'PrecificacaoController@testeCalculadora');
Route::get('/logaruid/{ID_USUARIO}', 'UsuarioController@loginById');

Route::get('/buscarusuariopornome/{nomeusuario}', 'CadastroMpme@buscarusuariopornome');
Route::get('/retornatipofinanciamento/{id_modalidade}', 'CadastroMpme@retornatipofinanciamento');
Route::get('/atualizadadosexportador/{idusuario}/{dataexpiracaolink}', 'CadastroMpme@atualizacadastro');
Route::get('/declaracao_compromisso', 'DeclaracaoCompromissoExportador@index')->name('declaracaocompromisso');
Route::get('/declaracao_compromisso/baixar', 'DeclaracaoCompromissoExportador@baixar')->name('baixardeclaracao');

Route::group(['middleware' => ['auth', 'verifica.redirect']], function () {
    Route::get('/cadastro', 'CadastroMpme@index')->name('cadastro');
    Route::get('/dashboard', 'HomeController@dashboard')->name('dashboard');
});

Route::post('/buscarcnpj', 'CadastroMpme@buscarcnpj');
Route::post('/cadastrar', 'CadastroController@cadastrar');
Route::post('/atualizarexportador/', 'CadastroMpme@atualizarexportador');

Route::group(['prefix' => '/ajax_upload/', 'as' => 'ajaxupload.'], function () {
    Route::post('/uploadComprovantePgRelatorio', 'AjaxUploadController@uploadComprovantePgRelatorio')->name('uploadComprovantePgRelatorio');
    Route::post('/uploadRelatorioInternacional', 'AjaxUploadController@uploadRelatorioInternacional')->name('uploadRelatorioInternacional');
    Route::post('/uploadCalculoLimiteCredito', 'AjaxUploadController@uploadCalculoLimiteCredito')->name('uploadCalculoLimiteCredito');
    Route::post('/uploadAntiCorrupcao', 'AjaxUploadController@UploadAntiCorrupcao')->name('uploadAntiCorrupcao');
});

//ROTAS COM NECESSIDADE DE LOGIN
Route::group(['middleware' => ['auth', 'verifica.redirect']], function () {

    //ARQUIVOS
    Route::post('/validar/visualizar-arquivo/', 'ValidarController@visulizar_arquivo')->name('visulizar_arquivo');
    Route::get('/validar/visualizar-arquivo/render/{hash_arquivo}', 'ValidarController@render_arquivo');

    //USUARIO
    Route::group(['prefix' => '/usuario/', 'as' => 'usuario.'], function () {
        Route::post('/alterar-senha', 'UsuarioController@alterarSenha');
        Route::get('/atualizacao-cadastral', 'UsuarioController@atualizacaoCadastral')->name('atualizacao_cadastral');
        Route::post('/atualizar-cadastro', 'UsuarioController@atualizarCadastro')->name('atualizar_cadastro');
    });

    //SINISTRO
    Route::group(['prefix' => '/sinistro/', 'as' => 'sinistro.'], function () {
        Route::get('/', 'SinistroController@index')->name('home');
        Route::post('/', 'SinistroController@consultampme')->name('consultar_sinistro');
        Route::get('/cadastrar/sinistro/{operacao}/{proposta}', 'SinistroController@cadastrarsinistro')->name('cadastrar_sinistro');
        Route::post('/salvarsinistro', 'SinistroController@SalvarSinistro')->name('salvar');
        Route::get('/mpme/{idmpme}', 'SinistroController@consultar');
        Route::get('/cadastrar/motivocancelamento', 'SinistroController@motivocancelamento');
        Route::get('/downloadcgc/{idmpme}/arquivo/{arquivo}', 'SinistroController@downloadcgc');
        Route::get('/excluircgc/{idmpme}/arquivo/{arquivo}', 'SinistroController@excluircgc');
        Route::post('/uploadcgc', 'SinistroController@uploadcgc');
    });

    //PRECIFICACAO
    Route::group(['prefix' => '/precificacao/'], function () {
        Route::post('/precificarValor', 'PrecificacaoController@precificarValor');
        Route::post('/precificarValorSimulacao', 'PrecificacaoController@precificarValorSimulacao');
    });

    // AJAX
    Route::group(['prefix' => '/ajax/', 'as' => 'ajax.'], function () {
        Route::post('/buscarncmnbs', 'AjaxController@buscarncmnbs');
        Route::post('/concluir_atualizacao', 'AjaxController@concluirAtualizacao')->name('concluir_atualizacao');
        Route::post('/checaEnvioDelacaraoCompromisso', 'AjaxController@checa_envio_delacarao_compromisso');
        Route::post('/novo-importador', 'AjaxController@novoImportador');
        Route::post('/buscarImportadoresPorPais', 'AjaxController@buscarImportadoresPorPais');
        Route::post('/buscarImportador', 'AjaxController@buscarImportador');
        Route::post('/buscarExportadorLogado', 'AjaxController@buscarDadosExportadorLogado');
        Route::post('/mudaStatusQuestionario', 'AjaxController@mudaStatusQuestionarioOperacao');
        Route::post('/historicoAprovacaoOperacao', 'AjaxController@historico_aprovacao_operacao');
        Route::post('/recusar-proposta', 'AjaxController@recusar_proposta');
        Route::post('/aprovar-proposta', 'PropostaController@aprovar_proposta');
        Route::get('/enquadramento-aprovarmodalidade', 'AjaxController@enquadramentoAprovaModalidade')->name('enquadramento-aprovarmodalidade');
        Route::post('/calcular-data', 'AjaxController@calcular_data');
        Route::post('/buscar-importador-unico', 'AjaxController@buscarImportadorUnico');
        Route::post('/atualiza-importador-unico', 'AjaxController@atualizaImportadorUnico');
        Route::post('/varificar-saldo', 'AjaxController@verificar_saldo');
        Route::post('/retorna-operacoes', 'AjaxController@retornaOperacoes');
        Route::post('/historicoAprovacaoEmbarque', 'AjaxController@historico_aprovacao_embarque');
        Route::post('/consulta-codigo-unico', 'AjaxController@ConsultaCodigoUnico')->name('ConsultaCodigoUnico');
        Route::post('/consulta-enquadramento-usuario/', 'AjaxController@ConsultaEnquadramentoUsuario')->name('consulta-enquadramento-usuario');
    });

    //ROTAS ACESSADA POR DIVERSOS PERFIS (CLIENTE, ABGF E BANCO)
    Route::post('proposta/historico-proposta', 'PropostaController@historico_proposta')->name('historicopropostas');
    Route::post('proposta/dados-questionario', 'PropostaController@dados_questionario')->name('dadosquestionario');
    Route::post('proposta/dados-proposta', 'PropostaController@dados_proposta');
    Route::post('visualizar-notificacao', 'NotificacaoController@visualizar_notificacao');
    Route::post('questionario_operacao/excluir', 'QuestionarioOperacaoController@excluir');
    Route::get('proposta/embarque-proposta/{ID_PROPOSTA}', 'PropostaController@embarque_proposta');

    Route::post('proposta/salva-embarque-proposta', 'PropostaController@salvar_embarque_proposta')->name('salva-embarque-proposta');

    Route::group(['prefix' => '/abgf/', 'as' => 'abgf.'], function () {
        Route::post('arquivos/novo', 'ArquivoController@novo')->name('novo');
        Route::get('arquivos/download/{id_mpme_arquivo}', 'ArquivoController@download_arquivo')->name('arquivo.download');
        Route::post('arquivos/inserir', 'ArquivoController@inserir')->name('inserir');
        Route::post('arquivos/inserir-boleto-premio', 'PropostaController@inserir_boleto_premio')->name('inserir_boleto_premio');
        Route::post('arquivos/inserir-cg', 'PropostaController@inserir_cg')->name('inserir_cg');
        Route::post('arquivos/inserir-cg-assinado', 'PropostaController@inserir_cg_assinado')->name('inserir_cg_assinado');
        Route::post('arquivos/inserir-apolice', 'PropostaController@inserir_apolice')->name('inserir_apolice');
        Route::post('arquivos/inserir-apolice-assinada', 'PropostaController@inserir_apolice_assinada')->name('inserir_apolice_assinada');
        Route::post('arquivos/inserir-boleto-relatorio', 'AbgfAnalisaLimiteController@inserir_boleto_relatorio')->name('inserir_boleto_relatorio');
    });

    //-----------------------------------------ROTAS AUTENTICADAS PARA O CLIENTE-------------------------------------

    Route::group(['middleware' => ['can:usuario_cliente']], function () {

        Route::group(['as' => 'notificacoes.'], function () {

            Route::get('/download/{id_mpme_arquivo}', 'ArquivoController@download_arquivo')->name('arquivo.download');


            //ROTAS PARA EMBARQUE
            Route::group(['prefix' => '/embarque/', 'as' => 'embarque.'], function () {
                Route::get('/{id_oper}/{id_proposta}', 'EmbarqueController@index');
                Route::get('/novo/{id_oper}/{id_proposta}', 'EmbarqueController@novo');
                Route::post('salvar', 'EmbarqueController@salvar');
                Route::get('editar/{id_oper}/{id_proposta}/{id_embarque}', 'EmbarqueController@editar')->name('editar');
            });

            //ROTAS PARA PROPOSTA
            Route::group(['prefix' => '/proposta/', 'as' => 'proposta.'], function () {
                Route::post('salvar', 'PropostaController@salvar');
                Route::post('enviar', 'PropostaController@enviar');
                Route::post('excluir', 'PropostaController@excluir');
                Route::match(['get', 'post'], 'lista-proposta-usuario', 'PropostaController@listar_propostas_usuario');
                Route::post('arquivos/inserir-comprovante-boleto-premio', 'PropostaController@inserir_comprovante_boleto_premio')->name('inserir_comprovante_boleto_premio');

                Route::group(['middleware' => 'verifica.operacao'], function () {
                    Route::match(['get', 'post'], '/{id_oper}', 'PropostaController@index');
                    Route::get('/nova/{id_oper}', 'PropostaController@nova');
                });
            });
        });

        //ROTAS PARA QUESTIONARIO OPERACAO
        Route::group(['prefix' => '/questionario_operacao/'], function () {
            Route::post('arquivos/inserir-comprovante-boleto-relatorio', 'QuestionarioOperacaoController@inserir_comprovante_boleto_relatorio');
            Route::match(['get', 'post'], '', 'QuestionarioOperacaoController@index');
            Route::get('novo', 'QuestionarioOperacaoController@novo');
            Route::post('salvar', 'QuestionarioOperacaoController@salvar');

            Route::group(['middleware' => 'verifica.operacao'], function () {
                Route::get('editar/{id_oper}', 'QuestionarioOperacaoController@editar');
            });
        });
    });

    //-----------------------------------------ROTAS AUTENTICADAS PARA O BANCO-------------------------------------

    Route::group(['middleware' => ['can:usuario_banco']], function () {

        Route::group(['prefix' => '/banco/', 'as' => 'banco.'], function () {

            Route::get('/', 'BancoController@index');
            Route::post('/devolverValidador', 'BancoController@devolverValidador');
            Route::post('atualizaFinancPre', 'BancoController@atualizaFinancPre')->name('atualizaFinancPre');
            Route::post('atualizaFinancPos', 'BancoController@atualizaFinancPos')->name('atualizaFinanc');
            Route::post('atualizaInfoAddExportador', 'BancoController@atualizaInfoAddExportador')->name('atualizaInfoAddExportador');
            Route::post('salvar-divergencia', 'BancoController@salvar_divergencia')->name('salvar_divergencia');

            Route::group(['prefix' => '/embarque/', 'as' => 'embarque.'], function () {
                Route::get('/{id_oper}/{id_proposta}', 'EmbarqueController@index');
                Route::post('aprova-embarque', 'EmbarqueController@AprovaEmbarque');
                Route::post('devolve-conferente', 'EmbarqueController@DevolveConferente');
            });

            Route::group(['prefix' => '/analisa/', 'as' => 'analisa.'], function () {
                //notificacoes/analise/exportador
                Route::get('/exportador/{ID_USUARIO}/{ID_NOTIFICACAO}', 'BancoController@analisaExportador')->name('exportador');
                Route::get('/listar-proposta-aprovacao', 'BancoController@listar_propostas_aprovacao')->name('listarpropostas');
            });

            //ROTAS PARA DESEMBOLSO
            Route::group(['prefix' => '/desembolso/', 'as' => 'desembolso.'], function () {
                Route::get('/{id_oper}/{id_proposta}', 'DesembolsoController@index');
                Route::post('/novo-desembolso/', 'DesembolsoController@novo_desembolso');
                Route::post('/salvar/', 'DesembolsoController@salvar');
                Route::post('/alterar-desembolso/', 'DesembolsoController@alterar_desembolso');
                Route::post('/recusar/', 'DesembolsoController@recusar_desembolso');
                Route::post('/aprovar/', 'DesembolsoController@aprovar_desembolso');
                Route::post('/historico-desembolso', 'DesembolsoController@historico_desembolso')->name('historicodesembolso');
            });
        });
    });

    //-----------------------------------------ROTAS AUTENTICADAS PARA USUARIOS ABGF-------------------------------------

    Route::group(['middleware' => ['can:usuario_abgf']], function () {

        //NOTIFICACOES
        Route::group(['prefix' => '/notificacoes/', 'as' => 'notificacoes.'], function () {

            Route::get('/', 'NotificacaoController@index')->name('index');

            Route::group(['prefix' => '/validacao/', 'as' => 'validacao.'], function () {
                Route::get('/exportador/', 'NotificacaoController@index')->name('exportador');
            });

            Route::group(['prefix' => '/analise/', 'as' => 'analise.'], function () {
                Route::get('/exportador/', function () {
                    return redirect('/abgf/exportador/analise');
                })->name('exportador');
            });
        });

        //ROTAS MODULO ABGF
        Route::group(['prefix' => '/abgf/', 'as' => 'abgf.'], function () {
            Route::get('/', 'NotificacaoController@validaExportador')->name('index');

            //Grupo de prefixo /abgf/exportador
            Route::group(['prefix' => '/exportador/', 'as' => 'exportador.'], function () {

                Route::match(['get', 'post'], '/', 'ExportadorController@index')->name('index');

                Route::match(['get', 'post'], '/atualizacao/cadastral', 'ExportadorController@atualizacaoCadastral')->name('atualizacao_cadastral');

                Route::get('/enquadramento', 'ExportadorController@enquadramento')->name('enquadramento');

                //Rota /abgf/exportador/validacao
                Route::get('/validacao/{idmpme}/{idNotificacao}', 'ExportadorController@validaExportador')->name('validacao');

                Route::post('/sustituir/arquivos', 'ExportadorController@substituirArquivos')->name('substituirArquivos');
                //Rota /abgf/exportador/analise
                Route::get('/analise/{idmpme}', 'NotificacaoController@analiseExportador')->name('analise');
                //Rpta /abgf/exportador/analise
                Route::post('salvaalteracaoexportador', 'ExportadorController@salvaAlteracoesExportador')->name('salvaAlteracoesExportador');
                Route::post('salvalistatarefa', 'ExportadorController@salvaListaTarefas')->name('salvaListaTarefa');
                Route::post('/ficha-cadastral', 'ExportadorController@fichaCadastral')->name('fichaCadastral');
                Route::post('/limite-operacional', 'AbgfAnalisaLimiteController@limite_operacional')->name('limite_operacional');

                Route::match(['get', 'post'], '/analisalimite/', 'AbgfAnalisaLimiteController@index')->name('listaquestionarioaprovacao');
                Route::get('/analisalimite/{idoper}/{id_exportador}', 'AbgfAnalisaLimiteController@listarQuestionario')->name('analiselimite');
                Route::post('/analisalimite/analistaaprovalimite', 'AbgfAnalisaLimiteController@analistaAprovaLimite')->name('analistaAprovaLimite');
                Route::post('/analisalimite/encaminhar', 'AbgfAnalisaLimiteController@encaminhar')->name('encaminhar');
                Route::post('/analisalimite/devolver', 'AbgfAnalisaLimiteController@devolver')->name('devolver');
                Route::post('/analisalimite/indeferir', 'AbgfAnalisaLimiteController@indeferir')->name('indeferir');
                Route::post('/analisalimite/concluir', 'AbgfAnalisaLimiteController@concluir')->name('concluir');
                Route::post('/analisalimite/operacional', 'QuestionarioOperacaoController@analise_operacional');

                Route::match(['get', 'post'], '/listar-proposta-aprovacao', 'PropostaController@listar_propostas_aprovacao')->name('listarpropostas');
                Route::post('/filtrar-proposta', 'PropostaController@filtrarPropostas')->name('filtrarPropostas');

                //proposta analista rec proprio
                Route::match(['get', 'post'], '/listar-proposta-embarque', 'PropostaController@listar_propostas_embarque')->name('listarpropostasembarque');

                // Devolve embarque para exportador
                Route::post('devolve-embarque', 'EmbarqueController@DevolveEmbarque');

                Route::post('aprova-embarque', 'EmbarqueController@AprovaEmbarque')->name('aprovaembarque');
            });

            Route::get('/cg', 'CgController@index')->name('cg');
            Route::get('/cg/gerar/{ID_USUARIO}/{NU_PROPOSTA}/{ANO_PROPOSTA}', 'CgController@gerarcgCondGerais')->name('gerarcgCondGerais');

            Route::group(['prefix' => '/paises-risco/', 'as' => 'paisesrisco.'], function () {
                Route::get('/lista', 'PaisesController@index_risco')->name('lista_paises_risco');
                Route::post('/gravar', 'PaisesController@gravar_risco')->name('gravar_paises_risco');
            });

            Route::group(['prefix' => '/relatorios/', 'as' => 'relatorios.'], function () {
                Route::get('/novo', 'RelatoriosController@index')->name('novo_relatorio');
                Route::post('/gerar', 'RelatoriosController@gerar_relatorio')->name('gerar_relatorio');
            });
        });
    });
});
