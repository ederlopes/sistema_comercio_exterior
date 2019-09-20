// Funções jQuery genéricas
// Versão: 2.6

emExecucao = false; // Esta variável será utilizada caso algum input em qualquer formulário for alterado

$(document).ready(function(){

	// Inicia mascaras de campos
	$('.CNPJ').mask('99.999.999/9999-99',{placeholder:' '});
	$('.Data').mask('99/99/9999',{placeholder:' '});
	$('.CEPMask').mask('99999-999',{placeholder:' '});
	$('.TelefoneMask').maskbrphone();
	iniciaMascara('Real');
	iniciaMascara('Porc');
	iniciaMascara('PorcP');
	iniciaMascara('Porc5');
	iniciaMascara('Meses');
	
	// Clica no botão "OK" ao teclar "Enter"
	$('input[name=NM_RESPONSAVEL]').keydown(function(e){
		var Tecla=(window.event)?event.keyCode:e.which;
		if (Tecla == 13) {
			$('.SalvarInforme').trigger('click');
		}
	});
	
	// Salva o informe quando o usuário clicar em "OK"
	$('.SalvarInforme').click(function(e) {
		if ($('input[name=NM_RESPONSAVEL]').val()=='') {
			alert('Preencha o responsável pelo preenchimento.');
		} else {
			$.ajax({
				type: 'post',
				url: '?p=ajax&a=informe',
				data: 'NM_RESPONSAVEL='+$("input[name=NM_RESPONSAVEL]").val()+(ID_INFORME>0?'&ID_INFORME='+ID_INFORME:''),
				beforeSend: function(){
				},
				success: function(data){
					if (data.indexOf('ERRO')>=0) {
						switch (data) {
							case 'ERRO_MSG1':
								alert('O campo Responsável não foi informado corretamente, por favor, tente novamente.');
								break;
							case 'ERRO_MSG2':
								alert('Erro desconhecido, tente novamente mais tarde.');
								break;
						}
					} else {
						if (ID_INFORME>0) {
							$('h1 span.MensagemTitulo').html('Formulário atualizado com sucesso!').fadeIn(0).delay(1000).fadeOut(500);
						} else {
							$('h1 span.MensagemTitulo').html('Formulário criado com sucesso!').fadeIn(0).delay(1000).fadeOut(500);
							ID_INFORME=data;
							$('ul.MenuAba li a').each(function(index, element) {
								$(this).attr('href',$(this).attr('href')+'&id='+data);
							});
							$('form[name=formSolicitante]').prop('action', $('form[name=formSolicitante]').prop('action')+ID_INFORME);
						}	
					}
				},
				error: function(erro){
				}
			});
		}
	});
	
	// Apaga as informações de um parceiro
	$('.Restaurar').click(function(e) {
		TIPO = $(this).attr('rel');
		if (confirm('Tem certeza que deseja restaurar o participante selecionado?')) {
			$.ajax({
				type: 'post',
				url: '?p=ajax&a=restaurar',
				data: 'ID_INFORME='+ID_INFORME+'&TIPO='+TIPO,
				beforeSend: function(){
				},
				success: function(data){
					if (data.indexOf('ERRO')>=0) {
						switch (data) {
							case 'ERRO_MSG1':
								alert('O informe não foi informado corretamente, por favor, tente novamente.');
								break;
							case 'ERRO_MSG2':
								alert('Erro desconhecido, tente novamente mais tarde.');
								break;
						}
					} else {
						emExecucao=false;
						window.location.reload(true);
					}
				},
				error: function(erro){
				}
			});
		}
	});

	// Evita que o usuário realize alguma operação antes de preencher o responsável e salvar o informe
	$('div.Passos').click(function(e) {
		if (!ID_INFORME) {
			e.preventDefault();
			alert('Preencha o responsável pelo preenchimento.');
		}
	});
	
	// Evita que o usuário saia de algum formulário antes de salva-lo
	$('div.Passos form input[type=text], div.Passos form input[type=password], div.Passos form textarea').keydown(function(e) {
		emExecucao=true;
	});
	$('div.Passos form input[type=checkbox], div.Passos form input[type=radio]').click(function(e) {
		emExecucao=true;
	});
	$('div.Passos form select').change(function(e) {
		emExecucao=true;
	});
	
	fixH();
	iniciaToggle();
	
	// Atualiza a página dos formulários ao clicar em "Cancelar"
	$('div.Botoes input.Cancelar').click(function(e) {
		window.location.reload(true);
	});

	// Atualiza a página dos formulários ao clicar em "Cancelar"
	$('div.Botoes input.Voltar').click(function(e) {
		window.location=$(this).attr('ref');
	});
	$('div#IndexBotoes input.Voltar').click(function(e) {
		Ref = $(this).attr('ref');
		if (Ref) {
			window.location=$(this).attr('ref');
		} else {
	        window.history.go(-1);
		}
    });
	
	/*
	$('div.Botoes input.Salvar').click(function(e) {
		e.preventDefault();
		alert('Os formulários estão em teste, portanto, não é possível salvar os dados.');
	});
	*/
	
	// Atualiza o risco do país selecionado
	$('select[name=ID_PAIS]').change(function(e) {
		CD_RISCO_VAL = $(this).find('option:selected').attr('risco');
		if (parseInt(CD_RISCO_VAL) > 0) {
			$('span#CD_RISCO').html(CD_RISCO_VAL);
		} else {
			$('span#CD_RISCO').html(0);
		}
    });
	
	// Envia o informe para os analistas
	$('form[name=formInforme]').bind('submit',function(e) {
		if (confirm('Deseja enviar o seu formulário aos analistas da ABGF ?')) {
			emExecucao = false;
			return true;
		} else {
			return false;
		}
	});
	
	coloreTabela();
	removeErro();
});

