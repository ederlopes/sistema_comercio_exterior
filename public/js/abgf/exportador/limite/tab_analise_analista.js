$(document).ready(function () {
    var alcadaAtual = $('#ID_MPME_ALCADA_ATUAL').val();
    var alcadaAtualAtual = $('#ID_MPME_ALCADA_ATUAL').val();
    
    var alcadaAnterior = $('#ID_ALCADA_ANTERIOR').val();




    $('#' + alcadaAtual + ' .subsTituirArquivo').on('change',function(){

       $('#' + alcadaAtual + ' .manterArquivo').hide('slow');

       $('#' + alcadaAtual + ' .novoArquivo').show('slow');

    });


    $('#' + alcadaAtual + ' .manterArquivoAtual').on('change',function(){

        $('#' + alcadaAtual + ' .manterArquivo').show('slow');

        $('#' + alcadaAtual + ' .novoArquivo').hide('slow');

    });
    
    $('#' + alcadaAtual + ' .COD_UNICO_OPERACAO').on('blur',function(){

        var codUnicoOp = $(this).val();
        var id_oper   = $('#ID_OPER').val();

        $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE+'ajax/consulta-codigo-unico',
                data: { codUnico:codUnicoOp, id_oper:id_oper },
                success: function(retorno) {
                    switch (retorno.status) {
                        case 'erro':
                            var alerta = swal("Erro!",retorno.msg,"error");
                            break;
                        case 'alerta':
                            var alerta = swal("Ops!",retorno.msg,"warning");
                            $('#' + alcadaAtual + ' #salvar').prop('disabled',true);
                            break;
                        case 'info':
                        $('#' + alcadaAtual + ' #salvar').prop('disabled',false);
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

    });



    $(".tabs_alcadas").on('click',function(e){
        e.preventDefault();
        alcadaAtual = $(this).data('idAlcada');
        
        //Oculta o bottão de salvar nas abas das alcadas anteriores
        if(alcadaAtualAtual != alcadaAtual){
          
             $('#' + alcadaAtual + ' #salvar').hide()
             $('#' + alcadaAtual + ' .DT_RECOMENDACAO').prop( "disabled", true );
             $('#' + alcadaAtual + ' #ds_recomendacao').prop( "disabled", true );
             $('#' + alcadaAtual + ' #vl_cred_concedido').prop( "disabled", true );
             $('#' + alcadaAtual + ' #id_mpme_fundo_garantia').prop( "disabled", true );
             $('#' + alcadaAtual + ' input[name="optradio"]').prop( "disabled", true );
             $('#' + alcadaAtual + ' input[name="resp"]').prop( "disabled", true );

            $('#' + alcadaAtual + ' .visualizarUpload').prop( "disabled", true );
            $('#' + alcadaAtual + ' .reenviarUpload').prop( "disabled", true );
            $('#' + alcadaAtual + ' .historicoParecer').hide()

            $('#' + alcadaAtual + ' input').prop( "disabled", true );
            $('#' + alcadaAtual + ' select').prop( "disabled", true );
            $('#' + alcadaAtual + ' textarea').prop( "disabled", true );


            $('#' + alcadaAtual + ' #aval1').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval3').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval4').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval5').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval6').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval7').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval8').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval9').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval92').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval10').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval11').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval12').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval13').prop( "disabled", true );
            $('#' + alcadaAtual + ' #aval14').prop( "disabled", true );
            $('#' + alcadaAtual + ' #r7').prop( "disabled", true );

             $('#' + alcadaAtual + ' #btnRecalcularCreditScore_pre').hide();
             $('#' + alcadaAtual + ' #btnRecalcularCreditScore').hide();    
           
         

        }
        
        if($('#modalidadeOperacao').val() == 1 || $('#modalidadeOperacao').val() == 2)
        {
           if ($('#' + alcadaAtual + ' .resp_pre:checked').val() == 1) {
      
            
            $('#' + alcadaAtual + ' #cred37_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred31_pre').show();
            $('#' + alcadaAtual + ' #cred32_pre').show();
            $('#' + alcadaAtual + ' #cred33_pre').show();
            $('#' + alcadaAtual + ' #cred38_pre').show();
            $('#' + alcadaAtual + ' #cred34_pre').show();
            $('#' + alcadaAtual + ' #cred35_pre').show();
            $('#' + alcadaAtual + ' #cred36_pre').show();
            $('#' + alcadaAtual + ' #cred1_pre').show();
            $('#' + alcadaAtual + ' #cred2_pre').show();
          
            CalcSomatorioCadastral_pre();
            CalcSomatorioQualidadeInfo_pre();
            CalcSomatorioAnaliseSetorial_pre();
            CalcSomatorioIndicadoresFinanceiros_pre();
            somaColunas_pre();
            scores_pre();

        } else {
            
            $('#' + alcadaAtual + ' #cred36_pre').hide('slow');
            $('#' + alcadaAtual + ' #cred37_pre').show();
            $('#' + alcadaAtual + ' #cred31_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred32_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred33_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred38_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred34_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred35_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred36_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred1_pre').show();
            $('#' + alcadaAtual + ' #cred2_pre').show();
            CalcSomatorioCadastral_pre();
            CalcSomatorioQualidadeInfo_pre();
            CalcSomatorioAnaliseSetorial_pre();
            CalcSomatorioIndicadoresFinanceiros_pre();
            somaColunas_pre();
            scores_pre();
        }
        
        }
        
        
        if($('#modalidadeOperacao').val() == 2 || $('#modalidadeOperacao').val() == 3)
        { 
                // para pos embarque
                if ($('#' + alcadaAtual + ' .resp:checked').val() == 1) {

                    $('#' + alcadaAtual + ' #cred37').hide().find('input').val('');
                    $('#' + alcadaAtual + ' #cred31').show();
                    $('#' + alcadaAtual + ' #cred32').show();
                    $('#' + alcadaAtual + ' #cred33').show();
                    $('#' + alcadaAtual + ' #cred38').show();
                    $('#' + alcadaAtual + ' #cred34').show();
                    $('#' + alcadaAtual + ' #cred35').show();
                    $('#' + alcadaAtual + ' #cred36').show();
                    $('#' + alcadaAtual + ' #cred1').show();
                    $('#' + alcadaAtual + ' #cred2').show();
                    CalcSomatorioCadastral();
                    CalcSomatorioQualidadeInfo();
                    CalcSomatorioAnaliseSetorial();
                    CalcSomatorioIndicadoresFinanceiros();
                    somaColunas();
                    scores();

                } else {
                    $('#' + alcadaAtual + ' #cred37').show();
                    $('#' + alcadaAtual + ' #cred31').hide().find('input').val('');
                    $('#' + alcadaAtual + ' #cred32').hide().find('input').val('');
                    $('#' + alcadaAtual + ' #cred33').hide().find('input').val('');
                    $('#' + alcadaAtual + ' #cred38').hide().find('input').val('');
                    $('#' + alcadaAtual + ' #cred34').hide().find('input').val('');
                    $('#' + alcadaAtual + ' #cred35').hide().find('input').val('');
                    $('#' + alcadaAtual + ' #cred36').hide().find('input').val('');
                    $('#' + alcadaAtual + ' #cred1').show();
                    $('#' + alcadaAtual + ' #cred2').show();
                    CalcSomatorioCadastral();
                    CalcSomatorioQualidadeInfo();
                    CalcSomatorioAnaliseSetorial();
                    CalcSomatorioIndicadoresFinanceiros();
                    somaColunas();
                    scores();
                }
                
             
        }
        
        
        
        
        $(document).on('click', '#' + alcadaAtual + ' #btnRecalcularCreditScore', function (e) {

            if(alcadaAtual > 2){


                swal({
                    title: 'Qual motivo da alteração do credit-score ?',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Salvar',
                    showLoaderOnConfirm: true,
                    preConfirm: (mt) => {
                        $('#' + alcadaAtual + ' #MOTIVO_ALTERACAO_CREDIT_SCORE').val(mt);
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });



            }

            e.preventDefault();
            CalcSomatorioCadastral();
            CalcSomatorioQualidadeInfo();
            CalcSomatorioAnaliseSetorial();
            CalcSomatorioIndicadoresFinanceiros();
            somaColunas();
            scores();

        });

        $(document).on('click', '#' + alcadaAtual + ' #btnRecalcularCreditScore_pre', function (e) {

            if(alcadaAtual > 2){


                swal({
                    title: 'Qual motivo da alteração do credit-score ?',
                    input: 'text',
                    inputAttributes: {
                        autocapitalize: 'off'
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Salvar',
                    showLoaderOnConfirm: true,
                    preConfirm: (mt) => {
                        $('#' + alcadaAtual + ' #MOTIVO_ALTERACAO_CREDIT_SCORE').val(mt);
                    },
                    allowOutsideClick: () => !swal.isLoading()
                });



            }


            e.preventDefault();
            CalcSomatorioCadastral_pre();
            CalcSomatorioQualidadeInfo_pre();
            CalcSomatorioAnaliseSetorial_pre();
            CalcSomatorioIndicadoresFinanceiros_pre();
            somaColunas_pre();
            scores_pre();

        });
    
        
    });

 /***
     * 
     *  
     *    Utilizado apenas caso haja a molidade pre-embarque 
     *    
     *    
     *    
     ***/

  if ($('#modalidadeOperacao').val() != 3) {
      
     function scores_pre()
    {

        var total_geral = parseInt($("#" + alcadaAtual + " #rRR4_pre").html());
        var ent = false;


        try {
            if (($('#' + alcadaAtual + ' #aval9_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval10_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval12_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval11_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval13_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval14_pre').val() == "5")) {
                $('#' + alcadaAtual + ' #id_aprova_pre').val('2');
                $('#' + alcadaAtual + ' #id_aprova_pre').attr('disabled', true);
                $('#' + alcadaAtual + ' #r7_pre').val('E');
                $('#' + alcadaAtual + ' #r8_pre').html('---');
                $('#' + alcadaAtual + ' #r9_pre').html('<font color="red"><b>SIM</b></font>');
                //////$('#vl_cred_concedido').value = "0,00";
                scoreAtual = 'E';
                ent = true;
            }
        } catch (e) {
        }

        try {
            if (($('#' + alcadaAtual + ' #aval92_pre').val() == "5")) {
                $('#' + alcadaAtual + ' #id_aprova_pre').val('2');
                $('#' + alcadaAtual + ' #id_aprova_pre').attr('disabled', true);
                $('#' + alcadaAtual + ' #r7_pre').val('E');
                $('#' + alcadaAtual + ' #r8_pre').html('---');
                $('#' + alcadaAtual + ' #r9_pre').html('<font color="red"><b>SIM</b></font>');
                //////$('#vl_cred_concedido').value = "0,00";
                scoreAtual = 'E';
                ent = true;
            }
        } catch (e) {
        }

        if (($('#' + alcadaAtual + ' #aval1_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval3_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval4_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval5_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval6_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval7_pre').val() == "5") || ($('#' + alcadaAtual + ' #aval8_pre').val() == "5")) {
            $('#' + alcadaAtual + ' #id_aprova_pre').val('2');
            $('#' + alcadaAtual + ' #id_aprova_pre').attr('disabled', true);
            $('#' + alcadaAtual + ' #r7_pre').val('E');
            $('#' + alcadaAtual + ' #r8_pre').html('---');
            $('#' + alcadaAtual + ' #r9_pre').html('<font color="red"><b>SIM</b></font>');
            //////$('#vl_cred_concedido').value = "0,00";
            scoreAtual = 'E';
            ent = true;
        }
        if ((total_geral > 0) && (ent == false)) {
            if (total_geral <= 17) {
                $('#' + alcadaAtual + ' #id_aprova_pre').attr('disabled', false);
                $('#' + alcadaAtual + ' #r7_pre').val('A');
                $('#' + alcadaAtual + ' #r8_pre').html('1');
                $('#' + alcadaAtual + ' #r9_pre').html('NÃO');

                scoreAtual = 'A';
            } else if (total_geral <= 25) {
                $('#' + alcadaAtual + ' #id_aprova_pre').attr('disabled', false);
                $('#' + alcadaAtual + ' #r7_pre').val('B');
                $('#' + alcadaAtual + ' #r8_pre').val('1,2');
                $('#' + alcadaAtual + ' #r9_pre').html('NÃO');
                //			$('#vl_cred_concedido').value = Arredondamento(parseFloat(parent.$("vl_comparativo").value.replace(".","").replace(".","").replace(".","").replace(".","").replace(".","").replace(",","."))*0.75,2);
                //			$('#id_aprova').value = "1";
                scoreAtual = 'B';
            } else if (total_geral <= 32) {
                $('#' + alcadaAtual + ' #id_aprova_pre').attr('disabled', false);
                $('#' + alcadaAtual + ' #r7_pre').val('C');
                $('#' + alcadaAtual + ' #r8_pre').html('1,5');
                $('#' + alcadaAtual + ' #r9_pre').html('NÃO');
                //			$('#vl_cred_concedido').value = Arredondamento(parseFloat(parent.$("vl_comparativo").value.replace(".","").replace(".","").replace(".","").replace(".","").replace(".","").replace(",","."))*0.50,2);
                //			$('#id_aprova').value = "1";
                scoreAtual = 'C';
            } else if (total_geral <= 40) {
                $('#' + alcadaAtual + ' #id_aprova_pre').attr('disabled', false);
                $('#' + alcadaAtual + ' #r7_pre').val('D');
                $('#' + alcadaAtual + ' #r8_pre').html('2');
                $('#' + alcadaAtual + ' #r9_pre').html('NÃO');
                //			$('#vl_cred_concedido').value = Arredondamento(parseFloat(parent.$("vl_comparativo").value.replace(".","").replace(".","").replace(".","").replace(".","").replace(".","").replace(",","."))*0.25,2);
                //			$('#id_aprova').value = "1";
                scoreAtual = 'D';
            } else {
                $('#' + alcadaAtual + ' #id_aprova_pre').val('2');
                $('#' + alcadaAtual + ' #id_aprova_pre').attr('disabled', true);
                $("#" + alcadaAtual + " #mt_pre_pre").show('slow');
                $("#" + alcadaAtual + " #mt_pos_pre").show('slow');
                $('#' + alcadaAtual + ' #r7_pre').val('E');
                $('#' + alcadaAtual + ' #r8_pre').html('---');
                $('#' + alcadaAtual + ' #r9_pre').html('<font color="red"><b>SIM</b></font>');
                //////$('#vl_cred_concedido').value = "0,00";
                scoreAtual = 'E';
            }
        }
        if (scoreAtual != 'xxx') {
            //CalculaLimiteFinal();
        }
        }
    }
    
    
     function CalcSomatorioCadastral_pre()
    {
        var TOTAL_CADASTRAL = 0;

        if (($('#' + alcadaAtual + ' #aval1_pre').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval3_pre').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval4_pre').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval5_pre').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval6_pre').val() != ""))
        {
            TOTAL_CADASTRAL = Math.round(parseFloat((parseInt($('#' + alcadaAtual + ' #aval1_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval3_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval4_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval5_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval6_pre').val())) / 5));

            $("#" + alcadaAtual + " #somatorioCadastral_pre").html(TOTAL_CADASTRAL);

            $("#" + alcadaAtual + " #somaCadastralPonderado_pre").html(parseInt(TOTAL_CADASTRAL * 2.0));
        }

    }


    function CalcSomatorioAnaliseSetorial_pre()
    {
        var TOTAL_ANA_SETORIAL = 0;

        if ($("#" + alcadaAtual + " #aval8_pre").val() != "")
        {
            TOTAL_ANA_SETORIAL = parseInt($("#" + alcadaAtual + " #aval8_pre").val());
            $('#' + alcadaAtual + ' #somaAnaSetorial_pre').html(parseInt(TOTAL_ANA_SETORIAL * 1.0));
        }
    }



    function CalcSomatorioQualidadeInfo_pre()
    {
        var TOTAL_QUALI_INFO = 0;

        if ($('#' + alcadaAtual + ' #aval7_pre').val() != "")
        {
            TOTAL_QUALI_INFO = parseInt($('#aval7_pre').val());
            $("#" + alcadaAtual + " #somaAnaQualidadeInfo_pre").html(parseInt(TOTAL_QUALI_INFO * 3.0));
        }
    }



    function CalcSomatorioIndicadoresFinanceiros_pre()
    {

        var TOTAL_IND_FINANC = 0;

        if ($('#' + alcadaAtual + ' #cred37_pre').css('display') == 'none') {
            if (($('#' + alcadaAtual + ' #aval9_pre').val() != "") && ($('#' + alcadaAtual + ' #aval10_pre').val() != "") && ($('#' + alcadaAtual + ' #aval11_pre').val() != "") && ($('#' + alcadaAtual + ' #aval12_pre').val() != "") && ($('#' + alcadaAtual + ' #aval13_pre').val() != "") && ($('#' + alcadaAtual + ' #aval14_pre').val() != ""))
            {
                TOTAL_IND_FINANC = Math.round(parseFloat((parseInt($('#' + alcadaAtual + ' #aval9_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval10_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval11_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval12_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval13_pre').val()) + parseInt($('#aval14_pre').val())) / 6));

                $("#" + alcadaAtual + " #somatorioIndFinanc_pre").html(TOTAL_IND_FINANC);
                $("#" + alcadaAtual + " #somaIndFinanc_pre").html(parseInt(TOTAL_IND_FINANC * 4.0));
                try
                {
                    $("#" + alcadaAtual + " #aval92_pre").val(parseInt(TOTAL_IND_FINANC * 4.0));
                } catch (e)
                {
                }

            }
        } else {
            if (($('#' + alcadaAtual + ' #aval92_pre').val() != ""))
            {
                TOTAL_IND_FINANC = parseInt($('#' + alcadaAtual + ' #aval92_pre').val());

                $("#" + alcadaAtual + " #somaIndFinanc2_pre").html(parseInt(TOTAL_IND_FINANC * 4.0));

            }
        }

    }


    function somaColunas_pre()
    {
        var tot = 0;
        if ($('#' + alcadaAtual + ' #cred37_pre').css('display') == 'none'){
            tot = parseInt($("#" + alcadaAtual + " #somaCadastralPonderado_pre").html()) + parseInt($("#" + alcadaAtual + " #somaAnaSetorial_pre").html()) + parseInt($("#" + alcadaAtual + " #somaAnaQualidadeInfo_pre").html()) + parseInt($("#" + alcadaAtual + " #somaIndFinanc_pre").html());
        
        }else{
            tot = parseInt($("#" + alcadaAtual + " #somaCadastralPonderado_pre").html()) + parseInt($("#" + alcadaAtual + " #somaAnaSetorial_pre").html()) + parseInt($("#" + alcadaAtual + " #somaAnaQualidadeInfo_pre").html()) + parseInt($("#" + alcadaAtual + " #somaIndFinanc2_pre").html());
        }    
        if (tot > 0)
        {
            
            $("#" + alcadaAtual + " #rRR4_pre").html(tot);
        }
    }

    function apenas5_pre(obj)
    {

        if (obj.value > 5 || obj.value == "" || obj.value == 0)
        {
            obj.value = "";
        }
    }
    
    
    
    
    //*******************************************//
    
    
    
    //Valida formulário ao enviar
  
    $('#' + alcadaAtual + ' #frmTbAnaliseAnalista').on('submit', function (event) {
        
        event.preventDefault();

        var rota = $(this).attr('action');

        CKEDITOR.instances['ds_parecer'+ alcadaAtual].updateElement();
        
        if(!validarForm()){
            return false;
        }
        if ($('#' + alcadaAtual + ' #r7').val() != "xxx") {

            $.ajax({
                url: rota,
                method: "POST",
                data: new FormData(this),
                dataType: 'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data)
                {
                    if(data.status == 'erro'){
                       var alerta = swal("Opss!", data.message, "error");

                    }else{

                    var alerta = swal("Sucesso!", data.message, "success");

                    }

                    alerta.then(function () {
                        location.reload();
                    });
                },
                error: function (data) {
                    var alerta = swal("Erro!", 'Ocorreu um erro ao salvar, verifique os dados e tente novamente', "error");
                    alerta.then(function () {
                        location.reload();
                    });
                },
                beforeSend: function () {
                    $('#' + alcadaAtual + ' #salvar').text(' Salvando...').prop('disabled', true);
                },
                complete: function () {
                    $('#' + alcadaAtual + ' #salvar').text(' Salvar').prop('disabled', false);
                    
                }
            });

            

        } else {

            swal("Erro!", 'Por favor recalcule o credit-score, antes de salvar as informações!', "error");
            
        }
    });



    //Apenas Pre-embarque
    
        if ($('#modalidadeOperacao').val() == 1) {
         // para pre-embarque   
    
        if ($('#' + alcadaAtual + ' .resp_pre:checked').val() == 1) {
      
            
            $('#' + alcadaAtual + ' #cred37_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred31_pre').show();
            $('#' + alcadaAtual + ' #cred32_pre').show();
            $('#' + alcadaAtual + ' #cred33_pre').show();
            $('#' + alcadaAtual + ' #cred38_pre').show();
            $('#' + alcadaAtual + ' #cred34_pre').show();
            $('#' + alcadaAtual + ' #cred35_pre').show();
            $('#' + alcadaAtual + ' #cred36_pre').show();
            $('#' + alcadaAtual + ' #cred1_pre').show();
            $('#' + alcadaAtual + ' #cred2_pre').show();
          
            CalcSomatorioCadastral_pre();
            CalcSomatorioQualidadeInfo_pre();
            CalcSomatorioAnaliseSetorial_pre();
            CalcSomatorioIndicadoresFinanceiros_pre();
            somaColunas_pre();
            scores_pre();

        } else {
            
            $('#' + alcadaAtual + ' #cred36_pre').hide('slow');
            $('#' + alcadaAtual + ' #cred37_pre').show();
            $('#' + alcadaAtual + ' #cred31_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred32_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred33_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred38_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred34_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred35_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred36_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred1_pre').show();
            $('#' + alcadaAtual + ' #cred2_pre').show();
            CalcSomatorioCadastral_pre();
            CalcSomatorioQualidadeInfo_pre();
            CalcSomatorioAnaliseSetorial_pre();
            CalcSomatorioIndicadoresFinanceiros_pre();
            somaColunas_pre();
            scores_pre();
        }

        }



    // Caso o Nao venha checado
    if ($('#modalidadeOperacao').val() == 2) {

    
        // para pre-embarque   
    
        if ($('#' + alcadaAtual + ' .resp_pre:checked').val() == 1) {
      
            
            $('#' + alcadaAtual + ' #cred37_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred31_pre').show();
            $('#' + alcadaAtual + ' #cred32_pre').show();
            $('#' + alcadaAtual + ' #cred33_pre').show();
            $('#' + alcadaAtual + ' #cred38_pre').show();
            $('#' + alcadaAtual + ' #cred34_pre').show();
            $('#' + alcadaAtual + ' #cred35_pre').show();
            $('#' + alcadaAtual + ' #cred36_pre').show();
            $('#' + alcadaAtual + ' #cred1_pre').show();
            $('#' + alcadaAtual + ' #cred2_pre').show();
          
            CalcSomatorioCadastral_pre();
            CalcSomatorioQualidadeInfo_pre();
            CalcSomatorioAnaliseSetorial_pre();
            CalcSomatorioIndicadoresFinanceiros_pre();
            somaColunas_pre();
            scores_pre();

        } else {
            
            $('#' + alcadaAtual + ' #cred36_pre').hide('slow');
            $('#' + alcadaAtual + ' #cred37_pre').show();
            $('#' + alcadaAtual + ' #cred31_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred32_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred33_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred38_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred34_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred35_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred36_pre').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred1_pre').show();
            $('#' + alcadaAtual + ' #cred2_pre').show();
            CalcSomatorioCadastral_pre();
            CalcSomatorioQualidadeInfo_pre();
            CalcSomatorioAnaliseSetorial_pre();
            CalcSomatorioIndicadoresFinanceiros_pre();
            somaColunas_pre();
            scores_pre();
        }




        // para pos embarque
        if ($('#' + alcadaAtual + ' .resp:checked').val() == 1) {

            $('#' + alcadaAtual + ' #cred37').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred31').show();
            $('#' + alcadaAtual + ' #cred32').show();
            $('#' + alcadaAtual + ' #cred33').show();
            $('#' + alcadaAtual + ' #cred38').show();
            $('#' + alcadaAtual + ' #cred34').show();
            $('#' + alcadaAtual + ' #cred35').show();
            $('#' + alcadaAtual + ' #cred36').show();
            $('#' + alcadaAtual + ' #cred1').show();
            $('#' + alcadaAtual + ' #cred2').show();
            CalcSomatorioCadastral();
            CalcSomatorioQualidadeInfo();
            CalcSomatorioAnaliseSetorial();
            CalcSomatorioIndicadoresFinanceiros();
            somaColunas();
            scores();

        } else {
            $('#' + alcadaAtual + ' #cred37').show();
            $('#' + alcadaAtual + ' #cred31').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred32').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred33').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred38').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred34').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred35').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred36').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred1').show();
            $('#' + alcadaAtual + ' #cred2').show();
            CalcSomatorioCadastral();
            CalcSomatorioQualidadeInfo();
            CalcSomatorioAnaliseSetorial();
            CalcSomatorioIndicadoresFinanceiros();
            somaColunas();
            scores();
        }


    } 
    
    if ($('#modalidadeOperacao').val() == 3) {
        // modalidade eh apenas pre-embarque


        // para pos embarque
        if ($('#' + alcadaAtual + ' .resp:checked').val() == 1) {

            $('#' + alcadaAtual + ' #cred37').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred31').show();
            $('#' + alcadaAtual + ' #cred32').show();
            $('#' + alcadaAtual + ' #cred33').show();
            $('#' + alcadaAtual + ' #cred38').show();
            $('#' + alcadaAtual + ' #cred34').show();
            $('#' + alcadaAtual + ' #cred35').show();
            $('#' + alcadaAtual + ' #cred36').show();
            $('#' + alcadaAtual + ' #cred1').show();
            $('#' + alcadaAtual + ' #cred2').show();
            CalcSomatorioCadastral();
            CalcSomatorioQualidadeInfo();
            CalcSomatorioAnaliseSetorial();
            CalcSomatorioIndicadoresFinanceiros();
            somaColunas();
            scores();

        } else {
            $('#' + alcadaAtual + ' #cred37').show();
            $('#' + alcadaAtual + ' #cred31').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred32').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred33').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred38').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred34').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred35').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred36').hide().find('input').val('');
            $('#' + alcadaAtual + ' #cred1').show();
            $('#' + alcadaAtual + ' #cred2').show();
            CalcSomatorioCadastral();
            CalcSomatorioQualidadeInfo();
            CalcSomatorioAnaliseSetorial();
            CalcSomatorioIndicadoresFinanceiros();
            somaColunas();
            scores();
        }

    }


    $('#' + alcadaAtual + ' .atualiza_upload_calculo_limite_credito').on('click', function () {
        $('#' + alcadaAtual + ' #upload_upload_calculo_limite_credito_realizado').hide('slow');
        $('#' + alcadaAtual + ' #upload_calculo_limite_credito').show('slow')
        return false;
    });


    $('#' + alcadaAtual + ' .atualiza_comprovante_pg_relatorio').on('click', function () {
        $('#' + alcadaAtual + ' #upload_comprovantepg_relatorio_realizado').hide('slow');
        $('#' + alcadaAtual + ' #upload_comprovantepg_relatorio').show('slow')
        return false;
    });

    $('#' + alcadaAtual + ' .atualiza_relatorio_internacional').on('click', function () {
        $('#' + alcadaAtual + ' #upload_relatorio_internacional_realizado').hide('slow');
        $('#' + alcadaAtual + ' #upload_relatorio_internacional').show('slow')
        return false;
    });

    $(document).on('click', '#' + alcadaAtual + ' .resp', function () {

        if ($(this).val() == 1) {

            $('#' + alcadaAtual + ' #cred37').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred31').show('slow');
            $('#' + alcadaAtual + ' #cred32').show('slow');
            $('#' + alcadaAtual + ' #cred33').show('slow');
            $('#' + alcadaAtual + ' #cred38').show('slow');
            $('#' + alcadaAtual + ' #cred34').show('slow');
            $('#' + alcadaAtual + ' #cred35').show('slow');
            $('#' + alcadaAtual + ' #cred36').show('slow');
            $('#' + alcadaAtual + ' #cred1').show('slow');
            $('#' + alcadaAtual + ' #cred2').show('slow');
            somaColunas();

        } else {
            $('#' + alcadaAtual + ' #cred37').show('slow');
            $('#' + alcadaAtual + ' #cred31').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred32').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred33').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred38').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred34').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred35').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred36').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred1').show('slow');
            $('#' + alcadaAtual + ' #cred2').show('slow');
            CalcSomatorioIndicadoresFinanceiros();
            somaColunas();
        }


    });
    
    /** Pre embarque **/
    $(document).on('click', '#' + alcadaAtual + ' .resp_pre', function () {

        if ($(this).val() == 1) {

            $('#' + alcadaAtual + ' #cred37_pre').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred31_pre').show('slow');
            $('#' + alcadaAtual + ' #cred32_pre').show('slow');
            $('#' + alcadaAtual + ' #cred33_pre').show('slow');
            $('#' + alcadaAtual + ' #cred38_pre').show('slow');
            $('#' + alcadaAtual + ' #cred34_pre').show('slow');
            $('#' + alcadaAtual + ' #cred35_pre').show('slow');
            $('#' + alcadaAtual + ' #cred36_pre').show('slow');
            $('#' + alcadaAtual + ' #cred1_pre').show('slow');
            $('#' + alcadaAtual + ' #cred2_pre').show('slow');
            somaColunas();

        } else {
            $('#' + alcadaAtual + ' #cred37_pre').show('slow');
            $('#' + alcadaAtual + ' #cred31_pre').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred32_pre').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred33_pre').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred38_pre').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred34_pre').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred35_pre').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred36_pre').hide('slow').find('input').val('');
            $('#' + alcadaAtual + ' #cred1_pre').show('slow');
            $('#' + alcadaAtual + ' #cred2_pre').show('slow');
            CalcSomatorioIndicadoresFinanceiros_pre();
            somaColunas_pre();
        }


    });
    
    /*** Fim parte pre embarque ***/


    $(document).on('click', '#' + alcadaAtual + ' #btnRecalcularCreditScore', function (e) {


        if(alcadaAtual > 2 ){

            swal({
                title: 'Qual motivo da alteração do credit-score ?',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Salvar',
                showLoaderOnConfirm: true,
                preConfirm: (mt) => {
                    $('#' + alcadaAtual + ' #MOTIVO_ALTERACAO_CREDIT_SCORE').val(mt);
                },
                allowOutsideClick: () => !swal.isLoading()
            });



        }
        e.preventDefault();
        CalcSomatorioCadastral();
        CalcSomatorioQualidadeInfo();
        CalcSomatorioAnaliseSetorial();
        CalcSomatorioIndicadoresFinanceiros();
        somaColunas();
        scores();

    });

    $(document).on('click', '#' + alcadaAtual + ' #btnRecalcularCreditScore_pre', function (e) {

        if(alcadaAtual > 2){


            swal({
                title: 'Qual motivo da alteração do credit-score ?',
                input: 'text',
                inputAttributes: {
                    autocapitalize: 'off'
                },
                showCancelButton: true,
                confirmButtonText: 'Salvar',
                showLoaderOnConfirm: true,
                preConfirm: (mt) => {
                    $('#' + alcadaAtual + ' #MOTIVO_ALTERACAO_CREDIT_SCORE').val(mt);
                },
                allowOutsideClick: () => !swal.isLoading()
            });



        }


        e.preventDefault();
        CalcSomatorioCadastral_pre();
        CalcSomatorioQualidadeInfo_pre();
        CalcSomatorioAnaliseSetorial_pre();
        CalcSomatorioIndicadoresFinanceiros_pre();
        somaColunas_pre();
        scores_pre();

    });



    $('#' + alcadaAtual + ' #upload_comprovantepg_relatorio').on('change', function () {
        $('#' + alcadaAtual + ' #upload_comprovantepg_relatorio').submit();
    });


    $('#' + alcadaAtual + ' #ds_recomendacao').on('change', function () {

        if ($(this).val() == 2) {
            $('#' + alcadaAtual + ' #mtIndeferimento').show('slow');
            if ($('#' + alcadaAtual + ' #ultimaAlcada').val() == "SIM") {
                $('#indeferir').show('slow');
            }
        } else {
            $('#' + alcadaAtual + ' #mtIndeferimento').hide('slow');
            $('#indeferir').hide('slow');
        }

    });


    $('#' + alcadaAtual + ' #upload_comprovantepg_relatorio').on('submit', function (event) {
        event.preventDefault();
        var rota = $(this).attr('action');
        $.ajax({
            url: rota,
            method: "POST",
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                $('#' + alcadaAtual + ' #select_comprovantepg_relatorio').hide('slow');
                $('#' + alcadaAtual + ' #message_comprovantepg_relatorio').css('display', 'block');

                if(data.message == 'Upload realizado com sucesso!') {
                    var alerta = swal("Sucesso!", data.message, "success");
                    alerta.then(function () {
                        location.reload();
                    });
                }else{
                    swal("Opss!", data.message[0], "error").then(function () {
                        location.reload();
                    });

                }

               // $('#' + alcadaAtual + ' #message_comprovantepg_relatorio').html(data.message);


                $('#' + alcadaAtual + ' #message_comprovantepg_relatorio').removeClass();
                $('#' + alcadaAtual + ' #message_comprovantepg_relatorio').addClass(data.class_name).fadeOut();
                $('#' + alcadaAtual + ' #upload_comprovantepg_relatorio').hide();
                $('#' + alcadaAtual + ' #upload_comprovantepg_relatorio_realizado').show('slow').html(data.upload_comprovantepg_relatorio_realizado);
            }
        });
    });


    //Calculo de limite de credito

    $('#' + alcadaAtual + ' #upload_calculo_limite_credito').on('change', function () {
        $('#' + alcadaAtual + ' #upload_calculo_limite_credito').submit();
    });

    $('#' + alcadaAtual + ' #upload_calculo_limite_credito').on('submit', function (event) {
        event.preventDefault();
        var rota = $(this).attr('action');
        $.ajax({
            url: rota,
            method: "POST",
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                $('#' + alcadaAtual + ' #select_upload_calculo_limite_credito').hide('slow');
                $('#' + alcadaAtual + ' #message_calculo_limite_credito').css('display', 'block');

                if(data.message == 'Upload realizado com sucesso!') {
                    var alerta = swal("Sucesso!", data.message, "success");
                    alerta.then(function () {
                        location.reload();
                    });
                }else{
                    swal("Opss!", data.message[0], "error").then(function () {
                        location.reload();
                    });

                }


                $('#' + alcadaAtual + ' #message_calculo_limite_credito').removeClass();
                $('#' + alcadaAtual + ' #message_calculo_limite_credito').addClass(data.class_name).fadeOut();
                $('#' + alcadaAtual + ' #upload_calculo_limite_credito').hide();
                $('#' + alcadaAtual + ' #upload_calculo_limite_credito_realizado').show('slow').html(data.upload_upload_calculo_limite_credito_realizado);
            }
        });
    });


    // Relatorio Internacional

    $('#' + alcadaAtual + ' #upload_relatorio_internacional').on('change', function () {


        $('#' + alcadaAtual + ' #upload_relatorio_internacional').submit();

    });

    $('#' + alcadaAtual + ' #upload_relatorio_internacional').on('submit', function (event) {
        event.preventDefault();
        var rota = $(this).attr('action');
        $.ajax({
            url: rota,
            method: "POST",
            data: new FormData(this),
            dataType: 'JSON',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data)
            {
                $('#' + alcadaAtual + ' #select_relatorio_internacional').hide('slow');
                $('#' + alcadaAtual + ' #message_relatorio_internacional').css('display', 'block');

                if(data.message == 'Upload realizado com sucesso!') {
                    var alerta = swal("Sucesso!", data.message, "success");
                    alerta.then(function () {
                        location.reload();
                    });
                }else{
                    swal("Opss!", data.message[0], "error").then(function () {
                        location.reload();
                    });

                }


                $('#' + alcadaAtual + ' #message_relatorio_internacional').removeClass();
                $('#' + alcadaAtual + ' #message_relatorio_internacional').addClass(data.class_name).fadeOut();
                $('#' + alcadaAtual + ' #upload_relatorio_internacional').hide();
                $('#' + alcadaAtual + ' #upload_relatorio_internacional_realizado').show('slow').html(data.upload_relatorio_internacional_realizado);
            }
        });
    });




    function scores()
    {

        var total_geral = parseInt($("#" + alcadaAtual + " #rRR4").html());
        var ent = false;


        try {
            if (($('#' + alcadaAtual + ' #aval9').val() == "5") || ($('#' + alcadaAtual + ' #aval10').val() == "5") || ($('#' + alcadaAtual + ' #aval12').val() == "5") || ($('#' + alcadaAtual + ' #aval11').val() == "5") || ($('#' + alcadaAtual + ' #aval13').val() == "5") || ($('#' + alcadaAtual + ' #aval14').val() == "5")) {
                $('#' + alcadaAtual + ' #id_aprova').val('2');
                $('#' + alcadaAtual + ' #id_aprova').attr('disabled', true);
                $('#' + alcadaAtual + ' #r7').val('E');
                $('#' + alcadaAtual + ' #r8').html('---');
                $('#' + alcadaAtual + ' #r9').html('<font color="red"><b>SIM</b></font>');
                //////$('#vl_cred_concedido').value = "0,00";
                scoreAtual = 'E';
                ent = true;
            }
        } catch (e) {
        }

        try {
            if (($('#' + alcadaAtual + ' #aval92').val() == "5")) {
                $('#' + alcadaAtual + ' #id_aprova').val('2');
                $('#' + alcadaAtual + ' #id_aprova').attr('disabled', true);
                $('#' + alcadaAtual + ' #r7').val('E');
                $('#' + alcadaAtual + ' #r8').html('---');
                $('#' + alcadaAtual + ' #r9').html('<font color="red"><b>SIM</b></font>');
                //////$('#vl_cred_concedido').value = "0,00";
                scoreAtual = 'E';
                ent = true;
            }
        } catch (e) {
        }

        if (($('#' + alcadaAtual + ' #aval1').val() == "5") || ($('#' + alcadaAtual + ' #aval3').val() == "5") || ($('#' + alcadaAtual + ' #aval4').val() == "5") || ($('#' + alcadaAtual + ' #aval5').val() == "5") || ($('#' + alcadaAtual + ' #aval6').val() == "5") || ($('#' + alcadaAtual + ' #aval7').val() == "5") || ($('#' + alcadaAtual + ' #aval8').val() == "5")) {
            $('#' + alcadaAtual + ' #id_aprova').val('2');
            $('#' + alcadaAtual + ' #id_aprova').attr('disabled', true);
            $('#' + alcadaAtual + ' #r7').val('E');
            $('#' + alcadaAtual + ' #r8').html('---');
            $('#' + alcadaAtual + ' #r9').html('<font color="red"><b>SIM</b></font>');
            //////$('#vl_cred_concedido').value = "0,00";
            scoreAtual = 'E';
            ent = true;
        }
        if ((total_geral > 0) && (ent == false)) {
            if (total_geral <= 17) {
                $('#' + alcadaAtual + ' #id_aprova').attr('disabled', false);
                $('#' + alcadaAtual + ' #r7').val('A');
                $('#' + alcadaAtual + ' #r8').html('1');
                $('#' + alcadaAtual + ' #r9').html('NÃO');

                scoreAtual = 'A';
            } else if (total_geral <= 25) {
                $('#' + alcadaAtual + ' #id_aprova').attr('disabled', false);
                $('#' + alcadaAtual + ' #r7').val('B');
                $('#' + alcadaAtual + ' #r8').val('1,2');
                $('#' + alcadaAtual + ' #r9').html('NÃO');
                //			$('#vl_cred_concedido').value = Arredondamento(parseFloat(parent.$("vl_comparativo").value.replace(".","").replace(".","").replace(".","").replace(".","").replace(".","").replace(",","."))*0.75,2);
                //			$('#id_aprova').value = "1";
                scoreAtual = 'B';
            } else if (total_geral <= 32) {
                $('#' + alcadaAtual + ' #id_aprova').attr('disabled', false);
                $('#' + alcadaAtual + ' #r7').val('C');
                $('#' + alcadaAtual + ' #r8').html('1,5');
                $('#' + alcadaAtual + ' #r9').html('NÃO');
                //			$('#vl_cred_concedido').value = Arredondamento(parseFloat(parent.$("vl_comparativo").value.replace(".","").replace(".","").replace(".","").replace(".","").replace(".","").replace(",","."))*0.50,2);
                //			$('#id_aprova').value = "1";
                scoreAtual = 'C';
            } else if (total_geral <= 40) {
                $('#' + alcadaAtual + ' #id_aprova').attr('disabled', false);
                $('#' + alcadaAtual + ' #r7').val('D');
                $('#' + alcadaAtual + ' #r8').html('2');
                $('#' + alcadaAtual + ' #r9').html('NÃO');
                //			$('#vl_cred_concedido').value = Arredondamento(parseFloat(parent.$("vl_comparativo").value.replace(".","").replace(".","").replace(".","").replace(".","").replace(".","").replace(",","."))*0.25,2);
                //			$('#id_aprova').value = "1";
                scoreAtual = 'D';
            } else {
                $('#' + alcadaAtual + ' #id_aprova').val('2');
                $('#' + alcadaAtual + ' #id_aprova').attr('disabled', true);
                $("#" + alcadaAtual + " #mt_pre").show('slow');
                $("#" + alcadaAtual + " #mt_pos").show('slow');
                $('#' + alcadaAtual + ' #r7').val('E');
                $('#' + alcadaAtual + ' #r8').html('---');
                $('#' + alcadaAtual + ' #r9').html('<font color="red"><b>SIM</b></font>');
                //////$('#vl_cred_concedido').value = "0,00";
                scoreAtual = 'E';
            }
        }
        if (scoreAtual != 'xxx') {
            //CalculaLimiteFinal();
        }
    }


    function CalcSomatorioCadastral()
    {
        var TOTAL_CADASTRAL = 0;

        if (($('#' + alcadaAtual + ' #aval1').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval3').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval4').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval5').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval6').val() != ""))
        {
            TOTAL_CADASTRAL = Math.round(parseFloat((parseInt($('#' + alcadaAtual + ' #aval1').val()) 
            + parseInt($('#' + alcadaAtual + ' #aval3').val()) 
            + parseInt($('#' + alcadaAtual + ' #aval4').val()) 
            + parseInt($('#' + alcadaAtual + ' #aval5').val()) 
            + parseInt($('#' + alcadaAtual + ' #aval6').val())) / 5));

            $("#" + alcadaAtual + " #somatorioCadastral").html(TOTAL_CADASTRAL);

            $("#" + alcadaAtual + " #somaCadastralPonderado").html(parseInt(TOTAL_CADASTRAL * 2.0));
        }

    }


    function CalcSomatorioAnaliseSetorial()
    {
        var TOTAL_ANA_SETORIAL = 0;

        if ($("#" + alcadaAtual + " #aval8").val() != "")
        {
            TOTAL_ANA_SETORIAL = parseInt($("#" + alcadaAtual + " #aval8").val());
            $('#' + alcadaAtual + ' #somaAnaSetorial').html(parseInt(TOTAL_ANA_SETORIAL * 1.0));
        }
    }



    function CalcSomatorioQualidadeInfo()
    {
        var TOTAL_QUALI_INFO = 0;

        if ($('#' + alcadaAtual + ' #aval7').val() != "")
        {
            TOTAL_QUALI_INFO = parseInt($('#aval7').val());
            $("#" + alcadaAtual + " #somaAnaQualidadeInfo").html(parseInt(TOTAL_QUALI_INFO * 3.0));
        }
    }



    function CalcSomatorioIndicadoresFinanceiros()
    {

        var TOTAL_IND_FINANC = 0;

        if ($('#' + alcadaAtual + ' #cred37').css('display') == 'none') {
            if (($('#' + alcadaAtual + ' #aval9').val() != "") && ($('#' + alcadaAtual + ' #aval10').val() != "") && ($('#' + alcadaAtual + ' #aval11').val() != "") && ($('#' + alcadaAtual + ' #aval12').val() != "") && ($('#' + alcadaAtual + ' #aval13').val() != "") && ($('#' + alcadaAtual + ' #aval14').val() != ""))
            {
                TOTAL_IND_FINANC = Math.round(parseFloat((parseInt($('#' + alcadaAtual + ' #aval9').val()) + parseInt($('#' + alcadaAtual + ' #aval10').val()) + parseInt($('#' + alcadaAtual + ' #aval11').val()) + parseInt($('#' + alcadaAtual + ' #aval12').val()) + parseInt($('#' + alcadaAtual + ' #aval13').val()) + parseInt($('#aval14').val())) / 6));

                $("#" + alcadaAtual + " #somatorioIndFinanc").html(TOTAL_IND_FINANC);
                $("#" + alcadaAtual + " #somaIndFinanc").html(parseInt(TOTAL_IND_FINANC * 4.0));
                try
                {
                    $("#" + alcadaAtual + " #aval92").val(parseInt(TOTAL_IND_FINANC * 4.0));
                } catch (e)
                {
                }

            }
        } else {
            if (($('#' + alcadaAtual + ' #aval92').val() != ""))
            {
                TOTAL_IND_FINANC = parseInt($('#' + alcadaAtual + ' #aval92').val());

                $("#" + alcadaAtual + " #somaIndFinanc2").html(parseInt(TOTAL_IND_FINANC * 4.0));

            }
        }

    }


    function somaColunas()
    {
        var tot = 0;
        if ($('#' + alcadaAtual + ' #cred37').css('display') == 'none')
            tot = parseInt($("#" + alcadaAtual + " #somaCadastralPonderado").html()) + parseInt($("#" + alcadaAtual + " #somaAnaSetorial").html()) + parseInt($("#" + alcadaAtual + " #somaAnaQualidadeInfo").html()) + parseInt($("#" + alcadaAtual + " #somaIndFinanc").html());
        else
            tot = parseInt($("#" + alcadaAtual + " #somaCadastralPonderado").html()) + parseInt($("#" + alcadaAtual + " #somaAnaSetorial").html()) + parseInt($("#" + alcadaAtual + " #somaAnaQualidadeInfo").html()) + parseInt($("#" + alcadaAtual + " #somaIndFinanc2").html());

        if (tot > 0)
        {
            $("#" + alcadaAtual + " #rRR4").html(tot);
        }
    }

    function apenas5(obj)
    {

        if (obj.value > 5 || obj.value == "" || obj.value == 0)
        {
            obj.value = "";
        }
    }
    
    
   
    
    
    
    


    $('#datemask').on('change', function () {

        var dataObj = new Date();
        var data1 = $("#" + alcadaAtual + " #datemask").val();
        if (data1 == "")
        {
            alert('Por favor, preencha a data de recomendação.');
            $("#" + alcadaAtual + " #datemask").focus();
            return false;
        }

        var dia = 0;
        var mes = 0;
        var ano = dataObj.getFullYear();

        if (dataObj.getDate() < 10)
            dia = '0' + dataObj.getDate();
        else
            dia = dataObj.getDate();

        if (parseInt(dataObj.getMonth() + 1) < 10)
            mes = '0' + parseInt(dataObj.getMonth() + 1);
        else
            mes = parseInt(dataObj.getMonth() + 1);

        var data2 = dia + '/' + mes + '/' + dataObj.getFullYear();

        var dataHoje = dataObj.setFullYear(ano, mes, dia);//adicionado wppl
        var dataRecomendacao = dataObj.setFullYear(data1.split("/")[2].toString(), data1.split("/")[1].toString(), data1.split("/")[0].toString());//adicionado wppl
        if (dataRecomendacao > dataHoje)
        {
            alert('Data da recomendação não pode ser posterior ao dia atual.');
            $("#" + alcadaAtual + " #datemask").focus();
            return false;
        }


    });



