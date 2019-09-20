var index_arquivos = 1;

$(document).ready(function() {
    $('div#novo-arquivo').on('show.bs.modal', function (ev) {
        var id_mpme_tipo_arquivo = $(ev.relatedTarget).data('idmpmetipoarquivo');
        var container = $(ev.relatedTarget).data('container');
        var limite = $(ev.relatedTarget).data('limite');
        var token = $(ev.relatedTarget).data('token');
        var id_flex = $(ev.relatedTarget).data('idflex')?$(ev.relatedTarget).data('idflex'):0;
        var id_oper = $(ev.relatedTarget).data('idoper')?$(ev.relatedTarget).data('idoper'):0;
        var texto = $(ev.relatedTarget).data('texto')?$(ev.relatedTarget).data('texto'):'';
        var pasta = $(ev.relatedTarget).data('pasta')?$(ev.relatedTarget).data('pasta'):'outros';
        var extensoes = $(ev.relatedTarget).data('extensoes');
        var in_ass_digital = $(ev.relatedTarget).data('inassdigital')?$(ev.relatedTarget).data('inassdigital'):'';

        $("div#novo-arquivo #enviar-arquivo").prop('disabled', false);

        if (total_arquivo(container,'') < parseInt(limite) || limite=='0')
        {
            var data_args =
                {
                    'id_mpme_tipo_arquivo':id_mpme_tipo_arquivo,
                    'token':token,
                    'id_flex':id_flex,
                    'id_oper':id_oper,
                    'texto':texto,
                    'container':container,
                    'pasta':pasta,
                    'index_arquivos':index_arquivos,
                    'extensoes':extensoes,
                    'in_ass_digital':in_ass_digital
                };
            $.ajax({
                type: "POST",
                url: URL_BASE+'abgf/arquivos/novo',
                data: data_args,
                context: this,
                beforeSend: function(){
                    $(".loading").show();
                },
                success: function(retorno)
                {
                    $(this).find('.modal-body form').html(retorno);
                    $(".loading").hide();
                },
                error: function(ev, xhr, settings, error)
                {
                    ajax_error(ev,$(this),true);
                }
            });
        } else {
            swal("Ops!", "Você alcançou o limite de "+limite+" arquivo(s) para esta seção.", "warning");
            return false;
        }
    });

    $('form[name=form-novo-arquivo]').on('change','label.arquivo-upload input[type=file]',function(ev) {
        if (!$(this).val() == '') {
            var form = $(this).closest('form');

            var id_mpme_tipo_arquivo = form.find('input[name=id_mpme_tipo_arquivo]').val();
            var container = form.find('input[name=container]').val();
            var extensoes = form.find('input[name=extensoes]').val();

            var no_arquivo = $(this).val().split('\\');
            var no_arquivo = remove_caracteres(no_arquivo[no_arquivo.length-1]);
            var ext_arquivo = no_arquivo.split('.');
            var ext_arquivo = ext_arquivo[ext_arquivo.length-1];
            if (extensoes!='') {
                var ext_permitidas = extensoes.split('|');
            } else {
                var ext_permitidas = extensoes_permitidas(id_mpme_tipo_arquivo);
            }
            if (ext_permitidas.indexOf(ext_arquivo.toLowerCase())<0)
            {
                swal("Ops!", "O arquivo selecionado é inválido, tipos de arquivo permitidos: <br /><br />."+ext_permitidas.join(', .'), "warning");
                $(this).replaceWith($(this).val('').clone(true));
                return false;
            } else if (total_arquivo(container,remove_caracteres(no_arquivo).toLowerCase())>0)
            {
                swal("Ops!", "O arquivo selecionado já foi inserido.", "warning");
                $(this).replaceWith($(this).val('').clone(true));
                return false;
            } else {
                form.find('div.msg-arquivo').slideUp();
                form.find('div.erros').slideUp();
                form.find('label.arquivo-upload h4').html(remove_caracteres(no_arquivo).toLowerCase());
                form.find('label.arquivo-upload h4').prop('title',remove_caracteres(no_arquivo).toLowerCase());
                switch (ext_arquivo)
                {
                    case 'pdf':
                        var classes_icone = 'fa fa-file-pdf-o';
                        break;
                    case 'png': case 'jpg': case 'bmp': case 'tif':
                    var classes_icone = 'fa fa-file-image-o';
                    form.find('div.msg-arquivo').slideDown();
                    form.find('div.msg-arquivo div.menssagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
                    break;
                    case 'doc': case 'docx':
                    var classes_icone = 'fa fa-file-word-o';
                    form.find('div.msg-arquivo').slideDown();
                    form.find('div.msg-arquivo div.menssagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
                    break;
                    case 'xls': case 'xlsx':
                    var classes_icone = 'fa fa-file-excel-o';
                    form.find('div.msg-arquivo').slideDown();
                    form.find('div.msg-arquivo div.menssagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
                    break;
                    case 'txt':
                        var classes_icone = 'fa fa-file-text-o';
                        form.find('div.msg-arquivo').slideDown();
                        form.find('div.msg-arquivo div.menssagem').html('O arquivo será convertido para <b>PDF</b> automaticamente.');
                        break;
                    case 'ppt': case 'pptx': case 'pps': case 'ppsx':
                    var classes_icone = 'fa fa-file-powerpoint-o';
                    break;
                    default:
                        var classes_icone = 'fa fa-file-o';
                        break;
                }
                form.find('label.arquivo-upload i').attr('class',classes_icone);
            }
        }
    });
    $('div#novo-arquivo').on('click','button.enviar-arquivo', function(ev) {



        var form                 = $('form[name=form-novo-arquivo]');
        var erros                = new Array();
        var obj_modal            = $(this).closest('.modal');
        var id_mpme_tipo_arquivo = form.find('input#id_mpme_tipo_arquivo').val();


        if (form.find('input#arquivo-upload').val()=='') {
            erros.push('O arquivo é obrigatório.');
        }


        if (erros.length>0) {
            form_error(erros);
        } else {
            $(this).prop('disabled', true);
            form.find('div.erros').slideUp();
            var data = new FormData(form.get(0));
            $.ajax({
                url: URL_BASE+'abgf/arquivos/inserir',
                type: 'POST',
                data: data,
                context: this,
                beforeSend: function()
                {
                    //ajax_beforesend(obj_modal);
                   // form.find('label.arquivo-upload').hide();
                    form.find('div.progresso-upload').show();
                },
                success: function(retorno)
                {
                    
                    
                   switch (id_mpme_tipo_arquivo)
                   {
                       case "9":
                           retorno_arquivo_comprovante(retorno,obj_modal);
                       break
                       case "10":
                           retorno_arquivo_cg(retorno,obj_modal);
                       break
                       case "13":
                           retorno_arquivo_cg_assinado(retorno,obj_modal);
                       break
                       case "15":
                           retorno_arquivo_apolice(retorno,obj_modal);
                       break
                       
                       case "16":
                           retorno_arquivo_apolice_assinada(retorno,obj_modal);
                       break
                       default:
                           retorno_arquivo(retorno,obj_modal);
                       break
                   }

                },
                error: function(ev, xhr, settings, error)
                {
                    ajax_error(ev,obj_modal,true);
                },
                cache: false,
                contentType: false,
                processData: false,
                xhr: function()
                {
                    var myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload)
                    {
                        myXhr.upload.addEventListener('progress', function (ev)
                        {
                            if (ev.lengthComputable)
                            {
                                var porcentagem = ev.loaded / ev.total;
                                porcentagem = parseInt(porcentagem * 100);

                                form.find('div.progresso-upload div.progress-bar').css('width',porcentagem+'%');

                                if (porcentagem >= 100)
                                {
                                    form.find('div.progresso-upload').hide();
                                } else if (porcentagem > 10)
                                {
                                    form.find('div.progresso-upload div.progress-bar').html(porcentagem+'%');
                                }
                            }
                        }, false);
                    }
                    return myXhr;
                }
            });
        }
    });

    $('body').on('click','div.arquivo>button.remover',function(ev) {
        ev.preventDefault();
        var index_arquivo = $(this).data('indexarquivo');
        var token = $(this).data('token');
        var obj_modal = $(this).closest('.modal');

        var data_args =
            {
                'token':token,
                'index_arquivo':index_arquivo
            };
        $.ajax({
            type: "POST",
            url: URL_BASE+'abgf/arquivos/remover',
            data: data_args,
            beforeSend: function()
            {
                ajax_beforesend(obj_modal);
            },
            success: function(retorno)
            {
                swal("Sucesso!",retorno.msg,"success").then(function() {
                    remove_arquivo(retorno,index_arquivo);
                    ajax_success(obj_modal);
                });
            },
            error: function(ev, xhr, settings, error)
            {
                ajax_error(ev,obj_modal,false);
            },
        });
    });

    $(".arquivoslancamento").on('change', function (ev) {

        var extensoes = $(this).data('extensoes');

        var no_arquivo = $(this).val().split('\\');
        var no_arquivo = remove_caracteres(no_arquivo[no_arquivo.length-1]);
        var ext_arquivo = no_arquivo.split('.');
        var ext_arquivo = ext_arquivo[ext_arquivo.length-1];

        if (extensoes!='') {
            var ext_permitidas = extensoes.split('|');
        }else{
            var ext_permitidas = 'pdf';
        }

        if (ext_permitidas.indexOf(ext_arquivo.toLowerCase())<0)
        {
            swal("Ops!", "O arquivo selecionado é inválido, tipos de arquivo permitidos: <br /><br />."+ext_permitidas.join(', .'), "warning");
            $(this).replaceWith($(this).val('').clone(true));
            return false;
        }
    })

});

function extensoes_permitidas(id_mpme_tipo_arquivo) {
    var permitidas = ['pdf','png','jpg','jpeg','bmp','tif','doc','docx','xls','xlsx','txt','ppt','pptx','pps','ppsx'];
    return permitidas;
}



function total_arquivo(container,no_arquivo) {
    if (no_arquivo!='') {
        var find = 'input[value="'+no_arquivo+'"]';
    } else {
        var find = 'div.arquivo';
    }
    return $(container).find(find).length;
}

function remove_arquivo(retorno,index_arquivo) {
    $(retorno.arquivo.container).find('div#'+index_arquivo).remove();
   // atualiza_assinar_todos(retorno.token);
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

    return text;
}

function ajax_beforesend(modal) {
    modal.find('.modal-content').addClass('carregando');
}
function ajax_success(modal,titulo) {
    if (titulo)
    {
        modal.find('.modal-title>span').html(titulo);
    }
    modal.find('.modal-content').removeClass('carregando');
}
function ajax_error(ev,modal,hide_modal) {
    tipo = 'error';
    titulo = 'Erro!';
    reload = false;
    switch (ev.status)
    {
        case 419:
            msg = 'Houve um problema na resposta do servidor, clique em OK para atualizar a página.';
            reload = true;
            break;
        case 422:
            tipo = 'warning';
            titulo = 'Ops!';
            msg = 'Alguns campos não foram devidamente preenchidos: <br /><br />';
            $.each(ev.responseJSON.errors,function(key,erro) {
                msg += erro+'<br />';
            });
            break;
        default:
            msg = 'Por favor, tente novamente mais tarde. Erro '+ev.status;
            if (APP_DEBUG)
            {
                if (typeof ev.responseJSON !== 'undefined') {
                    if (ev.responseJSON.message.length<=0)
                    {
                        msg += ' ('+ev.statusText+')';
                    } else {
                        msg += '<br /><br />'+ev.responseJSON.message;
                    }
                } else if (typeof ev.statusText !== 'undefined') {
                    msg += ' ('+ev.statusText+')';
                } else {
                    msg += ' (Erro desconhecido)';
                }
            }
            break;
    }

    swal(titulo, msg, tipo).then(function()
    {
        if (modal) {
            if (hide_modal)
            {
                modal.modal('hide');
            } else {
                modal.find('.modal-content').removeClass('carregando');
            }
        }
        if (reload) {
            location.reload();
        }
    });
}
function form_error(erros) {
    swal('Ops!', 'Alguns campos não foram devidamente preenchidos: <br /><br />'+erros.join('<br />'), 'warning');
}