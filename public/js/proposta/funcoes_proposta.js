$(document).ready(function () {

    $("#id_cliente_exportadores_modalidade").on('change', function () {
        var dados = $(this).val().split('#');
        var id_modalidade = dados[1];

        switch (id_modalidade) {
            case "1": //pre
                $("#prazo_dias_pos").val('').hide();
                $("#prazo_dias_pre").val('').show();
                $("#aceite").hide();
                $("#downpayment").hide();
                break;

            case "2": //pre+pos
                $("#prazo_dias_pos").val('').show();
                $("#prazo_dias_pre").val('').show();
                $("#aceite").show();
                $("#downpayment").show();
                $("#in_aceite").val('SIM').prop('disabled', true);
                break;

            case "3": //pos
                $("#prazo_dias_pos").val('').show();
                $("#prazo_dias_pre").val('').hide();
                $("#aceite").show();
                $("#downpayment").show();
                $("#in_aceite").val('SIM').prop('disabled', true);
                break;
        }
    })

    $("#btnCadastrar").on('click', function () {

        if (validarForm()) {

            $(this).attr('disabled', 'disabled').val('Aguarde processando');
            $("#in_aceite").prop('disabled', false);

            $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE + 'proposta/salvar',
                data: $("#frmProposta").serialize(),
                success: function (retorno) {
                    switch (retorno.status) {
                        case 'erro':
                            var alerta = swal("Erro!", retorno.msg, "error");
                            break;
                        case 'sucesso':
                            //processarCalculadora(retorno.id_oper, retorno.id_mpme_proposta);
                            var alerta = swal("Sucesso!", retorno.msg, "success");
                            break;
                        case 'alerta':
                            var alerta = swal("Ops!", retorno.msg, "warning");
                            break;
                    }
                    if (retorno.recarrega == 'true') {
                        alerta.then(function () {
                            location.reload();
                        });
                    } else if (retorno.recarrega == 'url') {
                        alerta.then(function () {
                            window.location = URL_BASE + retorno.url;
                        });
                    }
                },
                error: function (request, status, error) {
                    swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                        //location.reload();
                    });
                }
            });
        }
    })

    $(".prazo").on('blur', function () {
        if ($(this).val() > 180) {
            $(this).focus();
            swal('Ops!', 'O prazo informado não pode ultrapassar 180 dias!', 'info').then(function () {
                return false;
            });
        } else if ($(this).val() < 30) {
            $(this).focus();
            swal('Ops!', 'O prazo informado não pode ser menor que 30 dias!', 'info').then(function () {
                return false;
            });
        }
    })

    $(".btn-fechar-precificacao").on('click', function (event) {
        event.preventDefault();
        location.reload();
    })


    $("#vl_proposta").on('blur', function () {
        var valor_atual = $(this).maskMoney('unmasked')[0];
        if (valor_atual != "") {
            var novo_valor = valor_atual;
            var percentual = parseFloat($("#va_percentual_dw_payment").val());

            if (percentual > 0) {
                novo_valor = valor_atual - (valor_atual * (percentual / 100));
            }

            if (novo_valor > $("#vl_saldo").val()) {
                $(this).val('');
                swal('Ops!', 'O valor da proposta não pode ultrapassar o saldo!', 'info');
            }
        }
    })




    $('div#nova-precificacao').on('show.bs.modal', function (event) {
        var id_mpme_proposta = $(event.relatedTarget).data('idproposta');
        var id_oper = $(event.relatedTarget).data('idoper');

        if (id_oper == "" || id_mpme_proposta == "") {
            swal('Ops!', 'Dados informados inválidos!', 'info');
            return false;
        }
        $(".loading").show();
        $(this).find('.modal-title').html('<h3>Precificação</h3>');
        $(this).find('.modal-body').html('');

        processarCalculadora(id_oper, id_mpme_proposta);

    });


    $("#btnSimular").on('click', function () {
        if (validarForm()) {
            $("#nova-precificacao-simulacao").modal('show');
            $(".loading").show();
            $(this).find('.modal-title').html('<h3>Simulação de Precificação</h3>');
            $(this).find('.modal-body').html('');
            processarCalculadoraSimulacao();
        }

    });



    $('div#dados-apolice').on('show.bs.modal', function (event) {
        $(".id_oper").val('');
        $(".id_mpme_proposta").val('');
        $(".nu_apolice").val('');

        var id_mpme_proposta = $(event.relatedTarget).data('idproposta');
        var id_oper = $(event.relatedTarget).data('idoper');

        $(".id_oper").val(id_oper);
        $(".id_mpme_proposta").val(id_mpme_proposta);

        $(".loading").hide();
    });


    $('div#dados-apolice').on('click', '#btnSalvarApolice', function () {
        if ($("#nu_apolice").val() == "") {
            swal('Ops!', 'Favor informar o número da apólice!', 'info');
            return false;
        }

        if ($("#arquivo_apolice").val() == "") {
            swal('Ops!', 'Favor selecionar o arquivo da apólice!', 'info');
            return false;
        }

        var form = $("#form-nova-apolice");
        var data = new FormData(form.get(0));

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE + 'abgf/arquivos/inserir-apolice',
            data: data,
            cache: false,
            contentType: false,
            processData: false,
            context: this,
            success: function (retorno) {
                $(".loading").hide();
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!", retorno.msg, "error");
                        break;
                    case 'sucesso':
                        var alerta = swal("Sucesso!", retorno.msg, "success");
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!", retorno.msg, "warning");
                        break;
                }
                if (retorno.recarrega == 'true') {
                    alerta.then(function () {
                        location.reload();
                    });
                }
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                    location.reload();
                });
            }
        });

    });





    $('div#historico_proposta').on('show.bs.modal', function (event) {
        var id_mpme_proposta = $(event.relatedTarget).data('idproposta');
        var id_oper = $(event.relatedTarget).data('idoper');

        if (id_oper == "" || id_mpme_proposta == "") {
            swal('Ops!', 'Dados informados inválidos!', 'info');
            return false;
        }
        $(".loading").show();

        $(this).find('.modal-body').html('');


        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE + 'proposta/historico-proposta',
            data: {
                'id_mpme_proposta': id_mpme_proposta,
                'id_oper': id_oper
            },
            context: this,
            success: function (retorno) {
                $(".loading").hide();
                $(this).find('.modal-body').html(retorno);
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                    location.reload();
                });
            }
        });





    });

    $('div#recusar-proposta').on('show.bs.modal', function (event) {
        $(".loading").hide();

        var id_mpme_proposta = $(event.relatedTarget).data('idproposta');
        var id_oper = $(event.relatedTarget).data('idoper');

        $("#id_mpme_proposta").val(id_mpme_proposta);
        $("#id_oper").val(id_oper);


    });


    $('#id_setor').on('change', function (event) {
        var idrestricao = $(this).find(':selected').data('idrestricao');

        if (idrestricao == 1) {

            Swal({
                title: 'Atenção?',
                text: "Este setor de atividade esta na lista de restrição da ABGF. No momento do embarque o mesmo deve fazer upload de documentação solicitada pela ABGF. Deseja continuar?",
                type: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sim',
                cancelButtonText: 'Não'
            }).then((result) => {
                if (result.value) {
                    $("#termo_setor_atividade").show();
                    $("#in_aceite_restricoes").attr('checked', true);
                    $("#id_aceite_termo_setor_atividade").val(1);
                } else {
                    $("#termo_setor_atividade").hide();
                    $("#id_aceite_termo_setor_atividade").val(0);
                    $("#in_aceite_restricoes").attr('checked', false);
                }
            });


        }

    });


    $(".excluir_proposta").on('click', function () {
        swal({
            title: 'Atenção?',
            text: "Tem certeza que deseja excluir a proposta: " + $(this).data('idproposta') + " ?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não'
        }).then((result) => {
            if (result.value) {
                $('div#excluir_proposta').modal('show');
                $("#id_proposta_excluir").val($(this).data('idproposta'));
                $(".loading").hide();
            } else {
                $('#div#excluir_proposta').modal('hide');
            }
        })
    })


    $('div#excluir_proposta').on('click', '.btnExcluir', function (event) {
        var id_mpme_proposta = $("#id_proposta_excluir").val();
        var ds_motivo = $('div#excluir_proposta #ds_motivo').val();

        if (id_mpme_proposta == "" || ds_motivo.length < 5) {
            swal("Ops!", 'Favor preencher o motivo da exclusão', "warning");
            return false;
        }

        if (id_mpme_proposta > 0 && ds_motivo != "") {
            $.ajax({
                type: "POST",
                url: URL_BASE + 'proposta/excluir',
                data: {
                    'id_mpme_proposta': id_mpme_proposta,
                    'id_mpme_status_proposta': 17,
                    'ds_motivo': ds_motivo,
                },
                context: this,
                beforeSend: function () {
                    $(".loading").show();
                    $(this).attr('disabled', 'true');
                },
                success: function (retorno) {
                    $(".loading").hide();
                    switch (retorno.status) {
                        case 'erro':
                            var alerta = swal("Erro!", retorno.msg, "error");
                            break;
                        case 'sucesso':
                            var alerta = swal("Sucesso!", retorno.msg, "success");
                            break;
                        case 'alerta':
                            var alerta = swal("Ops!", retorno.msg, "warning");
                            break;
                    }
                    if (retorno.recarrega == 'true') {
                        alerta.then(function () {
                            location.reload();
                        });
                    }
                },
                error: function (request, status, error) {
                    swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function () {
                        $(this).modal('hide');
                    });
                }
            });
        }


    });



    $('.aprovar_proposta').on('click', function () {

        var id_oper = $(this).data('idoper');
        var id_mpme_proposta = $(this).data('idproposta');

        Swal({
            title: "Deseja aprovar a proposta Nº: " + id_mpme_proposta,
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não',
            html: '<div align="left"><br /><label>Número da proposta:</label><br/><input id="numero_proposta" minlength="3" class="form-control" value="" type="text" name="numero_proposta" placeholder="Número da proposta" /></div>',
        }).then((result) => {

            var numero_proposta = id_oper + '.' + id_mpme_proposta + '.';

            if ($("#numero_proposta").val() == numero_proposta) {
                if (result.value) {
                    swal("Ops!", "Por favor informar o número da proposta", "warning").then(function () {
                        return false;
                    });
                }
            } else {
                if (result.value) {
                    $.ajax({
                        type: "POST",
                        method: "POST",
                        url: URL_BASE + 'ajax/aprovar-proposta',
                        data: {
                            'id_mpme_proposta': id_mpme_proposta,
                            'id_oper': id_oper,
                            'in_decisao': '1',
                            'id_mpme_status_proposta': '8',
                            'in_aceite': 'SIM',
                            'nu_proposta': $("#numero_proposta").val(),
                        },
                        context: this,
                        success: function (retorno) {
                            $(".loading").hide();
                            switch (retorno.status) {
                                case 'erro':
                                    var alerta = swal("Erro!", retorno.msg, "error");
                                    break;
                                case 'sucesso':
                                    var alerta = swal("Sucesso!", retorno.msg, "success");
                                    break;
                                case 'alerta':
                                    var alerta = swal("Ops!", retorno.msg, "warning");
                                    break;
                            }
                            if (retorno.recarrega == 'true') {
                                alerta.then(function () {
                                    location.reload();
                                });
                            }
                        },
                        error: function (request, status, error) {
                            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                                location.reload();
                            });
                        }
                    });


                }
            }



        })





    });



    $('.enviar_proposta').on('click', function () {

        var id_oper = $(this).data('idoper');
        var id_mpme_proposta = $(this).data('idproposta');

        Swal({
            title: 'Atenção?',
            text: "Deseja enviar a proposta Nº: " + id_mpme_proposta,
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
                    url: URL_BASE + 'proposta/enviar',
                    data: {
                        'id_oper': id_oper,
                        'id_mpme_proposta': id_mpme_proposta,
                        'id_mpme_status_proposta': 2,
                    },
                    context: this,
                    success: function (retorno) {
                        $(".loading").hide();
                        switch (retorno.status) {
                            case 'erro':
                                var alerta = swal("Erro!", retorno.msg, "error");
                                break;
                            case 'sucesso':
                                var alerta = swal("Sucesso!", retorno.msg, "success");
                                break;
                            case 'alerta':
                                var alerta = swal("Ops!", retorno.msg, "warning");
                                break;
                        }
                        if (retorno.recarrega == 'true') {
                            alerta.then(function () {
                                location.reload();
                            });
                        }
                    },
                    error: function (request, status, error) {
                        swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                            location.reload();
                        });
                    }
                });


            }
        })

    });

    $('#recusarProposta').on('click', function () {

        Swal({
            title: 'Atenção você esta prestes a RECUSAR',
            text: "A proposta Nº: " + $("#id_mpme_proposta").val(),
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
                    url: URL_BASE + 'ajax/recusar-proposta',
                    data: {
                        'id_mpme_proposta': $("#id_mpme_proposta").val(),
                        'id_oper': $("#id_oper").val(),
                        'ds_motivo': $("#ds_motivo").val(),
                        'in_decisao': '2',
                        'in_aceite': 'NAO'
                    },
                    context: this,
                    success: function (retorno) {
                        $(".loading").hide();
                        switch (retorno.status) {
                            case 'erro':
                                var alerta = swal("Erro!", retorno.msg, "error");
                                break;
                            case 'sucesso':
                                var alerta = swal("Sucesso!", retorno.msg, "success");
                                break;
                            case 'alerta':
                                var alerta = swal("Ops!", retorno.msg, "warning");
                                break;
                        }
                        if (retorno.recarrega == 'true') {
                            alerta.then(function () {
                                location.reload();
                            });
                        }
                    },
                    error: function (request, status, error) {
                        swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                            location.reload();
                        });
                    }
                });

            }
        })
    });


    $('div#visualizar-arquivo').on('show.bs.modal', function (event) {
        var id_mpme_arquivo = $(event.relatedTarget).data('idmpmearquivo');
        var no_arquivo = $(event.relatedTarget).data('noarquivo');

        $(this).find('.modal-header h5.modal-title span').html(no_arquivo);

        if (id_mpme_arquivo > 0) {
            $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE + 'validar/visualizar-arquivo',
                data: { 'id_mpme_arquivo': id_mpme_arquivo },
                context: this,
                beforeSend: function () {
                    $(".loading").show();
                },
                success: function (retorno) {
                    $(".loading").hide();
                    $(this).find('.modal-body').html(retorno).fadeIn('fast');
                },
                error: function (request, status, error) {
                    swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function () {
                        $(this).modal('hide');
                    });
                }
            });
        }
    });

    $('div#visualizar-dados-operacao').on('show.bs.modal', function (event) {
        var id_mpme_proposta = $(event.relatedTarget).data('idmpmeproposta');
        var id_oper = $(event.relatedTarget).data('idoper');

        if (id_mpme_proposta == "" || id_oper == "") {
            swal("Ops!", "Dados informados inválidos", "info");
            return false;
        }

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE + 'proposta/dados-questionario',
            data: {
                'id_mpme_proposta': id_mpme_proposta,
                'id_oper': id_oper,
            },
            context: this,
            beforeSend: function () {
                $(this).find('.modal-body').html('');
                $(".loading").show();
            },
            success: function (retorno) {
                $(".loading").hide();
                $(this).find('.modal-body').html(retorno).fadeIn('fast');
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function () {
                    $(this).modal('hide');
                });
            }
        });


    });


    $('div#visualizar-dados-proposta').on('show.bs.modal', function (event) {
        var id_mpme_proposta = $(event.relatedTarget).data('idproposta');
        var id_oper = $(event.relatedTarget).data('idoper');

        if (id_mpme_proposta == "" || id_oper == "") {
            swal("Ops!", "Dados informados inválidos", "info");
            return false;
        }

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE + 'proposta/dados-proposta',
            data: {
                'id_mpme_proposta': id_mpme_proposta,
                'id_oper': id_oper,
            },
            context: this,
            beforeSend: function () {
                $(this).find('.modal-body').html('');
                $(".loading").show();
            },
            success: function (retorno) {
                $(".loading").hide();
                $(this).find('.modal-body').html(retorno).fadeIn('fast');
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function () {
                    $(this).modal('hide');
                });
            }
        });


    });




});

