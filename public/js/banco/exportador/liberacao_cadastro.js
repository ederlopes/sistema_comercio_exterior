$(document).ready(function () {
    
    //Verifica se eh para ativar ou nao
     if($('input[name="a"]:checked').val() == 'Sim'){
       $('.respa').prop( "disabled", true );
     }else{
       $('.respa').prop( "disabled", false );
     }
     
     if($('input[name="b"]:checked').val() == 'Sim'){
       $('.respb').prop( "disabled", true );
     }else{
       $('.respb').prop( "disabled", false );
     }
     
     if($('input[name="c"]:checked').val() == 'Sim'){
       $('.respc').prop( "disabled", true );
     }else{
       $('.respc').prop( "disabled", false );
     }
    
    if($('input[name="d"]:checked').val() == 'Nao'){
       $('.respd').prop( "disabled", true );
     }else{
       $('.respd').prop( "disabled", false );
     }
    
    
    
    // Caso escolha sim, ativa o campo
    $('input[name="a"]').on('click',function(){
        if($(this).val() == 'Sim'){
           CKEDITOR.instances['editor1'].setData(''); 
           CKEDITOR.instances['editor1'].setReadOnly(true);
           CKEDITOR.instances['editor1'].updateElement();
        }else{
           CKEDITOR.instances['editor1'].setReadOnly(false);
           CKEDITOR.instances['editor1'].updateElement();
        }   
    });
    
    $('input[name="b"]').on('click',function(){
        if($(this).val() == 'Sim'){
           CKEDITOR.instances['editor2'].setData('');
           CKEDITOR.instances['editor2'].setReadOnly(true);
           CKEDITOR.instances['editor2'].updateElement();
        }else{
           CKEDITOR.instances['editor2'].setReadOnly(false);
           CKEDITOR.instances['editor2'].updateElement();
        }   
    });
    
    $('input[name="c"]').on('click',function(){
        if($(this).val() == 'Sim'){
           CKEDITOR.instances['editor3'].setData(''); 
           CKEDITOR.instances['editor3'].setReadOnly(true);
           CKEDITOR.instances['editor3'].updateElement();
        }else{
           CKEDITOR.instances['editor3'].setReadOnly(false);
           CKEDITOR.instances['editor3'].updateElement();
        }   
    });
    
    $('input[name="d"]').on('click',function(){
        if($(this).val() == 'Nao'){
           CKEDITOR.instances['editor4'].setData('');
           CKEDITOR.instances['editor4'].setReadOnly(true);
           CKEDITOR.instances['editor4'].updateElement();
        }else{
           CKEDITOR.instances['editor4'].setReadOnly(false);
           CKEDITOR.instances['editor4'].updateElement();
        }   
    });
    
    
//    CKEDITOR.instances['respa'].updateElement();
//    CKEDITOR.instances['respb'].updateElement();
//    CKEDITOR.instances['respc'].updateElement();
//    CKEDITOR.instances['respd'].updateElement();
    
    // Copia dos dados dos campos do pre-embarque para o pos
    $('.copiarDadosPre').on('click', function (e) {
        e.preventDefault();
        $('input[name="NU_CEP"]').val($('input[name="NU_CEP_PRE"]').val());
        $('input[name="ID_AGENCIA"]').val($('input[name="ID_AGENCIA_PRE"]').val());
        $('input[name="DS_ENDERECO"]').val($('input[name="DS_ENDERECO_PRE"]').val());
        $('input[name="NO_CONTATO"]').val($('input[name="NO_CONTATO_PRE"]').val());
        $('input[name="NU_CNPJ"]').val($('input[name="NU_CNPJ_PRE"]').val());
        $('input[name="nu_ag_pos"]').val($('input[name="nu_ag"]').val());
        $('input[name="NU_TEL"]').val($('input[name="NU_TEL_PRE"]').val());
        $('input[name="NU_INSCRICAO"]').val($('input[name="NU_INSCRICAO_PRE"]').val());
        $('input[name="NO_CIDADE"]').val($('input[name="NO_CIDADE_PRE"]').val());
        $('input[name="DS_EMAIL"]').val($('input[name="DS_EMAIL_PRE"]').val());
        $('input[name="NO_CARGO"]').val($('input[name="NO_CARGO_PRE"]').val());

        $("#NO_ESTADO").val($('#NO_ESTADO_PRE').val());


    });


    $('#devolver').on('click', function (event) {
        event.preventDefault();

        var rota = '/banco/devolverValidador';

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
                $('#DE_MOTIVO_DEVOLUCAO').val(mt);
            },
            allowOutsideClick: () => !swal.isLoading()
        }).then((devolver) => {
            if (devolver.value) {
                var motivo = $('#DE_MOTIVO_DEVOLUCAO').val();
                var ID_NOTIFICACAO = $('.ID_NOTIFICACAO').val();

                $.ajax({
                    url: rota,
                    method: "POST",
                    type: "POST",
                    data: {motivo: motivo, ID_NOTIFICACAO: ID_NOTIFICACAO},
                    success: function (data)
                    {
                        $.toast({
                            heading: data.header,
                            text: data.message,
                            showHideTransition: 'slide',
                            icon: data.class_mensagem,
                            position: 'top-right',
                            hideAfter: 5000
                        });
                        window.location = "/banco";
                    },
                    error: function (data) {
                        $.toast({
                            heading: 'Erro',
                            text: 'Erro ao devolver',
                            showHideTransition: 'slide',
                            icon: 'error',
                            position: 'top-right',
                            hideAfter: 5000
                        });
                    },
                    beforeSend: function () {
                        $('#devolver').text(' Devolvendo...').prop('disabled', true);
                    },
                    complete: function () {
                        $('#devolver').text(' Devolver').prop('disabled', false);
                    }
                });



            }
        }
        )

    });
    
    
    $('.salvar').on('click',function(e){
        
        e.preventDefault();
        
        $('textarea[name="respa"]').prop('disabled',false);
        $('textarea[name="respa"]').val(CKEDITOR.instances['editor1'].getData());
         
        if($('input[name="a"]:checked').val() == 'Nao' && CKEDITOR.instances['editor1'].getData() == ""){
            swal ( "Oops" ,  "Voce deve preencher algum parecer para a pergunta A!" ,  "error" )
            return false;
        }

        if($('input[name="b"]:checked').val() == 'Nao' && CKEDITOR.instances['editor2'].getData() == ""){
            swal ( "Oops" ,  "Voce deve preencher algum parecer para a pergunta B!" ,  "error" )
            return false;
        }

        if($('input[name="c"]:checked').val() == 'Nao' && CKEDITOR.instances['editor3'].getData() == ""){
            swal ( "Oops" ,  "Voce deve preencher algum parecer para a pergunta C!" ,  "error" )
            return false;
        }

        if($('input[name="d"]:checked').val() == 'Sim' && CKEDITOR.instances['editor4'].getData() == ""){
            swal ( "Oops" ,  "Voce deve preencher algum parecer para a pergunta D!" ,  "error" )
            return false;
        }

        $('textarea[name="respb"]').prop('disabled',false);
        $('textarea[name="respb"]').val(CKEDITOR.instances['editor2'].getData());
        
        $('textarea[name="respc"]').prop('disabled',false);
        $('textarea[name="respc"]').val(CKEDITOR.instances['editor3'].getData());
        
        $('textarea[name="respd"]').prop('disabled',false);
        $('textarea[name="respd"]').val(CKEDITOR.instances['editor4'].getData());
        
       $("#frmatualizaInfoAddExportador").submit();
        
    })


    $('#btnDivergencia').on('click', function (){

        var id_usuario = $(this).data("idusuario");

        if (id_usuario == "")
        {
            swal ( "Oops",  "Parametros insuficientes!" ,  "warning" );
            return false;
        }

        if ($("#ds_divergencia").val() == "")
        {
            swal ( "Oops" ,  "Favor preencher o campo de divergência!" ,  "warning" );
            return false;
        }

        $.ajax({
            url: URL_BASE + 'banco/salvar-divergencia',
            method: "POST",
            type: "POST",
            data: {
                'ds_divergencia' : $("#ds_divergencia").val(),
                'id_usuario' : id_usuario,
            },
            success: function (retorno)
            {
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
            error: function (data) {
                $.toast({
                    heading: 'Erro',
                    text: 'Erro ao devolver',
                    showHideTransition: 'slide',
                    icon: 'error',
                    position: 'top-right',
                    hideAfter: 5000
                });
            },
            beforeSend: function () {
                $('#devolver').text(' Devolvendo...').prop('disabled', true);
            },
            complete: function () {
                $('#devolver').text(' Devolver').prop('disabled', false);
            }
        });


    });

    function limpa_formulário_cep() {
        // Limpa valores do formulário de cep.
        $("#DS_ENDERECO_PRE").val("...");
        $("#NO_CIDADE_PRE").val("...");
        $("#NO_ESTADO_PRE").val("...");
    }


    //Quando o campo cep perde o foco.
    $("#NU_CEP_PRE").blur(function() {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#DS_ENDERECO_PRE").val("...");
                $("#NO_CIDADE_PRE").val("...");
                $("#NO_ESTADO_PRE").val("...");


                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#DS_ENDERECO_PRE").val(dados.logradouro);
                        $("#NO_CIDADE_PRE").val(dados.localidade);
                        $("#NO_ESTADO_PRE").val(dados.uf);

                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });


    //Quando o campo cep perde o foco.
    $("#NU_CEP").blur(function() {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#DS_ENDERECO").val("...");
                $("#NO_CIDADE").val("...");
                $("#NO_ESTADO").val("...");


                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#DS_ENDERECO").val(dados.logradouro);
                        $("#NO_CIDADE").val(dados.localidade);
                        $("#NO_ESTADO").val(dados.uf);

                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulário_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulário_cep();
        }
    });




});