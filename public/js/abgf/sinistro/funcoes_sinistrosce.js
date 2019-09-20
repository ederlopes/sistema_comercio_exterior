$(document).ready(function () {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

     console.log($('.dt_dc_sinistro').val());
    
   if($('.dt_dc_sinistro').val() !=""){
        $('#IN_CANCELAMENTO').prop('checked',true);
        $('#COL_IN_CANCELAMENTO').collapse('show');
   }

   if($('#DTPGT1').val() !=""){
        $('#IN_PAGTO_ATRASO').prop('checked',true);
        $('#COL_IN_PAGTO_ATRASO').collapse('show');
   }

   if($('#ID_REGULACAO_SINISTRO').val() !=""){
        $('#IN_REGULACAO').prop('checked',true);
        $('#COL_IN_REGULACAO').collapse('show');
    }

    if($('#DT_ENVIO_COMUNICADO_GESTOR').val() !=""){
        $('#IN_INDENIZACAO').prop('checked',true);
        $('#COL_IN_INDENIZACAO').collapse('show');
    }

    if($('#DT_ASSINATURA_CONTRATO_RENEGOCIACAO').val() !=""){
        $('#IN_RENEGOCIACAO').prop('checked',true);
        $('#COL_IN_RENEGOCIACAO').collapse('show');
    }

  $(document).on('click','#modal_recuperacao .salvar',function(){
    
    var DT_PREVISTA  = $('#modal_recuperacao #modal_dt_prevista').val();
    var VL_PRINCIPAL = $('#modal_recuperacao #modal_vl_principal').val();
    var JUROS        = $('#modal_recuperacao #modal_juros').val();
    var DT_EFETIVA   = $('#modal_recuperacao #modal_dt_efetiva').val();
    var VLPAGO       = $('#modal_recuperacao #modal_vlpago').val();
    var OBS          = $('#modal_recuperacao #modal_obs').val();

  
    // verifica se a div existe caso exista oculta ela
    if($('#recuperacaoVazia').length){ 
        $('#recuperacaoVazia').hide()
    }
    
    var elemento = '<tr><td><input type="hidden" value="'+DT_PREVISTA+'" name="DT_PREVISTA[]">'+DT_PREVISTA+'</td><td><input type="hidden" value="'+VL_PRINCIPAL+'" name="VL_PRINCIPAL[]">'+VL_PRINCIPAL+'</td><td><input type="hidden" value="'+JUROS+'" name="JUROS[]">'+JUROS+'</td><td><input type="hidden" value="'+DT_EFETIVA+'" name="DT_EFETIVA[]">'+DT_EFETIVA+'</td><td><input type="hidden" value="'+VLPAGO+'" name="VLPAGO[]">'+VLPAGO+'</td><td><input type="hidden" value="'+OBS+'" name="OBS[]">'+OBS+'</td><td><button class="btn btn-danger btn-sm removerTrRecumentacao">X</button></td></tr>';
    // Adiciona os elementos na tabela
    $('#tdsRecuperacao').append(elemento);
    
    // Limpa a modal
    $('#modal_recuperacao').find("input,textarea,select").val('').end().find("input[type=checkbox], input[type=radio]").prop("checked", "").end();
    
    
    // Fecha o modal
    $('#modal_recuperacao').modal('toggle');

    
    return false;
  });

  $(document).on('click','.removerTrRecumentacao',function(){
    $(this).parents("tr").remove();
  
   if($('#tdsRecuperacao tr').length == 2){ 
        $('#recuperacaoVazia').show('slow')
    }

  });
  

});