$(document).ready(function(){
    $('div#novo-desembolso').on('show.bs.modal', function (event)
    {
        var id_proposta          =  $(event.relatedTarget).data('id_proposta');

        $(this).find('.modal-body').html('');
        $(".loading").show();

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'banco/desembolso/novo-desembolso',
            data: {'id_proposta':id_proposta},
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

    $('div#alterar_desembolso').on('show.bs.modal', function (event)
    {
        var id_proposta          =  $(event.relatedTarget).data('idmpmeproposta');
        var id_desembolso        =  $(event.relatedTarget).data('idmpmedesembolso');


        if ( id_proposta  == "" || id_desembolso == "")
        {
            swal('Ops!', 'Parametros informados inválidos', 'info' );
            return false;
        }

        $(this).find('.modal-body').html('');
        $(".loading").show();

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'banco/desembolso/alterar-desembolso',
            data: {'id_mpme_proposta':id_proposta, 'id_mpme_desembolso':id_desembolso},
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


    $('div#recusar-desembolso').on('show.bs.modal', function (event)
    {
        var id_mpme_desembolso   =  $(event.relatedTarget).data('idmpmedesembolso');
        $("#id_mpme_desembolso").val(id_mpme_desembolso);

        $(".loading").hide();
    });


    $('.aprovar_desembolso').on('click',  function()
    {
        var id_mpme_desembolso   =  $(this).data('idmpmedesembolso');
        var id_proposta          =  $(this).data('idproposta');

        if (id_mpme_desembolso == ""){
            swal('Ops!', 'Parametros informados inválidos', 'info' );
            return false;
        }



        Swal({
            title: 'Atenção?',
            text: "Deseja aprovar o desembolso Nº: "+id_mpme_desembolso,
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
                    url: URL_BASE+'banco/desembolso/aprovar',
                    data: {
                        'id_mpme_desembolso'  : id_mpme_desembolso,
                        'id_mpme_status'  : 2,
                        'id_proposta': id_proposta,
                    },
                    context: this,
                    success: function(retorno) {
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
                        swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                            location.reload();
                        });
                    }
                });
            }

        });



    });

    $('div#recusar-desembolso').on('click', '#recusarDesembolso', function (event)
    {
        var erros = new Array();

        if ( $('#ds_motivo').val() == "")
        {
            erros.push('O motivo deve ser informado.');
        }

        if (erros.length>0)
        {
            swal('Ops!', erros.join('<br />'), 'info' )
            return false;
        }

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'banco/desembolso/recusar',
            data: {
                    'id_mpme_desembolso': $("#id_mpme_desembolso").val(),
                    'id_mpme_status'    : 3,
                    'ds_motivo'         : $("#ds_motivo").val()
            },
            context: this,
            success: function(retorno){
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
                    //  location.reload();
                });
            }
        });
    });




    $('div#novo-desembolso, div#alterar_desembolso').on('blur', '#dt_desembolso',function (){
        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'ajax/calcular-data',
            data: {'dt_inicial':$(this).val(), 'nu_prazo': $("#nu_prazo_pre").val() },
            context: this,
            success: function(retorno){
                $("#dt_vencimento").val(retorno);
            },
            error: function (request, status, error) {
                //console.log(request.responseText);
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                    //  location.reload();
                });
            }
        });
    });


    $('div#novo-desembolso').on('click', '#btnSalvar',function (){

        if (validarForm() == true)
        {
            $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE+'banco/desembolso/salvar',
                data: $("#frmDesembolso").serialize(),
                context: this,
                success: function(retorno){
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
                        //  location.reload();
                    });
                }
            });
        }

    });

    $('div#alterar_desembolso').on('click', '#btnAlterar',function (){

        if (validarForm() == true)
        {
            $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE+'banco/desembolso/salvar',
                data: $("#frmDesembolso").serialize(),
                context: this,
                success: function(retorno){
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
                        //  location.reload();
                    });
                }
            });
        }

    });


    $('div#historico_desembolso').on('show.bs.modal', function (event)
    {
        var id_mpme_desembolso =  $(event.relatedTarget).data('iddesembolso');

        if ( id_mpme_desembolso == "" )
        {
            swal('Ops!', 'Dados informados inválidos!', 'info' );
            return false;
        }

        $(this).find('.modal-body').html('');

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'banco/desembolso/historico-desembolso',
            data: {
                'id_mpme_desembolso'  : id_mpme_desembolso
            },
            context: this,
            success: function(retorno) {
                $(".loading").hide();
                $(this).find('.modal-body').html(retorno);
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                    location.reload();
                });
            }
        });





    });

});

function validarForm()
{
    var erros = new Array();

    if ( $('#dt_desembolso').val() == "")
    {
        erros.push('O campo Data de desembolso.');
    }

    if ( $('#dt_vencimento').val() == "")
    {
        erros.push('O campo Data de vencimento.');
    }

    if ( $('#vl_proposta').val() == "")
    {
        erros.push('O campo valor.');
    }

    if ( $('#vl_desembolso').val() == "")
    {
        erros.push('O campo valor do desembolso.');
    }


    if (erros.length>0)
    {
        swal('Ops!', erros.join('<br />'), 'info' )

        return false;
    }

    return true;
}
