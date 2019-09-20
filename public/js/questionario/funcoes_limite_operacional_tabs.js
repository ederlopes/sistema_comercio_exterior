$(document).ready(function(){
    var alcadaAtual = $('#ID_MPME_ALCADA_ATUAL').val();
    var alcadaAtualAtual = $('#ID_MPME_ALCADA_ATUAL').val();

    $('#' + alcadaAtual + ' .perc_fundo').on('blur', function () {
        var obj                  = $(this);
        var id_fundo             = obj.data('fundo');
        var vl_cred_concedido    = $('#' + alcadaAtual + ' #vl_cred_concedido').val();
            vl_cred_concedido    = replace_all(vl_cred_concedido,".", "");
            vl_cred_concedido    = replace_all(vl_cred_concedido,",", ".");
        var tx_cotacao           = $('#' + alcadaAtual + ' #tx_cotacao').val();
 
        if (obj.val() > 100){
            obj.val('');
            swal("Ops!",'O limite não pode ultrapassar 100%',"warning");
        }
 
         var controle_fundo = ( id_fundo == 1 ) ? parseInt(id_fundo) +  parseInt(1) : parseInt(id_fundo) -  parseInt(1);
 
         if (obj.val() == 100)
         {
             $('#' + alcadaAtual + ' #vl_perc_fundo_'+controle_fundo).attr('readonly', true).val('0.00');
             $('#' + alcadaAtual + ' #in_saldo_suficiente_'+controle_fundo).attr('disabled', true).val('SIM');
             $('#' + alcadaAtual + ' #vl_total_real_'+controle_fundo).val('0.00');
 
             $('#' + alcadaAtual + ' #id_mpme_fundo_garantia_operacao').val(id_fundo);
 
         }else{
             if (id_fundo != 2)
             {
                 if ( $('#' + alcadaAtual + ' #vl_perc_fundo_'+controle_fundo).val() == "" ||  $('#' + alcadaAtual + ' #vl_perc_fundo_'+controle_fundo).val() == '0.00')
                 {
                     $('#' + alcadaAtual + ' #vl_perc_fundo_'+controle_fundo).removeAttr('readonly').val('0.00');
                     $('#' + alcadaAtual + ' #in_saldo_suficiente_'+controle_fundo).removeAttr('disabled').val(0);
                     $('#' + alcadaAtual + ' #vl_total_real_'+controle_fundo).val('0.00');
                 }
             }
         }
 
         var indice   = (obj.val()/100);
         var total    = parseFloat((vl_cred_concedido*tx_cotacao)*indice);
         

 
        $('#' + alcadaAtual + ' #vl_total_real_'+id_fundo).val(formatMoney(total));
     });

     
     $('#' + alcadaAtual + ' #vl_cred_concedido').on('blur', function () {

        var vl_cred_concedido    = $('#' + alcadaAtual + ' #vl_cred_concedido').val();
            vl_cred_concedido    = replace_all(vl_cred_concedido,".", "");
            vl_cred_concedido    = replace_all(vl_cred_concedido,",", ".");
        var tx_cotacao           = $('#' + alcadaAtual + ' #tx_cotacao').val();
        
        var vl_perc_fundo_abgf    = $('#' + alcadaAtual + ' #vl_perc_fundo_1').val();
            vl_perc_fundo_abgf    = replace_all(vl_perc_fundo_abgf,".", "");
            vl_perc_fundo_abgf    = replace_all(vl_perc_fundo_abgf,",", ".");

        var vl_perc_fundo_fgce    = $('#' + alcadaAtual + ' #vl_perc_fundo_2').val();
            
        if ( vl_perc_fundo_fgce != '.00' || vl_perc_fundo_fgce != '0.00' )
        {
            vl_perc_fundo_fgce    = replace_all(vl_perc_fundo_fgce,".", "");
            vl_perc_fundo_fgce    = replace_all(vl_perc_fundo_fgce,",", ".");
        }
        

        if (vl_perc_fundo_abgf != '')  
        {
            var valor_reais_abgf =  (vl_cred_concedido*tx_cotacao)*(vl_perc_fundo_abgf/100);    
            $('#' + alcadaAtual + ' #vl_total_real_1').val(valor_reais_abgf);
            $('#' + alcadaAtual + ' #vl_perc_fundo_1').trigger('blur');   
        }     

        if (vl_perc_fundo_fgce != '')  
        {
            var valor_reais_fgce =  (vl_cred_concedido*tx_cotacao)*(vl_perc_fundo_fgce/100);    
            $('#' + alcadaAtual + ' #vl_total_real_2').val(valor_reais_fgce);
            $('#' + alcadaAtual + ' #vl_perc_fundo_2').trigger('blur');
           
        } 

        if (vl_cred_concedido > $("#valor_solicitado_cliente").val() )
        {
            swal("Ops!",'O valor para aprovação não pode ser maior que o valor solicitado pelo Exportador',"warning");
            $(this).val('');
        }
        

     });

     $('#' + alcadaAtual + ' #id_mpme_fundo_garantia_operacao').on('change', function () {

        $('#' + alcadaAtual + ' #vl_perc_fundo_1').removeAttr('readonly');
        $('#' + alcadaAtual + ' #in_saldo_suficiente_1').removeAttr('disabled');

        $('#' + alcadaAtual + ' #vl_perc_fundo_2').removeAttr('readonly');
        $('#' + alcadaAtual + ' #in_saldo_suficiente_2').removeAttr('disabled');

        if ( $(this).val() == 2)
        {
            $('#' + alcadaAtual + ' #vl_perc_fundo_1').val('0.00').attr('readonly', true);
            $('#' + alcadaAtual + ' #vl_total_real_1').val('0.00');
            $('#' + alcadaAtual + ' #in_saldo_suficiente_1').val('SIM').attr('disabled', true);

            $('#' + alcadaAtual + ' #vl_perc_fundo_2').val('100.00');
            $('#' + alcadaAtual + ' #vl_perc_fundo_2').trigger('blur');
            $('#' + alcadaAtual + ' #in_saldo_suficiente_2').val('SIM').attr('disabled', true);

        }else{

            if ( $('#' + alcadaAtual + ' #vl_perc_fundo_1').val() == "" || $('#' + alcadaAtual + ' #vl_perc_fundo_1').val() == '0.00')
            {
                $('#' + alcadaAtual + ' #vl_perc_fundo_1').val('0.00').removeAttr('disabled');
                $('#' + alcadaAtual + ' #vl_total_real_1').val('0.00');
                $('#' + alcadaAtual + ' #in_saldo_suficiente_1').val(0).removeAttr('disabled');
            }

            if ( $('#' + alcadaAtual + ' #vl_perc_fundo_2').val() == "" || $('#' + alcadaAtual + ' #vl_perc_fundo_2').val() == '0.00') {
                $('#' + alcadaAtual + ' #vl_perc_fundo_2').val('0.00');
                $('#' + alcadaAtual + ' #vl_total_real_2').val('0.00');
                $('#' + alcadaAtual + ' #in_saldo_suficiente_2').val(0);
            }else if( $('#' + alcadaAtual + ' #vl_perc_fundo_2').val() == '100.00'){
                $('#' + alcadaAtual + ' #vl_perc_fundo_2').val('0.00');
                $('#' + alcadaAtual + ' #vl_total_real_2').val('0.00');
                $('#' + alcadaAtual + ' #in_saldo_suficiente_2').val(0);
            }
        }
    })

    $('#' + alcadaAtual + ' #id_mpme_fundo_garantia_operacao').trigger('change');
   



});


