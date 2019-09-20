index_atual = 1;
status_cnpj = 0;
status_login = 0;
$(document).ready(function() {
    clone_quadrosocio = $('form div#quadro-socio-1').clone();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    iniciaMascaras();

  	$('div#Cadastro').bootstrapWizard({
        onNext: function(tab, navigation, index) {
            var erros = validarWizard(index);
            if (erros.length>0) {
                swal({
                    title: "A validação falhou!",
                    text: erros.join('<br />'),
                    html: true,
                    type: 'warning'
                });
                return false;
            }
		},
        onTabClick: function(tab, navigation, index, target) {
            if (index==target) {
                return false;
            } else if (target>index) {
                var erros = validarWizard(index+1);
                if (erros.length>0) {
                    swal({
                        title: "A validação falhou!",
                        text: erros.join('<br />'),
                        html: true,
                        type: 'warning'
                    });
                    return false;
                }
            }
        },
        onTabShow: function(tab, navigation, index) {
		    var $total = navigation.find('li').length;
            var $current = index+1;
            var $percent = ($current/$total) * 100;
            $('div#Cadastro .progress-bar').css({width:$percent+'%'});
        }
	});

    $('div#Cadastro').on('click', '.finish a', function(ev) {
        ev.preventDefault();
        var erros = [];

        var validacaoDadosAcesso = validarDadosAcesso();
        var validacaoRepresentante = validarRepresentante();
        var validacaoQuadro = validarQuadro();
        var validacaoDadosCadastrais = validarDadosCadastrais();

        var erros = erros.concat(validacaoDadosAcesso, validacaoRepresentante, validacaoQuadro, validacaoDadosCadastrais);

        if (erros.length>0) {
            swal({
                title: "A validação falhou!",
                text: erros.join('<br />'),
                html: true,
                type: 'warning'
            });
            return false;
        } else {

        }
	});

    $('form').on('blur', 'input[name=NU_CNPJ]', function(ev) {
        var CNPJ = $(this).val();

        if(CNPJ != "")
        {
            ativaCarregando();
            $.ajax({
                type: "POST",
                url: URL_BASE+"/buscarcnpj",
                data: {
                    cnpj: CNPJ
                },
                context: this,
                success: function (retorno)
                {
                    status_cnpj = retorno;
                    if (retorno == '1') {

                        $(this).closest('div.form-group').find('div.mensagem div.alert').html('O CNPJ digitado já está em uso.').attr('class','alert alert-danger');
                        $(this).closest('div.form-group').attr('class','form-group has-error');
                    } else {
                        $(this).closest('div.form-group').attr('class','form-group');
                    }
                    desativaCarregando();
                },
                error: function (erro) {
                    alert('Erro desconhecido, tente novamente mais tarde.');
                }
            });
        }
    });

    $('form').on('blur', 'input[name=LOGIN]', function(ev) {
        var LOGIN = remove_caracteres($(this).val());
        $(this).val(LOGIN);

        if(LOGIN != "")
        {
            ativaCarregando();
            $.ajax({
                type: "GET",
                url: URL_BASE+'/buscarusuariopornome/'+LOGIN,
                context: this,
                success: function(retorno)
                {
                    status_login = retorno;
                    if(retorno == '1')
                    {
                        $(this).closest('div.form-group').find('div.mensagem div.alert').html('O usuário já cadastrado! <a href="http://scempme.abgf.gov.br/password/reset">Recupere sua senha</a>.').attr('class','alert alert-danger');
                        $(this).closest('div.form-group').attr('class','form-group has-error');
                    } else {
                        $(this).closest('div.form-group').find('div.mensagem div.alert').html('O usuário disponível!').attr('class','alert alert-success');
                        $(this).closest('div.form-group').attr('class','form-group has-success');
                    }
                    desativaCarregando();
                },
                error: function(erro) {
                    alert('Erro desconhecido, tente novamente mais tarde.');
                }
            });
        }
    });

    $('form').on('click', 'a.adicionar-socio', function(ev) {
        ev.preventDefault();

        if (somaSocios()>=100) {
            swal({
                title: 'Quadro societário completo',
                text: 'A soma das participações dos sócios não pode ultrapassar 100%',
                html: true,
                type: 'warning'
            });
            return false;
        } else {
            index_atual++;
            quadrosocio = clone_quadrosocio.clone().attr('id','quadro-socio-'+index_atual);

            HTML_REMOVER = '<div class="form-group">';
                HTML_REMOVER += '<div class="row">';
                    HTML_REMOVER += '<div class="col-md-3"></div>';
                    HTML_REMOVER += '<div class="col-md-5 text-right">';
                        HTML_REMOVER += '<a href="javascript:void(0);" class="btn btn-danger remover-socio" data-index="'+index_atual+'">REMOVER SÓCIO</a>';
                    HTML_REMOVER += '</div>';
                HTML_REMOVER += '</div>';
            HTML_REMOVER += '</div>';

            quadrosocio.find('h3 span').html(index_atual);
            quadrosocio.append(HTML_REMOVER);

            $('form div#quadrosocietario').append(quadrosocio);
            $('form select.selectpicker').selectpicker('refresh');

            iniciaMascaras();
        }
    });

    $('form').on('click', 'a.remover-socio', function(ev) {
        ev.preventDefault();
        var index = $(this).data('index');

        $('div#quadro-socio-'+index).remove();

        index_atual--;
    });

    $('form').on('change', 'select[name=simples_nacional]', function(ev) {
        switch ($(this).val()) {
            case '1':
                $('div#tipo_simples').show();
                break;
            case '2':
                $('div#tipo_simples').hide();
                $('div#tipo_simples').find('select').val('').selectpicker('refresh');
                break;
        }
    });

    $('form').on('change', 'select.TP_PESSOA_QUADRO', function(ev) {
        var obj_quadrosocio = $(this).closest('div.quadro-socio');
        var obj_label = obj_quadrosocio.find('div.quadro-socio-cpf').find('label');
        var obj_input = obj_quadrosocio.find('div.quadro-socio-cpf').find('input');

        switch ($(this).val()) {
            case 'F':
                obj_label.html('CPF <span class="required">*</span>');
                obj_input.val('').attr('placeholder','Digite o CPF do responsável.').attr('disabled',false).removeClass('CNPJ').addClass('CPF');
                break;
            case 'J':
                obj_label.html('CNPJ <span class="required">*</span>');
                obj_input.val('').attr('placeholder','Digite o CNPJ do responsável.').attr('disabled',false).removeClass('CPF').addClass('CNPJ');
                break;
        }
        iniciaMascaras();
    });



    //Quando o campo cep perde o foco.
    $("#DE_CEP").blur(function() {

        //Nova variável "cep" somente com dígitos.
        var cep = $(this).val().replace(/\D/g, '');

        //Verifica se campo cep possui valor informado.
        if (cep != "") {

            //Expressão regular para validar o CEP.
            var validacep = /^[0-9]{8}$/;

            //Valida o formato do CEP.
            if(validacep.test(cep)) {

                //Preenche os campos com "..." enquanto consulta webservice.
                $("#DE_ENDER").val("...");
                $("#DE_CIDADE").val("...");
                $("#CD_UF").val("...");

                ativaCarregando();
                //Consulta o webservice viacep.com.br/
                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {

                    if (!("erro" in dados)) {
                        //Atualiza os campos com os valores da consulta.
                        $("#DE_ENDER").val(dados.logradouro);
                        $("#DE_CIDADE").val(dados.localidade);
                        $("#CD_UF").val(dados.uf);
                        $('.selectpicker').selectpicker('refresh')
                        desativaCarregando();
                    } //end if.
                    else {
                        //CEP pesquisado não foi encontrado.
                        limpa_formulario_cep();
                        alert("CEP não encontrado.");
                        desativaCarregando();
                    }
                });
            } //end if.
            else {
                //cep é inválido.
                limpa_formulario_cep();
                alert("Formato de CEP inválido.");
            }
        } //end if.
        else {
            //cep sem valor, limpa formulário.
            limpa_formulario_cep();
        }
    });

    $(".finalizar_cadastro").on('click', function(e){
        e.preventDefault();
        ativaCarregando();

        var formdata = new FormData($("form[name='frmCadastro']")[0]);
        var rota = $('.form-dados').attr('action');
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
                    swal({
                        title: "Erro!",
                        text: text,
                        html: true,
                        type: 'warning'
                    });
                } else {
                    desativaCarregando();
                    var alerta = swal("Sucesso!", data.message, "success");
                    alerta.then(function () {
                        location.reload();
                    });
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
    });

    /* funcao para selecionar automaticamente o valor do select do pre caso o select do pre esteja selecionado */
    $("#id_financiador_pre").change(function() {
            $('#id_financiador2').val($(this).val())
            if($(this).val() == 16){ // 16 se refere ao option do banco do brasil, caso seja BB ele exibe o combo com a gecex (Agencia)
                $('.gecex').show('slow');
                $('.OcultarDadosBanco').hide('slow');

            }else{
                $('.gecex').hide('slow');
                $('.OcultarDadosBanco').show('slow');
                $('#id_gecex_pos').val('');

            }
    });

    $("select.perguntas").change(function() {
        ID_MPME_PERGUNTA = $(this).data('id');
        IN_OUTRA_RESPOSTA = $(this).find('option:selected').data('inoutraresposta');

        if (IN_OUTRA_RESPOSTA == 'S') {
            $('div#resposta_outros_'+ID_MPME_PERGUNTA).show('slow');
        } else {
            $('div#resposta_outros_'+ID_MPME_PERGUNTA).hide('slow');
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

        var listaModalidades = $('#id_modalidade').val();

        //Caso nao tenha modalidade ou seja null oculta os campos
        if(listaModalidades === undefined || listaModalidades === null){
            $("#pre-embarque").hide();
            $("#pos-embarque").hide();
            $('.repetir').hide();
        }else{

            if($.inArray('1#1#1',listaModalidades) >= 0 || $.inArray('2#5#2',listaModalidades) >= 0 || $.inArray('2#6#3',listaModalidades) >= 0) {
                $("#pre-embarque").show('slow');
            }else{
                $("#pre-embarque").hide('slow');
            }

            if($.inArray('2#5#2',listaModalidades) >= 0 || $.inArray('2#6#3',listaModalidades) >= 0 || $.inArray('3#3#5',listaModalidades) >= 0 || $.inArray('3#2#6',listaModalidades) >= 0) {
                $("#pos-embarque").show('slow');
            }else{
                $("#pos-embarque").hide('slow');
            }


        }



    })


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
function validarWizard(index) {
    var erros = [];
    switch (index) {
        case 1:
            var validacao = validarDadosAcesso();
            var erros = erros.concat(validacao);
            break;
        case 2:
            var validacao = validarRepresentante();
            var erros = erros.concat(validacao);
            break;
        case 3:
            var validacao = validarQuadro();
            var erros = erros.concat(validacao);
            break;
    }
    return erros;
}
function validarDadosAcesso() {
    var erros = [];
    var form = $('form[name=frmCadastro]');

    var NU_CNPJ = form.find('input[name=NU_CNPJ]').val();
    var LOGIN = form.find('input[name=LOGIN]').val().toUpperCase();
    var DS_SENHA = form.find('input[name=DS_SENHA]').val().toUpperCase();
    var DS_SENHA_C = form.find('input[name=DS_SENHA_C]').val().toUpperCase();

    if (NU_CNPJ=='') {
        erros.push('O campo "CNPJ da Empresa" é obrigatório.');
    } else {
        if (!validarCNPJ(NU_CNPJ)) {
            erros.push('O "CNPJ da Empresa" digitado é inválido.');
        } else {
            if (status_cnpj==1) {
                erros.push('O "CNPJ da Empresa" digitado já está em uso.');
            }
        }
    }
    if (LOGIN=='') {
        erros.push('O campo "Usuário" é obrigatório.');
    } else {
        if (status_login==1) {
            erros.push('O "Usuário" digitado já está em uso.');
        }
    }
    if (DS_SENHA=='') {
        erros.push('O campo "Senha" é obrigatório.');
    }
    if (DS_SENHA_C=='') {
        erros.push('O campo "Confirme a senha" é obrigatório.');
    } else {
        if (DS_SENHA!=DS_SENHA_C) {
            erros.push('O campo "Confirme a senha" precisa ser idêntico ao campo "Senha".');
        }
    }

    return erros;
}
function validarRepresentante() {
    var erros = [];
    var form = $('form[name=frmCadastro]');

    var NM_RESPONSAVEL = form.find('input[name=NM_RESPONSAVEL]').val();
    var EMAIL_RESPONSAVEL = form.find('input[name=EMAIL_RESPONSAVEL]').val();
    var CPF_RESPONSAVEL = form.find('input[name=CPF_RESPONSAVEL]').val();

    if (NM_RESPONSAVEL=='') {
        erros.push('O campo "Nome" é obrigatório.');
    }
    if (EMAIL_RESPONSAVEL=='') {
        erros.push('O campo "E-mail" é obrigatório.');
    } else {
        if (!validarEmail(EMAIL_RESPONSAVEL)) {
            erros.push('O "E-mail" digitado é inválido.');
        }
    }
    if (CPF_RESPONSAVEL=='') {
        erros.push('O campo "CPF" é obrigatório.');
    } else {
        if (!validarCPF(CPF_RESPONSAVEL)) {
            erros.push('O "CPF" digitado é inválido.');
        }
    }

    return erros;
}
function validarQuadro() {
    var erros = [];
    var form = $('form[name=frmCadastro]');

    form.find('div.quadro-socio').each(function(index) {
        var NOME_QUADRO = $(this).find('input.NOME_QUADRO').val();
        var TP_PESSOA_QUADRO = $(this).find('select.TP_PESSOA_QUADRO').val();
        var CPF_QUADRO = $(this).find('input.CPF_QUADRO').val();
        var PARTICIPACAO_QUADRO = $(this).find('input.PARTICIPACAO_QUADRO').val();

        if (NOME_QUADRO=='') {
            erros.push('O campo "Nome do sócio" do Sócio '+(index+1)+' é obrigatório.');
        }
        if (TP_PESSOA_QUADRO=='') {
            erros.push('O campo "Tipo da pessoa" do Sócio '+(index+1)+' é obrigatório.');
        }
        if (CPF_QUADRO=='') {
            erros.push('O campo "CPF ou CNPJ" do Sócio '+(index+1)+' é obrigatório.');
        } else {
            switch (TP_PESSOA_QUADRO) {
                case 'F':
                    if (!validarCPF(CPF_QUADRO)) {
                        erros.push('O "CPF" do Sócio '+(index+1)+' é inválido.');
                    }
                    break;
                case 'J':
                    if (!validarCNPJ(CPF_QUADRO)) {
                        erros.push('O "CNPJ" do Sócio '+(index+1)+' é inválido.');
                    }
                    break;
            }
        }
        if (PARTICIPACAO_QUADRO=='') {
            erros.push('O campo "Participação (%)" do Sócio '+(index+1)+' é obrigatório.');
        }
        if (erros.length>0) {
            erros.push(' ');
        }
    });
    if (somaSocios()!=100) {
        erros.push('A soma das participações dos sócios deve ser igual a 100%');
    }

    return erros;
}
function validarDadosCadastrais() {
    var erros = [];
    var form = $('form[name=frmCadastro]');

    var NM_USUARIO = form.find('input[name=NM_USUARIO]').val();
    var NM_FANTASIA = form.find('input[name=NM_FANTASIA]').val();
    var simples_nacional = form.find('select[name=simples_nacional]').val();
    var NU_INSCR_ESTADUAL = form.find('input[name=NU_INSCR_ESTADUAL]').val();
    var CAPITAL_QUADRO = form.find('input[name=CAPITAL_QUADRO]').val();
    var NU_FUNCIONARIO_EMPRESA = form.find('input[name=NU_FUNCIONARIO_EMPRESA]').val();
    var ID_TEMPO = form.find('select[name=ID_TEMPO]').val();
    var DE_CEP = form.find('input[name=DE_CEP]').val();
    var CD_UF = form.find('select[name=CD_UF]').val();
    var DE_CIDADE = form.find('input[name=DE_CIDADE]').val();
    var DE_ENDER = form.find('input[name=DE_ENDER]').val();
    var NM_CONTATO = form.find('input[name=NM_CONTATO]').val();
    var DE_CARGO = form.find('input[name=DE_CARGO]').val();
    var DE_TEL = form.find('input[name=DE_TEL]').val();
    var DE_EMAIL = form.find('input[name=DE_EMAIL]').val();
    var id_modalidade = form.find('select[name=id_modalidade]').val();
    var calendario_fiscal = form.find('select[name=calendario_fiscal]').val();
    var RE_ANUAL = form.find('input[name=RE_ANUAL]').val();
    var FT_ANUAL = form.find('input[name=FT_ANUAL]').val();
    var dre = form.find('input[name=dre]').val();
    var faturamento = form.find('input[name=faturamento]').val();
    var CHECK_PROMETE_ENVIO = form.find('input[name=CHECK_PROMETE_ENVIO]').is(':checked');
    var d_autorizar = form.find('input[name=d_autorizar]').is(':checked');

    if (NM_USUARIO=='') {
        erros.push('O campo "Razão Social" é obrigatório.');
    }
    if (NM_FANTASIA=='') {
        erros.push('O campo "Nome fantasia" é obrigatório.');
    }
    if (simples_nacional=='') {
        erros.push('O campo "Simples Nacional?" é obrigatório.');
    }
    if (NU_INSCR_ESTADUAL=='') {
        erros.push('O campo "Inscrição estadual" é obrigatório.');
    }
    if (CAPITAL_QUADRO=='') {
        erros.push('O campo "Capital social" é obrigatório.');
    }
    if (NU_FUNCIONARIO_EMPRESA=='') {
        erros.push('O campo "Nº de funcionários" é obrigatório.');
    }
    if (ID_TEMPO=='') {
        erros.push('O campo "Tempo de existência" é obrigatório.');
    }
    if (DE_CEP=='') {
        erros.push('O campo "CEP" é obrigatório.');
    }
    if (CD_UF=='') {
        erros.push('O campo "Estado" é obrigatório.');
    }
    if (DE_CIDADE=='') {
        erros.push('O campo "Cidade" é obrigatório.');
    }
    if (DE_ENDER=='') {
        erros.push('O campo "Endereço" é obrigatório.');
    }
    if (NM_CONTATO=='') {
        erros.push('O campo "Nome do contato" é obrigatório.');
    }
    if (DE_CARGO=='') {
        erros.push('O campo "Cargo do contato" é obrigatório.');
    }
    if (DE_TEL=='') {
        erros.push('O campo "Telefone do contato" é obrigatório.');
    }
    if (DE_EMAIL=='') {
        erros.push('O campo "E-mail do contato" é obrigatório.');
    }
    if (id_modalidade=='') {
        erros.push('O campo "Modalidade / Tipo de financ." é obrigatório.');
    }
    if (calendario_fiscal=='') {
        erros.push('O campo "Ano das informações" é obrigatório.');
    }
    if (RE_ANUAL=='') {
        erros.push('O campo "Faturamento bruto anual" é obrigatório.');
    }
    if (FT_ANUAL=='') {
        erros.push('O campo "Valor de exportação anual" é obrigatório.');
    }
    if (dre=='') {
        erros.push('O campo "DRE" é obrigatório.');
    }
    if (faturamento=='') {
        erros.push('O campo "Comprovante de exportações" é obrigatório.');
    }
    if (!CHECK_PROMETE_ENVIO) {
        erros.push('É necessário aceitar a solicitação de arquivos adicionais.');
    }
    if (!d_autorizar) {
        erros.push('É necessário aceitar os termos e condições de uso.');
    }

    return erros;
}
function somaSocios() {
    form = $('form[name=frmCadastro]');
    soma = 0;

    $('div.quadro-socio').each(function(key) {
        soma += parseFloat($(this).find('input.PARTICIPACAO_QUADRO').autoNumeric('get'));
    });

    return soma;
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
    $('.REAL').autoNumeric('init', {aSep:'.',aDec:',',aSign:'R$ ',pSign:'p',lZero:'deny', vMin:'0'});
    $('.DOLAR').autoNumeric('init', {aSep:'.',aDec:',',aSign:'US$ ',pSign:'p',lZero:'deny',vMin:'0'});
    $('.NUM').autoNumeric('init', {mDec:'0',pSign:'s',lZero:'deny',vMax:'99999'});
    $('.TEL').maskbrphone({useDdd : true});
}