function validarForm()
{
    var erros = new Array();
    var total = 0.0;
    var i     = 0;
    var j     = 0;

    if ($("#" + alcadaAtual + " #id_mpme_fundo_garantia_operacao").val() == 0){
        erros.push('Fundo principal para operação.');
    }
    
    if ($("#" + alcadaAtual + " #id_mpme_fundo_garantia_operacao").val() == 0){
        erros.push('Fundo principal para operação.');
    }

    $("#" + alcadaAtual + " .perc_fundo").each(function(){
        if ($(this).val() != "")
        {
            total = parseFloat(total) + parseFloat($(this).val());
        }else{
            total = parseFloat(total) + parseFloat(0);
        }

    });

    if ( total < 100 || total > 100 )
    {
        erros.push('O valor total dos fundos não podem ser superior ou menor a 100%.');
    }

    $("#" + alcadaAtual + " .in_saldo_suficiente").each(function(){
        if ( $(this).val() == 'NAO' )
        {
            i++;
        }
        if ( $(this).val() == 0 )
        {
            j++;
        }

    });

    if ( i < 2 )
    {
        $("#" + alcadaAtual + " #in_mpme_status").val();
    }else{
        $("#" + alcadaAtual + " #in_mpme_status").val();
    }

    if ( j > 0 )
    {
        erros.push('Favor selecionar se os dois fundos tem saldo suficiente.');
    }


    if ($("#" + alcadaAtual + " #in_saldo_suficiente_exp").val() == 0){
        erros.push('Favor selecionar se tem saldo suficiente no controle da Exportação.');
    }


    if (erros.length>0)
    {
        swal({
            title: 'Ops os seguintes campos devem ser preenchidos',
            type: 'warning',
            html: erros.join('<br />'),
            showCloseButton: true,
        })

        return false;
    }


    $("#" + alcadaAtual + " .in_saldo_suficiente").each(function(){
       $(this).removeAttr('disabled');
    });

    return true;
}






});


