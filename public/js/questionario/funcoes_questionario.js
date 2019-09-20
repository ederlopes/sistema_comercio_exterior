$(document).ready(function(){

    $("#id_nat_jur").on("change", function () {

        var objNatRisco =  $("#id_nat_risco");

        objNatRisco.attr("disabled", false);
        $("#id_nat_risco option").remove();

        if ($(this).val() == 1)
        {
            objNatRisco.append('<option value="">Selecione</option>');
            //objNatRisco.append('<option value="1">Político e Extraordinário</option>');
            objNatRisco.append('<option value="2">Comercial, Político e Extraordinário</option>');
        }else{
            objNatRisco.append('<option value="">Selecione</option>');
            objNatRisco.append('<option value="3">Soberano</option>');
            objNatRisco.append('<option value="4">Ordinário</option>');
        }

        objNatRisco.selectpicker('refresh');

    })

    $(".maskFone").mask("+99 (00) 0000-00009");


    $('#id_setor_atividade').on('change', function (event)
    {
        var id_setor_atividade  = $(this);
        var opcoes              = id_setor_atividade.val();
        var tem_restricao       = false;

        $.each(opcoes, function (key, opcao) {
            id_restricao = id_setor_atividade.find('option[value='+opcao+']').data('idrestricao');
            if (id_restricao == 1){
                tem_restricao = true;
            }
        });

        if (tem_restricao){
            $("#termo_setor_atividade").show();
            $("#in_aceite_restricoes").attr('checked', true);
            $("#id_aceite_termo_setor_atividade").val(1);
        }else{
            $("#termo_setor_atividade").hide();
            $("#id_aceite_termo_setor_atividade").val(0);
            $("#in_aceite_restricoes").attr('checked', false);
        }

    });

    $("#id_cliente_exportadores_modalidade").on("change", function () {

        var dados  = $(this).val().split('#');
        var id_modalidade = parseInt(dados[1]);

        $("#div_no_razao_social_input").hide();
        $("#div_no_razao_social_select").show();
        $("#div_no_razao_social_input").val('');
        $("#codigo_unico_importador").val(0);
        $("#id_cliente_mpme").val(0);

        $("#endereco").val('').prop("disabled", false);
        $("#cidade").val('').prop("disabled", false);
        $("#telefone").val('').prop("disabled", false);
        $("#cnpj").val('').prop("disabled", false);
        $("#cep").val('').prop("disabled", false);
        $("#contato").val('').prop("disabled", false);
        $("#e_mail").val('').prop("disabled", false);
        $("#id_pais").val('').prop("disabled", false).selectpicker('refresh');
        $("#id_nat_jur").val('').prop("disabled", false).selectpicker('refresh');
        $("#id_nat_risco").val('').selectpicker('refresh');
        $("#no_razao_social_select").append("<option value='0'>::SELECIONE::</option>").attr('disabled', true).selectpicker('refresh');

        switch (id_modalidade)
        {
            case 1:
                $("#msgModalidade").html('Os dados para cadastro devem ser referentes ao <strong>EXPORTADOR</strong>');
                $("#div_pergunta_5").hide();
                $("#div_pergunta_6").hide();
                buscarExportadorLogado();
                break;
            case 2:
                $("#div_pergunta_5").show();
                $("#div_pergunta_6").show();
                $("#msgModalidade").html('Os dados para cadastro devem ser referentes ao <strong>IMPORTADOR</strong>');
                break;
            case 3:
                $("#div_pergunta_5").show();
                $("#div_pergunta_6").show();
                $("#msgModalidade").html('Os dados para cadastro devem ser referentes ao <strong>IMPORTADOR</strong>');
                break;
        }

        $("#containerMsgModalidade").show();
    })


    $("#no_razao_social_select").on("change", function () {

        var valorSelect = $(this).val();
        var dadosSelect = valorSelect.split('#');

        if ( $(this).val() == "")
        {
            $("#div_no_razao_social_input").show();
            $("#div_no_razao_social_select").hide();
            $("#div_no_razao_social_input").val('');
            $("#codigo_unico_importador").val(0);

            $("#endereco").val('');
            $("#cidade").val('');
            $("#telefone").val('');
            $("#cnpj").val('');
            $("#cep").val('');
            $("#contato").val('');
            $("#e_mail").val('');


        }else {
            $("#div_no_razao_social_input").hide();
            $("#div_no_razao_social_select").show();
            $("#no_razao_social").val($("#no_razao_social_select option:selected").text());
            $("#codigo_unico_importador").val(dadosSelect[0]);
            $("#id_cliente_mpme").val(dadosSelect[1]);
            buscarDadosImportador(dadosSelect[0]);
        }

    })



    $("#id_pais").on("change", function(){
        var idrestrito = $(this).find(':selected').data('idrestrito');

        if ( idrestrito == 1)
        {
            swal("Erro!", "De acordo com as políticas de restrições da ABGF nós não trabalhamos com este País ", "error").then(function() {
                $("#id_pais").val('');
            });
            return false;
        }


        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'ajax/buscarImportadoresPorPais',
            data: {id_pais:$(this).val()},
            success: function(retorno) {
                $("#no_razao_social_select option").remove();
                $("#no_razao_social_select").append("<option value='0'>::SELECIONE::</option>");
                $("#no_razao_social_select").append("<option value=''>[ NOVO ]</option>");

                $.each( retorno, function( key, value ) {
                    $("#no_razao_social_select").append('<option value='+value.CODIGO_UNICO_IMPORTADOR+'#'+value.ID_MPME_CLIENTE+'>'+value.NOME_CLIENTE+'</option>');
                });

                $("#no_razao_social_select").attr("disabled", false).selectpicker('refresh');



            },
            error: function (request, status, error) {
                //console.log(request.responseText);
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                  //  location.reload();
                });
            }
        });
    })

    $(".enviarAbgf").on("click", function (e)
    {
        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE + 'ajax/checaEnvioDelacaraoCompromisso',
            context: this,
            success: function (retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!", retorno.msg, "error");
                        break;
                    case 'sucesso':
                        confirmarEnvio($(this).data('idoper'));
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!", retorno.msg, "info");
                        break;
                }
                if (retorno.recarrega == 'true') {
                    alerta.then(function () {
                        location.reload();
                    });
                }
            },
            error: function (request, status, error) {
                //console.log(request.responseText);
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                    location.reload();
                });
            }
        });


    });

    $("#btnCadastrar").on('click', function () {
        $(this).attr('disabled', true);
        if ( validarForm() == true )
        {
            $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE+'questionario_operacao/salvar',
                data: $("#frmQuestionario").serialize(),
                success: function(retorno) {
                    switch (retorno.status) {
                        case 'erro':
                            var alerta = swal("Erro!",retorno.msg,"error");
                            break;
                        case 'sucesso':
                            var alerta = swal("Sucesso!",retorno.msg,"success");
                            break;
                        case 'alerta':
                            var alerta = swal("Ops!",retorno.msg,"warning");
                            break;
                    }
                    if (retorno.recarrega=='true') {
                        alerta.then(function(){
                            location.reload();
                        });
                    }else if(retorno.recarrega=='url'){
                        alerta.then(function(){
                            window.location = URL_BASE+retorno.url;
                        });
                    }
                },
                error: function (request, status, error) {
                    //console.log(request.responseText);
                    swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                        location.reload();
                    });
                }
            });
        }else{
            $(this).attr('disabled', false);
        }

    })

    $('div#historico-aprovacao').on('show.bs.modal', function (event)
    {
        var id_oper          =  $(event.relatedTarget).data('idoper');

        $(this).find('.modal-title').html('<h3>Histórico de aprovações - '+id_oper+' </h3>');
        $(this).find('.modal-body').html('');
        $(".loading").show();

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'ajax/historicoAprovacaoOperacao',
            data: {'id_oper':id_oper},
            context: this,
            success: function(retorno){
                $(".loading").hide();
                $(this).find('.modal-body').html(retorno);
            },
            error: function (request, status, error) {
                //console.log(request.responseText);
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                    //  location.reload();
                });
            }
        });
    });

    $(".excluir_questionario").on('click', function(){
        swal({
            title: 'Atenção?',
            text: "Tem certeza que deseja excluir esta operação: " + $(this).data('idoper') + " ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não'
        }).then((result) => {
            if (result.value) {
                $('div#excluir_questionario').modal('show');
                $("#id_oper_excluir").val($(this).data('idoper'));
                $(".loading").hide();
            }else{
                $('#div#excluir_questionario').modal('hide');
            }
        })
    })


    $('div#excluir_questionario').on('click', '.btnExcluir', function (event)
    {
        var id_oper     = $("#id_oper_excluir").val();
        var ds_motivo   = $('div#excluir_questionario #ds_motivo').val();

        if ( ds_motivo == "" || ds_motivo.length < 5 )
        {
            swal("Ops!",'Favor preencher o motivo da exclusão',"warning");
            return false;
        }

        if (id_oper>0 && ds_motivo != "")
        {
            $.ajax({
                type: "POST",
                url: URL_BASE+'questionario_operacao/excluir',
                data: {
                    'id_oper':id_oper,
                    'ds_motivo':ds_motivo,
                },
                context: this,
                beforeSend: function() {
                    $(".loading").show();
                    $(this).attr('disabled', 'true');
                },
                success: function(retorno)
                {
                    $(".loading").hide();
                    switch (retorno.status) {
                        case 'erro':
                            var alerta = swal("Erro!",retorno.msg,"error");
                            break;
                        case 'sucesso':
                            var alerta = swal("Sucesso!",retorno.msg,"success");
                            break;
                        case 'alerta':
                            var alerta = swal("Ops!",retorno.msg,"warning");
                            break;
                    }
                    if (retorno.recarrega=='true') {
                        alerta.then(function(){
                            location.reload();
                        });
                    }
                },
                error: function (request, status, error){
                    swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function() {
                        $(this).modal('hide');
                    });
                }
            });
        }


    });


    $('div#visualizar-arquivo').on('show.bs.modal', function (event) {
        var id_mpme_arquivo = $(event.relatedTarget).data('idmpmearquivo');
        var no_arquivo = $(event.relatedTarget).data('noarquivo');

        $(this).find('.modal-header h5.modal-title span').html(no_arquivo);

        if (id_mpme_arquivo>0)
        {
            $.ajax({
                type: "POST",
                url: URL_BASE+'validar/visualizar-arquivo',
                data: {'id_mpme_arquivo':id_mpme_arquivo},
                context: this,
                beforeSend: function() {
                    $(".loading").show();
                },
                success: function(retorno)
                {
                    $(".loading").hide();
                    $(this).find('.modal-body').html(retorno).fadeIn('fast');
                },
                error: function (request, status, error) {
                    swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function() {
                        $(this).modal('hide');
                    });
                }
            });
        }

    });


});

