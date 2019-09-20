// Funções jQuery para a tela de operações
// Versão: 2.6

$(document).ready(function(){

	$('div#Resultado table tr td').click(function(e) {
		ID_INFORME = $(this).closest('tr').attr('id');
        ID_GRUPO_OPERACAO = $(this).closest('tr').attr('grupo');
        TIPO = $(this).closest('tr').attr('tipo');
		switch (ID_GRUPO_OPERACAO) {
			case '1':
				PASTA = 'aeronautica';
				break;
			case '2':
				PASTA = 'defesa';
				break;
			case '3':
				PASTA = 'default';
				break;
		}
		window.location='?p=prestacoes&id='+ID_INFORME+'&tipo='+TIPO;
    });

});