function validarForm()
{
    var erros = new Array();
    var total = 0.0;
    var i     = 0;
    var j     = 0;

    if ($('#' + alcadaAtual + ' #id_mpme_fundo_garantia_operacao').val() == 0){
        erros.push('Fundo principal para operação.');
    }

    $('#' + alcadaAtual + ' .perc_fundo').each(function(){
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

    $('#' + alcadaAtual + ' .in_saldo_suficiente').each(function(){
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
        $('#' + alcadaAtual + ' #in_mpme_status').val();
    }else{
        $('#' + alcadaAtual + ' #in_mpme_status').val();
    }

    if ( j > 0 )
    {
        erros.push('Favor selecionar se os dois fundos tem saldo suficiente.');
    }


    if ($('#' + alcadaAtual + ' #in_saldo_suficiente_exp').val() == 0){
        erros.push('Favor selecionar se tem saldo suficiente no controle da Exportação.');
    }


    if (erros.length>0)
    {
        swal({
            title: '<strong>Ops<br /></strong> os seguintes campos devem ser preenchidos <br> <br>',
            type: 'warning',
            html: erros.join('<br />'),
            showCloseButton: true,
        })

        return false;
    }


    if (i > 0  || $('#' + alcadaAtual + ' #in_saldo_suficiente_exp').val() == "NAO")
    {
        $('#' + alcadaAtual + ' #st_oper').val(21);
    }else{
        $('#' + alcadaAtual + ' #st_oper').val(12);
    }

    $('#' + alcadaAtual + ' .in_saldo_suficiente').each(function(){
       $(this).removeAttr('disabled');
    });

    return true;
}

