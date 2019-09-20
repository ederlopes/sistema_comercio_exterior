// Funções jQuery genéricas
// Versão: 2.1

$(document).ready(function(){
	if (navigator.userAgent.indexOf('MSIE 7.0')>0) {
		$('.ClearFix, .Campo').each(function(index, element) {
			$(this).append('<br class="Clear" />');
		});
		$('div#IndexSite div.Conteudo div.Passos div.Esquerda ul li a.On').css('width','125px');
		$('div.Form01 form div.Campo input').css('width','68%');
		$('div.Form01 form div.Campo select').css('width','71%');
		
		$('div.Form01 form div.Campo input.DDD').css('width','12%');
		$('div.Form01 form div.Campo input.Tel').css('width','51%');
		$('div#Login div.Form01 form div.Campo input').css('width','67%');
	}
});