function processarCalculadora(id_oper, id_mpme_proposta) {

    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE + 'precificacao/precificarValor',
        data: { 'tp_calculo': 'analista', 'id_oper': id_oper, 'id_mpme_proposta': id_mpme_proposta },
        context: this,
        success: function (retorno) {
            $(".loading").hide();
            switch (retorno.status) {
                case 'erro':
                    var alerta = swal("Erro!", retorno.msg, "error");
                    break;
                case 'sucesso':
                    var html = '<div class="alert alert-info"><strong>Valor solicitado:</strong><br><h3>' + retorno.resposta.SIGLA_MOEDA + ' ' + retorno.resposta.VL_SOLICITADO + '</h3></div>' +
                        '<div class="alert alert-info"><strong>Taxa do prêmio:</strong><br><h3>' + retorno.resposta.PC_COB_MIN + '%</h3></div>' +
                        '<div class="alert alert-success alert-valores"><strong>Valor do prêmio comercial:</strong><br><h3>' + retorno.resposta.SIGLA_MOEDA + ' ' + retorno.resposta.VL_COBERTURA_IMP_FORMATADO + '</h3></div>' +
                        '<br><div class="alert alert-warning"><strong>Observação: </strong>O valor acima é apenas uma simulação, podendo ser recalculdado pela ABGF.</div>';
                    $('div#nova-precificacao').find('.modal-body').html(html);
                    break;
                case 'alerta':
                    var alerta = swal("Ops!", retorno.msg, "warning");
                    break;
            }
            if (retorno.recarrega == 'true') {
                alerta.then(function () {
                    location.reload();
                });
            }
        },
        error: function (request, status, error) {
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                //location.reload();
            });
        }
    });
}


