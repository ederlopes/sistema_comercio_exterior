// Funções jQuery para o formulário "Financiamento"
// Versão: 2.9

var ValoresItens = [{Valor:'New Aircraft',Texto:'New Aircraft'},{Valor:'Used Aircraft',Texto:'Used Aircraft'},{Valor:'Spare Engines (with New Aircraft)',Texto:'Spare Engines (with New Aircraft)'},{Valor:'Spare Engines (without New Aircraft)',Texto:'Spare Engines (without New Aircraft)'},{Valor:'Spare Parts (with New Aircraft)',Texto:'Spare Parts (with New Aircraft)'},{Valor:'Spare Parts (without New Aircraft)',Texto:'Spare Parts (without New Aircraft)'},{Valor:'Conversion',Texto:'Conversion'},{Valor:'Major Modification, Refurbishment',Texto:'Major Modification, Refurbishment'},{Valor:'Maintenance and Service',Texto:'Maintenance and Service'},{Valor:'Engine Kits',Texto:'Engine Kits'}];
var ValoresEstruturas = [{Valor:'Asset Backend',Texto:'Asset Backend'},{Valor:'Non Asset Backed (Non Sovereign)',Texto:'Non Asset Backed (Non Sovereign)'},{Valor:'Non Asset Backed (Sovereign)',Texto:'Non Asset Backed (Sovereign)'}];
var ValoresEsquemas = [{Valor:'Lease Scheme',Texto:'Lease Scheme'},{Valor:'Equal Principal',Texto:'Equal Principal'},{Valor:'Non Standard',Texto:'Non Standard'}];

