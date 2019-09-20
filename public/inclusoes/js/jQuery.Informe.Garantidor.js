// Funções jQuery para o formulário "Garantia"
// Versão: 2.5

var CamposNM_PARC = ['NU_CNPJ_INSCR','TP_NAT','NM_CONTATO','DE_OCUPACAO','DE_ENDER','DE_CIDADE','NU_CEP','CD_UF','NU_DDD','NU_TEL','NU_DDD_FAX','NU_FAX','DE_EMAIL','ID_PAIS','ID_PAIS','TP_REL_JURIDICA','TP_SETOR','TP_ESTRUTURA','ID_SETOR'];
var ValoresTipo1 = [{Valor:'54',Texto:'TRANSPORTE AÉREO DE CARGA'},{Valor:'55',Texto:'TRANSPORTE AÉREO DE PASSAGEIROS'}];
var ValoresTipo2 = [{Valor:'73',Texto:'OPERADORA'},{Valor:'OUTROS',Texto:'OUTROS'}];

$(document).ready(function(){
	// Controla quais campos serão exibidos ao selecionar um "Garantia"
	// Para auxiliar, determinei 3 tipos de classes:
	// Tipo1 = São os campos que são exibidos ao selecionar "CCR" ou "Soberana"
	// Tipo2 = São os campos que são exibidos ao selecionar "Pessoal" ou "Bancária"
	$('select[name=TP_SETOR]').change(function(e) { // Ao selecionar um "Tipo"
		ocultaElemento('#ID_SETOR',true);
		exibeElemento('#ID_SETOR');
		if ($(this).val()=='1') { // Se selecionar "Voo regulares"
			alteraValores('#ID_SETOR',ValoresTipo1);
		} else if ($(this).val()=='2') { // Se selecionar "Voo não regulares"
			alteraValores('#ID_SETOR',ValoresTipo2);
			$('select[name=ID_SETOR]').change(function(e) {
				if ($(this).val()=='OUTROS') {
					carregaSetores('#ID_SETOR');
				}
			});
		} else if ($(this).val()=='3') { // Se selecionar "Leasing"
			carregaSetores('#ID_SETOR');
		} else if ($(this).val()=='4') { // Se selecionar "Outros"
			carregaSetores('#ID_SETOR');
		} else {
			ocultaElemento('#ID_SETOR',true);
		}
		fixH();
	});
	$('form[name=formGarantidor]').bind('submit',function(e) {
		if (validaFormulario($(this))) {
			emExecucao = false;
			return true;
		} else {
			return false;
		}
	});
});