function processarCalculadoraSimulacao() {
    $('.modal-body').html('');
    if (validarForm()) {
        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE + 'precificacao/precificarValorSimulacao',
            data: $("#frmProposta").serialize(),
            context: this,
            success: function (retorno) {
                $(".loading").hide();
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!", retorno.msg, "error");
                        break;
                    case 'sucesso':
                        var html = '<div class="alert alert-info"><strong>Valor solicitado:</strong><br><h3>' + retorno.resposta.SIGLA_MOEDA + ' ' + retorno.resposta.VL_SOLICITADO + '</h3></div>' +
                            '<div class="alert alert-info"><strong>Taxa do prêmio:</strong><br><h3>' + retorno.resposta.PC_COB_MIN + '%</h3></div>' +
                            '<div class="alert alert-success alert-valores"><strong>Valor do prêmio comercial:</strong><br><h3>' + retorno.resposta.SIGLA_MOEDA + ' ' + retorno.resposta.VL_COBERTURA_IMP_FORMATADO + '</h3></div>' +
                            '<br><div class="alert alert-warning"><strong>Observação: </strong>O valor acima é apenas uma simulação, podendo ser recalculdado pela ABGF.</div>';
                        $('div#nova-precificacao-simulacao').find('.modal-body').html(html);
                        $("#btnCadastrar").removeAttr('disabled');
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!", retorno.msg, "warning");
                        break;
                }
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                    //location.reload();
                });
            }
        });
    }
}


