// Funções jQuery para a tela de recuperar formulários
// Versão: 2.6

$(document).ready(function(){

	$('div#Resultado table tr td:not(.Opcoes)').click(function(e) {
		ID_INFORME = $(this).closest('tr').attr('id');
        ID_GRUPO_OPERACAO = $(this).closest('tr').attr('grupo');
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
		window.location=PASTA+'/?p=informe&id='+ID_INFORME;
    });
	
	$('div#Resultado table tr td a.Excluir').click(function(e) {
        ID_INFORME = $(this).attr('rel');
		NM_IMPORTADOR = $('div#Resultado table tr#'+ID_INFORME+' td.NM_IMPORTADOR').html();
		NM_PAIS = $('div#Resultado table tr#'+ID_INFORME+' td.NM_PAIS').html();
		if (parseInt(ID_INFORME)>0) {
			if (confirm('Formulário Nº.: '+ID_INFORME+' \rImportador: '+NM_IMPORTADOR+' \rPaís: '+NM_PAIS+' \r \rTem certeza que deseja excluir este formulário?')) {
				$.ajax({
					type: 'post',
					url: '?p=ajax&a=excluiinforme',
					data: 'ID_INFORME='+ID_INFORME,
					async: true,
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
								case 'ERRO_MSG3':
									alert('Você não pode excluir um informe que não está em sua lista.');
									break;
							}
						} else {
							alert('Operação excluída com sucesso.');
							window.location.reload(true);
						}
					},
					error: function(erro){
					}
				});
			}
		}
    });
	$('div#Resultado table tr th a.ExcluirTodos').click(function(e) {
        IDS = $(this).attr('rel');
		if (IDS.length>0) {
			if (confirm('Tem certeza que deseja excluir todos os formulários sem número de operação desta lista?')) {
				$.ajax({
					type: 'post',
					url: '?p=ajax&a=excluiinformes',
					data: 'IDS='+IDS+'&TIPO=ARRAY',
					async: true,
					beforeSend: function(){
					},
					success: function(data){
						console.log(data);
						if (data.indexOf('ERRO')>=0) {
							switch (data) {
								case 'ERRO_MSG1':
									alert('O campo Responsável não foi informado corretamente, por favor, tente novamente.');
									break;
								case 'ERRO_MSG2':
									alert('Erro desconhecido, tente novamente mais tarde.');
									break;
								case 'ERRO_MSG3':
									alert('Você não pode excluir um informe que não está em sua lista.');
									break;
							}
						} else {
							alert('Operações excluídas com sucesso.');
							window.location.reload(true);
						}
					},
					error: function(erro){
					}
				});
			}
		}
    });
});