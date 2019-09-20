// Funções jQuery para o formulário "Exportador"
// Versão: 2.0

var CamposNM_PARC = ['NU_CNPJ_INSCR','NM_CONTATO','DE_OCUPACAO','DE_ENDER','DE_CIDADE','NU_CEP','CD_UF','NU_DDD','NU_TEL','NU_DDD_FAX','NU_FAX','DE_EMAIL','ID_SETOR'];
$(document).ready(function(){
	$('form[name=formExportador]').bind('submit',function(e) {
		if (validaFormulario($(this))) {
			emExecucao = false;
			return true;
		} else {
			return false;
		}
	});
});