function validarForm() {
    var erros = new Array();

    if ($('#id_cliente_exportadores_modalidade').val() == "0") {
        erros.push('O campo Modalidade da proposta deve ser preenchido.');
    }

    if ($('#in_aceite').val() == "") {
        erros.push('O campo Aceite deve ser preenchido.');
    }

    if ($('#id_setor').val() == 0) {
        erros.push('O campo Setor de atividade deve ser preenchido.');
    }

    if ($('#vl_proposta').val() == "") {
        erros.push('O campo Valor da proposta deve ser preenchido.');
    }


    if ($('#id_setor').find(':selected').data('idrestricao') == 1) {
        if ($('#in_aceite_restricoes').is(':checked') == false) {
            erros.push('Favor aceitar as condições de restrições.');
        }
    }

    if ($('#id_cliente_exportadores_modalidade').val() != "") {
        var dados = $('#id_cliente_exportadores_modalidade').val().split('#');
        var id_modalidade = dados[1];
        var id_financiamento = dados[2];

        switch (id_modalidade) {
            case "1": //pre
                if ($('#nu_prazo_pre').val() == "") {
                    erros.push('O campo Prazo-pré deve ser preenchido.');
                }
                break;

            case "2": //pre+pos
                if ($('#nu_prazo_pre').val() == "") {
                    erros.push('O campo Prazo-pré deve ser preenchido.');
                }
                if ($('#nu_prazo_pos').val() == "") {
                    erros.push('O campo Prazo-pós deve ser preenchido.');
                }
                break;

            case "3": //pos
                if ($('#nu_prazo_pos').val() == "") {
                    erros.push('O campo prazo Prazo-pós deve ser preenchido');
                }
                break;
        }

        if (id_financiamento == 4) {
            if ($("#in_aceite").val() != 'SIM' && $("#va_percentual_dw_payment").val() == "") {
                erros.push('Caso a modalidade seja com Recursos próprios o cliente deve escolher Aceite como "SIM" ou informar um % de Down Payment');
            }
        }

        $(".prazo").each(function () {
            if ($(this).val() != "") {
                if ($(this).val() > 180) {
                    erros.push('O prazo informado não pode ultrapassar 180 dias!');
                } else if ($(this).val() < 30) {
                    erros.push('O prazo informado não pode ser menor que 30 dias!');
                }
            }
        })

    }

    var valor_atual = $("#vl_proposta").maskMoney('unmasked')[0];

    if (valor_atual != "") {
        var novo_valor = valor_atual;
        var percentual = parseFloat($("#va_percentual_dw_payment").val());

        if (percentual > 0) {
            novo_valor = valor_atual - (valor_atual * (percentual / 100));
        }

        if (novo_valor > $("#vl_saldo").val()) {
            $("#vl_proposta").val('');
            erros.push('O valor da proposta não pode ultrapassar o saldo!');
        }
    }


    if (erros.length > 0) {
        swal('Ops!', erros.join('<br />'), 'info')

        return false;
    }

    return true;
}


