$(document).ready(function(){
 
    $('.exportador').on('change', function() {
        var id_usuario = $(this).val();
        
        if(id_usuario != "#"){
        $.ajax({
            url:URL_BASE+'ajax/retorna-operacoes',
            method:"POST",
            type:"POST",
            data: {
                'id_usuario' : id_usuario,
            },
            success:function(data)
            {
                $('#operacao').empty();
                //$('.operacao').selectpicker('clear')
                $('#pesquisar').text('Pesquisar').prop('disabled', false);
                $('.divOperacao').css('display','inline-block');
                $.each(data, function(i, item) {
                    
                    if (data.length > 0){   
                            $('select[name=operacao]').append('<option value="'+item.ID_OPER+'" data-tokens="'+item.ID_OPER+'">'+item.ID_OPER+'</option>');
                    }

                })
                $('select[name=operacao]').selectpicker('refresh');
   
            },
            error:function(data){
              $.toast({
                heading: 'Erro',
                text: 'Ocorreu um erro ao processar a informação',
                showHideTransition: 'slide',
                icon: 'error',
                position : 'top-right',
                hideAfter: 5000
              });
            },
              beforeSend:function(){
                  $('#pesquisar').text(' Pesquisando...').prop('disabled', true);
              },
              complete:function(){
                  $('#pesquisar').text(' Pesquisar').prop('disabled', false);
              }
          });

        }


    });




})