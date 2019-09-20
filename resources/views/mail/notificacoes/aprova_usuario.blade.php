@component('mail::message')
# Liberação dos dados de acesso ao sistema de MPME/ABGF

<br><br>
Prezado Exportador,
<br><br>


Informamos que os dados de acesso ao sistema eletrônico de MPME/ABGF foram liberados e essa empresa encontra-se habilitada a cadastrar suas operações de exportação.<br>

<p>Seu login de acesso é: {{$data->CD_LOGIN}}</p>

<br>

@component('mail::button', ['url' => config('app.url')])
Acessar o sistema
@endcomponent
<br>
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
{{ config('app.name') }}
@endcomponent
