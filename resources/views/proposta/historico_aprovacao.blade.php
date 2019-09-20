<div class="modal_aprovacao">
    <div class="panel panel-primary">
        <div class="panel-heading">
            <h3 class="panel-title">Lista de Aprovação</h3>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th>Proposta</th>
                    <th>Data do Encaminhamento / Apovação</th>
                    <th>Alçada</th>
                    <th>Valor Aprovado</th>
                    <th>Decisão</th>
                    <th>Motivo</th>
                </tr>
                </thead>
                <tbody>
                @foreach($rsHistoricoAprovacao as $proposta)
                    <tr>
                        <td>{{formatar_codigo($proposta->ID_MPME_PROPOSTA)}}</td>
                        <td>{{formatar_data_hora($proposta->DT_CADASTRO)}}</td>
                        <td>{{$proposta->mpme_alcada->NO_ALCADA}}</td>
                        <td>{{formatar_valor_sem_moeda($proposta->VL_PROPOSTA)}}</td>
                        <td>{{getDecisao($proposta->IN_DECISAO)}}</td>
                        <td>{{$proposta->DS_MOTIVO}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>