$(document).ready(function(){

  var alcadaAtual =  $('#ID_MPME_ALCADA').val();
  //preenche campos via ajax

  $('#DS_RECOMENDACAO').val($('#'+alcadaAtual+' #ds_recomendacao').val());
  $('#IC_INDEFERIDA').val($('#'+alcadaAtual+' #IC_INDEFERIDA').val());
  $('#DT_RECOMENDACAO').val($('#'+alcadaAtual+' .DT_RECOMENDACAO').val());
  $('#VL_CRED_CONCEDIDO').val($('#'+alcadaAtual+' #vl_cred_concedido').val());
  $('#IN_DECISAO').val($('#'+alcadaAtual+' #ds_recomendacao').val());
  $('#VL_APROVADO').val($('#'+alcadaAtual+' #vl_cred_concedido').val());
  $('#NO_ALCADA').val($('#'+alcadaAtual+' #NO_ALCADA').val());
  $('#ID_CREDIT').val($('#'+alcadaAtual+' #ID_CREDIT').val());
  $('#DS_PARECER').val($('#'+alcadaAtual+' .ds_parecer').val());


    $('#encaminhar').on('click',function(event){
      event.preventDefault();

      if($('#CODIGO_UNICO_IMPORTADOR').val() == 0){
          var alerta = swal("Ops!","Você precisa cadastrar o importador como único","warning");

          alerta.then(function(){
              location.reload();
          });

          return false;

      }

      if($('#DS_RECOMENDACAO').val() == "" || $('#DT_RECOMENDACAO').val() == "" || $('#VL_CRED_CONCEDIDO').val() == "" ){
          $.toast({
          heading: 'Credit-Score & Parecer',
          text: 'Salve o credit-score e o parecer antes de encaminhar',
          showHideTransition: 'slide',
          icon: 'error',
          position : 'top-right',
          hideAfter: 5000
        });

      }else{
        $('#ID_MPME_FUNDO_GARANTIA').val($('#'+alcadaAtual+' #id_mpme_fundo_garantia').val());
        $('#frmEncaminharAlcada').submit();
      }

    });

    $('#frmEncaminharAlcada').on('submit', function(event){
     event.preventDefault();

     var rota = $(this).attr('action');
       $.ajax({
         url:rota,
         method:"POST",
         data:new FormData(this),
         dataType:'JSON',
         contentType:false,
         cache:false,
         processData: false,
         success:function(data)
         {
           $.toast({
             heading: data.header,
             text: data.message,
             showHideTransition: 'slide',
             icon: data.class_mensagem,
             position : 'top-right',
             hideAfter: 5000
           });

             window.location="/abgf/exportador/analisalimite";

         },
         error:function(data){
           $.toast({
             heading: 'Erro',
             text: 'Ocorreu um erro ao encaminhar',
             showHideTransition: 'slide',
             icon: 'error',
             position : 'top-right',
             hideAfter: 5000
           });
         },
           beforeSend:function(){
               $('#encaminhar').text(' Encaminhando...').prop('disabled', true);
           },
           complete:function(){
               $('#encaminhar').text(' Encaminhar').prop('disabled', false);
           }
       });

    });

    $('div#visualizar-dados-operacao').on('show.bs.modal', function (event) {
        var id_oper          = $(event.relatedTarget).data('idoper');

        if (  id_oper == "")
        {
            swal("Ops!", "Dados informados inválidos", "info");
            return false;
        }

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'proposta/dados-questionario',
            data: {
                'id_oper': id_oper,
            },
            context: this,
            beforeSend: function() {
                $(this).find('.modal-body').html('');
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


    });

    $('#devolver').on('click',function(event){
        event.preventDefault();
        $('#frmDevolverAlcada').submit();
    });

    $('#frmDevolverAlcada').on('submit', function(event){
        event.preventDefault();

        var rota = $(this).attr('action');

        swal({
            title: 'Qual motivo da devolução ?',
            input: 'text',
            inputAttributes: {
                autocapitalize: 'off'
            },
            showCancelButton: true,
            confirmButtonText: 'Devolver',
            showLoaderOnConfirm: true,
            preConfirm: (mt) => {
                $('#frmDevolverAlcada #DE_MOTIVO_DEVOLUCAO').val(mt);
            },
            allowOutsideClick: () => !swal.isLoading()
        }).then(() => {

            $.ajax({
                url:rota,
                method:"POST",
                data:new FormData(this),
                dataType:'JSON',
                contentType:false,
                cache:false,
                processData: false,
                success:function(data)
                {
                    $.toast({
                        heading: data.header,
                        text: data.message,
                        showHideTransition: 'slide',
                        icon: data.class_mensagem,
                        position : 'top-right',
                        hideAfter: 5000
                    });
                    window.location="/abgf/exportador/analisalimite";
                },
                error:function(data){
                    $.toast({
                        heading: 'Erro',
                        text: 'Erro ao devolver',
                        showHideTransition: 'slide',
                        icon: 'error',
                        position : 'top-right',
                        hideAfter: 5000
                    });
                },
                beforeSend:function(){
                    $('#devolver').text(' Devolvendo...').prop('disabled', true);
                },
                complete:function(){
                    $('#devolver').text(' Devolver').prop('disabled', false);
                }
            });



        })

    });



    $('#concluir').on('click',function(event){
        event.preventDefault();

        $('#frmConcluir #DS_RECOMENDACAO').val($('#'+alcadaAtual+' #ds_recomendacao').val());
        $('#frmConcluir #IC_INDEFERIDA').val($('#'+alcadaAtual+' #IC_INDEFERIDA').val());
        $('#frmConcluir #DT_RECOMENDACAO').val($('#'+alcadaAtual+' .DT_RECOMENDACAO').val());
        $('#frmConcluir #VL_CRED_CONCEDIDO').val($('#'+alcadaAtual+' #vl_cred_concedido').val());
        $('#frmConcluir #IN_DECISAO').val($('#'+alcadaAtual+' #ds_recomendacao').val());
        $('#frmConcluir #VL_APROVADO').val($('#'+alcadaAtual+' #vl_cred_concedido').val());
        $('#frmConcluir #NO_ALCADA').val($('#'+alcadaAtual+' #NO_ALCADA').val());
        $('#frmConcluir #ID_CREDIT').val($('#'+alcadaAtual+' #ID_CREDIT').val());
        $('#frmConcluir #DS_PARECER').val($('#'+alcadaAtual+' .ds_parecer').val());

        $('#frmConcluir').submit();
    });

    $('#frmConcluir').on('submit', function(event){
        event.preventDefault();

        var rota = $(this).attr('action');
        $.ajax({
            url:rota,
            method:"POST",
            data:new FormData(this),
            dataType:'JSON',
            contentType:false,
            cache:false,
            processData: false,
            success:function(data)
            {
                $.toast({
                    heading: data.header,
                    text: data.message,
                    showHideTransition: 'slide',
                    icon: data.class_mensagem,
                    position : 'top-right',
                    hideAfter: 5000
                });

                window.location="/abgf/exportador/analisalimite";

            },
            error:function(data){
                $.toast({
                    heading: 'Erro',
                    text: 'Ocorreu um erro ao concluir',
                    showHideTransition: 'slide',
                    icon: 'error',
                    position : 'top-right',
                    hideAfter: 5000
                });
            },
            beforeSend:function(){
                $('#concluir').text(' Concluir...').prop('disabled', true);
            },
            complete:function(){
                $('#concluir').text(' Concluir').prop('disabled', false);
            }
        });

    });


    $(document).on('click','#indeferir',function(event){
        event.preventDefault();

        swal({
            title: 'Atenção?',
            text: "Tem certeza que deseja indeferir essa operação?",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sim',
            cancelButtonText: 'Não'
        }).then((result) => {
            if (result.value) {
                $('#frmIndeferir').submit();

            }

        });

    });

    $('#frmIndeferir').on('submit', function(event){

        $('#frmIndeferir #DS_RECOMENDACAO').val($('#'+alcadaAtual+' #ds_recomendacao').val());
        $('#frmIndeferir #IC_INDEFERIDA').val($('#'+alcadaAtual+' #IC_INDEFERIDA').val());
        $('#frmIndeferir #DT_RECOMENDACAO').val($('#'+alcadaAtual+' .DT_RECOMENDACAO').val());
        $('#frmIndeferir #VL_CRED_CONCEDIDO').val($('#'+alcadaAtual+' #vl_cred_concedido').val());
        $('#frmIndeferir #IN_DECISAO').val($('#'+alcadaAtual+' #ds_recomendacao').val());
        $('#frmIndeferir #VL_APROVADO').val($('#'+alcadaAtual+' #vl_cred_concedido').val());
        $('#frmIndeferir #NO_ALCADA').val($('#'+alcadaAtual+' #NO_ALCADA').val());
        $('#frmIndeferir #ID_CREDIT').val($('#'+alcadaAtual+' #ID_CREDIT').val());
        $('#frmIndeferir #DS_PARECER').val($('#'+alcadaAtual+' .ds_parecer').val());
        $('#frmIndeferir #ID_MPME_FUNDO_GARANTIA').val($('#'+alcadaAtual+' #id_mpme_fundo_garantia').val());



        event.preventDefault();

        var rota = $(this).attr('action');
        $.ajax({
            url:rota,
            method:"POST",
            data:new FormData(this),
            dataType:'JSON',
            contentType:false,
            cache:false,
            processData: false,
            success:function(data)
            {

                var alerta = swal("Sucesso!", data.message, "success");
                alerta.then(function () {
                    location.reload();
                });


                window.location="/abgf/exportador/analisalimite";

            },
            error:function(data){
                var alerta = swal("Erro!", 'Ocorreu um erro ao indeferir', "error");
                alerta.then(function () {
                    location.reload();
                });
            },
            beforeSend:function(){
                $('#concluir').text(' Indeferir...').prop('disabled', true);
            },
            complete:function(){
                $('#concluir').text(' Indeferir').prop('disabled', false);
            }
        });

    });


    if($('#'+alcadaAtual+' #ds_recomendacao').val() == 2 && $('#'+alcadaAtual+' #ultimaAlcada').val() == "SIM"){
        $('#indeferir').show('slow');
        $('#concluir').hide();
    }else{
        $('#indeferir').hide();
        $('#concluir').show('slow');
    }

    $('#'+alcadaAtual+' #vl_cred_concedido').on('blur', function(){
        if ($('#'+alcadaAtual+' #id_mpme_fundo_garantia').val() != 0)
        {
            verificaCredito();
        }
    })

    $('#'+alcadaAtual+' #id_mpme_fundo_garantia').on('blur', function(){
        if ($(this).val() != 0)
        {
            verificaCredito();
        }
    })


});


function verificaCredito()
{
    var alcadaAtual     =  $('#ID_MPME_ALCADA').val();
    var ds_recomendacao = $('#'+alcadaAtual+' #ds_recomendacao').val();
    var id_mpme_fundo_garantia = $('#'+alcadaAtual+' #id_mpme_fundo_garantia').val();
    var vl_cred_concedido       = $('#'+alcadaAtual+' #vl_cred_concedido').val();

    if (ds_recomendacao == 2 )
    {
        //nao precisa verificar saldo
        $("#salvar").removeAttr('disabled');
        return true;
    }

    if ( id_mpme_fundo_garantia == 0 || vl_cred_concedido == "" || ds_recomendacao == "" )
    {
        swal("Ops!", "Parâmetros insuficientes para realizar a consulta", "info");
        return false;
    }

    $.ajax({
        type: "POST",
        method: "POST",
        async: false,
        url: URL_BASE+'ajax/varificar-saldo',
        data:{
            'id_oper' : $("#ID_OPER").val(),
            'id_mpme_fundo_garantia' : id_mpme_fundo_garantia,
            'vl_cred_concedido'      : $('#'+alcadaAtual+' #vl_cred_concedido').val(),
        },
        context: this,
        success: function(retorno) {
            switch (retorno.status) {
                case 'alerta':
                    var alerta = swal("Ops!",retorno.msg,"warning");
                    break;
                case 'saldo_insuficiente':
                    $("#salvar").attr('disabled', 'true');
                    $('#'+alcadaAtual+' #vl_cred_concedido').val('');
                    var alerta = swal("Ops!",retorno.msg,"warning");
                    break;
                case 'saldo_ok':
                    $("#salvar").removeAttr('disabled');
                    break;
            }
        },
        error:function(data){
            $.toast({
                heading: 'Erro',
                text: 'Ocorreu um erro ao concluir',
                showHideTransition: 'slide',
                icon: 'error',
                position : 'top-right',
                hideAfter: 5000
            });
            return false;
        }
    });
}