// Evento que captura quando o usuário tenta sair da página
window.onbeforeunload = function() {
	var Msg = "Você não salvou suas alterações...";
	if(emExecucao) {
		return Msg;
	}
}

// Colore alternadamente as linhas das tabelas que tiverem a classe "Tabela01"
function coloreTabela() {
	$('.Tabela01').each(function(index, element) {
		$('.Tabela01').find('tr:not(table.SubTabela01 tr):even').css('background-color','#FAFAFA');
		$('.Tabela01').find('tr:not(table.SubTabela01 tr):odd').css('background-color','#F3F3F3'); 
    });
}

// Fixa o tamanho de altura de todas as divs que possuírem a classe "FixH" em relação ao elemento "div.Passos"
function fixH() {
	$('.FixH').each(function(index, element) {
		Tam1 = $(this).height();
		Tam2 = $('div.Passos').height();
		if (Tam1<Tam2) {
	        $(this).height(Tam2);
		}
    });
}

// Função para alternar entre campo SELECT e INPUT, quando o valor selecionado no SELECT for "NOVO" ou o INPUT estiver vazio
// Para utilizar essa função, crie um elemento que servirá como "container" para o SELECT ou INPUT.
// Adicione a classe "Toggle" nos dois "containers" que irão se alterar. Eles deverão ser identificados por um atributo "id" (identificação do campo), e 
// por um atributo "rel" (identificação do campo com qual irá se relacionar)
// Exemplo:
//		<div class="Toggle" id="CAMPO_1" rel="CAMPO_2"><select><option value="NOVO">[NOVO]</option></select></div>
//		<div class="Toggle" id="CAMPO_2" rel="CAMPO_1"><input /></div>
// Obs.: Quando um campo for ativado, o outro será automaticamente desativado e voltará para seu valor padrão
function iniciaToggle(Obj) {
	if (Obj) {
		Id = Obj.closest('.Toggle,.Campo').attr('id');
		Rel = Obj.closest('.Toggle,.Campo').attr('rel');
		ocultaElemento('#'+Id,true);
		exibeElemento('#'+Rel,'#'+Rel+' input');
	} else {
		$('.Toggle').find('input, select').each(function(e) {
			if ($(this).is('select')) {
				$(this).change(function(e) {
					if ($(this).val()=='NOVO') {
						Id = $(this).closest('.Toggle').attr('id');
						Rel = $(this).closest('.Toggle').attr('rel');
						ocultaElemento('#'+Id,true);
						exibeElemento('#'+Rel,'#'+Rel+' input');
					}
				});
			}
			if ($(this).is('input')) {
				$(this).blur(function(e) {
					if ($(this).val()=='') {
						Id = $(this).closest('.Toggle').attr('id');
						Rel = $(this).closest('.Toggle').attr('rel');
						ocultaElemento('#'+Id);
						exibeElemento('#'+Rel);
					}
				});
			}
		});
	}
}

// Oculta campos e desabilita os inputs que neles estiverem
// Elemento = Elemento que será ocultado
// Limpa = Se deverá retornar o valor padrão do input (Opcional)
function ocultaElemento(Elemento,Limpa) {
	$(Elemento).hide().find('input, select').each(function(e) {
		$(this).attr('disabled',true);
		if (Limpa) {
			limpaElemento(Elemento);
		}
	});	
}

