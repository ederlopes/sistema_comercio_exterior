$(document).ready(function () {

    $("#ckd_tabela_operacoes").on('click', function(){
        if ($(this).is(":checked")){
           $("#tabela_operacoes").prop('disabled', false).selectpicker('refresh');
        }else{
            $("#tabela_operacoes").prop('disabled', true).val('').selectpicker('refresh');
        }
    })

    $("#ckd_tabela_status_operacao").on('click', function(){
        if ($(this).is(":checked")){
            $("#tabela_status_operacao").prop('disabled', false).selectpicker('refresh');
        }else{
            $("#tabela_status_operacao").prop('disabled', true).val('').selectpicker('refresh');
        }
    })

    $("#ckd_tabela_proposta").on('click', function(){
        if ($(this).is(":checked")){
            $("#tabela_proposta").prop('disabled', false).selectpicker('refresh');
        }else{
            $("#tabela_proposta").prop('disabled', true).val('').selectpicker('refresh');
        }
    })

    $("#ckd_tabela_status_proposta").on('click', function(){
        if ($(this).is(":checked")){
            $("#tabela_status_proposta").prop('disabled', false).selectpicker('refresh');
        }else{
            $("#tabela_status_proposta").prop('disabled', true).val('').selectpicker('refresh');
        }
    })

    $("#ckd_tabela_credit_score_importador").on('click', function(){
        if ($(this).is(":checked")){
            $("#tabela_credit_score_importador").prop('disabled', false).selectpicker('refresh');
        }else{
            $("#tabela_credit_score_importador").prop('disabled', true).val('').selectpicker('refresh');
        }
    })

    $("#ckd_tabela_credit_score_exportador").on('click', function(){
        if ($(this).is(":checked")){
            $("#tabela_credit_score_exportador").prop('disabled', false).selectpicker('refresh');
        }else{
            $("#tabela_credit_score_exportador").prop('disabled', true).val('').selectpicker('refresh');
        }
    })



    $("#btnGerarRelatorio").on('click', function () {

        if (validarForm())
        {

            if ($("#ckd_pdf").is(":checked"))
            {
                $("#frmRelatorio").submit();
            }else{
                $.ajax({
                    type: "POST",
                    method: "POST",
                    url: URL_BASE + 'abgf/relatorios/gerar',
                    data: $("#frmRelatorio").serialize(),
                    context: this,
                    beforeSend: function () {
                        $("#resultado_relatorio").show();
                        $(".loading").show();
                        $("#resultado").html('');
                    },
                    success: function (response, status, xhr) {
                        $(".loading").hide();
                        $("#resultado").html(response);

                    },
                    error: function (request, status, error) {
                        swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function () {
                            //location.reload();
                        });
                    }
                });
            }



        }


    })
});


function validarForm()
{
    var erros = new Array();


    if ($('#tabela_usuario').val() == "")
    {
        erros.push('Você deve escolher pelo menos 01 campo da tabela de usuários.');
    }

    if ($("#ckd_tabela_operacoes").is(":checked"))
    {
        if ($('#tabela_operacoes').val() == "")
        {
            erros.push('Você deve escolher pelo menos 01 campo da tabela de operações.');
        }
    }

    if ($("#ckd_tabela_status_operacao").is(":checked"))
    {
        if ($('#tabela_status_operacao').val() == "")
        {
            erros.push('Você deve escolher pelo menos 01 campo da tabela de operações.');
        }
    }

    if ($("#ckd_tabela_proposta").is(":checked"))
    {
        if ($('#tabela_proposta').val() == "")
        {
            erros.push('Você deve escolher pelo menos 01 campo da tabela de operações.');
        }
    }

    if ($("#ckd_tabela_status_proposta").is(":checked"))
    {
        if ($('#tabela_status_proposta').val() == "")
        {
            erros.push('Você deve escolher pelo menos 01 campo da tabela de operações.');
        }
    }

    if (erros.length > 0) {
        swal('Ops!', erros.join('<br />'), 'info');

        return false;
    }

    return true;
}
