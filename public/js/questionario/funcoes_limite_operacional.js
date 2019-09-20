$(document).ready(function(){

    $(".perc_fundo").on('blur', function () {
       var obj                  = $(this);
       var id_fundo             = obj.data('fundo');
       var vl_cred_concedido    = $("#vl_cred_concedido").val();
           vl_cred_concedido    = replace_all(vl_cred_concedido,".", "");
           vl_cred_concedido    = replace_all(vl_cred_concedido,",", ".");
       var tx_cotacao           = $("#tx_cotacao").val();

       if (obj.val() > 100){
           obj.val('');
           swal("Ops!",'O limite não pode ultrapassar 100%',"warning");
       }

        var controle_fundo = ( id_fundo == 1 ) ? parseInt(id_fundo) +  parseInt(1) : parseInt(id_fundo) -  parseInt(1);

        if (obj.val() == 100)
        {
            $("#vl_perc_fundo_"+controle_fundo).attr('readonly', true).val('0.00');
            $("#in_saldo_suficiente_"+controle_fundo).attr('disabled', true).val('SIM');
            $("#vl_total_real_"+controle_fundo).val('0.00');

            $("#id_mpme_fundo_garantia_operacao").val(id_fundo);

        }else{
            if (id_fundo != 2)
            {
                if ( $("#vl_perc_fundo_"+controle_fundo).val() == "" ||  $("#vl_perc_fundo_"+controle_fundo).val() == '0.00')
                {
                    $("#vl_perc_fundo_"+controle_fundo).removeAttr('readonly').val('');
                    $("#in_saldo_suficiente_"+controle_fundo).removeAttr('disabled').val(0);
                    $("#vl_total_real_"+controle_fundo).val('0.00');
                }
            }
        }

        var indice   = (obj.val()/100);
        var total    = (vl_cred_concedido*tx_cotacao)*indice;

       $("#vl_total_real_"+id_fundo).val(total.toFixed(2));
    });

    $("#btnGravarLimiteOperacional").on('click', function() {

       if (validarForm())
       {
            $.ajax({
                type: "POST",
                url: URL_BASE + 'abgf/exportador/analisalimite/operacional',
                data: $("#frmControleCapital").serialize(),
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


    $("#id_mpme_fundo_garantia_operacao").on('change', function () {

        $("#vl_perc_fundo_1").removeAttr('readonly');
        $("#in_saldo_suficiente_1").removeAttr('disabled');

        $("#vl_perc_fundo_2").removeAttr('readonly');
        $("#in_saldo_suficiente_2").removeAttr('disabled');

        if ( $(this).val() == 2)
        {
            $("#vl_perc_fundo_1").val('0.00').attr('readonly', true);
            $("#vl_total_real_1").val('0.00');
            $("#in_saldo_suficiente_1").val('SIM').attr('disabled', true);

            $("#vl_perc_fundo_2").val('100.00');
            $("#vl_perc_fundo_2").trigger('blur');
            $("#in_saldo_suficiente_2").val('SIM').attr('disabled', true);

        }else{

            if ( $("#vl_perc_fundo_1").val() == "" || $("#vl_perc_fundo_1").val() == '0.00')
            {
                $("#vl_perc_fundo_1").val('0.00').removeAttr('disabled');
                $("#vl_total_real_1").val('0.00');
                $("#in_saldo_suficiente_1").val(0).removeAttr('disabled');
            }

            if ( $("#vl_perc_fundo_2").val() == "" || $("#vl_perc_fundo_2").val() == '0.00') {
                $("#vl_perc_fundo_2").val('0.00');
                $("#vl_total_real_2").val('0.00');
                $("#in_saldo_suficiente_2").val(0);
            }else if($('#vl_perc_fundo_2').val() == '100.00'){
                $("#vl_perc_fundo_2").val('0.00');
                $("#vl_total_real_2").val('0.00');
                $("#in_saldo_suficiente_2").val(0);
            }
        }
    })

});


function validarForm()
{
    var erros = new Array();
    var total = 0.0;
    var i     = 0;
    var j     = 0;

    if ($("#id_mpme_fundo_garantia_operacao").val() == 0){
        erros.push('Fundo principal para operação.');
    }

    $(".perc_fundo").each(function(){
        if ($(this).val() != "")
        {
            total = parseFloat(total) + parseFloat($(this).val());
        }else{
            total = parseFloat(total) + parseFloat(0);
        }

    });

    if ( total < 100 || total > 100 )
    {
        erros.push('O valor total dos fundos não podem ser superior ou menor a 100%.');
    }

    $(".in_saldo_suficiente").each(function(){
        if ( $(this).val() == 'NAO' )
        {
            i++;
        }
        if ( $(this).val() == 0 )
        {
            j++;
        }

    });


    if ( i < 2 )
    {
        $("#in_mpme_status").val();
    }else{
        $("#in_mpme_status").val();
    }

    if ( j > 0 )
    {
        erros.push('Favor selecionar se os dois fundos tem saldo suficiente.');
    }


    if ($("#in_saldo_suficiente_exp").val() == 0){
        erros.push('Favor selecionar se tem saldo suficiente no controle da Exportação.');
    }

    if ($("#parecer_exp").val() == ""){
        erros.push('O parecer deve ser preenchido.');
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


    if (i > 0  || $("#in_saldo_suficiente_exp").val() == "NAO")
    {
        $("#st_oper").val(21);
    }else{
        $("#st_oper").val(12);
    }

    $(".in_saldo_suficiente").each(function(){
       $(this).removeAttr('disabled');
    });

    return true;
}

function replace_all(string,encontrar,substituir){
    while(string.indexOf(encontrar)>=0)
        string = string.replace(encontrar,substituir);
    return string;
}