// Limpa o input que for determinado
// Elemento = Input que terá seu valor apagado
function limpaElemento(Elemento) {
	$(Elemento).find('input, select').each(function(e) {
		if ($(this).is('input') || $(this).is('textarea')) {
			if ($(this).is(':radio') || $(this).is(':checkbox')) {
				$(this).each(function(index,element) {
					$(this).prop('checked', false);
				});
			} else {
				$(this).val('');
			}
		} else if ($(this).is('select')) {
			$(this).val(0).trigger('change');
		}
	});
}

// Exibe campos e habilita os inputs que nele estiverem
// Elemento = Campo que será exibido
// Focus = Determina um input que receberá o foco (Opcional)
function exibeElemento(Elemento,Focus,Disabled) {
	$(Elemento).show().find('input, select').each(function(e) {
		if (!Disabled) {
			$(this).attr('disabled',false);
		}
		if (Focus) {
			$(Focus).focus();
		}
	});	
}

// Altera as opções de um select
// Elemento = Campo que será alterado
// Opcoes = Opções que estarão disponíveis (Opcional)
// ValorSel = Valor que deverá ser selecionado
function alteraValores(Elemento,Opcoes,ValorSel) {
	Selected = '';
	if ($(Elemento).is('select')) {
			if ($(Elemento).attr('title')) {
				Options = '<option value="0">'+$(Elemento).attr('title')+'</option>';
			} else {
				Options = '';
			}
			if (Opcoes) {
				if (Opcoes.length>0) {
					$.each(Opcoes,function(Key,Opcao) {
						if (ValorSel) {
							if (ValorSel == Opcao.Valor || ValorSel == Opcao.Texto) {
								Selected = 'selected="selected"';
							} else {
								Selected = '';
							}
						}
						Options = Options+'<option value="'+Opcao.Valor+'" '+Selected+'>'+Opcao.Texto+'</option>';
					});
				}
			}
			$(Elemento).html(Options);
	} else {
		$(Elemento).find('select').each(function(e) {
			if ($(this).attr('title')) {
				Options = '<option value="0">'+$(this).attr('title')+'</option>';
			} else {
				Options = '';
			}
			if (Opcoes) {
				if (Opcoes.length>0) {
					$.each(Opcoes,function(Key,Opcao) {
						if (ValorSel) {
							if (ValorSel == Opcao.Valor || ValorSel == Opcao.Texto) {
								Selected = 'selected="selected"';
							} else {
								Selected = '';
							}
						}
						Options = Options+'<option value="'+Opcao.Valor+'" '+Selected+'>'+Opcao.Texto+'</option>';
					});
				}
			}
			$(this).html(Options);
		});
	}
}

// Inicia as máscaras utilizadas nos formularios
// Tipo = Tipo de máscara
function iniciaMascara(Tipo) {
	switch (Tipo) {
		case 'Real':
			$('.Real').autoNumeric('init', {aSep:'.',aDec:',',pSign:'s',lZero:'deny'});
			break;
		case 'Porc':
			$('.Porc').autoNumeric('init', {aSep:'',aDec:',',pSign:'s',lZero:'deny',vMax:'100'});
			break;
		case 'PorcP':
			$('.PorcP').autoNumeric('init', {aSep:'',aDec:',',aSign:'%',pSign:'s',lZero:'deny',vMax:'100'});
			break;
		case 'Porc5':
			$('.Porc5').autoNumeric('init', {aSep:'',aDec:',',pSign:'s',lZero:'deny',vMax:'100',mDec:'5'});
			break;
		case 'Meses':
			$('.Meses').autoNumeric('init', {mDec:'0',pSign:'s',lZero:'deny',vMax:'999'});
			break;
	}
	return true;
}

