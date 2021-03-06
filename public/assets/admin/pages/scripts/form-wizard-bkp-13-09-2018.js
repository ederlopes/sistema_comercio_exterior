var FormWizard = function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    return {
        //main function to initiate the module
        init: function () {
            if (!jQuery().bootstrapWizard) {
                return;
            }

            function format(state) {
                if (!state.id) return state.text; // optgroup
                return "<img class='flag' src='../../assets/global/img/flags/" + state.id.toLowerCase() + ".png'/>&nbsp;&nbsp;" + state.text;
            }

            $("#country_list").select2({
                placeholder: "Select",
                allowClear: true,
                formatResult: format,
                formatSelection: format,
                escapeMarkup: function (m) {
                    return m;
                }
            });

            var form = $('#submit_form');
            var error = $('.alert-danger', form);
            var success = $('.alert-success', form);

            form.validate({
                doNotHideMessage: true, //this option enables to show the error/success messages on tab switch.
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                rules: {
                    //account
                    username: {
                        minlength: 5,
                        required: true
                    },
                    password: {
                        minlength: 5,
                        required: true
                    },
                    rpassword: {
                        minlength: 5,
                        required: true,
                        equalTo: "#submit_form_password"
                    },
                    //profile
                    fullname: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    phone: {
                        required: true
                    },
                    gender: {
                        required: true
                    },
                    address: {
                        required: true
                    },
                    city: {
                        required: true
                    },
                    country: {
                        required: true
                    },
                    //payment
                    card_name: {
                        required: true
                    },
                    card_number: {
                        minlength: 16,
                        maxlength: 16,
                        required: true
                    },
                    card_cvc: {
                        digits: true,
                        required: true,
                        minlength: 3,
                        maxlength: 4
                    },
                    card_expiry_date: {
                        required: true
                    },
                    'payment[]': {
                        required: true,
                        minlength: 1
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    'payment[]': {
                        required: "Please select at least one option",
                        minlength: jQuery.validator.format("Please select at least one option")
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.attr("name") == "gender") { // for uniform radio buttons, insert the after the given container
                        error.insertAfter("#form_gender_error");
                    } else if (element.attr("name") == "payment[]") { // for uniform radio buttons, insert the after the given container
                        error.insertAfter("#form_payment_error");
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success.hide();
                    error.show();
                    Metronic.scrollTo(error, -200);
                },

                highlight: function (element) { // hightlight error inputs
                    $(element)
                        .closest('.form-group').removeClass('has-success').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    if (label.attr("for") == "gender" || label.attr("for") == "payment[]") { // for checkboxes and radio buttons, no need to show OK icon
                        label
                            .closest('.form-group').removeClass('has-error').addClass('has-success');
                        label.remove(); // remove error label here
                    } else { // display success icon for other inputs
                        label
                            .addClass('valid') // mark the current input as valid and display OK icon
                            .closest('.form-group').removeClass('has-error').addClass('has-success'); // set success class to the control group
                    }
                },

                submitHandler: function (form) {
                    success.show();
                    error.hide();
                    //add here some ajax code to submit your form or just call form.submit() if you want to submit the form without ajax
                }

            });

            var displayConfirm = function() {
                $('#tab4 .form-control-static', form).each(function(){
                    var input = $('[name="'+$(this).attr("data-display")+'"]', form);
                    if (input.is(":radio")) {
                        input = $('[name="'+$(this).attr("data-display")+'"]:checked', form);
                    }
                    if (input.is(":text") || input.is("textarea")) {
                        $(this).html(input.val());
                    } else if (input.is("select")) {
                        $(this).html(input.find('option:selected').text());
                    } else if (input.is(":radio") && input.is(":checked")) {
                        $(this).html(input.attr("data-title"));
                    } else if ($(this).attr("data-display") == 'payment') {
                        var payment = [];
                        $('[name="payment[]"]:checked').each(function(){
                            payment.push($(this).attr('data-title'));
                        });
                        $(this).html(payment.join("<br>"));
                    }
                });
            }

            var handleTitle = function(tab, navigation, index) {
                var total = navigation.find('li').length;
                var current = index + 1;
                // set wizard title
                $('.step-title', $('#form_wizard_1')).text('Passo ' + (index + 1) + ' de ' + total);
                // set done steps
                jQuery('li', $('#form_wizard_1')).removeClass("done");
                var li_list = navigation.find('li');
                for (var i = 0; i < index; i++) {
                    jQuery(li_list[i]).addClass("done");
                }

                if (current == 1) {
                    $('#form_wizard_1').find('.button-previous').hide();
                } else {
                    $('#form_wizard_1').find('.button-previous').show();
                }

                if (current >= total) {
                    $('#form_wizard_1').find('.button-next').hide();
                    $('#form_wizard_1').find('.button-submit').show();
                    displayConfirm();
                } else {
                    $('#form_wizard_1').find('.button-next').show();
                    $('#form_wizard_1').find('.button-submit').hide();
                }
                Metronic.scrollTo($('.page-title'));
            }


            $('.PARTICIPACAO').on('focusout',function() {
                /*
                var quadro01 = parseFloat($('input[name=PARTICIPACAO_QUADRO]').val());
                var quadro02 = parseFloat($('input[name=PARTICIPACAO_QUADRO1]').val());
                var quadro03 = parseFloat($('input[name=PARTICIPACAO_QUADRO2]').val());
                soma = quadro01 + quadro02 + quadro03;
                */

            });

            // default form wizard
            $('#form_wizard_1').bootstrapWizard({
                'nextSelector': '.button-next',
                'previousSelector': '.button-previous',
                onTabClick: function (tab, navigation, index, clickedIndex) {
                    // return false;

                    success.hide();
                    error.hide();



                    var erro = 0;


                    if( soma != 100.00 && clickedIndex > 2 || soma == 0.00 && clickedIndex > 2){
                        alert('A soma da participação deve ser 100 %');
                        erro = 1;

                    }

                    if (erro == 1) {

                        return false;

                    }



                    handleTitle(tab, navigation, clickedIndex);

                },
                onNext: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    var n = $('.PARTICIPACAO').length-1;
                    var soma = 0;
                    for(i=0;i<=n;i++){
                        soma = soma + parseFloat($('#PARTICIPACAO_QUADRO-' + i).val());
                    }

                    var erro = 0;

                    if( soma != 100.00 && index == 3 || soma == 0.00 && index == 3){
                        alert('A soma da participação deve ser 100 %');
                        erro = 1;

                    }

                    if (form.valid() == false || erro == 1) {

                        return false;

                    }









                    handleTitle(tab, navigation, index);
                },
                onPrevious: function (tab, navigation, index) {
                    success.hide();
                    error.hide();

                    handleTitle(tab, navigation, index);
                },
                onTabShow: function (tab, navigation, index) {
                    var total = navigation.find('li').length;
                    var current = index + 1;
                    var $percent = (current / total) * 100;
                    $('#form_wizard_1').find('.progress-bar').css({
                        width: $percent + '%'
                    });
                }
            });
            var rota = $('#rota').val();
            $('#form_wizard_1').find('.button-previous').hide();
            $('#form_wizard_1 .button-submit').click(function () {

                var length = $('.PARTICIPACAO').length;
                $('#quant_socios').val(length);

                var myform = $('form[name=frmCadastro]');

                // Find disabled inputs, and remove the "disabled" attribute
                var disabled = myform.find(':input:disabled').removeAttr('disabled');

                var dados = $('form[name=frmCadastro]').serialize();

               //$('form[name=frmCadastro]').submit();
                console.log(dados);

                $.ajax({
                    type: "GET",
                    url: '/cadastrar',
                    data: dados,
                    beforeSend: function() {
                        // setting a timeout
                        $('#form_wizard_1 .button-submit').html('enviando...').attr('disabled','disabled');
                    },
                    success: function( retorno ) // retorna um json com a mensagem de erro ou sucesso
                    {

                        if(retorno == "Cadastrado com sucesso!"){

                            swal(retorno, "", "success");
                            //alert(retorno); // exibe a mensagem de sucesso caso tenha salvo ou atualizado
                            setTimeout(function () {
                                window.location.href = "http://www.abgf.gov.br";
                            }, 1000);


                        }else{

                            jQuery.each(retorno.errors, function (key, value) {
                                // alert(value);
                                $('.enviar').prop('disabled',false);
                                $('#form_wizard_1 .button-submit').removeAttr("disabled");
                                $('#form_wizard_1 .button-submit').html('Enviar');
                                swal(value, "", "error");

                                return false;
                            });


                        }



                    },
                    error: function(erro) {
                        //console.log(erro);
                       //alert('ocorreu um erro ao efetuar seu cadastro, entre em contato com o setor de tecnilogia da ABGF para verificar o ocorrido. Cod do erro: 0012015')
                        swal(erro, "", "Error");
                    }
                });




                // re-disabled the set of inputs that you previously enabled
                disabled.attr('disabled','disabled');



               // $('form[name=frmCadastro]').submit();

            }).hide();
        }

    };

}();