function buscarDadosImportador(param_codigo_unico_importador)
{
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE+'ajax/buscarImportador',
        data: {codigo_unico_importador:param_codigo_unico_importador},
        success: function(retorno){
            $("#endereco").val(retorno[0].ENDERECO).attr('read ');
            $("#cidade").val(retorno[0].CIDADE);
            $("#telefone").val(retorno[0].TELEFONE);
            $("#cnpj").val(retorno[0].CNPJ);
            $("#cep").val(retorno[0].CEP);
            $("#contato").val(retorno[0].CONTATO);
            $("#e_mail").val(retorno[0].E_MAIL);
            $("#id_nat_jur").val(retorno[0].NAT_JURIDICA);
            $("#id_nat_jur").trigger('change');
            setTimeout(function () {
                $("#id_nat_risco").val(retorno[0].NAT_RISCO).selectpicker('refresh');
            }, 1000);
        },
        error: function (request, status, error) {
            //console.log(request.responseText);
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                //  location.reload();
            });
        }
    });
}

function buscarExportadorLogado()
{
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE+'ajax/buscarExportadorLogado',
        success: function(retorno){
            $("#endereco").val(retorno.DE_ENDER).prop("disabled", true);
            $("#cidade").val(retorno.DE_CIDADE).prop("disabled", true);
            $("#telefone").val(retorno.DE_TEL).prop("disabled", true);
            $("#cnpj").val(retorno.CPF_CNPJ_QUADRO).prop("disabled", true);
            $("#cep").val(retorno.DE_CEP).prop("disabled", true);
            $("#contato").val(retorno.NM_CONTATO).prop("disabled", true);
            $("#e_mail").val(retorno.DE_EMAIL).prop("disabled", true);
            //$("#id_nat_jur").val(retorno.NAT_JURIDICA).prop("disabled", true).selectpicker('refresh');
            //$("#id_nat_jur").trigger('change').prop("disabled", true).selectpicker('refresh');
            $("#id_pais").val(retorno.ID_PAIS).prop("disabled", true).selectpicker('refresh');
            $("#id_pais").trigger('change').prop("disabled", true).selectpicker('refresh');

            $('#codigo_unico_importador').val(0); //NESTE CASO REFERECE AO EXPORTADOR
            $('#id_cliente_mpme').val(retorno.ID_MPME_CLIENTE);

            $("#div_no_razao_social_select").hide();
            $("#div_no_razao_social_input").show();
            $("#no_razao_social").val(retorno.NOME_FANTASIA).prop("disabled", true);

          /*  setTimeout(function () {
                $("#id_nat_risco").val(retorno.NAT_RISCO).prop("disabled", true).selectpicker('refresh');
            }, 500);*/


        },
        error: function (request, status, error) {
            //console.log(request.responseText);
            swal("Erro!", "Erro ao buscar os dados do Exportador", "error").then(function() {
                //  location.reload();
            });
        }
    });
}