// Carrega os Parceiros via AJAX
// Campo = Campo que receberá os valores do AJAX
// Tipo = Tipo de solicitante
// Novo = Se definido como 'NOVO', o campo receberá uma opção de NOVO (Opcional)
// NM_PARC = Nome do parceiro para ser selecionado por padrão (Opcional)
function carregaNomes(Campo,Tipo,Novo,NM_PARC,Extras) {
	$.ajax({
		type: 'post',
		url: '?p=ajax&a=nomeparceiro',
		data: 'TP_SOLICITANTE='+Tipo+'&NOVO='+Novo,
		beforeSend: function(){
			ativaCarregando('Carregando nomes');
		},
		success: function(data){
			if (data instanceof Object) {
				alteraValores(Campo,data,NM_PARC);
			} else {
				if (data.indexOf('ERRO')>=0) {
					switch (data) {
						case 'ERRO_MSG1':
							alert('O solicitante não foi informado corretamente, tente novamente mais tarde.');
							break;
						case 'ERRO_MSG2':
							alert('Erro desconhecido, tente novamente mais tarde.');
							break;
					}
				}
			}
			ativaCarregando();
		},
		error: function(erro){
		}
	});
}

// Carrega os dados de um Parceiro via AJAX
// ID = ID da consulta
// Campos = Campos que os dados serão inseridos
// Arquivo = Arquivo que será buscado os dados
function carregaCamposParc(ID,Campos,Arquivo) {
	if (ID == '0' || ID == 'NOVO' || ID == null) {
		if (Campos!='') {
			$.each(Campos,function(Key,Campo) {
				if ($('input[name='+Campo+']').length > 0) {
					if ($('input[name='+Campo+']').is(':radio')) {
						$('input[name='+Campo+']').each(function(index,element) {
							$(this).prop('checked', false);
						});
					} else {
						$('input[name='+Campo+']').val('');
					}
				} else if ($('select[name='+Campo+']').length > 0) {
					$('select[name='+Campo+']').val(0).trigger('change');
				}
			});
		}
	} else {
		$.ajax({
			type: 'post',
			url: '?p=ajax&a='+Arquivo,
			data: 'ID='+ID+'&Campos='+encodeURIComponent(Campos),
			beforeSend: function(){
				ativaCarregando('Carregando dados do parceiro');
			},
			success: function(data){
				if (data instanceof Object) {
					if (data!='') {
						$.each(data,function(Key,Valor) {
							if (Valor) {
								if ($('input[name='+Key+']').length > 0) {
									if ($('input[name='+Key+']').is(':radio')) {
										$('input[name='+Key+']').each(function(index,element) {
											if ($(this).is('[value='+Valor+']')) {
												$(this).prop('checked', true);
											}
										});
									} else {
										$('input[name='+Key+']').val(Valor).trigger('keyup');
									}
									removeErro('input[name='+Key+']');
								} else if ($('select[name='+Key+']').length > 0) {
									$('select[name='+Key+']').val(Valor).trigger('change');
									removeErro('select[name='+Key+']');
								}
							}
						});
					}
				} else {
					switch (data) {
						case 'ERRO_MSG1':
							alert('O ID não foi informado corretamente, tente novamente mais tarde.');
							break;
						case 'ERRO_MSG2':
							alert('Erro desconhecido, tente novamente mais tarde.');
							break;
					}
				}
				ativaCarregando();
			},
			error: function(jqXHR,textStatus){
			}
		});
	}
}


// Carrega os Setoresvia AJAX
// Campo = Campo que receberá os valores do AJAX
// ID_SETOR = Nome da agência para ser selecionada por padrão (Opcional)
function carregaSetores(Campo,ID_SETOR,Disabled) {
	var Res = '';
	$.ajax({
		type: 'post',
		url: '?p=ajax&a=setores',
		data: '',
		beforeSend: function(){
			ativaCarregando('Carregando setores');
		},
		success: function(data){
			if (data instanceof Object) {
				if (!Disabled) {
					$(Campo).find('select').attr('disabled',false);
				}
				alteraValores(Campo,data,ID_SETOR);
			} else {
				if (data.indexOf('ERRO')>=0) {
					switch (data) {
						case 'ERRO_MSG2':
							alert('Erro desconhecido, tente novamente mais tarde.');
							break;
					}
				}
			}
			ativaCarregando();
		},
		error: function(erro){
		}
	});
}

// Carrega as Agencias do BB via AJAX
// Campo = Campo que receberá os valores do AJAX
// Novo = Se definido como 'NOVO', o campo receberá uma opção de NOVO (Opcional)
// NM_AGENCIA = Nome da agência para ser selecionada por padrão (Opcional)
function carregaAgenciasBB(Campo,Novo,NM_AGENCIA) {
	var Res = '';
	$.ajax({
		type: 'post',
		url: '?p=ajax&a=agenciabb',
		data: 'NOVO='+Novo,
		beforeSend: function(){
			ativaCarregando('Carregando agências');
		},
		success: function(data){
			if (data instanceof Object) {
				$(Campo).find('select').attr('disabled',false);
				alteraValores(Campo,data,NM_AGENCIA);
			} else {
				if (data.indexOf('ERRO')>=0) {
					switch (data) {
						case 'ERRO_MSG2':
							alert('Erro desconhecido, tente novamente mais tarde.');
							break;
					}
				}
			}
			ativaCarregando();
		},
		error: function(erro){
		}
	});
}

