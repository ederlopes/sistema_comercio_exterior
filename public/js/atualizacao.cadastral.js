index_atual = 1;
$(document).ready(function() {
   

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    var preembarque = 0;
    var posembarque = 0;
    
    iniciaMascaras();

  
    //Quando o campo cep perde o foco.
    $(document).on('blur','#cep_f',function() {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#ag_endereco").val("...");
                $("#ag_cidade").val("...");
                $("#ag_uf").val("...");

                ativaCarregando(); 
                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#ag_endereco").val(dados.logradouro);
                        $("#ag_cidade").val(dados.localidade);
                        $("#ag_uf").val(dados.uf);
                        $('.selectpicker').selectpicker('refresh')
                        desativaCarregando();
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulario_cep();
                        swal("Atenção!", 'Cep não encontrato. Caso tenha certeza que é esse, continue com o preenchimento dos dados', "warning");
                        desativaCarregando();
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulario_cep();
                swal("Atenção!", 'CEP inválido', "warning");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulario_cep();
        }
    });

     //Quando o campo cep perde o foco.
     $(document).on('blur','#cep_f_pos',function() {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#ag_endereco_pos").val("...");
                $("#ag_cidade_pos").val("...");
                $("#ag_uf_pos").val("...");

                ativaCarregando(); 
                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#ag_endereco_pos").val(dados.logradouro);
                        $("#ag_cidade_pos").val(dados.localidade);
                        $("#ag_uf_pos").val(dados.uf);
                        $('.selectpicker').selectpicker('refresh')
                        desativaCarregando();
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulario_cep();
                        swal("Atenção!", 'Cep não encontrato. Caso tenha certeza que é esse, continue com o preenchimento dos dados', "warning");
                        desativaCarregando();
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulario_cep();
                swal("Atenção!", 'CEP inválido', "warning");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulario_cep();
        }
    });



    $(".atualizar").on('click', function(e){
        e.preventDefault();
        ativaCarregando();
       
    if( preembarque == 1){
        var erros = validarDadosPreEmbarque();
    }

    if( posembarque == 1){
        var erros = validarDadosPosEmbarque();
    }

    if (erros.length>0) {
        swal("Atenção!", erros.join('<br />'), "warning");
        desativaCarregando();
        return false;
    }
        

        var formdata = new FormData($("form[name='frmAtualizacaoCadastro']")[0]);
        var rota = $("form[name='frmAtualizacaoCadastro']").attr('action');
        
        
        $.ajax({
            url: rota,
            method: "POST",
            type:"POST",
            data: formdata,
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                desativaCarregando();
                if(data.status == 'erro'){
                    if(Array.isArray(data.message)) {
                        var text = data.message.join('<br />');
                    } else {
                        var text = data.message;
                    }
                    swal("Erro!", text, "error");
                }else{
                    desativaCarregando();
                    swal("Sucesso!", data.message, "success");
                    

                }

            
            },
            error: function (data) {
                desativaCarregando();
                var alerta = swal("Erro!", 'Ocorreu um erro ao salvar, verifique os dados e tente novamente', "error");
                alerta.then(function () {
                    location.reload();
                });
            },
            beforeSend: function () {
                $('.finalizar_cadastro').text(' Finalizar o cadastro...').prop('disabled', true);
            },
            complete: function () {
                desativaCarregando();
                $('.finalizar_cadastro').text(' Finalizar o cadastro').prop('disabled', false);
                
            }
        });




    })

    /* funcao para selecionar automaticamente o valor do select do pre caso o select do pre esteja selecionado */
    $("#id_financiador_pre").change(function() {
        ativaCarregando();
            $('#id_financiador2').val($(this).val())
            if($(this).val() == 16){ // 16 se refere ao option do banco do brasil, caso seja BB ele exibe o combo com a gecex (Agencia)
                $('.gecex').show('slow');
                $('.OcultarDadosBanco').hide('slow');
                desativaCarregando();
            }else{
                $('.gecex').hide('slow');
                $('.OcultarDadosBanco').show('slow');
                $('#id_gecex_pos').val('');
                desativaCarregando();
            }
    });

        /* funcao para selecionar automaticamente o valor do select do pos caso o select do pre esteja selecionado */
        $("#id_financiador2").change(function() {
            if($(this).val() == 16){ // 16 se refere ao option do banco do brasil, caso seja BB ele exibe o combo com a gecex (Agencia)
                $('.gecex').show('slow');
                $('.OcultarDadosBanco').hide('slow');
               
            }else{
                $('.gecex').hide('slow');
                $('.OcultarDadosBanco').show('slow');
               
              
            }
    });



    // Exibe os campos conforme a modalidade
    $("#id_modalidade").on('change',function () {
        ativaCarregando();
        var listaModalidades = $('#id_modalidade').val();
       
        //Caso nao tenha modalidade ou seja null oculta os campos
        if(listaModalidades === undefined || listaModalidades === null){
            $("#pre-embarque").hide();
            $("#pos-embarque").hide();
            $('.financiador_pre').prop('disabled',true);
            $('.financiador_pos').prop('disabled',true);
            $('.repetir').hide();
            preembarque = 0;
            posembarque = 0;
            desativaCarregando();
        }else{

            if($.inArray('1#1#1',listaModalidades) >= 0 || $.inArray('2#5#2',listaModalidades) >= 0 || $.inArray('2#6#3',listaModalidades) >= 0) {
                $("#pre-embarque").show('slow');
                $('.financiador_pre').prop('disabled',false);
                preembarque = 1;
                $(".financiador").prop('disabled',false);
                $(".atualizar").prop('disabled',false);
                desativaCarregando();
            }else{
                $("#pre-embarque").hide('slow');
                $('.financiador_pre').prop('disabled',true);
                preembarque = 0;
                desativaCarregando();
            }

            if($.inArray('2#5#2',listaModalidades) >= 0 || $.inArray('2#6#3',listaModalidades) >= 0 || $.inArray('3#3#5',listaModalidades) >= 0 || $.inArray('3#2#6',listaModalidades) >= 0) {
                $("#pos-embarque").show('slow');
                $('.financiador_pos').prop('disabled',false);
                posembarque = 1;
                $(".financiador").prop('disabled',false);
                $(".atualizar").prop('disabled',false);
                desativaCarregando();
            }else{
                $("#pos-embarque").hide('slow');
                $('.financiador_pos').prop('disabled',true);
                posembarque = 0;
                desativaCarregando();
            }

            //Caso tenha selecionado apenas recurso proprio, desabilita a instituição financeira
            if( (listaModalidades.length == 1 && $.inArray('3#4#4',listaModalidades) >= 0) || listaModalidades.length == 0 ){
                $(".financiador").prop('disabled',true);
                $(".atualizar").prop('disabled',true);
                $('.financiador_pre').prop('disabled',true);
                $('.financiador_pos').prop('disabled',true);
            }
        
         

        }

        if(preembarque == 1 && posembarque == 1){
            $('#copiar').show('slow');
        }else{
            $('#copiar').hide('slow');
        }
    })

        /* funcao para selecionar automaticamente o valor do select do pre caso o select do pre esteja selecionado */
        $(".financiador").change(function() {

            if($(this).val() == 16){ // 16 se refere ao option do banco do brasil, caso seja BB ele exibe o combo com a gecex (Agencia)
                $('.SelectGecex').prop('disabled',false);
               
            }else{
                $('.SelectGecex').prop('disabled',true);
                $('.SelectGecex').val('');
              
            }
    });

    /** copia os dados do pre-embarque para os campos de pos-embarque **/
    $('#copiar').on('click',function(){
        $('#ID_AGENCIA_POS').val($('#no_agencia').val())
        $('#cep_f_pos').val($('#cep_f').val())
        $('#ag_endereco_pos').val($('#ag_endereco').val())
        $('#ag_uf_pos').val($('#ag_uf').val())
        $('#ag_cidade_pos').val($('#ag_cidade').val())
        $('#ag_cnpj_pos').val($('#ag_cnpj').val())
        $('#ag_inscr_est_pos').val($('#ag_inscr_est').val())
        $('#contato_fin_pos').val($('#contato_fin').val())
        $('#cargo_fin_pos').val($('#cargo_fin').val())
        $('#telefone_f_pos').val($('#telefone_f').val())
        $('#email_f_pos').val($('#email_f').val())

    });
    

});

