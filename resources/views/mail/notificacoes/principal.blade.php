
<div style="background:#F5F5F5; font-size:13px; font-family:\'Trebuchet MS\', Arial, Helvetica, sans-serif; padding:10px">
	<div style="margin-bottom:10px;">
		<img src="{{asset('imagens/sce_logo_100.png')}}" alt="SCE MPME" width="130px" />
	</div>
	<h3>Sistema de Notificação do MPME/ABGF.</h3>

	<br><br>
	Prezado(a) <b><?=$dados_usuario->NM_USUARIO;?></b>, sua empresa está recebendo uma notificação da <b>OPERAÇÃO: <?=formatar_codigo($dados['id_oper']);?></b><br><br>
    <?php
    if ( array_key_exists("id_mpme_proposta", $dados) )
    {
    if ($dados['id_mpme_proposta'] != "")
    {
    ?>
	e <b> PROPOSTA: <?=formatar_codigo($dados['id_mpme_proposta']);?></b>
    <?php
    }
    }
    ?>
	<br><br>
    	ASSUNTO: <b><?=$dados['msg'];?></b>
	<br>
	<?php
    if ( array_key_exists("msg_ext", $dados) )
	{
		echo $dados['msg_ext'];
	}
	 ?>
	<br>
	<p>Quaisquer dúvidas, favor entrar em contato com a área responsável por MPME.</p>
	<br>
	<br>

	Atenciosamente,
	<p>
		Gerência Executiva de Operações de Garantias de Exportação de MPME</br>
		Agencia Brasileira Gestora de Fundos Garantidores e Garantias S.A.</br>
		Matriz - Setor Comercial Norte, Quadra 02, Bloco A, nº 190, 10º andar, sala 1002. Edifício Corporate Financial Center, Brasília-DF.</br>
		Filial - Rua da Quitanda, nº 86 – 2º andar – Edifício Sul América – Centro – Rio de Janeiro-RJ </br>
		CEP: 20091-005</br>
		Telefone: (21) 2510-5000
	</p>
	<p style="line-height:20px;font-size:11px;margin-bottom:0">Este é um e-mail automático, por favor não o responda.<br /> @php date("Y"); @endphp &copy; SCE MPME.</p>
</div>