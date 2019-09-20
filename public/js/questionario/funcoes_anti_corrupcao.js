$(document).ready(function(){

    $(document).on('click','.enviardcanticorrupcao', function(event){
        event.preventDefault;
            $('#UploadAntiCorrupcao').show('slow');
    }) ;


    $('#upload_anticorrupcao').on('submit', function(event){
        event.preventDefault();
        var rota = $(this).attr('action');
        $.ajax({
            url:rota,
            method:"POST",
            data:new FormData(this),
            dataType:'JSON',
            contentType:false,
            cache:false,
            processData: false,
            success:function(data)
            {
                $('#upload_anticorrupcao').hide('slow');
                $('#message_docanticorrupcao_relatorio').css('display', 'block');
                $('#message_docanticorrupcao_relatorio').html(data.message);
                $('#message_docanticorrupcao_relatorio').removeClass();
                $('#message_docanticorrupcao_relatorio').addClass(data.class_name);
                $('#upload_anticorrupcao_realizado').show('slow').html(data.upload_anticorrupcao_realizado);
            }
        });
    });


});