// Carrega os dados de um Parceiro via AJAX
// ID = ID da consulta
// Campos = Campos que os dados serão inseridos
function carregaCamposBB(ID,Campos) {
	if (ID == '0' || ID == 'NOVO' || ID == null) {
		if (Campos!='') {
			$.each(Campos,function(Key,Campo) {
				if ($('input[name='+Campo+']').length > 0) {
					$('input[name='+Campo+']').val('');
				} else if ($('select[name='+Campo+']').length > 0) {
					$('select[name='+Campo+']').val(0).trigger('change');
				}
			});
		}
	} else {
		$.ajax({
			type: 'post',
			url: '?p=ajax&a=dadosbb',
			data: 'ID='+ID+'&Campos='+encodeURIComponent(Campos),
			beforeSend: function(){
				ativaCarregando('Carregando dados da agência');
			},
			success: function(data){
				if (data instanceof Object) {
					if (data!='') {
						$.each(data,function(Key,Campo) {
							if (Campo) {
								if ($('input[name='+Key+']').length > 0) {
									if ($('input[name='+Key+']').is(':radio')) {
										$('input[name='+Key+']').each(function(index,element) {
											if ($(this).is('[value='+Campo+']')) {
												$(this).prop('checked', true);
											}
										});
									} else {
										$('input[name='+Key+']').val(Campo);
									}
									removeErro('input[name='+Key+']');
								} else if ($('select[name='+Key+']').length > 0) {
									$('select[name='+Key+']').val(Campo).trigger('change');
									removeErro('select[name='+Key+']');
								}
							}
						});
					}
				} else {
					switch (data) {
						case 'ERRO_MSG1':
							alert('O ID não foi informado corretamente, tente novamente mais tarde.');
							break;
						case 'ERRO_MSG2':
							alert('Erro desconhecido, tente novamente mais tarde.');
							break;
					}
				}
				ativaCarregando();
			},
			error: function(jqXHR,textStatus){
			}
		});
	}
}

// Carrega os dados do BNDES via AJAX
// Campos = Campos que os dados serão inseridos
function carregaCamposBNDES(Campos) {
	$.ajax({
		type: 'post',
		url: '?p=ajax&a=dadosbndes',
		data: 'Campos='+encodeURIComponent(Campos),
		beforeSend: function(){
			ativaCarregando('Carregando dados do BNDES');
		},
		success: function(data){
			if (data instanceof Object) {
				if (data!='') {
					$.each(data,function(Key,Campo) {
						if (Campo) {
							if ($('input[name='+Key+']').length > 0) {
								if ($('input[name='+Key+']').is(':radio')) {
									$('input[name='+Key+']').each(function(index,element) {
										if ($(this).is('[value='+Campo+']')) {
											$(this).prop('checked', true);
										}
									});
								} else {
									$('input[name='+Key+']').val(Campo);
								}
								removeErro('input[name='+Key+']');
							} else if ($('select[name='+Key+']').length > 0) {
								$('select[name='+Key+']').val(Campo).trigger('change');
								removeErro('select[name='+Key+']');
							}
						}
					});
				}
			} else {
				switch (data) {
					case 'ERRO_MSG1':
						alert('Erro desconhecido, tente novamente mais tarde.');
						break;
				}
			}
			ativaCarregando();
		},
		error: function(jqXHR,textStatus){
		}
	});
}

// Carrega os Produtos via AJAX
// Campo = Campo que receberá os valores do AJAX
// ID_PRODUTO = ID do Produto para ser selecionado por padrão (Opcional)
function carregaProdutos(Campo,ID_PRODUTO) {
	$.ajax({
		type: 'post',
		url: '?p=ajax&a=produtos',
		data: '',
		beforeSend: function(){
			ativaCarregando('Carregando mercadorias');
		},
		success: function(data){
			if (data instanceof Object) {
				alteraValores(Campo,data,ID_PRODUTO);
			} else {
				if (data.indexOf('ERRO')>=0) {
					switch (data) {
						case 'ERRO_MSG1':
							alert('Erro desconhecido, tente novamente mais tarde.');
							break;
					}
				}
			}
			ativaCarregando();
		},
		error: function(erro){
		}
	});
}

