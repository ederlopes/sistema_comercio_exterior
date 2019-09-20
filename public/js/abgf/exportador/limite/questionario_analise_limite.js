$(document).ready(function(){

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

    $('div#limite_operacional').on('show.bs.modal', function (event) {
        var id_oper = $(event.relatedTarget).data('idoper');

        if (id_oper>0)
        {
            $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE+'abgf/exportador/limite-operacional',
                data: {'id_oper':id_oper},
                context: this,
                beforeSend: function() {
                    $(".loading").show();
                    $(this).find('.modal-body').html('');
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


})


function retorno_arquivo(retorno,modal)
{
    $.ajax({
        type: "POST",
        method: "POST",
        url: URL_BASE+'abgf/arquivos/inserir-boleto-relatorio',
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