function validarForm()
{
    var erros = new Array();

    if ( $('#id_cliente_exportadores_modalidade_financiamento').val() == "")
    {
        erros.push('O campo Modalidade da operação.');
    }


    if ( $('#id_setor').val() == "")
    {
        erros.push('O campo País.');
    }

    if ( $('#no_razao_social').val() == "")
    {
        erros.push('O campo Rezão Social.');
    }

    if ( $('#id_nat_jur').val() == "")
    {
        erros.push('O campo Natureza Jurídica.');
    }

    if ( $('#id_nat_risco').val() == "")
    {
        erros.push('O campo Natureza do Risco.');
    }

    if ( $('#endereco').val() == "")
    {
        erros.push('O campo Endereço.');
    }

    if ( $('#cidade').val() == "")
    {
        erros.push('O campo Cidade.');
    }

    if ( $('#telefone').val() == "")
    {
        erros.push('O campo Telefone.');
    }

    if ( $('#cnpj').val() == "")
    {
        erros.push('O campo CNPJ.');
    }

    if ( $('#contato').val() == "")
    {
        erros.push('O campo Contato.');
    }

    if ($("#vl_proposta").val() == "")
    {
        erros.push('O valor da proposta tem que ser maior do que 0,00.');
    }

    if ( $('#cep').val() == "")
    {
        erros.push('O campo CEP.');
    }

    if ( $('#e_mail').val() == "")
    {
        erros.push('O campo E-mail.');
    }

    if ( $('#vl_proposta').val() == "")
    {
        erros.push('O campo Valor da proposta.');
    }

    if ( $('#id_moeda').val() == "")
    {
        erros.push('O campo Moeda da operação.');
    }

    if ( $('#in_documentacao').is(':checked')  == false)
    {
        erros.push('Enviar Demonstrativos financeiros dos últimos 03 anos.');
    }


    if (erros.length>0)
    {
        swal({
            title: '<strong>Ops<br /></strong> os seguintes campos devem ser preenchidos <br> <br>',
            type: 'warning',
            html: erros.join('<br />'),
            showCloseButton: true,
        })

        return false;
    }

    $("#frmQuestionario :input").prop("disabled", false);

    return true;
}

