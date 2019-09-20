$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });



    $('.modalLoading .modal-body').hide();
    $(".money").maskMoney({
        decimal: ",",
        thousands: "."
    });

    $('.datetimepicker4').datetimepicker({
        format: 'DD/MM/YYYY',
        widgetPositioning: {
            vertical: 'bottom',
            horizontal: 'left'
        }
    });

    $('div#aterar-senha').on('show.bs.modal', function (event)
    {
        $(".loading").hide();
    });

    $("#no_nova_senha").on('blur', function () {
        if ($("#no_repetir_senha").val() != "")
        {
            if ( $(this).val() !=  $("#no_repetir_senha").val())
            {
                swal('Ops!', 'Senhas não coincidem!', 'info' );
                $(this).val('');
                return false;
            }
        }
    })

    $("#no_repetir_senha").on('blur', function () {
        if ($("#no_nova_senha").val() != "")
        {
            if ( $(this).val() !=  $("#no_nova_senha").val())
            {
                swal('Ops!', 'Senhas não coincidem!', 'info' );
                $(this).val('');
                return false;
            }
        }
    })

    $(".btnAlterarSenha").on('click', function () {

        if ( $("#no_senha_atual").val() == "")
        {
            swal('Ops!', 'Favor informar a senha atual!', 'info' );
            $(this).val('');
            return false;
        }

        if ( $("#no_nova_senha").val() == "")
        {
            swal('Ops!', 'Favor informar a nova senha!', 'info' );
            $(this).val('');
            return false;
        }

        if ( $("#no_repetir_senha").val() == "")
        {
            swal('Ops!', 'Favor informar o campo repetir senha!', 'info' );
            $(this).val('');
            return false;
        }


        $("#no_nova_senha").on('blur', function () {
            if ($("#no_repetir_senha").val() != "")
            {
                if ( $(this).val() !=  $("#no_repetir_senha").val())
                {
                    swal('Ops!', 'Senhas não coincidem!', 'info' );
                    $(this).val('');
                    return false;
                }
            }
        })

        $("#no_repetir_senha").on('blur', function () {
            if ($("#no_nova_senha").val() != "")
            {
                if ( $(this).val() !=  $("#no_nova_senha").val())
                {
                    swal('Ops!', 'Senhas não coincidem!', 'info' );
                    $(this).val('');
                    return false;
                }
            }
        })

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'usuario/alterar-senha',
            data: {
                'no_senha_atual'    : $('#no_senha_atual').val(),
                'no_nova_senha'     : $('#no_nova_senha').val(),
                'no_repetir_senha'  : $('#no_repetir_senha').val(),
            },
            context: this,
            beforeSend: function() {
                $(".loading").show();
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
                }else if(retorno.recarrega=='url'){
                    alerta.then(function(){
                        window.location = URL_BASE+retorno.url;
                    });
                }
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function() {
                    $(this).modal('hide');
                });
            }
        });

    })


    $(".phone").mask("(999) 999-9999");
    $(".cnpj").mask("99.999.999/9999-99");
    $(".mobile").mask("(999) 999-9999");
    $(".tin").mask("99-9999999");
    $(".ssn").mask("999-99-9999");
    $('.somentenumero').mask('0#');
    $('.percentual').mask('##0.00', {reverse: true}).attr('maxlength','6');

    
    $(".marcar_como_lida").on('click', function(){

        var id_oper                     =  $(this).data('idoper');
        var id_mpme_tipo_notificacao    =  $(this).data('idmpmetiponotificacao');


        Swal({
            title: 'Atenção?',
            text: "Deseja marcar como lida esta notificação da OPERAÇÃO: "+id_oper,
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
                    url: URL_BASE+'visualizar-notificacao',
                    data: {
                        'id_oper': id_oper,
                        'id_mpme_tipo_notificacao': id_mpme_tipo_notificacao,
                    },
                    context: this,
                    beforeSend: function() {
                        $(".loading").show();
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
                    error: function (request, status, error) {
                        swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function() {
                            $(this).modal('hide');
                        });
                    }
                });

            }
        })

    })

 
    $('.modalLoading').on('shown.bs.modal',function (e){
          $('.modalLoading .loading').hide();  
          $('.modalLoading .modal-body').show(); 
    });



})

/*
* FUNCOES AUXILIARES
*/

function replace_all(string,encontrar,substituir){
    while(string.indexOf(encontrar)>=0)
        string = string.replace(encontrar,substituir);
    return string;
}    


function formatMoney(n, c, d, t) {
    c = isNaN(c = Math.abs(c)) ? 2 : c, d = d == undefined ? "," : d, t = t == undefined ? "." : t, s = n < 0 ? "-" : "", i = parseInt(n = Math.abs(+n || 0).toFixed(c)) + "", j = (j = i.length) > 3 ? j % 3 : 0;
    return s + (j ? i.substr(0, j) + t : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + t) + (c ? d + Math.abs(n - i).toFixed(c).slice(2) : "");
  }


