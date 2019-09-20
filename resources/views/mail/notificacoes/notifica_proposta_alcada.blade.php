@component('mail::message')
    # Aprovação da proposta

    <br><br>
    Prezado funcionário você tem uma proposta para aprovar

    @component('mail::button', ['url' => config('app.url')])
        Acessar o sistema
    @endcomponent
    <br>
    <br>
    <br>
    <br>
    <br>

    <p>
        Atenciosamente,</br>
        Gerência Executiva de Operações de Garantias de Exportação de MPME</br>
        Agencia Brasileira Gestora de Fundos Garantidores e Garantias S.A.</br>
        Matriz - Setor de Autarquias Sul, Quadra 03, Bloco O, 11º Andar, Sala 1000 - Brasilia (DF)</br>
        Filial - Rua da Quitanda, nº 86 – 2º andar – Edifício Sul América – Centro – Rio de Janeiro-RJ </br>
        CEP: 20091-005</br>
        Telefone: (21) 2510-5000
    </p>
    {{ config('app.name') }}
@endcomponent