// Carrega os Modelos dos Produtos via AJAX
// Campo = Campo que receberá os valores do AJAX
// Novo = Se definido como 'NOVO', o campo receberá uma opção de NOVO (Opcional)
// ID_PRODUTO = ID do Produto para ser selecionado por padrão (Opcional)
function carregaModelosProduto(Campo,Novo,ID_PRODUTO,ID_MODELO_PRODUTO) {
	$.ajax({
		type: 'post',
		url: '?p=ajax&a=modelosproduto',
		data: 'ID_PRODUTO='+ID_PRODUTO+'&NOVO='+Novo,
		beforeSend: function(){
			ativaCarregando('Carregando modelos');
		},
		success: function(data){
			if (data instanceof Object) {
				alteraValores(Campo,data,ID_MODELO_PRODUTO);
			} else {
				if (data.indexOf('ERRO')>=0) {
					switch (data) {
						case 'ERRO_MSG1':
							alert('A mercadoria não foi informada corretamente, tente novamente mais tarde.');
							break;
						case 'ERRO_MSG2':
							$(Campo).html('<option value="N/A">N/A</option>').prop('disabled',true);
							break;
					}
				}
			}
			ativaCarregando();
		},
		error: function(erro){
		}
	});
}

// Carrega as Linhas de Financiamento via AJAX
// Campo = Campo que receberá os valores do AJAX
// ID_LINHA_FINANCIAMENTO = ID da Linha para ser selecionado por padrão (Opcional)
function carregaLinhas(Campo,Novo,TP_BANCO_FINANC,ID_LINHA_FINANCIAMENTO) {
	$.ajax({
		type: 'post',
		url: '?p=ajax&a=linhasfinanciamento',
		data: 'TP_BANCO_FINANC='+TP_BANCO_FINANC+'&NOVO='+Novo,
		beforeSend: function(){
			ativaCarregando('Carregando linhas de financiamento');
		},
		success: function(data){
			if (data instanceof Object) {
				alteraValores(Campo,data,ID_LINHA_FINANCIAMENTO);
			} else {
				if (data.indexOf('ERRO')>=0) {
					switch (data) {
						case 'ERRO_MSG1':
							alert('O Banco Financiador não foi informado corretamente, tente novamente mais tarde.');
							break;
						case 'ERRO_MSG2':
							alert('Erro desconhecido, tente novamente mais tarde.');
							break;
					}
				}
			}
			ativaCarregando();
		},
		error: function(erro){
		}
	});
}

// Alterna a div de "Carregando"
// Texto = Texto a ser exibido (Opcional)
function ativaCarregando(Texto) {
	fixH();
	if ($('div.Direita div.Carregando')) {
		if (Texto) {
			$('div.Direita div.Carregando span b').html(Texto);
		}
		if ($('div.Direita div.Carregando').css('display') == 'none') {
			$('div.Direita div.Carregando').css('display','table');
		} else {
			$('div.Direita div.Carregando').css('display','none');
		}
		return true;
	} else {
		return false;
	}
}

