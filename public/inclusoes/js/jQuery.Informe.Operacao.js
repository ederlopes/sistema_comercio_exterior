// Funções jQuery para o formulário "Operação"
// Versão: 2.6

emAcaoM = false;
emAcaoC = false;

$(document).ready(function(){
	$('input.MERC').blur(function(e) {
		calculaTotal('input.MERC','#VL_MERC_TOT','input[name=VL_MERC_TOT]');
	});
	$('input.PACTE_LOGISTICO').blur(function(e) {
		calculaTotal('input.PACTE_LOGISTICO','#VL_PACTE_LOGISTICO_TOT','input[name=VL_PACTE_LOGISTICO_TOT]');
	});
	$('input.OUTR').blur(function(e) {
		calculaTotal('input.OUTR','#VL_OUTR_TOT','input[name=VL_OUTR_TOT]');
	});
	
	$('input.BRA').blur(function(e) {
		calculaTotal('input.BRA','#VL_BRA_TOT','input[name=VL_BRA_TOT]');
	});
	$('input.EST').blur(function(e) {
		calculaTotal('input.EST','#VL_EST_TOT','input[name=VL_EST_TOT]');
	});
	$('input.LOC').blur(function(e) {
		calculaTotal('input.LOC','#VL_LOC_TOT','input[name=VL_LOC_TOT]');
	});
	
	$('input.BRA.SERVT').blur(function(e) {
		calculaTotal('input.BRA.SERVT','#VL_BRA_TOT_SERV','input[name=VL_BRA_TOT_SERV]');
	});
	$('input.EST.SERVT').blur(function(e) {
		calculaTotal('input.EST.SERVT','#VL_EST_TOT_SERV','input[name=VL_EST_TOT_SERV]');
	});
	$('input.LOC.SERVT').blur(function(e) {
		calculaTotal('input.LOC.SERVT','#VL_LOC_TOT_SERV','input[name=VL_LOC_TOT_SERV]');
	});
	
	$('#Preco input').blur(function(e) {
		calculaTotal('input.TOT_SERV','#VL_TOT_SERV','input[name=VL_TOT_SERV]',false);
	});
	$('#Preco input').blur(function(e) {
		calculaTotal('input.TOT','#VL_TOTAL','input[name=VL_TOTAL]',false);
	});
	
	$('#DiscMercadorias a.Incluir').click(function(e) {
		if (emAcaoM==true) {
			alert('Por favor, termine com uma ação antes de prosseguir com outra.');
		} else {
			ocultaElemento('#DiscMercadorias tr.Incluir');
			$('#DiscMercadorias tr.Incluir').before('<tr class="Novo"><td class="Ultima" colspan="5" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="36%"><select name="NM_MERC" title="Selecione"></select></td><td width="20%"><input type="text" name="DE_NCM" /></td><td width="20%"><input type="text" name="VL_UNIT" class="Real" /></td><td width="15%"><input type="text" name="QT_MERC" /></td><td width="9%" rowspan="2" class="Opcoes"><a href="javascript:void(0);" class="Botao Botao01 Opcao Salvar"><img src="imagens/Icone04.png" /></a><a href="javascript:void(0);" class="Botao Botao01 Opcao Cancelar"><img src="imagens/Icone05.png" /></a></td></tr><tr><td width="100%" colspan="4" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="10%">Modelo:</td><td width="28%"><div id="ID_MODELO_PRODUTO_NOVO" class="Toggle" rel="NO_MODELO_NOVO"><select name="ID_MODELO_PRODUTO" title="Selecione" disabled="disabled"></select></div><div id="NO_MODELO_NOVO" class="Toggle None" rel="ID_MODELO_PRODUTO_NOVO"><input type="text" name="NO_MODELO_NOVO" /></div></td><td width="18%">Fabricante:</td><td width="44%"><input type="text" name="DE_FABRIC" /></td></tr></table></td></tr></table></td></tr>');
			iniciaMascara('Real');
			carregaProdutos('select[name=NM_MERC]');
			iniciaToggle();
			fixH();
			emAcaoM=true;
		}
	});
	$('#DiscMercadorias').on('change','select[name=NM_MERC]',function() {
		if ($(this).val() > 0) {
			$('select[name=ID_MODELO_PRODUTO]').prop('disabled',false);
			carregaModelosProduto('select[name=ID_MODELO_PRODUTO]','NOVO',$(this).val());
		} else {
			$('select[name=ID_MODELO_PRODUTO]').html('').prop('disabled',true);
		}
	});
	$('#DiscMercadorias').on('click','a.Salvar',function() {
		MSGErro='';
		PrimeiroErro='';
		
		var ID_PRODUTO = $('#DiscMercadorias tr.Novo select[name=NM_MERC]').val();
		var NM_MERC = $('#DiscMercadorias tr.Novo select[name=NM_MERC] option:selected').text();
		var DE_NCM = $('#DiscMercadorias tr.Novo input[name=DE_NCM]').val();
		var VL_UNIT = $('#DiscMercadorias tr.Novo input[name=VL_UNIT]').val();
		var QT_MERC = $('#DiscMercadorias tr.Novo input[name=QT_MERC]').val();
		var DE_FABRIC = $('#DiscMercadorias tr.Novo input[name=DE_FABRIC]').val();
		var ID_MODELO_PRODUTO = $('#DiscMercadorias tr.Novo select[name=ID_MODELO_PRODUTO]').val();
		var NO_MODELO = $('#DiscMercadorias tr.Novo select[name=ID_MODELO_PRODUTO] option:selected').text();
		var NO_MODELO_NOVO = $('#DiscMercadorias tr.Novo input[name=NO_MODELO_NOVO]').val();
		
		if (ID_PRODUTO == '0') {
			MSGErro='Preencha o campo "Mercadoria" \n';
			PrimeiroErro = 'NM_MERC';
		}
		if (DE_NCM == '') {
			MSGErro=MSGErro+'Preencha o campo "NCM" \n';
			PrimeiroErro = PrimeiroErro?PrimeiroErro:'DE_NCM';
		}
		if (VL_UNIT == '') {
			MSGErro=MSGErro+'Preencha o campo "Vl. Unit. ($)" \n';
			PrimeiroErro = PrimeiroErro?PrimeiroErro:'VL_UNIT';
		}
		if (QT_MERC == '') {
			MSGErro=MSGErro+'Preencha o campo "Qtde." \n';
			PrimeiroErro = PrimeiroErro?PrimeiroErro:'QT_MERC';
		}
		if (DE_FABRIC == '') {
			MSGErro=MSGErro+'Preencha o campo "Fabricante" \n';
			PrimeiroErro = PrimeiroErro?PrimeiroErro:'DE_FABRIC';
		}
		if (NO_MODELO_NOVO) {
			if (NO_MODELO_NOVO == '') {
				MSGErro=MSGErro+'Preencha o campo "Modelo" \n';
				PrimeiroErro = PrimeiroErro?PrimeiroErro:'NO_MODELO_NOVO';
			}
		} else {
			if (ID_MODELO_PRODUTO == 0) {
				MSGErro=MSGErro+'Selecione um "Modelo" \n';
				PrimeiroErro = PrimeiroErro?PrimeiroErro:'ID_MODELO_PRODUTO';
			}
		}
		if (MSGErro) {
			alert(MSGErro);
			$('#DiscMercadorias input[name='+PrimeiroErro+']').focus();
		} else {
			// O ID_MERC_VAL está setado no botão salvar
			// O atributo "rel" só é inserido no botão salvar, quando a ação é "alterar"
			ID_MERC_VAL = $(this).attr('rel');
			
			$.ajax({
				type: 'post',
				url: '?p=ajax&a=mercadoria',
				data: 'ID_INFORME='+ID_INFORME+'&ID_PRODUTO='+ID_PRODUTO+'&NM_MERC='+NM_MERC+'&DE_NCM='+DE_NCM+'&VL_UNIT='+VL_UNIT+'&QT_MERC='+QT_MERC+'&DE_FABRIC='+DE_FABRIC+(ID_MODELO_PRODUTO>0?'&ID_MODELO_PRODUTO='+ID_MODELO_PRODUTO:'')+(NO_MODELO_NOVO?'&NO_MODELO='+NO_MODELO_NOVO:'')+(ID_MERC_VAL>0?'&ID_MERC='+ID_MERC_VAL+'&ACAO=A':'&ACAO=N'),
				beforeSend: function(){
				},
				success: function(data){
					if (data.indexOf('ERRO')>=0) {
						switch (data) {
							case 'ERRO_MSG1':
								alert('Algo na mercadoria não foi informado corretamente, por favor, tente novamente.');
								break;
							case 'ERRO_MSG2':
								alert('Erro desconhecido, tente novamente mais tarde.');
								break;
						}
					} else {
						// Verifica se a ação é para salvar um novo ou alterar uma mercadoria
						if (ID_MERC_VAL>0) {
							$('#DiscMercadorias tr#'+ID_MERC_VAL+' input[name=ID_PRODUTO]').val(ID_PRODUTO);
							$('#DiscMercadorias tr#'+ID_MERC_VAL+' td span#NM_MERC').html(NM_MERC);
							$('#DiscMercadorias tr#'+ID_MERC_VAL+' td#DE_NCM').html(DE_NCM);
							$('#DiscMercadorias tr#'+ID_MERC_VAL+' td#VL_UNIT').html(VL_UNIT);
							$('#DiscMercadorias tr#'+ID_MERC_VAL+' td#QT_MERC').html(QT_MERC);
							$('#DiscMercadorias tr#'+ID_MERC_VAL+' td#DE_FABRIC').html(DE_FABRIC);
							$('#DiscMercadorias tr#'+ID_MERC_VAL+' td span#ID_MODELO_PRODUTO').html((NO_MODELO_NOVO?NO_MODELO_NOVO:NO_MODELO));
							$('#DiscMercadorias tr#'+ID_MERC_VAL+' input[name=ID_MODELO_PRODUTO]').val((NO_MODELO_NOVO?NO_MODELO_NOVO:ID_MODELO_PRODUTO));
							
							exibeElemento('#DiscMercadorias tr#'+ID_MERC_VAL);
						} else {
							// O ID_MERC será retornado pelo AJAX
							var ID_MERC = data;
							
							$('#DiscMercadorias tr.Incluir').before('<tr class="Itens" id="'+ID_MERC+'"><td colspan="5" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="36%"><span id="NM_MERC">'+NM_MERC+'</span><input type="hidden" name="ID_PRODUTO" value="'+ID_PRODUTO+'" /></td><td width="20%" id="DE_NCM">'+DE_NCM+'</td><td width="20%" id="VL_UNIT">'+VL_UNIT+'</td><td width="15%" id="QT_MERC">'+QT_MERC+'</td><td width="9%" rowspan="2" class="Opcoes"><a href="javascript:void(0);" class="Botao Botao01 Opcao Editar" rel="'+ID_MERC+'"><img src="imagens/Icone06.png" /></a><a href="javascript:void(0);" class="Botao Botao01 Opcao Excluir" rel="'+ID_MERC+'"><img src="imagens/Icone05.png" /></a></td></tr><tr><td width="100%" colspan="4" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="10%">Modelo:</td><td width="28%"><span id="ID_MODELO_PRODUTO">'+(NO_MODELO_NOVO?NO_MODELO_NOVO:NO_MODELO)+'</span><input type="hidden" name="ID_MODELO_PRODUTO" value="'+(NO_MODELO_NOVO?NO_MODELO_NOVO:ID_MODELO_PRODUTO)+'" /></td><td width="18%">Fabricante:</td><td id="DE_FABRIC" width="44%">'+DE_FABRIC+'</td></tr></table></td></tr></table></td></tr>');
						}
						$('#DiscMercadorias tr.Novo').remove();
						exibeElemento('#DiscMercadorias tr.Incluir');
						fixH();
						emAcaoM=false;
						coloreTabela();
					}
				},
				error: function(erro){
				}
			});
		}
	});
	$('#DiscMercadorias').on('click','a.Editar',function() {
		if (emAcaoM==true) {
			alert('Por favor, termine com uma ação antes de prosseguir com outra.');
		} else {
			var ID_MERC = $(this).attr('rel');
			var ID_PRODUTO = $('#DiscMercadorias tr#'+ID_MERC+' input[name=ID_PRODUTO]').val();
			var DE_NCM = $('#DiscMercadorias tr#'+ID_MERC+' td#DE_NCM').html();
			var VL_UNIT = $('#DiscMercadorias tr#'+ID_MERC+' td#VL_UNIT').html().replace(/[^0-9\,]+/g,"").replace(',','.');
			var QT_MERC = $('#DiscMercadorias tr#'+ID_MERC+' td#QT_MERC').html();
			var DE_FABRIC = $('#DiscMercadorias tr#'+ID_MERC+' td#DE_FABRIC').html();
			var ID_MODELO_PRODUTO = $('#DiscMercadorias tr#'+ID_MERC+' input[name=ID_MODELO_PRODUTO]').val();
			
			$('#DiscMercadorias tr#'+ID_MERC).before('<tr class="Novo"><td class="Ultima" colspan="5" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="36%"><select name="NM_MERC" title="Selecione"></select></td><td width="20%"><input type="text" name="DE_NCM" value="'+DE_NCM+'" /></td><td width="20%"><input type="text" name="VL_UNIT" value="'+VL_UNIT+'" class="Real" /></td><td width="15%"><input type="text" name="QT_MERC" value="'+QT_MERC+'" /></td><td width="9%" rowspan="2" class="Opcoes"><a href="javascript:void(0);" class="Botao Botao01 Opcao Salvar" rel="'+ID_MERC+'"><img src="imagens/Icone04.png" /></a><a href="javascript:void(0);" class="Botao Botao01 Opcao Cancelar" rel="'+ID_MERC+'"><img src="imagens/Icone05.png" /></a></td></tr><tr><td width="100%" colspan="4" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="10%">Modelo:</td><td width="28%"><div id="ID_MODELO_PRODUTO_ALTERAR" class="Toggle" rel="NO_MODELO_ALTERAR"><select name="ID_MODELO_PRODUTO" title="Selecione"></select></div><div id="NO_MODELO_ALTERAR" class="Toggle None" rel="ID_MODELO_PRODUTO"><input type="text" name="NO_MODELO_NOVO"></div></td><td width="18%">Fabricante:</td><td width="44%"><input type="text" name="DE_FABRIC" value="'+DE_FABRIC+'" /></td></tr></table></tr></table></td></tr>');
			ocultaElemento('#DiscMercadorias tr#'+ID_MERC);
			iniciaMascara('Real');
			carregaProdutos('select[name=NM_MERC]',ID_PRODUTO);
			carregaModelosProduto('select[name=ID_MODELO_PRODUTO]','NOVO',ID_PRODUTO,ID_MODELO_PRODUTO);
			iniciaToggle();
			fixH();
			emAcaoM=true;
		}
	});
	$('#DiscMercadorias').on('click','a.Cancelar',function() {
		var ID_MERC = $(this).attr('rel');
		if (ID_MERC>0) {
			exibeElemento('#DiscMercadorias tr#'+ID_MERC);
		}
		$('#DiscMercadorias tr.Novo').remove();
		exibeElemento('#DiscMercadorias tr.Incluir');
		fixH();
		emAcaoM=false;
	});
	$('#DiscMercadorias').on('click','a.Excluir',function() {
		if (confirm('Tem certeza que deseja excluir a mercadoria selecionada?')) {
			// O ID_MERC está setado no botão excluir
			var ID_MERC = $(this).attr('rel');
			$.ajax({
				type: 'post',
				url: '?p=ajax&a=mercadoria',
				data: 'ID_MERC='+ID_MERC+'&ACAO=E',
				beforeSend: function(){
				},
				success: function(data){
					if (data.indexOf('ERRO')>=0) {
						switch (data) {
							case 'ERRO_MSG1':
								alert('Algo na mercadoria não foi informado corretamente, por favor, tente novamente.');
								break;
							case 'ERRO_MSG2':
								alert('Erro desconhecido, tente novamente mais tarde.');
								break;
						}
					} else {
						$('#DiscMercadorias tr#'+ID_MERC).remove();
						fixH();
						coloreTabela();
					}
				},
				error: function(erro){
				}
			});
		}
	});
	
	// Aba Concorrentes
	$('#DiscConcorrentes a.Incluir').click(function(e) {
		if (emAcaoC==true) {
			alert('Por favor, termine com uma ação antes de prosseguir com outra.');
		} else {
			ocultaElemento('#DiscConcorrentes tr.Incluir');
			$('#DiscConcorrentes tr.Incluir').before('<tr class="Novo"><td class="Ultima" colspan="2" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="82%"><input type="text" name="NM_CONCORR" /></td><td width="18%" class="Opcoes2"><a href="javascript:void(0);" class="Botao Botao01 Opcao Salvar"><img src="imagens/Icone04.png" /></a><a href="javascript:void(0);" class="Botao Botao01 Opcao Cancelar"><img src="imagens/Icone05.png" /></a></td></tr></table></td></tr>');
			fixH();
			emAcaoC=true;
		}
	});
	$('#DiscConcorrentes').on('click','a.Salvar',function() {
		MSGErro='';
		PrimeiroErro='';
		var NM_CONCORR = $('#DiscConcorrentes tr.Novo input[name=NM_CONCORR]').val();
		
		if (NM_CONCORR == '') {
			MSGErro='Preencha o campo "Concorrente" \n';
			PrimeiroErro = 'NM_CONCORR';
		}
		if (MSGErro) {
			alert(MSGErro);
			$('#DiscConcorrentes input[name='+PrimeiroErro+']').focus();
		} else {
			// O ID_CONCORR está setado no botão salvar
			// O atributo "rel" só é inserido no botão salvar, quando a ação é "alterar"
			var ID_CONCORR_VAL = $(this).attr('rel');
			
			$.ajax({
				type: 'post',
				url: '?p=ajax&a=concorrente',
				data: 'ID_INFORME='+ID_INFORME+'&NM_CONCORR='+NM_CONCORR+(ID_CONCORR_VAL>0?'&ID_CONCORR='+ID_CONCORR_VAL+'&ACAO=A':'&ACAO=N'),
				beforeSend: function(){
				},
				success: function(data){
					if (data.indexOf('ERRO')>=0) {
						switch (data) {
							case 'ERRO_MSG1':
								alert('Algo no concorrente não foi informado corretamente, por favor, tente novamente.');
								break;
							case 'ERRO_MSG2':
								alert('Erro desconhecido, tente novamente mais tarde.');
								break;
						}
					} else {
						// Verifica se a ação é para salvar um novo ou alterar um concorrent
						// O atributo "rel" só é inserido no botão salvar, quando a ação é "alterar"
						if (ID_CONCORR_VAL>0) {
							$('#DiscConcorrentes tr#'+ID_CONCORR_VAL+' td#NM_CONCORR').html(NM_CONCORR);
							
							exibeElemento('#DiscConcorrentes tr#'+ID_CONCORR_VAL);
						} else {
							// O ID_CONCORR será retornado pelo AJAX
							var ID_CONCORR = data;
			
							$('#DiscConcorrentes tr.Incluir').before('<tr class="Itens" id="'+ID_CONCORR+'"><td colspan="2" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="82%" id="NM_CONCORR">'+NM_CONCORR+'</td><td width="18%" class="Opcoes2"><a href="javascript:void(0);" class="Botao Botao01 Opcao Editar" rel="'+ID_CONCORR+'"><img src="imagens/Icone06.png" /></a><a href="javascript:void(0);" class="Botao Botao01 Opcao Excluir" rel="'+ID_CONCORR+'"><img src="imagens/Icone05.png" /></a></td></tr></table></td></tr>');
						}
						$('#DiscConcorrentes tr.Novo').remove();
						exibeElemento('#DiscConcorrentes tr.Incluir');
						fixH();
						emAcaoC=false;
						coloreTabela();
					}
				},
				error: function(erro){
				}
			});
		}
	});
	$('#DiscConcorrentes').on('click','a.Editar',function() {
		if (emAcaoC==true) {
			alert('Por favor, termine com uma ação antes de prosseguir com outra.');
		} else {
			var ID_CONCORR = $(this).attr('rel');
			var NM_CONCORR = $('#DiscConcorrentes tr#'+ID_CONCORR+' td#NM_CONCORR').html();
		
			$('#DiscConcorrentes tr#'+ID_CONCORR).before('<tr class="Novo"><td class="Ultima" colspan="2" style="padding:0;"><table cellpadding="0" cellspacing="0" class="SubTabela01" width="100%"><tr><td width="82%"><input type="text" name="NM_CONCORR" value="'+NM_CONCORR+'" /></td><td width="18%" class="Opcoes2"><a href="javascript:void(0);" class="Botao Botao01 Opcao Salvar" rel="'+ID_CONCORR+'"><img src="imagens/Icone04.png" /></a><a href="javascript:void(0);" class="Botao Botao01 Opcao Cancelar" rel="'+ID_CONCORR+'"><img src="imagens/Icone05.png" /></a></td></tr></table></td></tr>');
			ocultaElemento('#DiscConcorrentes tr#'+ID_CONCORR);
			fixH();
			emAcaoC=true;
		}
	});
	$('#DiscConcorrentes').on('click','a.Cancelar',function() {
		var ID_CONCORR = $(this).attr('rel');
		if (ID_CONCORR>0) {
			exibeElemento('#DiscConcorrentes tr#'+ID_CONCORR);
		}
		$('#DiscConcorrentes tr.Novo').remove();
		exibeElemento('#DiscConcorrentes tr.Incluir');
		fixH();
		emAcaoC=false;
	});
	$('#DiscConcorrentes').on('click','a.Excluir',function() {
		if (confirm('Tem certeza que deseja excluir o concorrente selecionado?')) {
			// O ID_CONCORR está setado no botão excluir
			var ID_CONCORR = $(this).attr('rel');
			$.ajax({
				type: 'post',
				url: '?p=ajax&a=concorrente',
				data: 'ID_CONCORR='+ID_CONCORR+'&ACAO=E',
				beforeSend: function(){
				},
				success: function(data){
					if (data.indexOf('ERRO')>=0) {
						switch (data) {
							case 'ERRO_MSG1':
								alert('Algo no concorrente não foi informado corretamente, por favor, tente novamente.');
								break;
							case 'ERRO_MSG2':
								alert('Erro desconhecido, tente novamente mais tarde.');
								break;
						}
					} else {
						$('#DiscConcorrentes tr#'+ID_CONCORR).remove();
						fixH();
						coloreTabela();
					}
				},
				error: function(erro){
				}
			});
		}
	});

	$('form[name=formOperacao]').bind('submit',function(e) {
		if (validaFormulario($(this))) {
			emExecucao = false;
			return true;
		} else {
			return false;
		}
	});
});

// Calcula o total de um determinado tipo de "preço"
// Elemento = Elemento que serão buscado os valores
// TextoTotal = Objeto que irá receber o total em forma de texto
// InputTotal = Objeto que irá receber o total em forma de número
// UseGet = Usar a função "autoNumeric('get')"
function calculaTotal(Elemento,TextoTotal,InputTotal,UseGet) {
	Total = 0;
	UseGet = (typeof UseGet == 'undefined')? UseGet = true : UseGet=UseGet;
	$(Elemento).each(function(e) {
		Valor = UseGet ? $(this).autoNumeric('get') : $(this).val();
		if (Valor>0) {
			Total = Total+parseFloat(Valor);
		}
	});
	$(TextoTotal).autoNumeric('set',Total);
	$(InputTotal).val(Total);
}