function retorno_arquivo_comprovante(retorno, modal) {
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE + 'proposta/arquivos/inserir-comprovante-boleto-premio',
        context: this,
        data: { 'token': retorno.token, 'id_mpme_proposta': $('#id_flex').val(), 'id_oper': $('#id_oper').val() },
        success: function (retorno) {
            $(".loading").hide();
            modal.modal('hide');
            switch (retorno.status) {
                case 'erro':
                    var alerta = swal("Erro!", retorno.msg, "error");
                    break;
                case 'sucesso':
                    var alerta = swal("Sucesso!", retorno.msg, "success");
                    break;
                case 'alerta':
                    var alerta = swal("Ops!", retorno.msg, "warning");
                    break;
            }
            if (retorno.recarrega == 'true') {
                alerta.then(function () {
                    location.reload();
                });
            }
        },
        error: function (request, status, error) {
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                location.reload();
            });
        }
    });

}


function retorno_arquivo(retorno, modal) {
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE + 'abgf/arquivos/inserir-boleto-premio',
        context: this,
        data: { 'token': retorno.token, 'id_mpme_proposta': $('#id_flex').val(), 'id_oper': $('#id_oper').val() },
        success: function (retorno) {
            $(".loading").hide();
            modal.modal('hide');
            switch (retorno.status) {
                case 'erro':
                    var alerta = swal("Erro!", retorno.msg, "error");
                    break;
                case 'sucesso':
                    var alerta = swal("Sucesso!", retorno.msg, "success");
                    break;
                case 'alerta':
                    var alerta = swal("Ops!", retorno.msg, "warning");
                    break;
            }
            if (retorno.recarrega == 'true') {
                alerta.then(function () {
                    location.reload();
                });
            }
        },
        error: function (request, status, error) {
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                location.reload();
            });
        }
    });

}
function retorno_arquivo_cg(retorno, modal) {
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE + 'abgf/arquivos/inserir-cg',
        context: this,
        data: { 'token': retorno.token, 'id_mpme_proposta': $('#id_flex').val(), 'id_oper': $('#id_oper').val() },
        success: function (retorno) {
            $(".loading").hide();
            modal.modal('hide');
            switch (retorno.status) {
                case 'erro':
                    var alerta = swal("Erro!", retorno.msg, "error");
                    break;
                case 'sucesso':
                    var alerta = swal("Sucesso!", retorno.msg, "success");
                    break;
                case 'alerta':
                    var alerta = swal("Ops!", retorno.msg, "warning");
                    break;
            }
            if (retorno.recarrega == 'true') {
                alerta.then(function () {
                    location.reload();
                });
            }
        },
        error: function (request, status, error) {
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                location.reload();
            });
        }
    });

}

