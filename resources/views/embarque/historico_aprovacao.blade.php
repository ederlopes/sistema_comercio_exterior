<div class="modal_aprovacao">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Lista de Aprovacao</h3>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Parecer</th>
                </tr>
                </thead>
                <tbody>
                @foreach($historico_embarque as $historico)
                    <tr>
                        <td>{{formatar_data_hora($historico->DT_CADASTRO)}}</td>
                        <td>{{$historico->status->NO_STATUS}}</td>
                        <td>{{$historico->DS_OBSERVACAO}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>