function retorno_arquivo(retorno,modal)
{
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE+'questionario_operacao/arquivos/inserir-comprovante-boleto-relatorio',
        context: this,
        data: {'token':retorno.token},
        success: function(retorno) {
            $(".loading").hide();
            modal.modal('hide');
            switch (retorno.status) {
                case 'erro':
                    var alerta = swal("Erro!",retorno.msg,"error");
                    break;
                case 'sucesso':
                    var alerta = swal("Sucesso!",retorno.msg,"success");
                    break;
                case 'alerta':
                    var alerta = swal("Ops!",retorno.msg,"warning");
                    break;
            }
            if (retorno.recarrega=='true') {
                alerta.then(function(){
                    location.reload();
                });
            }
        },
        error: function (request, status, error) {
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                location.reload();
            });
        }
    });

}

function confirmarEnvio(id_oper) {
    swal({
        title: 'Atenção?',
        text: "Tem certeza que deseja enviar a operação de aprovação para Análise da ABGF?",
        type: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim',
        cancelButtonText: 'Não'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE + 'ajax/mudaStatusQuestionario',
                data: { 'id_oper': id_oper, 'st_oper': 20, 'ic_enviado': 1, 'in_notificacao': 'S' },
                success: function (retorno) {
                    switch (retorno.status) {
                        case 'erro':
                            var alerta = swal("Erro!", "Erro ao processar registro", "error");
                            break;
                        case 'sucesso':
                            var alerta = swal("Sucesso!", 'Formulário enviado com sucesso!', "success");
                            break;
                        case 'alerta':
                            var alerta = swal("Ops!", 'Falta de parametros informados', "warning");
                            break;
                    }
                    if (retorno.recarrega == 'true') {
                        alerta.then(function () {
                            location.reload();
                        });
                    }
                },
                error: function (request, status, error) {
                    //console.log(request.responseText);
                    swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                        location.reload();
                    });
                }
            });

        }
    })
}