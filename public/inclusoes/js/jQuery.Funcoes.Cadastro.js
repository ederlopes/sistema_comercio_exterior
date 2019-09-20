// Funções jQuery para a tela de "Cadastro"
// Versão: 2.6

$(document).ready(function(){

	// Envia o cadastro
	$('form[name=formCadastro]').bind('submit',function(e) {
		if (validaFormulario($(this))) {
			emExecucao = false;
			return true;
		} else {
			return false;
		}
	});
	
});