$(document).ready(function(){
	$("a.Incluir").click(function(e) {
		ItemSelecionado = '';
		$.fancybox({
			href: '#CenarioForm',
			fitToView:false,
			width:600,
			topRatio:0,
			autoSize:false,
			autoHeight:true,
			closeClick:false,
			openEffect:'none',
			closeEffect:'none',
			parent: "form:first",
			'afterLoad': function () {
				$('tr.Itens input[name=ITEM_SUPORTE]').each(function(index, element) {
                    ItemSelecionado = $(this).val();
                });
				alteraValores('div#CenarioForm select[name=ITEM_SUPORTE]',ValoresItens,ItemSelecionado);
				alteraValores('div#CenarioForm select[name=ESTRUTURA_COLATERAL]',ValoresEstruturas);
				alteraValores('div#CenarioForm select[name=ESQUEMA_REEMBOLSO]',ValoresEsquemas);
				
				if (ItemSelecionado.length>0) {
					$('div#CenarioForm select[name=ITEM_SUPORTE]').before('<select name="ITEM_SUPORTE_FALSO" disabled="disabled"><option value="0">'+ItemSelecionado+'</option></select>');
					$('div#CenarioForm select[name=ITEM_SUPORTE]').hide();
				}
			},
			'afterClose': function () {
				$('div#CenarioForm select').val(0);
				$('div#CenarioForm select[name=ITEM_SUPORTE_FALSO]').remove();
				$('div#CenarioForm input[type=radio]').prop('checked',false);
				$('div#CenarioForm input[type=text]').each(function(index, element) {
					if ($(this).attr('name') != 'CONTRATO_EXPORTACAO_MONTANTE') {
						$(this).autoNumeric('destroy');
						$(this).val('');
					}
                });
				iniciaMascara('Real');
				iniciaMascara('Porc');
				iniciaMascara('Meses');
				removeErro('div#CenarioForm',true);
			}
		});
	});
	$('div#CenarioForm input.CancelarCenario').click(function(e) {
        $.fancybox.close();
    });
	$('div#CenarioForm input.Salvar').click(function(e) {
		if (validaFormulario($('div#CenarioForm'))) {
			if (validaRegras()) {
				var ITEM_SUPORTE = $('div#CenarioForm select[name=ITEM_SUPORTE]').val();
				var ITEM_SUPORTE_TEXT = $('div#CenarioForm select[name=ITEM_SUPORTE] option:selected').text();
				var ESTRUTURA_COLATERAL = $('div#CenarioForm select[name=ESTRUTURA_COLATERAL]').val();
				var CONTRATO_EXPORTACAO_MONTANTE = $('div#CenarioForm input[name=CONTRATO_EXPORTACAO_MONTANTE]').autoNumeric('get');
				var PC_TAXA_ANTECIPADO = $('div#CenarioForm input[name=PC_TAXA_ANTECIPADO]').autoNumeric('get');
				var ESQUEMA_REEMBOLSO = $('div#CenarioForm select[name=ESQUEMA_REEMBOLSO]').val();
				var PRAZO_REEMBOLSO = $('div#CenarioForm input[name=PRAZO_REEMBOLSO]').autoNumeric('get');
				var PERIODICIDADE_PRAZO_REEMBOLSO = $('div#CenarioForm select[name=PERIODICIDADE_PRAZO_REEMBOLSO]').val();
				var INICIO_PAGTO = $('div#CenarioForm select[name=INICIO_PAGTO]').val();
				var PREMIO_FINANCIADO = $('div#CenarioForm input[name=PREMIO_FINANCIADO]:checked').val();
				
				// O ID_CENARIO está setado no botão salvar
				// O atributo "rel" só é inserido no botão salvar, quando a ação é "alterar"
				var ID_CENARIO_VAL = parseInt($(this).attr('rel'));

				$.ajax({
					type: 'post',
					url: '?p=ajax&a=cenariofinanciamento',
					data: 'ID_INFORME='+ID_INFORME+'&ITEM_SUPORTE='+ITEM_SUPORTE+'&ESTRUTURA_COLATERAL='+ESTRUTURA_COLATERAL+'&CONTRATO_EXPORTACAO_MONTANTE='+CONTRATO_EXPORTACAO_MONTANTE+'&PC_TAXA_ANTECIPADO='+PC_TAXA_ANTECIPADO+'&ESQUEMA_REEMBOLSO='+ESQUEMA_REEMBOLSO+'&PRAZO_REEMBOLSO='+PRAZO_REEMBOLSO+'&PERIODICIDADE_PRAZO_REEMBOLSO='+PERIODICIDADE_PRAZO_REEMBOLSO+'&INICIO_PAGTO='+INICIO_PAGTO+'&PREMIO_FINANCIADO='+PREMIO_FINANCIADO+(ID_CENARIO_VAL>0?'&ID_CENARIO_PRECIFICACAO='+ID_CENARIO_VAL+'&ACAO=A':'&ACAO=N'),
					beforeSend: function(){
					},
					success: function(data){
						console.log(data);
						if (data.indexOf('ERRO')>=0) {
							switch (data) {
								case 'ERRO_MSG1':
									alert('Algo no cenário não foi informado corretamente, por favor, tente novamente.');
									break;
								case 'ERRO_MSG2':
									alert('Erro desconhecido, tente novamente mais tarde.');
									break;
							}
						} else {
							// Verifica se a ação é para salvar um novo ou alterar uma mercadoria
							if (ID_CENARIO_VAL>0) {
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' td#ITEM_SUPORTE').html(ITEM_SUPORTE_TEXT);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' td#CONTRATO_EXPORTACAO_MONTANTE').autoNumeric('set',CONTRATO_EXPORTACAO_MONTANTE);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=ITEM_SUPORTE]').val(ITEM_SUPORTE);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=ESTRUTURA_COLATERAL]').val(ESTRUTURA_COLATERAL);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=CONTRATO_EXPORTACAO_MONTANTE]').val(CONTRATO_EXPORTACAO_MONTANTE);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=PC_TAXA_ANTECIPADO]').val(PC_TAXA_ANTECIPADO);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=ESQUEMA_REEMBOLSO]').val(ESQUEMA_REEMBOLSO);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=PRAZO_REEMBOLSO]').val(PRAZO_REEMBOLSO);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=PERIODICIDADE_PRAZO_REEMBOLSO]').val(PERIODICIDADE_PRAZO_REEMBOLSO);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=INICIO_PAGTO]').val(INICIO_PAGTO);
								$('#DiscCenarios tr#'+ID_CENARIO_VAL+' input[name=PREMIO_FINANCIADO]').val(PREMIO_FINANCIADO);
							} else {
								// O ID_CENARIO será retornado pelo AJAX
								var ID_CENARIO = data;
								
								TOTAL_ITENS = $('#DiscCenarios tr.Itens').length;
								
								var Hiddens = '<input type="hidden" name="ITEM_SUPORTE" value="'+ITEM_SUPORTE+'" /><input type="hidden" name="ESTRUTURA_COLATERAL" value="'+ESTRUTURA_COLATERAL+'" /><input type="hidden" name="CONTRATO_EXPORTACAO_MONTANTE" value="'+CONTRATO_EXPORTACAO_MONTANTE+'" /><input type="hidden" name="PC_TAXA_ANTECIPADO" value="'+PC_TAXA_ANTECIPADO+'" /><input type="hidden" name="ESQUEMA_REEMBOLSO" value="'+ESQUEMA_REEMBOLSO+'" /><input type="hidden" name="PRAZO_REEMBOLSO" value="'+PRAZO_REEMBOLSO+'" /><input type="hidden" name="PERIODICIDADE_PRAZO_REEMBOLSO" value="'+PERIODICIDADE_PRAZO_REEMBOLSO+'" /><input type="hidden" name="INICIO_PAGTO" value="'+INICIO_PAGTO+'" /><input type="hidden" name="PREMIO_FINANCIADO" value="'+PREMIO_FINANCIADO+'" />';
								$('#DiscCenarios tr.Incluir').before('<tr class="Itens" id="'+ID_CENARIO+'"><td>'+(TOTAL_ITENS+1)+'</td><td id="ITEM_SUPORTE">'+ITEM_SUPORTE_TEXT+'</td><td id="CONTRATO_EXPORTACAO_MONTANTE" class="Real">'+CONTRATO_EXPORTACAO_MONTANTE+'</td><td class="Opcoes2"><a href="javascript:void(0);" class="Botao Botao01 Opcao Editar" rel="'+ID_CENARIO+'"><img src="imagens/Icone06.png" /></a><a href="javascript:void(0);" class="Botao Botao01 Opcao Excluir" rel="'+ID_CENARIO+'"><img src="imagens/Icone05.png" /></a>'+Hiddens+'</td></tr>');
							}
							emExecucao=false;
							$.fancybox.close();
							iniciaMascara('Real');
							fixH();
							coloreTabela();
						}
					},
					error: function(erro){
					}
				});
				
				return true;	
			} else {
				return false;
			}
		} else {
			validaRegras();
			return false;
		}
    });
	
	$('#DiscCenarios').on('click','a.Editar',function() {
		var ID_CENARIO = $(this).attr('rel');
		var ITEM_SUPORTE = $('#DiscCenarios tr#'+ID_CENARIO+' input[name=ITEM_SUPORTE]').val();
		var ESTRUTURA_COLATERAL = $('#DiscCenarios tr#'+ID_CENARIO+' input[name=ESTRUTURA_COLATERAL]').val();
		var CONTRATO_EXPORTACAO_MONTANTE = parseFloat($('#DiscCenarios tr#'+ID_CENARIO+' input[name=CONTRATO_EXPORTACAO_MONTANTE]').val());
		var PC_TAXA_ANTECIPADO = parseFloat($('#DiscCenarios tr#'+ID_CENARIO+' input[name=PC_TAXA_ANTECIPADO]').val());
		var ESQUEMA_REEMBOLSO = $('#DiscCenarios tr#'+ID_CENARIO+' input[name=ESQUEMA_REEMBOLSO]').val();
		var PRAZO_REEMBOLSO = parseInt($('#DiscCenarios tr#'+ID_CENARIO+' input[name=PRAZO_REEMBOLSO]').val());
		var PERIODICIDADE_PRAZO_REEMBOLSO = $('#DiscCenarios tr#'+ID_CENARIO+' input[name=PERIODICIDADE_PRAZO_REEMBOLSO]').val();
		var INICIO_PAGTO = $('#DiscCenarios tr#'+ID_CENARIO+' input[name=INICIO_PAGTO]').val();
		var PREMIO_FINANCIADO = $('#DiscCenarios tr#'+ID_CENARIO+' input[name=PREMIO_FINANCIADO]').val();

		$.fancybox({
			href: '#CenarioForm',
			fitToView:false,
			width:600,
			topRatio:0,
			autoSize:false,
			autoHeight:true,
			closeClick:false,
			openEffect:'none',
			closeEffect:'none',
			parent: "form:first",
			'afterLoad': function () {
				alteraValores('div#CenarioForm select[name=ITEM_SUPORTE]',ValoresItens,ITEM_SUPORTE);
				alteraValores('div#CenarioForm select[name=ESTRUTURA_COLATERAL]',ValoresEstruturas,ESTRUTURA_COLATERAL);
				$('div#CenarioForm input[name=CONTRATO_EXPORTACAO_MONTANTE]').autoNumeric('set',CONTRATO_EXPORTACAO_MONTANTE);
				$('div#CenarioForm input[name=PC_TAXA_ANTECIPADO]').autoNumeric('set',PC_TAXA_ANTECIPADO);				
				alteraValores('div#CenarioForm select[name=ESQUEMA_REEMBOLSO]',ValoresEsquemas,ESQUEMA_REEMBOLSO);
				$('div#CenarioForm input[name=PRAZO_REEMBOLSO]').autoNumeric('set',PRAZO_REEMBOLSO);
				$('div#CenarioForm select[name=PERIODICIDADE_PRAZO_REEMBOLSO]').val(PERIODICIDADE_PRAZO_REEMBOLSO);
				$('div#CenarioForm select[name=INICIO_PAGTO]').val(INICIO_PAGTO);
				$('div#CenarioForm input[name=PREMIO_FINANCIADO]').val([PREMIO_FINANCIADO]);
				$('div#CenarioForm input.Salvar').attr('rel',ID_CENARIO);
				
				if (ITEM_SUPORTE.length>0) {
					$('div#CenarioForm select[name=ITEM_SUPORTE]').before('<select name="ITEM_SUPORTE_FALSO" disabled="disabled"><option value="0">'+ITEM_SUPORTE+'</option></select>');
					$('div#CenarioForm select[name=ITEM_SUPORTE]').hide();
				}
			},
			'afterClose': function () {
				$('div#CenarioForm select').val(0);
				$('div#CenarioForm input[type=radio]').prop('checked',false);
				$('div#CenarioForm input[type=text]').each(function(index, element) {
					if ($(this).attr('name') != 'CONTRATO_EXPORTACAO_MONTANTE') {
						$(this).autoNumeric('destroy');
						$(this).val('');
					}
                });
				iniciaMascara('Real');
				iniciaMascara('Porc');
				iniciaMascara('Meses');
				removeErro('div#CenarioForm',true);
				$('div#CenarioForm input.Salvar').removeAttr('rel');
				
				$('div#CenarioForm select[name=ITEM_SUPORTE]').show();
				$('div#CenarioForm select[name=ITEM_SUPORTE_FALSO]').remove();
			}
		});
	});
	$('#DiscCenarios').on('click','a.Excluir',function() {
		if (confirm('Tem certeza que deseja excluir o cenário selecionado?')) {
			// O ID_CENARIO está setado no botão excluir
			var ID_CENARIO_VAL = $(this).attr('rel');
			$.ajax({
				type: 'post',
				url: '?p=ajax&a=cenariofinanciamento',
				data: 'ID_CENARIO_PRECIFICACAO='+ID_CENARIO_VAL+'&ACAO=E',
				beforeSend: function(){
				},
				success: function(data){
					if (data.indexOf('ERRO')>=0) {
						switch (data) {
							case 'ERRO_MSG1':
								alert('Algo no cenário não foi informado corretamente, por favor, tente novamente.');
								break;
							case 'ERRO_MSG2':
								alert('Erro desconhecido, tente novamente mais tarde.');
								break;
						}
					} else {
						$('#DiscCenarios tr#'+ID_CENARIO_VAL).remove();
						fixH();
						coloreTabela();
					}
				},
				error: function(erro){
				}
			});
		}
	});

	$('form[name=formFinanciamento]').bind('submit',function(e) {
		if ($('tr.Itens').length==0) {
			alert('Para prosseguir é preciso informar ao menos um cenário de financiamento');
			return false;
		} else {
			emExecucao = false;
			return true;
		}
	});
});
function validaRegras() {
	Res=true;
	var ITEM_SUPORTE = $('div#CenarioForm select[name=ITEM_SUPORTE]').val();
	var ESTRUTURA_COLATERAL = $('div#CenarioForm select[name=ESTRUTURA_COLATERAL]').val();
	var CONTRATO_EXPORTACAO_MONTANTE = $('div#CenarioForm input[name=CONTRATO_EXPORTACAO_MONTANTE]').autoNumeric('get');
	var PRAZO_REEMBOLSO = parseInt($('div#CenarioForm input[name=PRAZO_REEMBOLSO]').val());
	
	// Máximos
	CONTRATO_EXPORTACAO_MONTANTE_MAX = -1;
	PRAZO_REEMBOLSO_MAX = -1;
	
	switch (ITEM_SUPORTE) {
		case '1':
			if (ESTRUTURA_COLATERAL=='2' || ESTRUTURA_COLATERAL=='3') {
				if (TP_NAT==1) {
					CONTRATO_EXPORTACAO_MONTANTE_MAX = 15000000;
				}
				PRAZO_REEMBOLSO_MAX = 120;
			} else {
				PRAZO_REEMBOLSO_MAX = 144;
			}
			break;
		case '2':
			if (ESTRUTURA_COLATERAL=='1' || ESTRUTURA_COLATERAL=='3') {
				PRAZO_REEMBOLSO_MAX = 120;
			} else {
				PRAZO_REEMBOLSO_MAX = 102;
			}
			break;
		case '3': case '5':
			if (ESTRUTURA_COLATERAL=='2' || ESTRUTURA_COLATERAL=='3') {
				if (TP_NAT==1) {
					CONTRATO_EXPORTACAO_MONTANTE_MAX = 15000000;
				}
				PRAZO_REEMBOLSO_MAX = 120;
			} else {
				PRAZO_REEMBOLSO_MAX = 144;
			}
			break;
		case '4':
			if (CONTRATO_EXPORTACAO_MONTANTE<10000000) {
				PRAZO_REEMBOLSO_MAX = 96;
			} else {
				PRAZO_REEMBOLSO_MAX = 120;
			}
			break;
		case '6':
			if (CONTRATO_EXPORTACAO_MONTANTE<=5000000) {
				PRAZO_REEMBOLSO_MAX = 24;
			} else {
				PRAZO_REEMBOLSO_MAX = 60;
			}
			break;
		case '9':
			PRAZO_REEMBOLSO_MAX = 36;
			break;
		case '10':
			PRAZO_REEMBOLSO_MAX = 60;
			break;
		default:
			PRAZO_REEMBOLSO_MAX = 180;
			break;
	}
	if (PRAZO_REEMBOLSO_MAX>0 && PRAZO_REEMBOLSO>PRAZO_REEMBOLSO_MAX) {
		$('input[name=PRAZO_REEMBOLSO]').addClass('Erro');
		if ($('span.Erro[rel=PRAZO_REEMBOLSO]').length==0) {
			$('input[name=PRAZO_REEMBOLSO]').after('<span class="Erro" rel="PRAZO_REEMBOLSO">O prazo máximo do repagamento é de '+PRAZO_REEMBOLSO_MAX+' meses ('+(PRAZO_REEMBOLSO_MAX/12)+' anos)</span>');
			$('span.Erro[rel=PRAZO_REEMBOLSO]').slideDown(function() {
				fixH();
			});
		} else {
			$('span.Erro[rel=PRAZO_REEMBOLSO]').html('O prazo máximo do repagamento é de '+PRAZO_REEMBOLSO_MAX+' meses ('+(PRAZO_REEMBOLSO_MAX/12)+' anos)');
		}
		Res=false;
	}
	if (CONTRATO_EXPORTACAO_MONTANTE_MAX>0 && CONTRATO_EXPORTACAO_MONTANTE>CONTRATO_EXPORTACAO_MONTANTE_MAX) {
		$('input[name=CONTRATO_EXPORTACAO_MONTANTE]').addClass('Erro');
		if ($('span.Erro[rel=CONTRATO_EXPORTACAO_MONTANTE]').length==0) {
			$('input[name=CONTRATO_EXPORTACAO_MONTANTE]').after('<span class="Erro" rel="CONTRATO_EXPORTACAO_MONTANTE">O valor máximo do contrato comercial é de '+(CONTRATO_EXPORTACAO_MONTANTE_MAX/1000000)+' milhões</span>');
			$('span.Erro[rel=CONTRATO_EXPORTACAO_MONTANTE]').slideDown(function() {
				fixH();
			});
		} else {
			$('span.Erro[rel=PRAZO_REEMBOLSO]').html('O valor máximo do contrato comercial é de '+(CONTRATO_EXPORTACAO_MONTANTE_MAX/1000000)+' milhões');
		}
		Res=false;
	}
	removeErro();
	return Res;
}