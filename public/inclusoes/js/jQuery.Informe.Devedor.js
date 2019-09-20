// Funções jQuery para o formulário "Importador"
// Versão: 2.2

var CamposNM_PARC = ['NM_PARC','NU_CNPJ_INSCR','TP_NAT','NM_CONTATO','DE_OCUPACAO','DE_ENDER','DE_CIDADE','NU_CEP','CD_UF','NU_DDD','NU_TEL','NU_DDD_FAX','NU_FAX','DE_EMAIL','ID_PAIS','TP_SETOR','TP_ESTRUTURA','ID_SETOR'];
var ValoresTipo1 = [{Valor:'54',Texto:'TRANSPORTE AÉREO DE CARGA'},{Valor:'55',Texto:'TRANSPORTE AÉREO DE PASSAGEIROS'}];
var ValoresTipo2 = [{Valor:'73',Texto:'OPERADORA'},{Valor:'OUTROS',Texto:'OUTROS'}];

$(document).ready(function(){
	$('select[name=TP_DEVEDOR]').change(function(e) {
        ocultaElemento('.Comum, #NM_PARC_LISTA, #NM_PARC_BOX, #ID_SETOR',true);
		
		if ($(this).val()=='I') { // Se selecionar "Exportador"
			if (ID_IMPORTADOR>0) {
				exibeElemento('#NM_PARC_BOX',false,true);
				exibeElemento('.Comum',false,true);
				exibeElemento('#ID_SETOR',false,true);
				exibeElemento('#TP_ESTRUTURA',false,true);
			
				iniciaNM_PARC(CamposNM_PARC);
				
				carregaSetores('#ID_SETOR',false,true);
				
				carregaCamposParc(ID_IMPORTADOR,CamposNM_PARC,'dadosparceiro');
				
			} else {
				alert('Um importador não foi informado.');
				$(this).val(0);
			}
		} else if ($(this).val()=='O') {
			exibeElemento('#NM_PARC_LISTA');
			exibeElemento('.Comum');
			
			carregaNomes('#NM_PARC_LISTA','D','NOVO');
			
			iniciaNM_PARC(CamposNM_PARC);
		}
		fixH();
    });
	$('select[name=TP_SETOR]').change(function(e) { // Ao selecionar um "Tipo"
		if ($('select[name=TP_DEVEDOR]').val()=='O') {
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
		}
	});
	$('form[name=formDevedor]').bind('submit',function(e) {
		if (validaFormulario($(this))) {
			emExecucao = false;
			return true;
		} else {
			return false;
		}
	});
});