<div class="modal_aprovacao">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Lista de Aprovação</h3>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th>Data da Cadastro/Apovação</th>
                    <th>Usuário</th>
                    <th>Motivo</th>
                    <th>Status</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rsHistoricoAprovacao as $desembolso)
                    <tr>
                        <td>{{formatar_data_hora($desembolso->DT_CADASTRO)}}</td>
                        <td>{{$desembolso->usuario->NM_USUARIO}}</td>
                        <td>{{$desembolso->DS_OBSERVACAO}}</td>
                        <td>{{$desembolso->status->NO_STATUS}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>