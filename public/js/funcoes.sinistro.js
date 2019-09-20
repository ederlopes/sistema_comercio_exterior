$(document).ready(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    //desabilita os campos para ao acessar a tela

    $(".dt_dc_sinistro").prop("disabled", true);
    $(".dt_envio_carta_cobranca").prop("disabled", true);
    $(".dt_envio_parecer_tecnico_sain").prop("disabled", true);
    $(".dt_ds_pis").prop("disabled", true);
    $(".ft_gerador_sinistro").prop("disabled", true);
    $(".regulacao_sinistro").prop("disabled", true);
    $(".dt_caractarizacao_sinistro").prop("disabled", true);
    //$(".pg_atraso").hide();
    $(".dt_ind_gestor_fge").prop("disabled", true);
    $(".dt_pg_indenizacao").prop("disabled", true);
    $(".dt_assinatura_contrato_renegociacao").prop("disabled", true);


    $('.datetimepicker4').datetimepicker({
        format: 'DD/MM/YYYY',
        widgetPositioning: {
            vertical: 'bottom',
            horizontal: 'left'
        }
    });


    //Coloca mascara nos campos

    $("input[name='VA_PAGAMENTO_INDENIZACAO']").maskMoney({
        decimal: ",",
        thousands: "."
    });

    $("input[name='VA_REPACTUACAO_RENEGOCIACAO']").maskMoney({
        decimal: ",",
        thousands: "."
    });

    $(".money").maskMoney({
        decimal: ",",
        thousands: "."
    });

    //executa acao ao mudar o status do campo
    $("#ativa_dt_ameaca_sinistro").change(function () {

        //Ao checar o botom ativa os campos input
        if ($('#ativa_dt_ameaca_sinistro').is(":checked")) {
            $(".dt_dc_sinistro").prop("disabled", false); // habilita o campo
        } else {
            $(".dt_dc_sinistro").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".dt_dc_sinistro").val('');
        }

    }); // Fecha funcao change

    $("#ativa_dt_envio_carta_cobranca").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativa_dt_envio_carta_cobranca').is(":checked")) {
            $(".dt_envio_carta_cobranca").prop("disabled", false); // habilita o campo
        } else {
            $(".dt_envio_carta_cobranca").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".dt_envio_carta_cobranca").val('');
        }
    }); // Fecha funcao change

    $("#ativa_envio_parecer_tecnico_sain").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativa_envio_parecer_tecnico_sain').is(":checked")) {
            $(".dt_envio_parecer_tecnico_sain").prop("disabled", false); // habilita o campo
        } else {
            $(".dt_envio_parecer_tecnico_sain").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".dt_envio_parecer_tecnico_sain").val('');
        }
    }); // Fecha funcao change

    $("#ativ_dt_ds_pis").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativ_dt_ds_pis').is(":checked")) {
            $(".dt_ds_pis").prop("disabled", false); // habilita o campo
        } else {
            $(".dt_ds_pis").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".dt_ds_pis").val('');
        }
    }); // Fecha funcao change

    $("#ativar_ft_gerador_sinistro").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativar_ft_gerador_sinistro').is(":checked")) {
            $(".ft_gerador_sinistro").prop("disabled", false); // habilita o campo
        } else {
            $(".ft_gerador_sinistro").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".ft_gerador_sinistro").val('');
        }
    }); // Fecha funcao change


    $("#ativar_regulacao_sinistro").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativar_regulacao_sinistro').is(":checked")) {
            $(".regulacao_sinistro").prop("disabled", false); // habilita o campo
        } else {
            $(".regulacao_sinistro").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".regulacao_sinistro").val('');
        }
    }); // Fecha funcao change


    $("#ativar_dt_caractarizacao_sinistro").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativar_dt_caractarizacao_sinistro').is(":checked")) {
            $(".dt_caractarizacao_sinistro").prop("disabled", false); // habilita o campo
        } else {
            $(".dt_caractarizacao_sinistro").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".dt_caractarizacao_sinistro").val('');
        }
    }); // Fecha funcao change


    $("#ativar_pg_atraso").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativar_pg_atraso').is(":checked")) {
            $(".pg_atraso").show(); // habilita o campo
        } else {
            $(".pg_atraso").hide(); // Desativa o campo caso o mesmo tenha sito checado e removido o check
        }
    }); // Fecha funcao change


    $("#ativar_dt_ind_gestor_fge").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativar_dt_ind_gestor_fge').is(":checked")) {
            $(".dt_ind_gestor_fge").prop("disabled", false); // habilita o campo
        } else {
            $(".dt_ind_gestor_fge").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".dt_ind_gestor_fge").val('');
        }
    }); // Fecha funcao change

    $("#ativar_dt_pg_indenizacao").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativar_dt_pg_indenizacao').is(":checked")) {
            $(".dt_pg_indenizacao").prop("disabled", false); // habilita o campo
            if($(".ft_gerador_sinistro").val() == 1) {
                $("#VA_PAGAMENTO_INDENIZACAO").val($("#valorRC").val());
            }
            if($(".ft_gerador_sinistro").val() == 2) {
                 $("#VA_PAGAMENTO_INDENIZACAO").val($("#valorRP").val());

            }

        } else {
            $(".dt_pg_indenizacao").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".dt_pg_indenizacao").val('');
        }
    }); // Fecha funcao change

    $("#assinar_dt_assinatura_contrato_renegociacao").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#assinar_dt_assinatura_contrato_renegociacao').is(":checked")) {
            $(".dt_assinatura_contrato_renegociacao").prop("disabled", false); // habilita o campo
        } else {
            $(".dt_assinatura_contrato_renegociacao").prop("disabled", true); // Desativa o campo caso o mesmo tenha sito checado e removido o check
            $(".dt_assinatura_contrato_renegociacao").val('');
        }
    }); // Fecha funcao change


    // Funcao Converte data formado dd/mm/YYY para YY-m-d
    function toDate(selector) {
        var from = selector.split("/");
        return new Date(from[2], from[1] - 1, from[0]).toString();
    }


    //Aplica cor caso seja prazo superior
    if($('#dt_declaracao_sinistro').val() != '' && $('#dtVencimento').val() != ''){
    var dtVencimento =  toDate($("#dtVencimento").val());
    var dtCalculada = new Date(dtVencimento).add(30).days();
    var dtHoje = new Date(toDate($('#dt_declaracao_sinistro').val()));


        if(dtHoje.getTime() > dtCalculada.getTime()){
            $('#dt_declaracao_sinistro').css("background-color","#a94442").css("color","#fff");
        }

    }


    //Funcao change ao perder o foco do campo input especifico
    $("#dt_declaracao_sinistro").on('blur',function(){
       // alert(toDate($('#dt_declaracao_sinistro')));
       // Pega data do vencimento
       var dtVencimento =  toDate($("#dtVencimento").val());
       var dtCalculada = new Date(dtVencimento).add(30).days();
       var dtHoje = new Date(toDate($('#dt_declaracao_sinistro').val()));

       if($('#dt_declaracao_sinistro').val() != ''){
           if(dtHoje.getTime() > dtCalculada.getTime()){
               $('#dt_declaracao_sinistro').css("background-color","#a94442").css("color","#fff");
               alert('Data fora do prazo!');
           }else{
               $('#dt_declaracao_sinistro').removeAttr('style');
           }

       }

    }); // Fecha change campo input especifico

    //Aplica cor caso seja prazo superior
    if($('#DT_CARACTERIZACAO_SINISTRO').val() != '' && $('#DT_ENVIO_DS_PI').val() != ''){
        var dtCaterizacaoSinistro =  toDate($("#DT_CARACTERIZACAO_SINISTRO").val());
        var dtCalculadaSinistro = new Date(dtCaterizacaoSinistro).add(30).days();

        var dtHojeSinistro = new Date(toDate($('#DT_ENVIO_DS_PI').val()));


        if(dtHojeSinistro.getTime() > dtCalculadaSinistro.getTime()){
            $('#DT_ENVIO_DS_PI').css("background-color","#a94442").css("color","#fff");

        }

    }


    //Funcao change ao perder o foco do campo input especifico, calc vencimento caracterização e DS/PI
    $("#DT_ENVIO_DS_PI").on('blur',function(){

        // Pega data do vencimento
        var dtCaterizacaoSinistro =  toDate($("#DT_CARACTERIZACAO_SINISTRO").val());
        var dtCalculadaSinistro = new Date(dtCaterizacaoSinistro).add(30).days();

        var dtHojeSinistro = new Date(toDate($('#DT_ENVIO_DS_PI').val()));

        //alert(dtHojeSinistro);
        if($('#DT_CARACTERIZACAO_SINISTRO').val() != ''){
            if(dtHojeSinistro.getTime() > dtCalculadaSinistro.getTime()){
                $('#DT_ENVIO_DS_PI').css("background-color","#a94442").css("color","#fff");
                alert('Data fora do prazo!');
            }else{
                $('#DT_ENVIO_DS_PI').removeAttr('style');
            }

        }

    }); // Fecha change campo input especifico


    //Adiciona campos


    $('#addFieldsDtPag').on('click',function(){
        $('<input type="text" class="form-control tdPag" name="tdPag[]">').appendTo(".dvtdPag");
        $('<input type="text" class="form-control vlPag" name="vlPag[]">').appendTo(".dvvlPag");
        return false;
    });



    $(document).on('click','.addFieldsPgt',function(){
        $('<tr class="ClassFieldsPgt">\n' +
            '                                             <td>\n' +
            '                                                 <div class="form-group" >\n' +
            '                                                      <div class=\'input-group date datetimepicker4\'>\n' +
            '                                                            <input type="text" id="text01" class="form-control input-sm datetimepicker4" name="DTPGT[]" value="">\n' +
            '                                                            <span class="input-group-addon">\n' +
            '                                                <span class="glyphicon glyphicon-calendar"></span>\n' +
            '                                            </span>\n' +
            '                                                        </div>\n' +
            '                                                    </div>\n' +
            '                                                </td>\n' +
            '                                                <td><input type="text" id="text01" class="form-control input-sm money" name="VLPGT[]" value=""></td>\n' +
            '                                                <td>\n' +
            '                                                    <div class="form-group">\n' +
            '                                                        <a href="#" class="btn btn-success addFieldsPgt">+</a>\n' +
            '                                                    </div>\n' +
            '                                                    <div class="form-group">\n' +
            '                                                        <a href="#" class="btn btn-danger DelFieldsPgt">-</a>\n' +
            '                                                    </div>\n' +
            '                                                </td>\n' +
            '                                        </tr>').appendTo(".fieldsPgt");

        $('.fieldsPgt').find('.datetimepicker4').datetimepicker({
            format: 'DD/MM/YYYY',
            widgetPositioning: {
                vertical: 'bottom',
                horizontal: 'left'
            }
        });

        $('.fieldsPgt').find('.money').maskMoney({
            decimal: ",",
            thousands: "."
        });

        return false;

    });

    $(document).on('click','.DelFieldsPgt',function(){
        $('.ClassFieldsPgt:last').remove();
        return false;
    });



    $(document).on('click','.addFieldsDtPrev',function(){
        $('<tr class="fildadd">\n' +
            '                                <td>\n' +
            '                                    <div class="form-group" >\n' +
            '                                        <div class=\'input-group date datetimepicker4\'>\n' +
            '                                            <input type="text" id="text01" class="form-control input-sm datetimepicker4" name="DT_PREVISTA[]" value="">\n' +
            '                                            <span class="input-group-addon">\n' +
            '                                                <span class="glyphicon glyphicon-calendar"></span>\n' +
            '                                            </span>\n' +
            '                                        </div>\n' +
            '                                    </div>\n' +
            '                                </td>\n' +
            '                                <td><input type="text" id="text01" class="form-control input-sm money" name="VL_PRINCIPAL[]"></td>\n' +
            '                                <td><input type="text" id="text01" class="form-control input-sm money" name="JUROS[]"></td>\n' +
            '                                <td>\n' +
            '                                    <div class="form-group">\n' +
            '                                        <div class=\'input-group date datetimepicker4\'>\n' +
            '                                            <input type="text" id="text01" class="form-control input-sm datetimepicker4 " name="DT_EFETIVA[]" value="" style="clear: both;">\n' +
            '                                            <span class="input-group-addon">\n' +
            '                                                <span class="glyphicon glyphicon-calendar"></span>\n' +
            '                                            </span>\n' +
            '                                        </div>\n' +
            '                                    </div>\n' +
            '\n' +
            '                                </td>\n' +
            '                                <td><input type="text" id="text01" class="form-control input-sm money" name="VLPAGO[]"></td>\n' +
            '                                <td><input type="text" id="text01" class="form-control input-sm" name="OBS[]"></td>\n' +
            '                                <td>\n' +
            '                                    <div class="form-group">\n' +
            '                                        <a href="#" class="btn btn-success addFieldsDtPrev">+</a>\n' +
            '                                    </div>\n' +
            '                                    <div class="form-group">\n' +
            '                                        <a href="#" class="btn btn-danger DellFieldsDtPrev">-</a>\n' +
            '                                    </div>\n' +
            '                                </td>\n' +
            '                            </tr>').appendTo(".fields");

        $('.fields').find('.datetimepicker4').datetimepicker({
            format: 'DD/MM/YYYY',
            widgetPositioning: {
                vertical: 'bottom',
                horizontal: 'left'
            }
        });

        $('.fields').find('.money').maskMoney({
            decimal: ",",
            thousands: "."
        });




        return false;


    });
    $('.porcentagem').mask('##0.00', {reverse: true}).attr('maxlength','6');

    $(document).on('click','.DellFieldsDtPrev',function(){
        $('.fildadd:last').remove();
        return false;
    });


    //Funcao upload ajax

    var $fileupload = $('#fileupload');
        $fileupload.fileupload({
            url: '/uploadcgc',
            dataType: 'json',
            formData: {_token: $fileupload.data('token'), userId: $fileupload.data('userId')},
            done: function (e, data) {
              //  console.log(data);
                $.each(data.result, function (index, file) {
                    if(data.result.sucesso == 1){
                        $('#arquivo').val(data.result.filename);
                        $('#caminho_arquivo').val(data.result.diretorio);
                        $('.upajax').hide('slow');
                        $('.btnupajax').show('slow');
                        $('.excluirupload').show('slow');
                        $('.btnupajax').attr("href", "/downloadcgc/" + data.result.userId + '/arquivo/' + data.result.arquivocript);
                        $('.excluirupload').attr("href", "/excluircgc/" + data.result.userId + '/arquivo/' + data.result.arquivocript);

                    }
                 });

            },
            progressall: function (e, data) {
                var progress = parseInt(data.loaded / data.total * 100, 10);
                $('#progress .progress-bar').css(
                    'width',
                    progress + '%'
                );
            }
        })

    $('.excluirupload').on('click',function(){

        $.ajax({
            type: "GET",
            url: $('.excluirupload').attr('href'),
            timeout: 3000,
            contentType: "application/json; charset=utf-8",
            cache: false,
            beforeSend: function() {
                //$("h2").html("Carregando..."); //Carregando
            },
            error: function() {
                $("h2").html("O servidor não conseguiu processar o pedido");
            },
            success: function (e, data) {

                $('.upajax').show('slow');
                $('.btnupajax').hide('slow');
                $('.excluirupload').hide('slow');
                $('#progress .progress-bar').css(
                'width',
                '0%'
                );

                $('#arquivo').val('');
                $('#caminho_arquivo').val('');

            }
        });

        return false;


    });


    $('#cadastrar_motivo_cancelamento').on('click',function(){

        var motivo = $('#motivo').val();
        var abvMotivo = $('#abvMotivo').val();


        $.ajax({
            type: "GET",
            url: '/cadastrar/motivocancelamento',
            data: {motivo:motivo, abvMotivo:abvMotivo},
            timeout: 3000,
            contentType: "application/json; charset=utf-8",
            cache: false,
            beforeSend: function() {
                //$("h2").html("Carregando..."); //Carregando
            },
            error: function() {
               // $("h2").html("O servidor não conseguiu processar o pedido");
            },
            success: function (data) {
                 if(data.sucesso == 1){
                     $("<option value='"+data.id_motivo+"'>"+data.motivo+"</option>").insertAfter("#ID_MOTIVO_CANCELAMENTO_DAS option:first").attr('selected','selected');
                     $('#motivo').val('');
                     $('#abvMotivo').val('');
                     $('#ModalCadMotivoCancelamento').modal('toggle');

                 }else{
                     alert('Ocooreu um erro ao cadastrar, tente novamente mais tarde!');
                 }
            }
        });

        return false;


    });

    $('#ID_MOTIVO_CANCELAMENTO_DAS').on('change',function(){
        if($('#ID_MOTIVO_CANCELAMENTO_DAS').val() == 'Outros'){
            $('#ModalCadMotivoCancelamento').modal('toggle');
        }
    });



    // Campos adicionados


    $("#ativar_vl_recuperado").change(function () {
        //Ao checar o botom ativa os campos input
        if ($('#ativar_vl_recuperado').is(":checked")) {
            $(".vl_recuperado").show(); // habilita o campo
        } else {
            $(".vl_recuperado").hide(); // Desativa o campo caso o mesmo tenha sito checado e removido o check
        }
    }); // Fecha funcao change
    $(document).on('click','.addFieldsVlRecuperado',function(){
        $('<tr class="ClassFieldsVlRecuperado">\n' +
            '                                             <td>\n' +
            '                                                 <div class="form-group" >\n' +
            '                                                      <div class=\'input-group date datetimepicker4\'>\n' +
            '                                                            <input type="text" id="text01" class="form-control input-sm datetimepicker4" name="DTPGTREC[]" value="">\n' +
            '                                                            <span class="input-group-addon">\n' +
            '                                                <span class="glyphicon glyphicon-calendar"></span>\n' +
            '                                            </span>\n' +
            '                                                        </div>\n' +
            '                                                    </div>\n' +
            '                                                </td>\n' +
            '                                                <td><input type="text" id="text01" class="form-control input-sm money" name="VLPGTREC[]" value=""></td>\n' +
            '                                                <td>\n' +
            '                                                    <div class="form-group">\n' +
            '                                                        <a href="#" class="btn btn-success addFieldsVlRecuperado">+</a>\n' +
            '                                                    </div>\n' +
            '                                                    <div class="form-group">\n' +
            '                                                        <a href="#" class="btn btn-danger DelFieldsVlRecuperado">-</a>\n' +
            '                                                    </div>\n' +
            '                                                </td>\n' +
            '                                        </tr>').appendTo(".fieldsVlRecuperado");

        $('.fieldsVlRecuperado').find('.datetimepicker4').datetimepicker({
            format: 'DD/MM/YYYY',
            widgetPositioning: {
                vertical: 'bottom',
                horizontal: 'left'
            }
        });

        $('.fieldsVlRecuperado').find('.money').maskMoney({
            decimal: ",",
            thousands: "."
        });

        return false;

    });

    $(document).on('click','.DelFieldsVlRecuperado',function(){
        $('.ClassFieldsVlRecuperado:last').remove();
        return false;
    });



})