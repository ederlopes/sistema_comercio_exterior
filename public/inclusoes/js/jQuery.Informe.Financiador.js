// Funções jQuery para o formulário "Financiador"
// Versão: 2.5

var CamposNM_PARC = ['NM_AGENCIA','NU_CNPJ_INSCR','NM_CONTATO','DE_OCUPACAO','DE_ENDER','DE_CIDADE','NU_CEP','CD_UF','NU_DDD','NU_TEL','NU_DDD_FAX','NU_FAX','DE_EMAIL','ID_SETOR','ID_PAIS'];
var CamposNM_AGENCIA = ['NU_CNPJ_INSCR','DE_ENDER','DE_CIDADE','NU_CEP','CD_UF','NU_DDD','NU_TEL','ID_PAIS'];
var CamposBNDES = ['NU_CNPJ_INSCR','NM_CONTATO','DE_OCUPACAO','DE_ENDER','DE_CIDADE','NU_CEP','CD_UF','NU_DDD','NU_TEL','NU_FAX','DE_EMAIL'];
// var ValoresFinanciamento1 = [{Valor:'2',Texto:'BNDES exim Pós-Embarque'},{Valor:'3',Texto:'BNDES exim Pós-Embarque com PROEX Equalização'}];
//var ValoresFinanciamento1 = [{Valor:'2',Texto:'BNDES exim Pós-Embarque'},{Valor:'4',Texto:'FINAME'}];
//var ValoresFinanciamento2 = [{Valor:'1',Texto:'PROEX Financiamento'},{Valor:'8',Texto:'Outros'}];

$(document).ready(function(){
	// Controla quais campos serão exibidos ao selecionar um "Banco Financiador"
	// Para auxiliar, determinei 2 tipos de classes:
	// Condicional = São os campos que são exibidos dependendo do "Banco Financiador"
	// Comum = São os campos exibidos para qualquer caso
	$('select[name=TP_BANCO_FINANC]').change(function(e) { // Ao selecionar um "Banco Financiador"
		ocultaElemento('.Condicional, .Comum',true);
		if ($(this).val()=='1') { // Se selecionar "BNDES"
			exibeElemento('#TP_PROG_FINANC');
			exibeElemento('#TP_EQUALIZ');
			exibeElemento('.Comum');
			
			carregaCamposBNDES(CamposBNDES);
			
			carregaLinhas('#TP_PROG_FINANC','NOVO',$(this).val());
//			alteraValores('#TP_PROG_FINANC',ValoresFinanciamento1);
		} else if ($(this).val()=='2') { // Se selecionar "Banco do Brasil"
			exibeElemento('#TP_PROG_FINANC');
			exibeElemento('#TP_EQUALIZ');
			exibeElemento('#NM_AGENCIA_LISTA');
			exibeElemento('.Comum');
			
			carregaLinhas('#TP_PROG_FINANC',false,$(this).val());
//			alteraValores('#TP_PROG_FINANC',ValoresFinanciamento2);
			
			carregaAgenciasBB('#NM_AGENCIA_LISTA');
			iniciaNM_AGENCIA(CamposNM_AGENCIA);
		} else if ($(this).val()=='3') { // Se selecionar "Outro"
			exibeElemento('#NM_AGENCIA_BOX');
			$('div#NM_AGENCIA_BOX').removeClass('Toggle');
			
			exibeElemento('#NM_PARC_LISTA');
			exibeElemento('.Comum');
			
			carregaNomes('#NM_PARC_LISTA','F','NOVO');
			
			iniciaNM_PARC(CamposNM_PARC);
		}
		fixH();
	});
	$('form[name=formFinanciador]').bind('submit',function(e) {
		if (validaFormulario($(this))) {
			emExecucao = false;
			return true;
		} else {
			return false;
		}
	});
});