function validaFormulario(Form) {
	Res=true;
	$(Form).find('input.Requerido[disabled!=disabled], select.Requerido[disabled!=disabled], textarea.Requerido[disabled!=disabled]').each(function(Index, Element) {
		if (($(this).hasClass('Real') || $(this).hasClass('Porc') || $(this).hasClass('PorcP') || $(this).hasClass('Porc5') || $(this).hasClass('Meses')) && $(this).autoNumeric('get')==0) {
			$(this).addClass('Erro');
			Name = $(this).attr('name').replace('[]','');
			if ($('span.Erro[rel='+Name+']').length==0) {
				$(this).after('<span class="Erro" rel="'+Name+'">'+$(this).attr('msgerro')+'</span>');
				$('span.Erro[rel='+Name+']').slideDown(function() {
					fixH();
				});
			}
			Res=false;
		} else if ($(this).val()==0 || !$(this).val()) {
			$(this).addClass('Erro');
			Name = $(this).attr('name').replace('[]','');
			if ($('span.Erro[rel='+Name+']').length==0) {
				$(this).after('<span class="Erro" rel="'+Name+'">'+$(this).attr('msgerro')+'</span>');
				$('span.Erro[rel='+Name+']').slideDown(function() {
					fixH();
				});
			}
			Res=false;
		} else {
			if ($(this).hasClass('CNPJ')) {
				if (!validaCNPJ($(this).val())) {
					$(this).addClass('Erro');
					Name = $(this).attr('name').replace('[]','');
					if ($('span.Erro[rel='+Name+']').length==0) {
						$(this).after('<span class="Erro" rel="'+Name+'">CNPJ inválido</span>');
						$('span.Erro[rel='+Name+']').slideDown(function() {
							fixH();
						});
					}
					Res=false;
				}
			}
			if ($(this).hasClass('Email')) {
				if (!validaEmail($(this).val())) {
					$(this).addClass('Erro');
					Name = $(this).attr('name').replace('[]','');
					if ($('span.Erro[rel='+Name+']').length==0) {
						$(this).after('<span class="Erro" rel="'+Name+'">E-mail inválido</span>');
						$('span.Erro[rel='+Name+']').slideDown(function() {
							fixH();
						});
					}
					Res=false;
				}
			}
		}
	});
	$(Form).find('div.Requerido, table.Requerido').each(function(Index, Element) {
		if (!$(this).hasClass('Disabled')) {
			if ($(this).hasClass('Radios')) {
				if ($(this).find(':radio:checked').length==0) {
					$(this).addClass('Erro');
					if ($('span.Erro[rel='+$(this).attr('rel')+']').length==0) {
						$(this).after('<span class="Erro" rel="'+$(this).attr('rel')+'">'+$(this).attr('msgerro')+'</span>');
						$('span.Erro[rel='+$(this).attr('rel')+']').slideDown(function() {
							fixH();
						});
					}
					Res=false;
				}
			} else if ($(this).hasClass('Valores')) {
				if ($('input[name='+$(this).attr('valor')+']').val()=='0') {
					$(this).addClass('Erro');
					if ($('span.Erro[rel='+$(this).attr('rel')+']').length==0) {
						$(this).after('<span class="Erro Erro2" rel="'+$(this).attr('rel')+'">'+$(this).attr('msgerro')+'</span>');
						$('span.Erro[rel='+$(this).attr('rel')+']').slideDown(function() {
							fixH();
						});
					}
					Res=false;
				}
			} else if ($(this).hasClass('Campos')) {
				ErroCampos = false;
				$(this).find('input').each(function(index, element) {
                    if ($(this).val()=='') {
						ErroCampos = true;
					}
                });
				if (ErroCampos) {
					$(this).addClass('Erro');
					if ($('span.Erro[rel='+$(this).attr('rel')+']').length==0) {
						$(this).append('<span class="Erro Erro2" rel="'+$(this).attr('rel')+'">'+$(this).attr('msgerro')+'</span>');
						$('span.Erro[rel='+$(this).attr('rel')+']').slideDown(function() {
							fixH();
						});
					}
					Res=false;
				}
			}
		}
	});
	removeErro();
	return Res;
}

// Inicia a função que remove a classe de erro ao selecionar um input com erro
// Elemento = Elemento que será removido o erro (Opcional)
// Todos = Remover os erros de toda a página (Opcional)
function removeErro(Elemento,Todos) {
	if (Elemento) {
		if (Todos) {
			$(Elemento).find('.Erro').each(function(index, element) {
                if ($('span.Erro[rel='+$(this).attr('rel')+']').length > 0) {
					$('span.Erro[rel='+$(this).attr('rel')+']').remove();
				}
				$(this).removeClass('Erro');
            });
		} else {
			$(Elemento).removeClass('Erro');
			$('span.Erro[rel='+$(Elemento).attr('name').replace('[]','')+']').slideUp(function() {
				$(this).remove();
				fixH();
			});
		}
	} else {
		$('input.Erro, select.Erro, textarea.Erro, div.Erro input').focus(function(e) {
			$(this).removeClass('Erro');
			$('span.Erro[rel='+$(this).attr('name').replace('[]','')+']').slideUp(function() {
				$(this).remove();
				fixH();
			});
		});
		$('table.Erro input, div.Erro input').focus(function(e) {
            Rel = $(this).closest('table, div').attr('rel');
			$('table[rel='+Rel+'], div[rel='+Rel+']').removeClass('Erro');
			$('span.Erro[rel='+Rel+']').slideUp(function() {
				$(this).remove();
				fixH();
			});
        });
	}
}