function limpa_formulario_cep() {
    // Limpa valores do formulário de cep.
    $("#DE_ENDER").val("");
    $("#DE_CIDADE").val("");
    $("#CD_UF").val("");

}


function validarCNPJ(cnpj) {
    cnpj = cnpj.replace(/[^\d]+/g,'');

    if(cnpj == '')
        return false;
    if (cnpj.length != 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;

    // Valida DVs
    tamanho = cnpj.length - 2
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--)
    {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
    return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--)
    {
        soma += numeros.charAt(tamanho - i) * pos--;
        if (pos < 2)
        pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
        return false;

    return true;
}
function validarCPF(cpf) {
	cpf = cpf.replace(/[^\d]+/g,'');
	if(cpf == '') return false;
	// Elimina CPFs invalidos conhecidos
	if (cpf.length != 11 ||
		cpf == "00000000000" ||
		cpf == "11111111111" ||
		cpf == "22222222222" ||
		cpf == "33333333333" ||
		cpf == "44444444444" ||
		cpf == "55555555555" ||
		cpf == "66666666666" ||
		cpf == "77777777777" ||
		cpf == "88888888888" ||
		cpf == "99999999999")
			return false;
	// Valida 1o digito
	add = 0;
	for (i=0; i < 9; i ++)
		add += parseInt(cpf.charAt(i)) * (10 - i);
		rev = 11 - (add % 11);
		if (rev == 10 || rev == 11)
			rev = 0;
		if (rev != parseInt(cpf.charAt(9)))
			return false;
	// Valida 2o digito
	add = 0;
	for (i = 0; i < 10; i ++)
		add += parseInt(cpf.charAt(i)) * (11 - i);
	rev = 11 - (add % 11);
	if (rev == 10 || rev == 11)
		rev = 0;
	if (rev != parseInt(cpf.charAt(10)))
		return false;
	return true;
}
function validarEmail(email) {
    return /^[\w+.]+@\w+\.\w{2,}(?:\.\w{2})?$/.test(email)
}



function validarDadosPreEmbarque() {
    var erros = [];
    var form = $('form[name=frmAtualizacaoCadastro]');

    var NO_AGENCIA_PRE = form.find('input[name=NO_AGENCIA_PRE]').val();
    var AG_CEP_PRE = form.find('input[name=AG_CEP_PRE]').val();
    var AG_ENDERECO_PRE = form.find('select[name=AG_ENDERECO_PRE]').val();
    var AG_ESTADO_PRE = form.find('input[name=AG_ESTADO_PRE]').val();
    var AG_CIDADE_PRE = form.find('input[name=AG_CIDADE_PRE]').val();
    var AG_CNPJ_PRE = form.find('input[name=AG_CNPJ_PRE]').val();
    var AG_INSCR_PRE = form.find('input[name=AG_INSCR_PRE]').val();
    var AG_CONTATO_PRE = form.find('input[name=AG_CONTATO_PRE]').val();
    var AG_CARGO_PRE = form.find('input[name=AG_CARGO_PRE]').val();
    var AG_TEL_PRE = form.find('input[name=AG_TEL_PRE]').val();
    var AG_EMAIL_PRE = form.find('input[name=AG_EMAIL_PRE]').val();
    
    if (NO_AGENCIA_PRE=='') {
        erros.push('O campo "Agência do pré-embarque" é obrigatório.');
    }
    if (AG_CEP_PRE=='') {
        erros.push('O campo "Cep do pré-embarque" é obrigatório.');
    }
    if (AG_ENDERECO_PRE=='') {
        erros.push('O campo "Endereço do pré-embarque" é obrigatório.');
    }
    if (AG_ESTADO_PRE=='') {
        erros.push('O campo "Estado do pré-embarque" é obrigatório.');
    }
    if (AG_CIDADE_PRE=='') {
        erros.push('O campo "Cidade do pré-embarque" é obrigatório.');
    }
    if (AG_CNPJ_PRE=='') {
        erros.push('O campo "CNPJ do pré-embarque" é obrigatório.');
    }else{
        if (!validarCNPJ(AG_CNPJ_PRE)) {
            erros.push('O "CNPJ do pré-embarque" é inválido.');
        } 
    }
    if (AG_INSCR_PRE=='') {
        erros.push('O campo "Inscrição Estadual do pré-embarque" é obrigatório.');
    }
    if (AG_CONTATO_PRE=='') {
        erros.push('O campo "Contato do pré-embarque" é obrigatório.');
    }
    if (AG_CARGO_PRE=='') {
        erros.push('O campo "Cargo do pré-embarque" é obrigatório.');
    }
    if (AG_TEL_PRE=='') {
        erros.push('O campo "Telefone do pré-embarque" é obrigatório.');
    }
    if (AG_EMAIL_PRE=='') {
        erros.push('O campo "E-mail do pré-embarque" é obrigatório.');
    }else {
        if (!validarEmail(AG_EMAIL_PRE)) {
            erros.push('O "E-mail" digitado é inválido.');
        }
    }

    return erros;
}

function validarDadosPosEmbarque() {
    var erros = [];
    var form = $('form[name=frmAtualizacaoCadastro]');

    var ID_AGENCIA_POS = form.find('input[name=ID_AGENCIA_POS]').val();
    var AG_CEP_POS = form.find('input[name=AG_CEP_POS]').val();
    var AG_ENDERECO_POS = form.find('select[name=AG_ENDERECO_POS]').val();
    var AG_ESTADO_POS = form.find('input[name=AG_ESTADO_POS]').val();
    var AG_CIDADE_POS = form.find('input[name=AG_CIDADE_POS]').val();
    var AG_CNPJ_POS = form.find('input[name=AG_CNPJ_POS]').val();
    var AG_INSCR_POS = form.find('input[name=AG_INSCR_POS]').val();
    var AG_CONTATO_POS = form.find('input[name=AG_CONTATO_POS]').val();
    var AG_CARGO_POS = form.find('input[name=AG_CARGO_POS]').val();
    var AG_TEL_POS = form.find('input[name=AG_TEL_POS]').val();
    var AG_EMAIL_POS = form.find('input[name=AG_EMAIL_POS]').val();
    
    if (ID_AGENCIA_POS=='') {
        erros.push('O campo "Agência do pós-embarque" é obrigatório.');
    }
    if (AG_CEP_POS=='') {
        erros.push('O campo "Cep do pós-embarque" é obrigatório.');
    }
    if (AG_ENDERECO_POS=='') {
        erros.push('O campo "Endereço do pós-embarque" é obrigatório.');
    }
    if (AG_ESTADO_POS=='') {
        erros.push('O campo "Estado do pós-embarque" é obrigatório.');
    }
    if (AG_CIDADE_POS=='') {
        erros.push('O campo "Cidade do pós-embarque" é obrigatório.');
    }
    if (AG_CNPJ_POS=='') {
        erros.push('O campo "CNPJ do pós-embarque" é obrigatório.');
    }else{
        if (!validarCNPJ(AG_CNPJ_POS)) {
            erros.push('O "CNPJ do pós-embarque" é inválido.');
        } 
    }
    if (AG_INSCR_POS=='') {
        erros.push('O campo "Inscrição Estadual do pós-embarque" é obrigatório.');
    }
    if (AG_CONTATO_POS=='') {
        erros.push('O campo "Contato do pós-embarque" é obrigatório.');
    }
    if (AG_CARGO_POS=='') {
        erros.push('O campo "Cargo do pós-embarque" é obrigatório.');
    }
    if (AG_TEL_POS=='') {
        erros.push('O campo "Telefone do pós-embarque" é obrigatório.');
    }
    if (AG_EMAIL_POS=='') {
        erros.push('O campo "E-mail do pós-embarque" é obrigatório.');
    }else {
        if (!validarEmail(AG_EMAIL_POS)) {
            erros.push('O "E-mail" digitado é inválido.');
        }
    }

    return erros;
}

function ativaCarregando() {
    $('div#Carregando').show();
}
function desativaCarregando() {
    $('div#Carregando').hide();
}
function remove_caracteres(text) {
    text = text.replace(/[áàâãªä]/g, 'a')
           .replace(/[ÁÀÂÃÄ]/g, 'A')
           .replace(/[ÍÌÎÏ]/g, 'I')
           .replace(/[íìîï]/g, 'i')
           .replace(/[éèêë]/g, 'e')
           .replace(/[ÉÈÊË]/g, 'E')
           .replace(/[óòôõºö]/g, 'o')
           .replace(/[ÓÒÔÕÖ]/g, 'O')
           .replace(/[úùûü]/g, 'u')
           .replace(/[ÚÙÛÜ]/g, 'U')
           .replace(/[ç]/g, 'c')
           .replace(/[Ç]/g, 'C')
           .replace(/ñ/g, 'n')
           .replace(/Ñ/g, 'N')
           .replace(/–/g, '-')
           .replace(/[’‘‹›‚]/g, '')
           .replace(/[“”«»„]/g, '')
           .replace(/ /g, '_');

    text = text.replace(/[^A-Za-z0-9]/g,'');

	return text;
}
function iniciaMascaras() {
    $(".CNPJ").mask('99.999.999/9999-99');
    $(".CPF").mask('999.999.999-99');
    $(".CEP").mask('99999-999');
    $('.PORCP').autoNumeric('init', {aSep:'',aDec:',',aSign:'%',pSign:'s',lZero:'deny',vMax:'100'});
    $('.REAL').autoNumeric('init', {aSep:'.',aDec:',',aSign:'R$ ',pSign:'p',lZero:'deny'});
    $('.DOLAR').autoNumeric('init', {aSep:'.',aDec:',',aSign:'US$ ',pSign:'p',lZero:'deny'});
    $('.NUM').autoNumeric('init', {mDec:'0',pSign:'s',lZero:'deny',vMax:'99999'});
    $('.TEL').maskbrphone({useDdd : true});
}
