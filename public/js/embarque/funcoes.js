$(document).ready(function(){

    $("#nu_due").prop("readonly", true);
    $("#nu_rvs").prop("readonly", true);

    $("#id_mpme_tipo_embarque").on('change', function () {
        if ( $(this).val() == 1 ) //mercadoria
        {
            $("#nu_due").prop("readonly", false);
            $("#nu_rvs").prop("readonly", true).val('');
        }else{ // 2 - servico
            $("#nu_due").prop("readonly", true).val('');
            $("#nu_rvs").prop("readonly", false);
        }
    })


    $("#frmEmbarque").on('click','.btnAdd',function(e) {

        var id          = $('table#tabela_mercadoria tr').length;
        var nova_linha  = $('<tr class="fildadd" id="mercadoria_'+id+'">');
        var colunas     = "";

        colunas += '<td><input id="ncm_'+id+'" type="text" class="form-control input-sm ncm somentenumero" name="mercadoria[ncm][]" value=""></td>';
        colunas += '<td><input id="nm_mercadoria_'+id+'" type="text" class="form-control input-sm nm_mercadoria" name="mercadoria[nm_mercadoria][]" readonly="readonly"></td>';
        colunas += '<td>' +
            '<select class="form-control input-sm in_aceite" id="in_aceite_'+id+'" name="mercadoria[in_aceite][]">\n' +
            '   <option value="">Selecione</option>\n' +
            '   <option value="S">SIM</option>\n' +
            '   <option value="N">NÃO</option>\n' +
            '</select>' +
            '</td>';
        colunas += '<td><input type="text" id="vl_mercadoria_'+id+'" class="form-control input-sm money vl_mercadoria" name="mercadoria[vl_mercadoria][]"></td>';
        colunas += '<td><input type="text" id="no_observacao_'+id+'" class="form-control input-sm no_observacao" name="mercadoria[no_observacao][]"></td>';
        colunas += '<td><a href="javascript:void(0);" class="btn btn-success btnAdd">+</a>&nbsp;<a href="javascript:void(0)" class="btn btn-danger remover">-</a></td>';
        nova_linha.append(colunas);
        id++;

        $("#tabela_mercadoria").append(nova_linha);

        $(".money").maskMoney({
            decimal: ",",
            thousands: "."
        });

        $('.somentenumero').mask('0#');

    })


    $("#tabela_mercadoria").on("click", ".remover", function(e){
        if ( $("#tabela_mercadoria tr").length > 2)
        {
            $(this).closest('tr').remove();
        }else{
            alert('Esta linha não pode ser removida');
        }

    });

    $("#btnCadastrar").on("click", function(){

        validarForm();


    })

    $('#dt_embarque').on('blur',function (){
        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'ajax/calcular-data',
            data: {'dt_inicial':$(this).val(), 'nu_prazo': $("#nu_prazo_pos").val() },
            context: this,
            success: function(retorno){
                $("#dt_vencimento").val(retorno);
            },
            error: function (request, status, error) {
                //console.log(request.responseText);
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                    //  location.reload();
                });
            }
        });
    });


    $("#btnCadastrar").on('click', function(){
        if (validarForm() == true)
        {
            $.ajax({
                type: "POST",
                method: "POST",
                url: URL_BASE+'embarque/salvar',
                data: $("#frmEmbarque").serialize(),
                context: this,
                success: function(retorno){
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
                error: function (request, status, error) {
                    //console.log(request.responseText);
                    swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                        //  location.reload();
                    });
                }
            });
        }
    })



    $(document).on('blur','.ncm',function(e){
      var ncm              = $(this).val();
      var id_mpme_tipo_embarque = $("#id_mpme_tipo_embarque").val();

      if (id_mpme_tipo_embarque == "")
      {
          $(this).val('');
          swal("Ops!", "Favor selecionar o Tipo de Embarque antes", "info").then(function() {
              $("#id_mpme_tipo_embarque").focus();
          });
          return false;
      }

        if (ncm == "")
        {
            $(this).val('');
            swal("Ops!", "O campo NCM/NBS não pode ser vazio", "info");
            return false;
        }

        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE+'ajax/buscarncmnbs',
            data: {
                'id_mpme_tipo_embarque' : id_mpme_tipo_embarque,
                'codigoncm'        : ncm,
            },
            context: this,
            success: function(retorno) {
                $(".loading").hide();
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!",retorno.msg,"error");
                        break;
                    case 'sucesso':
                        $(this).closest('tr').find('.nm_mercadoria').val(retorno.value);
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!",retorno.msg,"warning");
                        break;
                }
                if (retorno.recarrega=='true') {
                    alerta.then(function(){
                        //  location.reload();
                    });
                }
            },
            error: function (request, status, error) {
                swal("Erro!", "Por favor, tente novamente mais tarde. Erro nº X", "error").then(function() {
                    //location.reload();
                });
            }
        });

    })


    




});


function validarForm()
{
    var erros = new Array();


    if ( $('#id_mpme_tipo_embarque').val() == "")
    {
        erros.push('O campo Tipo de embarque deve ser preenchido.');
    }

    if ( $('#vl_embarque').val() == "")
    {
        erros.push('O campo Valor do embarque deve ser preenchido.');
    }

    if ( $('#vl_financiamento').val() == "")
    {
        erros.push('O campo Valor do financiamento deve ser preenchido.');
    }

    if ( $('#nu_fatura').val() == "")
    {
        erros.push('O campo Número da fatura deve ser preenchido.');
    }

    if ( $('#id_mpme_tipo_embarque').val() == 1 && $('#nu_due').val() == "")
    {
        erros.push('O campo Número da DU-E deve ser preenchido.');
    }

    if ( $('#id_mpme_tipo_embarque').val() == 2 && $('#nu_rvs').val() == "")
    {
        erros.push('O campo Número da RVS deve ser preenchido.');
    }

    if ( $('#dt_embarque').val() == "")
    {
        erros.push('O campo Data de embarque deve ser preenchido.');
    }

    if ( $('#dt_vencimento').val() == "")
    {
        erros.push('O campo Data de vencimento deve ser preenchido.');
    }

    var i = 0;

    $('table#tabela_mercadoria tr ').each(function (el) {

        if (i>0) //retirando a coluna de titulos
        {
            if ($(this).find('.ncm').val()=="")
            {
                erros.push('O campo NCM da linha: '+i+' deve ser preenchido.');
            }

            if ($(this).find('.nm_mercadoria').val()=="")
            {
                erros.push('O campo Nome da mercadoria da linha: '+i+' deve ser preenchido.');
            }

            if ($(this).find('.in_aceite').val()=="")
            {
                erros.push('O campo Aceite de Titulo da linha: '+i+' deve ser preenchido.');
            }

            if ($(this).find('.vl_mercadoria').val()=="")
            {
                erros.push('O campo Aceite de Titulo da linha: '+i+' deve ser preenchido.');
            }
        }

        i++;

    })

    if (erros.length>0)
    {
        swal('Ops!', erros.join('<br />'), 'info' );
        return false;
    }

    return true;
}