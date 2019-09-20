<div style="background:#F5F5F5; font-size:13px; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; padding:10px">
	<h2 style="margin:0 0 10px 0;"><img src="{{asset('images/sce_logo_100.png')}}" alt="SCE MPME" /></h2>
	<div style="background:#FFF;border:1px solid #005071; padding:15px">
        Prezado(a) <b><?=$usuario->NM_USUARIO;?></b>,<br /><br />
       	Seu limite de crédito foi aprovado;<br />

		<p>Atente-se aos seguintes documentos:</p>

		<p>

			<a href="{{Route('notificacoes.arquivo.download', ['ID_MPME_ARQUIVO' => $id_arquivo_reg])}}" class="btn btn-primary">Regras e Condições</a> |
			<a href="{{Route('notificacoes.arquivo.download', ['ID_MPME_ARQUIVO' => $operacao->ID_MPME_ARQUIVO_COND_GERAIS])}}" class="btn btn-primary">Condições Gerais</a> |
			<a href="{{Route('notificacoes.arquivo.download', ['ID_MPME_ARQUIVO' => $operacao->ID_MPME_ARQUIVO_COND_PARTICULARES])}}" class="btn btn-primary">Condições Particulares</a> |
			<a href="{{Route('notificacoes.arquivo.download', ['ID_MPME_ARQUIVO' => $operacao->ID_MPME_ARQUIVO_COND_ESPECIAIS])}}" class="btn btn-primary">Condições Especiais</a>

		</p>


	</div>
	<p style="line-height:20px;font-size:11px;margin-bottom:0">Este é um e-mail automático, por favor não o responda.<br /> @php date("Y"); @endphp &copy; SCE MPME.</p>
</div>
