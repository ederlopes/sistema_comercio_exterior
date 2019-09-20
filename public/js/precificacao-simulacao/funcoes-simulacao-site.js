$(document).ready(function () {

    $("#id_modalidade").on('change', function () {
        var id_modalidade = $(this).val();

        switch (id_modalidade) {
            case "1": //pre
                $("#prazo_dias_pos").hide();
                $("#prazo_dias_pre").show();
                $("#nu_prazo_pos").val('');
                $("#downpayment").hide();
                $("#div_importadores").hide();
                break;

            case "2": //pre+pos
                $("#prazo_dias_pos").show();
                $("#prazo_dias_pre").show();
                $("#nu_prazo_pre").val('');
                $("#nu_prazo_pos").val('');
                $("#downpayment").show();
                $("#div_importadores").show();
                break;

            case "3": //pos
                $("#prazo_dias_pos").show();
                $("#prazo_dias_pre").hide();
                $("#nu_prazo_pre").val('');
                $("#div_importadores").show();
                $("#downpayment").show();
                break;
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

    $("#vl_proposta").on('blur', function () {
        $("#vl_financiado").val($(this).val());
    })


    $("#va_percentual_dw_payment, #vl_proposta").on('blur', function () {
        var valor_atual = $("#vl_proposta").maskMoney('unmasked')[0];
        if (valor_atual != "") {
            var novo_valor = valor_atual;
            var percentual = parseFloat($("#va_percentual_dw_payment").val());

            novo_valor = valor_atual - (valor_atual * (percentual / 100));

            if ($("#va_percentual_dw_payment").val() != "") {
                $("#vl_financiado").val(novo_valor);
            }
        }
    })

    $("#btnSimularSite").on('click', function () {
        if (validarForm()) {
            processarCalculadoraSimulacao();

        }
    });

});


function processarCalculadoraSimulacao() {
    if (validarForm()) {
        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE + 'precificacao/precificarValorSimulacaoSite',
            data: $("#frmSimulacaoSite").serialize(),
            context: this,
            beforeSend: function () {
                $("#resultado_precificacao").show();
                $(".loading").show();
                $("#resultado").html('');
            },
            success: function (retorno) {
                $(".loading").hide();
                $("#resultado").html(retorno);
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Servidores ocupados", "error").then(function () {
                    //location.reload();
                });
            }
        });
    }
}


function validarForm() {
    var erros = new Array();
    var id_modalidade = $('#id_modalidade').val();

    if (id_modalidade == "0") {
        erros.push('O campo Modalidade deve ser preenchido.');
    }

    if ($('#id_moeda').val() == "") {
        erros.push('O campo moeda deve ser preenchido.');
    }

    if ($('#vl_proposta').val() == "") {
        erros.push('O campo valor da proposta deve ser preenchido.');
    }

    if ($('#vl_financiado').val() == "") {
        erros.push('O campo valor de financiando deve ser preenchido.');
    }

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
            if ($('#id_pais_importador').val() == "") {
                erros.push('O campo país do importador.');
            }
            break;

        case "3": //pos
            if ($('#nu_prazo_pos').val() == "") {
                erros.push('O campo prazo Prazo-pós deve ser preenchido');
            }
            if ($('#nu_prazo_pos').val() == "") {
                erros.push('O campo Prazo-pós deve ser preenchido.');
            }
            break;
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


    if (erros.length > 0) {
        swal('Ops!', erros.join('<br />'), 'info')

        return false;
    }

    return true;
}