function retorno_arquivo_cg_assinado(retorno, modal) {
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE + 'abgf/arquivos/inserir-cg-assinado',
        context: this,
        data: { 'token': retorno.token, 'id_mpme_proposta': $('#id_flex').val(), 'id_oper': $('#id_oper').val() },
        success: function (retorno) {
            $(".loading").hide();
            modal.modal('hide');
            switch (retorno.status) {
                case 'erro':
                    var alerta = swal("Erro!", retorno.msg, "error");
                    break;
                case 'sucesso':
                    var alerta = swal("Sucesso!", retorno.msg, "success");
                    break;
                case 'alerta':
                    var alerta = swal("Ops!", retorno.msg, "warning");
                    break;
            }
            if (retorno.recarrega == 'true') {
                alerta.then(function () {
                    location.reload();
                });
            }
        },
        error: function (request, status, error) {
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                location.reload();
            });
        }
    });
}

function retorno_arquivo_apolice(retorno, modal) {
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE + 'abgf/arquivos/inserir-apolice',
        context: this,
        data: { 'token': retorno.token, 'id_mpme_proposta': $('#id_flex').val(), 'id_oper': $('#id_oper').val() },
        success: function (retorno) {
            $(".loading").hide();
            modal.modal('hide');
            switch (retorno.status) {
                case 'erro':
                    var alerta = swal("Erro!", retorno.msg, "error");
                    break;
                case 'sucesso':
                    var alerta = swal("Sucesso!", retorno.msg, "success");
                    break;
                case 'alerta':
                    var alerta = swal("Ops!", retorno.msg, "warning");
                    break;
            }
            if (retorno.recarrega == 'true') {
                alerta.then(function () {
                    location.reload();
                });
            }
        },
        error: function (request, status, error) {
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                location.reload();
            });
        }
    });

}

function retorno_arquivo_apolice_assinada(retorno, modal) {
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE + 'abgf/arquivos/inserir-apolice-assinada',
        context: this,
        data: { 'token': retorno.token, 'id_mpme_proposta': $('#id_flex').val(), 'id_oper': $('#id_oper').val() },
        success: function (retorno) {
            $(".loading").hide();
            modal.modal('hide');
            switch (retorno.status) {
                case 'erro':
                    var alerta = swal("Erro!", retorno.msg, "error");
                    break;
                case 'sucesso':
                    var alerta = swal("Sucesso!", retorno.msg, "success");
                    break;
                case 'alerta':
                    var alerta = swal("Ops!", retorno.msg, "warning");
                    break;
            }
            if (retorno.recarrega == 'true') {
                alerta.then(function () {
                    location.reload();
                });
            }
        },
        error: function (request, status, error) {
            swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                location.reload();
            });
        }
    });
}
