// Funções jQuery para a tela inicial (Painel)
// Versão: 2.6

$(document).ready(function(){

	$('ul.Menu>li>a.SubMenu').click(function(e) {
		$(this).toggleClass('On');
        $(this).nextAll('ul').slideToggle();
    });
	
	$('ul#Produtos a').click(function(e) {
        MSG = 'Existem formulários que estáo incompletos.\rDeseja recuperá-los?\r\r<OK>          Recupera formulários incompletos.\r<Cancelar> Novo formulário.';
		ID_GRUPO_OPERACAO = $(this).attr('grupo');
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
		if (confirm(MSG)) {
			window.location='?p=recupera&grupo='+ID_GRUPO_OPERACAO;
		} else {
			window.location=PASTA+'/?p=informe';
		}
    });
});