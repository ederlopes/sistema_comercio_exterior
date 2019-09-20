   var alcadaAtual = ;

 $(document).on('click', '#' + alcadaAtual + ' #btnRecalcularCreditScore_pre', function (e) {
    
        e.preventDefault();
        CalcSomatorioCadastral_pre();
        CalcSomatorioQualidadeInfo_pre();
        CalcSomatorioAnaliseSetorial_pre();
        CalcSomatorioIndicadoresFinanceiros_pre();
        somaColunas_pre();
        scores_pre();


    });
    
    
    /***
     * 
     *  
     *    Utilizado apenas caso haja a molidade pre-embarque 
     *    
     *    
     *    
     ***/

      
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
    
    
    
     function CalcSomatorioCadastral_pre()
    {
        var TOTAL_CADASTRAL = 0;

        if (($('#' + alcadaAtual + ' #aval1_pre').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval3_pre').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval4_pre').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval5_pre').val() != "") && ($('#' + alcadaAtual + ' #' + alcadaAtual + ' #aval6_pre').val() != ""))
        {
            TOTAL_CADASTRAL = Math.round(parseFloat((parseInt($('#' + alcadaAtual + ' #aval1_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval3_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval4_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval5_pre').val()) + parseInt($('#' + alcadaAtual + ' #aval6_pre').val())) / 5));

            $("#" + alcadaAtual + " #somatorioCadastral_pre").html(TOTAL_CADASTRAL);

            $("#" + alcadaAtual + " #somaCadastralPonderado_pre").html(parseInt(TOTAL_CADASTRAL * 4.0));
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
            $("#" + alcadaAtual + " #somaAnaQualidadeInfo_pre").html(parseInt(TOTAL_QUALI_INFO * 1.0));
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
            console.log('tot');
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
    
    
    