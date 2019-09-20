jQuery(document).ready(function() {


    //Simples Nacional
    $('.simples_nacional').on('click',function() {
        if($(this).val() == 2){
            $('.enquadramento').show('slow');
        }else{
            $('.enquadramento').hide('slow');
        }
    });

    $(".outros").change(function(){

        var	id_pergunta_resposta =	$(this).find(':selected').data('idperguntaresposta');
        var	in_outra_resposta 	 =	$(this).find(':selected').data('inoutraresposta');
        if (in_outra_resposta == 'S')
        {
            $("#in_outra_resposta_id_"+id_pergunta_resposta).show();
            ir_resposta = id_pergunta_resposta;
        }else{
            $("#in_outra_resposta_id_"+ir_resposta).val('').hide();
        }
    })


// MultiSelect Modalidades

    var modalidadeSelecionadas = [];


    $('#id_modalidade').searchableOptionList();
    // MultiSelect Financiamentos


    // Validador de cpf
      function validacpf(valor){

            CPF = valor;

            if(!CPF){ return false;}
            erro  = new String;
            cpfv  = CPF;
            if(cpfv.length == 14 || cpfv.length == 11){
                cpfv = cpfv.replace('.', '');
                cpfv = cpfv.replace('.', '');
                cpfv = cpfv.replace('-', '');

                var nonNumbers = /\D/;

                if(nonNumbers.test(cpfv)){
                    return false;
                }else{
                    if (cpfv == "00000000000" ||
                        cpfv == "11111111111" ||
                        cpfv == "22222222222" ||
                        cpfv == "33333333333" ||
                        cpfv == "44444444444" ||
                        cpfv == "55555555555" ||
                        cpfv == "66666666666" ||
                        cpfv == "77777777777" ||
                        cpfv == "88888888888" ||
                        cpfv == "99999999999") {

                          return false;
                    }
                    var a = [];
                    var b = new Number;
                    var c = 11;

                    for(i=0; i<11; i++){
                        a[i] = cpfv.charAt(i);
                        if (i < 9) b += (a[i] * --c);
                    }
                    if((x = b % 11) < 2){
                        a[9] = 0
                    }else{
                        a[9] = 11-x
                    }
                    b = 0;
                    c = 11;
                    for (y=0; y<10; y++) b += (a[y] * c--);

                    if((x = b % 11) < 2){
                        a[10] = 0;
                    }else{
                        a[10] = 11-x;
                    }
                    if((cpfv.charAt(9) != a[9]) || (cpfv.charAt(10) != a[10])){
                        return false;
                    }
                }
            }else{
                if(cpfv.length == 0){
                    return true;
                }else{
                    return false;

                }
            }
            if (erro.length > 0){


                setTimeout(function(){$(this).focus();},50);
                return false;
            }
            return true;

    }


    jQuery.fn.validacnpj = function(){
        this.change(function(){
            CNPJ = $(this).val();
            if(!CNPJ){ return false;}
            erro = new String;
            if(CNPJ == "00.000.000/0000-00"){ erro += "CNPJ inválido\n\n";}
            CNPJ = CNPJ.replace(".","");
            CNPJ = CNPJ.replace(".","");
            CNPJ = CNPJ.replace("-","");
            CNPJ = CNPJ.replace("/","");

            var a = [];
            var b = new Number;
            var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
            for(i=0; i<12; i++){
                a[i] = CNPJ.charAt(i);
                b += a[i] * c[i+1];
            }
            if((x = b % 11) < 2){
                a[12] = 0
            }else{
                a[12] = 11-x
            }
            b = 0;
            for(y=0; y<13; y++){
                b += (a[y] * c[y]);
            }
            if((x = b % 11) < 2){
                a[13] = 0;
            }else{
                a[13] = 11-x;
            }
            //if((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){ erro +="Dígito verificador com problema!";}
            if (erro.length > 0){
                $(this).val('');
                alert(erro);
                setTimeout(function(){ $(this).focus()},50);
            }
            return $(this);
        });
    }

    function validacnpj(valor){

              CNPJ = valor
              if(!CNPJ){ return false;}
              erro = new String;
              if(CNPJ == "00.000.000/0000-00"){ return false;}
              CNPJ = CNPJ.replace(".","");
              CNPJ = CNPJ.replace(".","");
              CNPJ = CNPJ.replace("-","");
              CNPJ = CNPJ.replace("/","");

              var a = [];
              var b = new Number;
              var c = [6,5,4,3,2,9,8,7,6,5,4,3,2];
              for(i=0; i<12; i++){
                  a[i] = CNPJ.charAt(i);
                  b += a[i] * c[i+1];
              }
              if((x = b % 11) < 2){
                  a[12] = 0
              }else{
                  a[12] = 11-x
              }
              b = 0;
              for(y=0; y<13; y++){
                  b += (a[y] * c[y]);
              }
              if((x = b % 11) < 2){
                  a[13] = 0;
              }else{
                  a[13] = 11-x;
              }
              if((CNPJ.charAt(12) != a[12]) || (CNPJ.charAt(13) != a[13])){return false;}
              if (erro.length > 0){

                  setTimeout(function(){ $(this).focus()},50);
              }
              return true;

      }


    // initiate layout and plugins
    Metronic.init(); // init metronic core components
    Layout.init(); // init current layout
    QuickSidebar.init(); // init quick sidebar
    Demo.init(); // init demo features
    FormWizard.init();

    $("#CPF_RESPONSAVEL").mask('999.999.999-99');

  //  $("#CPF_RESPONSAVEL").validacpf();

    //$('.CPF_QUADRO').mask('999.999.999-99');
    $('.CPF_QUADRO').live('click',function(){
        $(this).unmask();
        $(this).val('');

    })

    $('.CPF_QUADRO').live('blur',function(){

      switch ($(this).val().length) {
        case 11:
            $(this).mask('999.999.999-99');
            if(!validacpf($(this).val())){
              alert('CPF Inválido');
              $(this).focus();
              $(this).unmask();
                $(this).val('');
            }

        break;
        case 14:
        $(this).mask('99.999.999/9999-99');
        if(!validacnpj($(this).val())){
          alert('CNPJ Inválido');
          $(this).focus();
          $(this).unmask();
            $(this).val('');
        }
        break;
      }
  })



  //  $("#CPF_QUADRO1").mask('999.999.999-99');
  //  $("#CPF_QUADRO2").mask('999.999.999-99');


    //$("#CPF_QUADRO1").validacpf();
  //  $("#CPF_QUADRO2").validacpf();

    $("#NU_CNPJ").mask('99.999.999/9999-99');
    $("#NU_CNPJ").validacnpj();
    $("#ag_cnpj").mask('99.999.999/9999-99');
    $('#ag_cnpj').validacnpj();
    $("#ag_cnpj2").mask('99.999.999/9999-99');
    $('#ag_cnpj2').validacnpj();
    $("#DE_CEP").mask('99999-999');
    $("#cep_f").mask('99999-999');
    $("#cep_f2").mask('99999-999');
    $("#DE_TEL").mask('(99) 9999-9999');
    $("#DE_FAX").mask('(99) 9999-9999');
    $("#telefone_f").mask('(99) 9999-9999');
    $("#fax_f").mask('(99) 9999-9999');
    $("#telefone_f2").mask('(99) 9999-9999');
    $("#fax_f2").mask('(99) 9999-9999');
    $('.data').datepicker({
        format: 'dd/mm/yyyy',
        autoclose: true,
        language: "pt-BR"
    })

    var rota = $('#rota').val();

    $(".PARTICIPACAO").maskMoney({thousands:'.', decimal:'.', allowZero:true}).attr('maxlength','6');

    $(".PARTICIPACAO").live('click', function () {
        $(".PARTICIPACAO").maskMoney({thousands:'.', decimal:'.', allowZero:true}).attr('maxlength','6');
    })

    $(".PARTICIPACAO").live('focus', function () {
        $(".PARTICIPACAO").maskMoney({thousands:'.', decimal:'.', allowZero:true}).attr('maxlength','6');
    })



    $(".CAPITAL_QUADRO").maskMoney({thousands:'.', decimal:',', allowZero:true});
    $("#RE_ANUAL").maskMoney({thousands:'.', decimal:',', allowZero:true});
    $("#FT_ANUAL").maskMoney({thousands:'.', decimal:',', allowZero:true});
    $("#FT_ANUAL3").maskMoney({thousands:'.', decimal:',', allowZero:true});

    $("#pre-embarque").hide();
    $("#pos-embarque").hide();
    $('.gecex').hide();
    $('#id_financiador2 option[value=10000]').attr('disabled', 'disabled');

    $('#proex_pre3').on("click",function() {

        if($(this).val() == "0"){

            $('#id_financiador2 option').filter('[value="10000"]').attr('disabled', true);
        }else{
            $('#id_financiador2 option').filter('[value="10000"]').attr('disabled', false);
        }


        $.uniform.update();
    });







    $('#DT_FUNDACAO').on("change",function() {

            var data = $(this).val();
            var data1 = new Date();
            dt = data;
            dia = dt.substr(0,2);
            mes = dt.substr(3,2);
            ano = dt.substr(6,4);

            if(Date.UTC(ano,mes,dia,0,0,0)>Date.UTC(data1.getFullYear(),data1.getMonth()+1,data1.getDate(),0,0,0))
            {
               $('.dataspam').html('<span class="help-block alert alert-danger">Data de Fundação não pode ser posterior a atual!</spam>');
            }
            else
            {
                $('.dataspam').html('');
                var dif = Date.UTC(data1.getFullYear(),data1.getMonth()+1,data1.getDate(),0,0,0) - Date.UTC(ano,mes,dia,0,0,0);
                var diferenca = Math.abs((dif / 1000 / 60 / 60 / 24));
                if(diferenca<=1095) {

                    $("#ID_TEMPO option[value='1']").attr('selected','selected');

                } else
                if(diferenca>1095) {

                    $("#ID_TEMPO option[value='2']").attr('selected','selected');
                } else
                    $("#ID_TEMPO option[value='']").attr('selected','selected');

                $('#dtOk').val(1);
            }


    });
    function calculaTempoExistencia(data){

    }




    var salvouTermoPos = 0;


    var listModalidade = $('#id_modalidade').val();


    $('.tp_aceito').on("click",function() {
        $('#termo_aceito').val($(this).val())
        if(listModalidade.indexOf("1#1#1") != -1 && listModalidade.indexOf("2#5#2") != -1 && listModalidade.indexOf("2#6#3") != -1 && salvouTermoPos == 1){
            $('#termo_aceitopre').val($(this).val());
        }
    });

    $('.tp_aceitopre').on("click",function() {

            $('#termo_aceitopre').val($(this).val())

    });



    $('.salvar').on("click",function(e) {

        var termo = $('#termo_aceito').val();
        var listaModalidades = $('#id_modalidade').val();

        if(termo == 0){
            alert('Você não aceitou os termos');
        }else{

           if(listaModalidades.indexOf("1#1#1") != -1 || listaModalidades.indexOf("2#5#2") != -1 || listaModalidades.indexOf("2#6#3") != -1){
               var termopre = $('#termo_aceitopre').val();
               salvouTermoPos = salvouTermoPos + 1;

               $('#myModal').modal('hide');

               if(salvouTermoPos == 1){
                   $('#myModalPre').modal('show');
                   $.uniform.update();
               }

             if(salvouTermoPos > 1){
               if(termopre == 1){
                   $('#myModalPre').modal('hide');
               }else{
                    alert('Você não aceitou os termos');
                }
             }

           }else{

               $('#myModal, #myModalPre').modal('hide');

           }


        }

    });




    $('.imprimir').on("click",function() {

        var divContents = $("#myModal").html();
        //$('divContents .termos').hide();
        var printWindow = window.open('', '', 'height=400,width=800');
        printWindow.document.write('<html><head><title>Termos e Condições de Uso</title>');
        printWindow.document.write('</head><body >');
        printWindow.document.write(divContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();


    });

    $('.imprimirPre').on("click",function() {

        var divContents = $("#myModalPre").html();
        //$('divContents .termos').hide();
        var printWindow = window.open('', '', 'height=400,width=800');
        printWindow.document.write('<html><head><title>Termos e Condições de Uso</title>');
        printWindow.document.write('</head><body >');
        printWindow.document.write(divContents);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        printWindow.print();


    });


    /* Função para selecionar automaticamente o valor do select do pos caso o select do pre esteja selecionado */
    $(".financiador").change(function() {
        var BancoEscolhido = $(this).val();

        $('.nomefinanciador').html($(this).find(":selected").text());


        if(BancoEscolhido == ''){
            $('.financiador option').eq(0).prop('selected', true);
        }else{
            $('.financiador option[value='+BancoEscolhido+']').attr('selected','selected');

            if(BancoEscolhido == 16){ // 16 se refere ao option do banco do brasil, caso seja BB ele exibe o combo com a gecex (Agencia)
                $('.gecex').show('slow');
                  $('.OcultarDadosBanco').hide('slow');
            }else{
                $('.gecex').hide('slow');
                  $('.OcultarDadosBanco').show('slow');
                $('#id_gecex_pos').val('');
            }
        }

    });




    /* Função para selecionar automaticamente o valor do select do pos gecex caso o select do pre gecex esteja selecionado */
    $("#id_gecex_pos2").change(function() {
        var GecexEscolhida = $("#id_gecex_pos2 option:selected").val();


        if(GecexEscolhida == ''){

            $('#id_gecex_pos option').eq(0).prop('selected', true);
            //$('#id_gecex_pos option[value=0]').attr('selected','selected');

        }else{

            $('#id_gecex_pos option[value='+GecexEscolhida+']').attr('selected','selected');

        }

    });

    $('#RESP1').on("click",function() {

        $('#no_agencia2').val($('#no_agencia').val());
        $('#ag_endereco2').val($('#ag_endereco').val());
        $('#ag_cidade2').val($('#ag_cidade').val());
        $('#ag_uf2').val($('#ag_uf').val());
        $('#ag_cnpj2').val($('#ag_cnpj').val());
        $('#ag_inscr_est2').val($('#ag_inscr_est').val());
        $('#contato_fin2').val($('#contato_fin').val());
        $('#cargo_fin2').val($('#cargo_fin').val());
        $('#ddd_telefone_f2').val($('#ddd_telefone_f').val());
        $('#telefone_f2').val($('#telefone_f').val());
        $('#ddd_fax_f2').val($('#ddd_fax_f').val());
        $('#fax_f2').val($('#fax_f').val());
        $('#email_f2').val($('#email_f').val());
        $('#cep_f2').val($('#cep_f').val());

         var InstituicaoFinanceiraPre = $("#id_financiador option:selected").val();
         $('#id_financiador2 option[value='+InstituicaoFinanceiraPre+']').attr('selected','selected');

        if(InstituicaoFinanceiraPre == 16 || InstituicaoFinanceiraPre == 1086){
             $('.gecex').show('slow');
        }

        return false;
    });

    $('input [name="proex_pre3"]').on("click",function() {
        $('#proex_pre_valor2').val($(this).val());

    });
    $('input [name="proex_pre"]').on("click",function() {
        $('#proex_pre_valor').val($(this).val());

    });

    $('.financ').on("click",function() {
        $('#proex_pre_valor2').val($(this).val());

        if ($(this).val() == 2) { // Caso seja recurso proprio
            $('.recursoproprio').hide();
            $('#id_financiador2 option[value=10000]').attr('disabled', false);
            $('#id_financiador2 option[value=10000]').attr('selected', 'selected');
            $('#id_financiador2').attr('disabled', 'disabled');
            $('.gecex').hide('slow');


            $('input[name=NO_AGENCIA_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_ENDERECO_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_CIDADE_POS]').val('-').attr('disabled', 'disabled');
            $('#ag_uf2').attr('disabled', 'disabled');
            $('input[name=AG_CNPJ_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_INSCR_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_CONTATO_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_CARGO_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_TEL_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_FAX_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_EMAIL_POS]').val('-').attr('disabled', 'disabled');
            $('input[name=AG_CEP_POS]').val('-').attr('disabled', 'disabled');



        }

        else if ($(this).val() == 1) { // caso seja proex
            $('.recursoproprio').show();
            $('#id_financiador2 option[value=10000]').attr('disabled', 'disabled');
            $('#id_financiador2 option[value=16]').attr('selected', 'selected');
            $('#id_financiador2').attr('disabled', 'disabled');


            $('.gecex').show('slow');


            $('input[name=NO_AGENCIA_POS]').val('').attr('disabled', false);
            $('input[name=AG_ENDERECO_POS]').val('').attr('disabled', false);
            $('input[name=AG_CIDADE_POS]').val('').attr('disabled', false);
            $('#ag_uf2').attr('disabled', false);
            $('input[name=AG_CNPJ_POS]').val('').attr('disabled', false);
            $('input[name=AG_INSCR_POS]').val('').attr('disabled', false);
            $('input[name=AG_CONTATO_POS]').val('').attr('disabled', false);
            $('input[name=AG_CARGO_POS]').val('').attr('disabled', false);
            $('input[name=AG_TEL_POS]').val('').attr('disabled', false);
            $('input[name=AG_FAX_POS]').val('').attr('disabled', false);
            $('input[name=AG_EMAIL_POS]').val('').attr('disabled',false);
            $('input[name=AG_CEP_POS]').val('').attr('disabled', false);
        }

        else{ // caso nao seja nenhuma opcao acima
            $('.recursoproprio').show();
        $('#id_financiador2 option[value=""]').attr('selected', 'selected');
        $('#id_financiador2 option[value=10000]').attr('disabled', 'disabled');
        $('#id_financiador2').attr('disabled', false);
        $('.gecex').show('slow');


            $('input[name=NO_AGENCIA_POS]').val('').attr('disabled', false);
            $('input[name=AG_ENDERECO_POS]').val('').attr('disabled', false);
            $('input[name=AG_CIDADE_POS]').val('').attr('disabled', false);
            $('#ag_uf2').attr('disabled', 'disabled');
            $('input[name=AG_CNPJ_POS]').val('').attr('disabled', false);
            $('input[name=AG_INSCR_POS]').val('').attr('disabled', false);
            $('input[name=AG_CONTATO_POS]').val('').attr('disabled', false);
            $('input[name=AG_CARGO_POS]').val('').attr('disabled', false);
            $('input[name=AG_TEL_POS]').val('').attr('disabled', false);
            $('input[name=AG_FAX_POS]').val('').attr('disabled', false);
            $('input[name=AG_EMAIL_POS]').val('').attr('disabled',false);
            $('input[name=AG_CEP_POS]').val('').attr('disabled', false);

       }
        return false;
    });


    $("input[name='FT_ANUAL']").change(function() {

        if (parseFloat($("input[name='FT_ANUAL']").val().replace(".", "").replace(".", "").replace(".", "").replace(".", "").replace(".", "").replace(",", ".")) > 5000000) {
            $('.vlExport').html('<span class="help-block alert alert-danger text-center">Seu faturamento não se enquadra no perfil de MPME.</span>');
        }else{
            $('.vlExport').html('');
        }
    });

    $("input[name='RE_ANUAL']").change(function() {

        if (parseFloat($("input[name='RE_ANUAL']").val().replace(".", "").replace(".", "").replace(".", "").replace(".", "").replace(".", "").replace(",", ".")) > 300000000) {
            $('#perfil').html('<span class="help-block alert alert-danger text-center">Seu faturamento não se enquadra no perfil de MPME.</span>');
        }else{
            $('#perfil').html('');
        }
    });

    $("#id_modalidade").on('change',function () {
        var listaModalidades = $('#id_modalidade').val();
        console.log(listaModalidades);
        var tipoModalidade = [];


        if(listaModalidades === undefined || listaModalidades === null){
            $("#pre-embarque").hide();
            $("#pos-embarque").hide();
            $('.repetir').hide();
        }else{






        // Verifica se o foi selecionado alguma das opções de pre-pos
        if(listaModalidades.indexOf("2#5#2") != -1 || listaModalidades.indexOf("2#6#3") != -1){
           // $('#d_autorizar').attr('data-target','#myModalPre');
            $("#pre-embarque").show('slow');
            $("#pos-embarque").show('slow');
            $('.repetir').show('slow');
            $('.txt-termo').html('');
            $('.txt-termo').html('Para darmos continuidade a sua análise, a partir do envio do seu cadastro, solicitamos encaminhar, o mais breve possível, os 3 últimos Balanços Patrimoniais e Demonstrativos de Resultado Anuais.');

            $('input[name=NO_AGENCIA_POS]').val('').attr('disabled', false);
            $('input[name=AG_ENDERECO_POS]').val('').attr('disabled', false);
            $('input[name=AG_CIDADE_POS]').val('').attr('disabled', false);
            $('#ag_uf2').attr('disabled', 'disabled');
            $('input[name=AG_CNPJ_POS]').val('').attr('disabled', false);
            $('input[name=AG_INSCR_POS]').val('').attr('disabled', false);
            $('input[name=AG_CONTATO_POS]').val('').attr('disabled', false);
            $('input[name=AG_CARGO_POS]').val('').attr('disabled', false);
            $('input[name=AG_TEL_POS]').val('').attr('disabled', false);
            $('input[name=AG_FAX_POS]').val('').attr('disabled', false);
            $('input[name=AG_EMAIL_POS]').val('').attr('disabled',false);
            $('input[name=AG_CEP_POS]').val('').attr('disabled', false);
            $('#id_financiador2').attr('disabled', false);
        }


        // Apenas Pos embarque

        if(listaModalidades.indexOf("3#4#4") != -1 || listaModalidades.indexOf("3#3#5") != -1 || listaModalidades.indexOf("3#2#6") != -1){
            $('#d_autorizar').attr('data-target','#myModal');

            $('.txt-termo').html('');
            $('.txt-termo').html('Para darmos continuidade a sua análise, a partir do envio do seu cadastro, solicitamos encaminhar, o mais breve possível, o Demonstrativo de Resultado relativo ao ano civil anterior.');
            $("#pos-embarque").show('slow');
        }




            if(listaModalidades.indexOf("3#4#4") != -1 || listaModalidades.indexOf("3#3#5") != -1 || listaModalidades.indexOf("3#2#6") != -1){
                $('#d_autorizar').attr('data-target','#myModal');

                $('.txt-termo').html('');
                $('.txt-termo').html('Para darmos continuidade a sua análise, a partir do envio do seu cadastro, solicitamos encaminhar, o mais breve possível, o Demonstrativo de Resultado relativo ao ano civil anterior.');
                $("#pos-embarque").show('slow');
            }



            //Oculta os campos caso não tenha nenhuma opção de pos embarque selecionado
        if(listaModalidades.indexOf("2#5#2") == -1 && listaModalidades.indexOf("2#6#3") == -1 && listaModalidades.indexOf("3#4#4") == -1 && listaModalidades.indexOf("3#3#5") == -1 && listaModalidades.indexOf("3#2#6") == -1){
            $("#pos-embarque").hide('slow');
        }

        // OculTa a opção de pré-embarque caso só tenha opção de POS selecionada
        if(listaModalidades.indexOf("1#1#1") == -1 && listaModalidades.indexOf("2#5#2") == -1 && listaModalidades.indexOf("2#6#3") == -1){
            $("#pre-embarque").hide('slow');
        }

        } // fecha else da verificacao do array vazio

    });




    $('#LOGIN').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $('#NM_USUARIO').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $('#DE_CARGO').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $('#ag_endereco2').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $('.maiusculo').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $('#NM_FANTASIA').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $('#NM_RESPONSAVEL').keyup(function() {
        this.value = this.value.toUpperCase();
    });
    $('#NOME_QUADRO').keyup(function() {
        this.value = this.value.toUpperCase();
    });
    $('#NOME_QUADRO1').keyup(function() {
        this.value = this.value.toUpperCase();
    });
    $('#NOME_QUADRO2').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $('#NM_CONTATO').keyup(function() {
        this.value = this.value.toUpperCase();
    });

    $("#LOGIN").on('blur',function () { // Ao perder o foco verifica se usuário ja existe
        var usuario = $(this).val();

        if(usuario == ""){
            $("#VerUsuario").html('<span class="help-block alert alert-danger">Digite o Usuário!</span>');
            $('#LOGIN').focus();
        }else{
                $.ajax({
                        type: "GET",
                        url: rota+'/buscarusuariopornome/' + usuario, // defini a rota com a condição if acima
                        success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
                        {
                            if(retorno == '1') {
                                $("#VerUsuario").html('<span class="help-block alert alert-danger">Usuário ja cadastrado!</span>');
                                $('#LOGIN').focus();

                            }else{
                                $("#VerUsuario").html('<span class="help-block alert alert-success">Usuário disponivel!</span>');
                            }
                        },
                        error: function(erro) {

                        }
                    });
        }
    });


    $("#NU_CNPJ").on('blur',function () { // Ao perder o foco verifica se usuário ja existe
        var cpnj = $(this).val();
        var qtd = 0;
        if(cpnj == ""){
            $("#VerCnpj").html('<span class="help-block alert alert-danger">Digite o CNPJ!</span>');
            $('#NU_CNPJ').focus();
        }else{



            if(qtd < 2) {

                $.ajax({
                    type: "POST",
                    url: "/buscarcnpj", // defini a rota com a condição if acima
                    data: {cnpj: $('#NU_CNPJ').val()},
                    success: function (retorno) // retorna um json com a mensagem de erro ou sucesso
                    {
                        if (retorno == '1') {
                            //$("#VerCnpj").html('<span class="help-block alert alert-danger">CNPJ Já Cadastrado, acesse seu e-mail!</span>');
                            swal("CNPJ já cadastrado, enviamos um link de atualização cadastral para o e-mail anteriormente cadastrado!", "", "error");
                            setTimeout(function(){ location.reload(); }, 3000);

                        }
                    },
                    error: function (erro) {
                        alert('erro');
                    }
                });
            }
        }
    });


    $("#CPF_RESPONSAVEL").on('blur',function () { // Ao perder o foco verifica se usuário ja existe
        var usuario = $(this).val();

        if(usuario == ""){
            $("#VerUsuario").html('<span class="help-block alert alert-danger">Digite o Usuário!</span>');
            $('#LOGIN').focus();
        }else{
            $.ajax({
                type: "GET",
                url: rota+'/buscarusuariopornome/' + usuario, // defini a rota com a condição if acima
                success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
                {
                    if(retorno == '1') {
                        $("#VerUsuario").html('<span class="help-block alert alert-danger">Usuário ja cadastrado!</span>');
                        $('#LOGIN').focus();

                    }else{
                        $("#VerUsuario").html('<span class="help-block alert alert-success">Usuário disponivel!</span>');
                    }
                },
                error: function(erro) {

                }
            });
        }
    });



    $("#NU_CNPJ").on('blur',function () { // Ao perder o foco verifica se usuário ja existe
        var usuario = $(this).val();

        if(usuario == ""){
            $("#VerUsuario").html('<span class="help-block alert alert-danger">Digite o Usuário!</span>');
            $('#LOGIN').focus();
        }else{
            $.ajax({
                type: "GET",
                url: rota+'/buscarusuariopornome/' + usuario, // defini a rota com a condição if acima
                success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
                {
                    if(retorno == '1') {
                        $("#VerUsuario").html('<span class="help-block alert alert-danger">Usuário ja cadastrado!</span>');
                        $('#LOGIN').focus();

                    }else{
                        $("#VerUsuario").html('<span class="help-block alert alert-success">Usuário disponivel!</span>');
                    }
                },
                error: function(erro) {

                }
            });
        }
    });





    $("#NM_USUARIO").on('blur',function () {
        var NM_USUARIO = $(this).val();
        $('.rz_social').html(NM_USUARIO);
    });

    $("#NU_CNPJ").on('blur',function () {
        var NU_CNPJ = $(this).val();
        $('.rz_socialcnpj').html(NU_CNPJ);
    });



// Carrega dados agencia Quando a modalidade é POS
    $(".SelectGecex").on('change',function () {
        var gecex = $(this).val();
/*
        $.ajax({
                type: "GET",
                url: rota+'/retornaenderecogecex/' + gecex, // defini a rota com a condição if acima
                dataType: "json",
                success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
                {

                  switch ($("select[name='ID_MODALIDADE']").val()) {
                    case '2':

                    // Dados do Pre
                    $("input[name='NO_AGENCIA_PRE']").val(retorno.ID_USUARIO_FK); // Precisamos da agencia
                    $("input[name='AG_CEP_PRE']").val(retorno.DE_CEP);
                    $("input[name='AG_ENDERECO_PRE']").val(retorno.DE_ENDERECO);
                    $("input[name='AG_CIDADE_PRE']").val(retorno.DE_CIDADE);
                    $("select[name='AG_ESTADO_PRE'] option[value="+retorno.DE_ESTADO+"]").prop("selected", "selected");
                    $("input[name='AG_CNPJ_PRE']").val('-'); // precisamos do cnpj
                    $("input[name='AG_INSCR_PRE']").val('-'); // precisamos da inscricao estadual
                    $("input[name='AG_CONTATO_PRE']").val(retorno.NO_CONTATO_BANCO);
                    $("input[name='AG_CARGO_PRE']").val('-'); // Precisamos do cargo
                    $("input[name='AG_TEL_PRE']").val(retorno.DE_TELEFONE);
                    $("input[name='AG_EMAIL_PRE']").val(retorno.DE_EMAIL);

                    // Dados do POS
                    $("input[name='NO_AGENCIA_POS']").val(retorno.ID_USUARIO_FK); // Precisamos da agencia
                    $("input[name='AG_CEP_POS']").val(retorno.DE_CEP);
                    $("input[name='AG_ENDERECO_POS']").val(retorno.DE_ENDERECO);
                    $("input[name='AG_CIDADE_POS']").val(retorno.DE_CIDADE);
                    $("select[name='AG_ESTADO_POS'] option[value="+retorno.DE_ESTADO+"]").prop("selected", "selected");
                    $("input[name='AG_CNPJ_POS']").val('-'); // precisamos do cnpj
                    $("input[name='AG_INSCR_POS']").val('-'); // precisamos da inscricao estadual
                    $("input[name='AG_CONTATO_POS']").val(retorno.NO_CONTATO_BANCO);
                    $("input[name='AG_CARGO_POS']").val('-'); // Precisamos do cargo
                    $("input[name='AG_TEL_POS']").val(retorno.DE_TELEFONE);
                    $("input[name='AG_EMAIL_POS']").val(retorno.DE_EMAIL);

                    break;
                      case '3':
                      // Dados do POS
                      $("input[name='NO_AGENCIA_POS']").val(retorno.ID_USUARIO_FK); // Precisamos da agencia
                      $("input[name='AG_CEP_POS']").val(retorno.DE_CEP);
                      $("input[name='AG_ENDERECO_POS']").val(retorno.DE_ENDERECO);
                      $("input[name='AG_CIDADE_POS']").val(retorno.DE_CIDADE);
                      $("select[name='AG_ESTADO_POS'] option[value="+retorno.DE_ESTADO+"]").prop("selected", "selected");
                      $("input[name='AG_CNPJ_POS']").val('-'); // precisamos do cnpj
                      $("input[name='AG_INSCR_POS']").val('-'); // precisamos da inscricao estadual
                      $("input[name='AG_CONTATO_POS']").val(retorno.NO_CONTATO_BANCO);
                      $("input[name='AG_CARGO_POS']").val('-'); // Precisamos do cargo
                      $("input[name='AG_TEL_POS']").val(retorno.DE_TELEFONE);
                      $("input[name='AG_EMAIL_POS']").val(retorno.DE_EMAIL);
                    break;
                  }
                  $('.OcultarDadosBanco').hide();
                },
                beforeSend: function() {
                  switch ($("select[name='ID_MODALIDADE']").val()) {
                    case 2:
                  // Dados do Pre

                  $("input[name='NO_AGENCIA_PRE']").val('Carregando...'); // Precisamos da agencia
                  $("input[name='AG_CEP_PRE']").val('Carregando...');
                  $("input[name='AG_ENDERECO_PRE']").val('Carregando...');
                  $("input[name='AG_CIDADE_PRE']").val('Carregando...');
                  $("input[name='AG_CNPJ_PRE']").val('Carregando...'); // precisamos do cnpj
                  $("input[name='AG_INSCR_PRE']").val('Carregando...'); // precisamos da inscricao estadual
                  $("input[name='AG_CONTATO_PRE']").val('Carregando...');
                  $("input[name='AG_CARGO_PRE']").val('Carregando...'); // Precisamos do cargo
                  $("input[name='AG_TEL_PRE']").val('Carregando...');
                  $("input[name='AG_EMAIL_PRE']").val('Carregando...');


                  // Dados do Pos

                  $("input[name='NO_AGENCIA_POS']").val('Carregando...'); // Precisamos da agencia
                  $("input[name='AG_CEP_POS']").val('Carregando...');
                  $("input[name='AG_ENDERECO_POS']").val('Carregando...');
                  $("input[name='AG_CIDADE_POS']").val('Carregando...');
                  $("input[name='AG_CNPJ_POS']").val('Carregando...'); // precisamos do cnpj
                  $("input[name='AG_INSCR_POS']").val('Carregando...'); // precisamos da inscricao estadual
                  $("input[name='AG_CONTATO_POS']").val('Carregando...');
                  $("input[name='AG_CARGO_POS']").val('Carregando...'); // Precisamos do cargo
                  $("input[name='AG_TEL_POS']").val('Carregando...');
                  $("input[name='AG_EMAIL_POS']").val('Carregando...');

                  break;

                  case 3:
                  // Dados do Pos

                  $("input[name='NO_AGENCIA_POS']").val('Carregando...'); // Precisamos da agencia
                  $("input[name='AG_CEP_POS']").val('Carregando...');
                  $("input[name='AG_ENDERECO_POS']").val('Carregando...');
                  $("input[name='AG_CIDADE_POS']").val('Carregando...');
                  $("input[name='AG_CNPJ_POS']").val('Carregando...'); // precisamos do cnpj
                  $("input[name='AG_INSCR_POS']").val('Carregando...'); // precisamos da inscricao estadual
                  $("input[name='AG_CONTATO_POS']").val('Carregando...');
                  $("input[name='AG_CARGO_POS']").val('Carregando...'); // Precisamos do cargo
                  $("input[name='AG_TEL_POS']").val('Carregando...');
                  $("input[name='AG_EMAIL_POS']").val('Carregando...');

                  break;

                }
              },
                error: function(erro) {

                }
            });
            */
    });



    $("#EMAIL_RESPONSAVEL").on('blur',function () { // Ao perder o foco verifica se usuário ja existe
        var email = $(this).val();

        $.ajax({
                type: "GET",
                url:  rota+'/validaremail/' + email, // defini a rota com a condição if acima
                success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
                {
                    if(retorno == '1') {
                        $(".erromail").html('<span class="help-block alert alert-danger">E-mail Inválido!</span>');
                    }
                },
                error: function(erro) {

                }
            }
        );

    });

    $("#email_f").on('blur',function () { // Ao perder o foco verifica se usuário ja existe
        var email = $(this).val();

        $.ajax({
                type: "GET",
                url:  rota+'/validaremail/' + email, // defini a rota com a condição if acima
                success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
                {
                    if(retorno == '1') {
                        $(".erromail").html('<span class="help-block alert alert-danger">E-mail Inválido!</span>');
                    }else{
                        $(".erromail").html('<span class="help-block alert alert-success">E-mail Valido!</span>');
                    }
                },
                error: function(erro) {

                }
            }
        );

    });


    $("#DE_CEP").on("focusout",function(){
        var cepExportador = $(this).val().replace("-","");

        if(cepExportador.length < 9){
            $.getJSON(
                "http://cep.correiocontrol.com.br/"+cepExportador+".json",
                function(data){
                    $('#DE_ENDER').val(data.logradouro);
                    $('#DE_CIDADE').val(data.localidade);
                    $("#CD_UF option[value="+data.uf+"]").attr('selected','selected');
                }

            );

        }
    });
    $("#cep_f").on("focusout",function(){
        var cepExportador = $(this).val().replace("-","");

        if(cepExportador.length < 9){
            $.getJSON(
                "http://cep.correiocontrol.com.br/"+cepExportador+".json",
                function(data){
                    $('#ag_endereco').val(data.logradouro);
                    $('#ag_cidade').val(data.localidade);
                    $("#ag_uf option[value="+data.uf+"]").attr('selected','selected');
                }

            );

        }
    });

        $("#cep_f2").on("focusout",function(){
        var cepExportador = $(this).val().replace("-","");

        if(cepExportador.length < 9){
            $.getJSON(
                "http://cep.correiocontrol.com.br/"+cepExportador+".json",
                function(data){
                    $('#ag_endereco2').val(data.logradouro);
                    $('#ag_cidade2').val(data.localidade);
                    $("#ag_uf2 option[value="+data.uf+"]").attr('selected','selected');
                }

            );

        }
    });

    $("#DS_SENHA_C").on("focusout",function(){
        var senha = $('#DS_SENHA').val();

        if($('#DS_SENHA_C').val() != senha ){
             alert('As senhas devem ser iguais!');
            $('#DS_SENHA').focus();
        }
    });


    var scntDiv = $('#NSocios');
    var i = $('#scntDiv').size() + 1;
    $('#BotaoRemover').hide();
    $("#AdicionarSocio").on("click",function(){
    //$('#NSocios').show('slow');
   //$('#BotaoRemover').show('slow');
        var length = $('.PARTICIPACAO').length;
        $("#outros_socios").append('<hr><div class="form-group">'+
            '<label class="control-label col-md-3">Nome do Sócio <span class="required">'+
            '* </span>'+
            '</label>'+
            '<div class="col-md-4">'+
            '<input type="text" class="form-control" name="NOME_QUADRO[]" id="NOME_QUADRO-'+length+'" required title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio"/>'+
            '<span class="help-block">'+
            'Nome completo do Sócio </span>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
            '<label class="control-label col-md-3">CPF / CNPJ <span class="required">* </span>'+
            '</label>'+
            '<div class="col-md-4">'+
            '<input type="text" class="form-control CPF_QUADRO" name="CPF_QUADRO[]" id="CPF_QUADRO-'+length+'" required title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio"/>'+
            '<span class="help-block">'+
            'Digite o CPF ou CNPJ do sócio (Apenas numeros)</span>'+
        '</div>'+
        '</div>'+
        '<div class="form-group">'+
            '<label class="control-label col-md-3">Participação (%) <span class="required">'+
            '* </span>'+
            '</label>'+
            '<div class="col-md-4">'+
            '<input type="text" class="form-control PARTICIPACAO" name="PARTICIPACAO_QUADRO[]" id="PARTICIPACAO_QUADRO-'+length+'" class="PARTICIPACAO" required title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio" value="0.00"/>'+
            '<span class="help-block">'+
            'Digite a participação (%) </span>'+
        '</div>'+
        '</div>');
        
        $('#quant_socios').val(length);
        
/*
        if(i < 3){

            $('<div class="form-group"><label class="control-label col-md-3">Nome do Sócio ' + i + '<span class="required">* </span></label><div class="col-md-4"><input type="text" class="form-control NOME_QUADRO" name="NOME_QUADRO' + i + '" id="NOME_QUADRO' + i + '"/><span class="help-block">Nome completo do Nome do Sócio ' + i + '</span></div></div><div class="form-group"><label class="control-label col-md-3">CPF <span class="required">* </span></label><div class="col-md-4"><input type="text" class="form-control CPF_QUADRO" name="CPF_QUADRO' + i + '" id="CPF_QUADRO' + i + '"/><span class="help-block">Digite o CPF do Socio </span></div></div><div class="form-group"><label class="control-label col-md-3">Participação (%) <span class="required">* </span></label><div class="col-md-4"><input type="text" class="form-control PARTICIPACAO" name="PARTICIPACAO_QUADRO' + i + '" id="PARTICIPACAO_QUADRO' + i + '"/><span class="help-block">Digite a Participação (%) </span></div></div>').appendTo(scntDiv);

            $(".PARTICIPACAO").maskMoney({thousands:'.', decimal:'.', allowZero:true}).attr('maxlength','6');
            $(".CAPITAL_QUADRO").maskMoney({thousands:'.', decimal:',', allowZero:true});
            $(".CPF_QUADRO").mask('999.999.999-99');
            $(".CPF_QUADRO").validacpf();
            $('.NOME_QUADRO').keyup(function() {
                this.value = this.value.toUpperCase();
            });
            i++;
        }else{
            $('#BotaoRemover').show('slow');
        }

*/

        return false;
    });


    $('#BotaoRemover').on("click",function() {
        $("#NSocios").hide('slow');
        //$("#NSocios").hide('slow');

        $("#BotaoRemover").hide('slow');
        i = 1;
        return false;

    });


    $('.atualizardadosexportador').on("click",function() {

        var dados = $('form[name=frmAtualizaExportador]').serialize();
        $.ajax({
            type: "POST",
            url: '/atualizarexportador',
            data: dados,
            beforeSend: function() {
                // setting a timeout
                $('#form_wizard_1 .button-submit').html('enviando...').attr('disabled','disabled');
            },
            success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
            {
                if(retorno == 'erro_validacao'){
                    swal("Todos os campos \n devem ser preenchidos!", "", "error");
                }else{
                        swal(retorno, "", "success");
                        setTimeout(function(){
                           // window.location.href = "http://www.abgf.gov.br";
                        }, 1000);
                }
            },
            error: function(erro) {
                //console.log(erro);
                //alert('ocorreu um erro ao efetuar seu cadastro, entre em contato com o setor de tecnilogia da ABGF para verificar o ocorrido. Cod do erro: 0012015')
                swal(error, "", "Error");
            }
        });


        return false;

    });





});
