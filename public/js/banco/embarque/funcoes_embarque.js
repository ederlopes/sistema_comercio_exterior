$(document).ready(function () {


$('[data-toggle="tooltip"]').tooltip();

     $('.aprovar_embarque').on('click', function (event) {
             event.preventDefault();
             var ehrecursoproprio = 0;
             ehrecursoproprio = $(this).data('recurso-proprio');
             
             if(ehrecursoproprio == 1){
                rota = '/abgf/exportador/aprova-embarque';
             }else{
                rota = '/banco/embarque/aprova-embarque';
             }   
              

                           
              var ID_MPME_EMBARQUE = $(this).data('id-mpme-embarque');


              swal({
                  title: 'Registre um Parecer',
                  input: 'textarea',
                  inputAttributes: {
                      autocapitalize: 'off'
                  },
                  showCancelButton: true,
                  confirmButtonText: 'Aprovar',
                  showLoaderOnConfirm: true,
                  preConfirm: (mt) => {
                      $('#parecer').val(mt);
                  },
                  allowOutsideClick: () => !swal.isLoading()
              }).then((devolver) => {
                  if (devolver.value) {
                      var parecer = $('#parecer').val();
                      $.ajax({
                          url: rota,
                          method: "POST",
                          type: "POST",
                          data: {parecer: parecer, ID_MPME_EMBARQUE: ID_MPME_EMBARQUE, ehrecursoproprio:ehrecursoproprio},
                          success: function (data)
                          {
                              $.toast({
                                  heading: 'Sucesso',
                                  text: 'Parecer salvo com sucesso!',
                                  showHideTransition: 'slide',
                                  icon: 'success',
                                  position: 'top-right',
                                  hideAfter: 5000
                              });
                              if(ehrecursoproprio == 1){
                                window.location = "/abgf/exportador/listar-proposta-embarque";
                              }else{
                                 window.location = "/banco";
                            }
                          },
                          error: function (data) {
                              $.toast({
                                  heading: 'erro',
                                  text: 'Erro ao registrar o parecer',
                                  showHideTransition: 'slide',
                                  icon: 'error',
                                  position: 'top-right',
                                  hideAfter: 5000
                              });
                          },
                      });



                  }
              })

          }); // Fecha aprovação



          $('.devolver_embarque').on('click', function (event) {
                   event.preventDefault();
                   var rota = '';
                   var tipoRetorno = $(this).data('devolve-exportador-analista');
                   
                   if(tipoRetorno == 1){
                    rota = '/abgf/exportador/devolve-embarque';
                   }else{
                    rota = '/banco/embarque/devolve-conferente';
                   }

                   var ID_MPME_EMBARQUE = $(this).data('id-mpme-embarque');
                   var devolve_exportador = $(this).data('devolve-exportador');
                   
                   swal({
                       title: 'Motivo da devolução',
                       input: 'textarea',
                       inputAttributes: {
                           autocapitalize: 'off'
                       },
                       showCancelButton: true,
                       confirmButtonText: 'Devolver',
                       showLoaderOnConfirm: true,
                       preConfirm: (mt) => {
                           $('#parecer').val(mt);
                       },
                       allowOutsideClick: () => !swal.isLoading()
                   }).then((devolver) => {
                       if (devolver.value) {
                           var parecer = $('#parecer').val();
                           $.ajax({
                               url: rota,
                               method: "POST",
                               type: "POST",
                               data: {parecer: parecer, ID_MPME_EMBARQUE: ID_MPME_EMBARQUE, devolve_exportador:devolve_exportador},
                               success: function (data)
                               {
                                   $.toast({
                                       heading: 'Sucesso',
                                       text: 'Parecer devolvido!',
                                       showHideTransition: 'slide',
                                       icon: 'success',
                                       position: 'top-right',
                                       hideAfter: 5000
                                   });
                                   if(tipoRetorno == 1){
                                    window.location = "/abgf/exportador/listar-proposta-embarque";
                                   }else{
                                    window.location = "/banco";
                                   }
                                   
                               },
                               error: function (data) {
                                   $.toast({
                                       heading: 'Erro',
                                       text: 'Erro ao registrar o motivo',
                                       showHideTransition: 'slide',
                                       icon: 'error',
                                       position: 'top-right',
                                       hideAfter: 5000
                                   });
                               },
                           });



                       }
                   })

               }); // Fecha aprovação

               $('div#historico_embarque').on('show.bs.modal', function (event)
               {   
                  
                   var id_mpme_embarque =  $(event.relatedTarget).data('id-mpme-embarque');
                   var id_oper          =  $(event.relatedTarget).data('idoper');
           
                   if ( id_oper == "" || id_mpme_embarque == "")
                   {
                       swal('Ops!', 'Dados informados inválidos!', 'info' );
                       return false;
                   }
                   $(".loading").show();
           
                   $(this).find('.modal-body').html('');
           
                   $.ajax({
                       type: "POST",
                       method: "POST",
                       url: URL_BASE+'ajax/historicoAprovacaoEmbarque',
                       data: {
                           'id_mpme_embarque'  : id_mpme_embarque,
                           'id_oper'           : id_oper
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
