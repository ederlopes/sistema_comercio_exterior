<div class="modal_aprovacao">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Alçadas de Aprovação</h3>
        </div>
        <div class="panel-body">
            <ul class="timeline timeline-horizontal">
                <!-- if($importador->creditoConcedido('ANA',$importador->ID_OPER)->VL_CRED_CONCEDIDO !='')  @formatar_valor($importador->creditoConcedido('ANA',$importador->ID_OPER)->VL_CRED_CONCEDIDO)  else R$ 00,00 endif -->
                @foreach($crontroleAlcadas['ALCADA']  as $alcada)
                    <li class="timeline-item">
                        <div class="alcada {{$alcada['ALCADA_HABILITADA']}}">{{$alcada['NO_ALCADA']}}</br><div class="disabled">{{ formatar_valor_sem_moeda($alcada['VL_APROVADO_ALCADA'])}}</div></div>
                        <div class="timeline-badge {{$alcada['NO_CLASSE']}} {{$alcada['ALCADA_HABILITADA']}}"><i class="glyphicon @if ($alcada['VL_APROVADO_ALCADA']) glyphicon-check @else glyphicon-unchecked @endif"></i></div>
                    </li>
                @endforeach


            </ul>
        </div>
    </div>
</div>

<div class="modal_aprovacao">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Lista de Aprovação</h3>
        </div>
        <div class="panel-body">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th>Operação</th>
                    <th>Data da Apovação</th>
                    <th>Alçada</th>
                    <th>Valor Aprovado</th>
                    <th>Decisão</th>
                </tr>
                </thead>
                <tbody>
                @foreach($mpme_aprovacao_valor_alcada as $operacoes_aprovadas)
                    <tr>
                        <td>{{$operacoes_aprovadas->ID_OPER}}</td>
                        <td>{{formatar_data_hora($operacoes_aprovadas->DT_CADASTRO)}}</td>
                        <td>{{$operacoes_aprovadas->mpme_alcada->NO_ALCADA}}</td>
                        <td>{{formatar_valor_sem_moeda($operacoes_aprovadas->VL_APROVADO)}}</td>
                        <td>{{getDecisao($operacoes_aprovadas->IN_DECISAO)}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>