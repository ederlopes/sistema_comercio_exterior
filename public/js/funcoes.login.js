jQuery(document).ready(function() {
    $('#login').on('click',function (){

        $('.erro').fadeOut(1000);

    });
    $('.erro').hide();
    $('.carregando').hide();
    $('#logar').on("click",function() {

        var dados = $('form[name=formlogin]').serialize(); // pega todos os campos do formulario
        var erro = 0;

        $.ajax({
            type: "POST",
            url: $('#rota').val(), // defini a rota com a condição if acima
            data: dados, // envia todos os campos do fomulário
            beforeSend: function() {
                // setting a timeout
                $('#formlogin').hide();
                $('#dvrodape').hide();
                $('.carregando').show();
            },
            success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
            {

                if(retorno == 1){ // verifica se exite algum erro na validação
                    $('#formlogin').show()
                    $('#dvrodape').show();
                    $('#login').focus();
                    $('.erro').fadeIn(600);
                    $('.erro').html("<div class='alert alert-danger'>Seu perfil não está ativo!</div>");
                    $('.erro').fadeOut(3000);
                    erro = 1;

                }
                else if(retorno == 2){ // verifica se exite algum erro na validação
                   $('#formlogin').show()
                    $('#dvrodape').show();
                    $('#login').focus();
                    $('.erro').fadeIn(600);
                    $('.erro').html("<div class='alert alert-danger'>Login ou senha invalidos!</div>");
                    $('.erro').fadeOut(3000);
                    erro = 1;


                }else{
                    $('#formlogin').show()
                    $('#dvrodape').show();
                    $('form[name=formlogin]').submit();
                }
            },
            complete: function() {
                $('#formlogin').show()
                $('#dvrodape').show();
                $('.carregando').hide();

                if(erro == 0) {
                    $('#formlogin').hide();
                    $('#dvrodape').hide();
                    $('.carregando').show();
                }
            },

            error: function(erro) {
                console.error(erro)
                swal(erro, "Concluido!", "error");
                //alert(retorno); // exibe a mensagem de sucesso caso tenha salvo ou atualizado
                setTimeout(function(){
                    location.reload();
                }, 1000);
            }
        });


        return false;


    });



});
  