//
function iniciaNM_PARC(Campos) {
	if (Campos) {
		$('select[name=NM_PARC_LISTA]').change(function(e) {
			if ($(this).val() == '0' || $(this).val() == 'NOVO') {
				$('input[name=NM_PARC]').val('');
			} else {
				$('input[name=NM_PARC]').val($(this).find('option:selected').text());
			}
			if (!$(this).hasClass('SemCampos')) {
				carregaCamposParc($(this).val(),Campos,'dadosparceiro');
			}
		});
		$('input[name=NM_PARC_BOX]').keyup(function(e) {
			if ($(this).val() == '') {
				$('select[name=NM_PARC_LISTA]').val(0).trigger('change');
			}
			$('input[name=NM_PARC]').val($(this).val());
		});
		$('input[name=NM_PARC]').keyup(function(e) {
			$('input[name=NM_PARC_BOX]').val($(this).val());
		});
	} else {
		$('select[name=NM_PARC_LISTA]').change(function(e) {
			if ($(this).val() == '0' || $(this).val() == 'NOVO') {
				$('input[name=NM_PARC]').val('');
			} else {
				$('input[name=NM_PARC]').val($(this).find('option:selected').text());
			}
		});
		$('input[name=NM_PARC_BOX]').keyup(function(e) {
			$('input[name=NM_PARC]').val($(this).val());
		});
		$('input[name=NM_PARC]').keyup(function(e) {
			$('input[name=NM_PARC_BOX]').val($(this).val());
		});
	}
}

function iniciaNM_AGENCIA(Campos) {
	$('select[name=NM_AGENCIA]').change(function(e) {
		carregaCamposBB($(this).val(),Campos);
	});
	$('input[name=NM_AGENCIA]').keyup(function(e) {
		if ($(this).val() == '') {
			$('select[name=NM_AGENCIA]').val(0).trigger('change');
		}
	});
}

// Função para validar CNPJ
function validaCNPJ(CNPJ) {
	CNPJ = CNPJ.replace(/[^\d]+/g,'');

	if(CNPJ == '')
		return false;

	if (CNPJ.length != 14)
		return false;

	if (CNPJ == "00000000000000" || CNPJ == "11111111111111" || CNPJ == "22222222222222" || CNPJ == "33333333333333" || CNPJ == "44444444444444" || CNPJ == "55555555555555" || CNPJ == "66666666666666" || CNPJ == "77777777777777" || CNPJ == "88888888888888" || CNPJ == "99999999999999")
		return false;

	Tamanho = CNPJ.length - 2
	Numeros = CNPJ.substring(0,Tamanho);
	Digitos = CNPJ.substring(Tamanho);
	Soma = 0;
	Pos = Tamanho - 7;
	for (i = Tamanho; i >= 1; i--) {
		Soma += Numeros.charAt(Tamanho - i) * Pos--;
		if (Pos < 2)
			Pos = 9;
	}
	Resultado = Soma % 11 < 2 ? 0 : 11 - Soma % 11;
	if (Resultado != Digitos.charAt(0))
		return false;
	
	Tamanho = Tamanho + 1;
	Numeros = CNPJ.substring(0,Tamanho);
	Soma = 0;
	Pos = Tamanho - 7;
	for (i = Tamanho; i >= 1; i--) {
		Soma += Numeros.charAt(Tamanho - i) * Pos--;
		if (Pos < 2)
			Pos = 9;
	}
	Resultado = Soma % 11 < 2 ? 0 : 11 - Soma % 11;
	if (Resultado != Digitos.charAt(1))
		return false;
	
	return true;
}

function validaEmail(Email) {
	var RegEmail = new RegExp(/^[a-zA-Z0-9._%+-]+@(?:[a-zA-Z0-9-]+\.)+[a-zA-Z]{2,4}$/gi);
	return Email.match(RegEmail);
}
function validaData(Data) {
	var RegData = new RegExp(/((0[1-9]|[12][0-9]|3[01])\/(0[13578]|1[02])\/[12][0-9]{3})|((0[1-9]|[12][0-9]|30)\/(0[469]|11)\/[12][0-9]{3})|((0[1-9]|1[0-9]|2[0-8])\/02\/[12][0-9]([02468][1235679]|[13579][01345789]))|((0[1-9]|[12][0-9])\/02\/[12][0-9]([02468][048]|[13579][26]))/gi);
	return Data.match(RegData);
}
function converteData(Data) {
	DataArray = Data.split("/");
	ObjData = new Date(DataArray[2],(DataArray[1]-1),DataArray[0]);
	return ObjData;
}
function resizeIframe(Obj) {
	Obj.style.height = Obj.contentWindow.document.body.scrollHeight + 'px';
}