// Funções jQuery para a tela de "Acesso"
// Versão: 2.6

$(document).ready(function(){
	
	$('form[name=formLogin]').bind('submit',function(e) {
		if (validaFormulario($(this))) {
			emExecucao = false;
			return true;
		} else {
			return false;
		}
	});
	
});