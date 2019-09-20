$(document).ready(function () {
    $('#lista_pais').DataTable({
        "ordering": false,
        "autoWidth": false,
        "lengthMenu": [[50, 100, 200, 300, -1], [50, 100, 200, 300, "All"]],
        "language": {
            "lengthMenu": "Exibindo _MENU_ registros por página",
            "zeroRecords": "Desculpe não foram encontrados registros com estes parametros",
            "info": "Exibindo _PAGE_ de _PAGES_ páginas",
            "infoEmpty": "Sem registros",
            "infoFiltered": "",
            "search": "Pesquisar",
            "paginate": {
                "previous": "Anterior",
                "next": "Próximo",
            }
        }
    });

    $("#lista_pais").on('click', '.inputrisco', function () {
        var id_pais = $(this).data('idpais');
        if ($(this).is(':checked')) {
            $("#novo_risco_" + id_pais).prop("disabled", false);
            $("#risco_atual_" + id_pais).prop("disabled", false);
            $("#novo_risco_" + id_pais).focus();
        } else {
            $("#novo_risco_" + id_pais).prop("disabled", true);
            $("#risco_atual_" + id_pais).prop("disabled", true);
        }

    })

    $(".novorisco").on('blur', function () {
        var objeto = $(this);
        if (objeto.val() > 7) {
            swal("Ops!", "O valor do campo não pode ser superior a 7", "info").then(function () {
                objeto.val('').focus();
            });
        } else if (objeto.val() < 0) {
            swal("Ops!", "O valor do campo não pode ser menor que 0", "info").then(function () {
                objeto.val('').focus();
            });
        } else {
            $(".input-sm").val('');
        }

    })

    /*setTimeout(function () {
        $(".input-sm").val('');
    }, 1000);*/

    $("#btnCadastrar").on('click', function () {
        $.ajax({
            type: "POST",
            method: "POST",
            url: URL_BASE + 'abgf/paises-risco/gravar',
            context: this,
            data: $("#frmRiscoPais").serialize(),
            success: function (retorno) {
                switch (retorno.status) {
                    case 'erro':
                        var alerta = swal("Erro!", retorno.msg, "error");
                        break;
                    case 'sucesso':
                        var alerta = swal("Sucesso!", retorno.msg, "success");
                        break;
                    case 'alerta':
                        var alerta = swal("Ops!", retorno.msg, "warning");
                        break;
                }
                if (retorno.recarrega == 'true') {
                    alerta.then(function () {
                        location.reload();
                    });
                }
            },
            error: function (request, status, error) {
                swal("Erro!", "Erro ao atualizar os dados do Risco", "error").then(function () {
                    //location.reload();
                });
            }
        });
    })

});


function validarForm() {
    var erros = new Array();
    $(".novorisco").each(function (index) {
        var nm_pais = $(this).data('nmpais');
        var objeto = $(this);
        if (objeto.val() == '') {
            erros.push('O novo risco do país' + nm_pais + ' não pode ficar vazio');
        } else if (objeto.val() > 7) {
            erros.push('O valor do risco do país' + nm_pais + ' não pode ser superior a 7');
        } else if (objeto.val() < 0) {
            erros.push('O valor do risco do país' + nm_pais + ' não pode ser menor do que 0');
        }
    });

}
