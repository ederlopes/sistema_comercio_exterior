$(document).ready(function(){


    $('#btnCadastrarImnportador').on('click', function ()
    {

        var id_oper          = $(this).data('idoper');
        Swal({
            title: 'Atenção?',
            text: "Deseja realmente cadastrar esse importador",
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
                    url: URL_BASE+'ajax/novo-importador',
                    data: {
                        'id_oper' : id_oper,
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
                      //  console.log(request.responseText);
                        swal("Erro!", "Por favor, tente novamente mais tarde. Erro: ' " +error+ " '", "error").then(function() {
                            location.reload();
                        });
                    }
                });


            }
        })





    });


    $('#listarImportadores').on('click',function(e){
        e.preventDefault();
        var id_pais = $(this).data('id_pais');

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'ajax/buscar-importador-unico',
            data: {id_pais:id_pais},
            success: function(retorno) {
              $('#TabelaImportador').html('<tr class="header"><th style="width:20%;"># Código</th><th>Razão Social</th><th>País</th></tr>');
                $.each( retorno, function( key, value ) {
                    $('#TabelaImportador').append("<tr style='cursor:pointer;' class='selecionaImportadorUnico' data-codigo_unico_importador="+value.CODIGO_UNICO_IMPORTADOR+" data-id_mpme_cliente="+value.ID_MPME_CLIENTE+"><td>"+value.CODIGO_UNICO_IMPORTADOR+"</td><td>"+value.NOME_CLIENTE+"</td><td>"+value.CD_SIGLA+"</td></tr>");
                });

            },
            error: function (request, status, error) {
                //console.log(request.responseText);
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                  //  location.reload();
                });
            }
        });

    });

    $('#TabelaImportador').on("click", ".selecionaImportadorUnico",function(e){
      e.preventDefault();
      var codigo_unico_importador = $(this).data('codigo_unico_importador');
      var id_oper                 = $('#listarImportadores').data('id_oper');
      var id_mpme_cliente         = $(this).data('id_mpme_cliente');

      Swal({
          title: 'Atenção?',
          text: "Deseja realmente selecionar esse importador como referencia?",
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
                  url: URL_BASE+'ajax/atualiza-importador-unico',
                  data: {
                      'id_oper' : id_oper,
                      'codigo_unico_importador' : codigo_unico_importador,
                      'id_mpme_cliente' : id_mpme_cliente,
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
                    //  console.log(request.responseText);
                      swal("Erro!", "Por favor, tente novamente mais tarde. Erro: ' " +error+ " '", "error").then(function() {
                          location.reload();
                      });
                  }
              });


          }
      });


    }); // fecha click importador unico


});
