@extends('layouts.app')

@section('scripts')
    <script>
        setTimeout(function(){
            window.location.reload();
        }, 180000);





        window.chartColors = {
            red: 'rgb(255, 99, 132)',
            orange: 'rgb(255, 159, 64)',
            yellow: 'rgb(255, 205, 86)',
            green: 'rgb(75, 192, 192)',
            blue: 'rgb(54, 162, 235)',
            purple: 'rgb(153, 102, 255)',
            grey: 'rgb(201, 203, 207)'
        };

        $(document).ready(function(){
            $('div#visualizar-dados-operacao').on('show.bs.modal', function (event) {
                var id_oper          = $(event.relatedTarget).data('idoper');

                if (id_oper == "")
                {
                    swal("Ops!", "Dados informados inválidos", "info");
                    return false;
                }

                $.ajax({
                    type: "POST",
                    method: "POST",
                    url: URL_BASE+'proposta/dados-questionario',
                    data: {
                        'id_oper': id_oper,
                    },
                    context: this,
                    beforeSend: function() {
                        $(this).find('.modal-body').html('');
                        $(".loading").show();
                    },
                    success: function(retorno)
                    {
                        $(".loading").hide();
                        $(this).find('.modal-body').html(retorno).fadeIn('fast');
                    },
                    error: function (request, status, error) {
                        swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function() {
                            $(this).modal('hide');
                        });
                    }
                });
            });

            $('div#visualizar-dados-proposta').on('show.bs.modal', function (event) {
                var id_mpme_proposta = $(event.relatedTarget).data('idproposta');
                var id_oper          = $(event.relatedTarget).data('idoper');

                if ( id_mpme_proposta == "" || id_oper == "")
                {
                    swal("Ops!", "Dados informados inválidos", "info");
                    return false;
                }

                $.ajax({
                    type: "POST",
                    method: "POST",
                    url: URL_BASE+'proposta/dados-proposta',
                    data: {
                        'id_mpme_proposta': id_mpme_proposta,
                        'id_oper': id_oper,
                    },
                    context: this,
                    beforeSend: function() {
                        $(this).find('.modal-body').html('');
                        $(".loading").show();
                    },
                    success: function(retorno)
                    {
                        $(".loading").hide();
                        $(this).find('.modal-body').html(retorno).fadeIn('fast');
                    },
                    error: function (request, status, error) {
                        swal("Erro!", "Por favor, tente novamente mais tarde. Erro ARQ506", "error").then(function() {
                            $(this).modal('hide');
                        });
                    }
                });


            });
        });
    </script>
@endsection

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1 class="m-0 text-dark">Dashboard</h1>
    </section>

    <!-- Main content -->
    <section id="dashboard" class="content">
        <div class="row">
            <!--MENU DA PAGINA-->
            @if(\Auth::user()->ID_PERFIL != 15)
            <div class="col-md-2">
               @include('layouts.menu_abgf')
            </div>
            @endif

            <!--CONTEUDO DA PAGINA-->
            <div class="@if(\Auth::user()->ID_PERFIL != 15) col-md-10 @else col-md-12 @endif">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="dashitem clearfix">
                            <div class="page-header">
                                <h2>Exportadores</h2>
                            </div>
                            <div class="col-md-3">
                                <div class="total-box bg-primary">
                                    <div class="icon">
                                        <i class="fa fa-users" aria-hidden="true"></i>
                                    </div>
                                    <h1>{{count($totais['usuarios'])}}</h1>
                                    <p>Exportadores</p>
                                    <a href="#detalhes-clientes" class="btn btn-primary" data-toggle="modal">VER DETALHES</a>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative; height:200px">
                                <canvas id="pie_clientes" width="400" height="400"></canvas>
                            </div>
                            <div class="col-md-6" style="position: relative; height:200px">
                                <canvas id="line_clientes" width="400" height="400"></canvas>
                            </div>
                        </div>

                        <div class="dashitem clearfix">
                            <div class="page-header">
                                <h2>Operações</h2>
                            </div>
                            <div class="col-md-3">
                                <div class="total-box bg-success">
                                    <div class="icon">
                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </div>
                                    <h1>{{count($totais['operacoes'])}}</h1>
                                    <p>Operações</p>
                                    <a href="#detalhes-operacoes" class="btn btn-success" data-toggle="modal">VER DETALHES</a>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative; height:200px">
                                <canvas id="pie_operacoes" width="400" height="400"></canvas>
                            </div>
                            <div class="col-md-6" style="position: relative; height:200px">
                                <canvas id="line_operacoes" width="400" height="400"></canvas>
                            </div>
                        </div>

                        <div class="dashitem clearfix">
                            <div class="page-header">
                                <h2>Propostas</h2>
                            </div>
                            <div class="col-md-3">
                                <div class="total-box bg-info">
                                    <div class="icon">
                                        <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </div>
                                    <h1>{{count($totais['propostas'])}}</h1>
                                    <p>Propostas</p>
                                    <a href="#detalhes-propostas" class="btn btn-info" data-toggle="modal">VER DETALHES</a>
                                </div>
                            </div>
                            <div class="col-md-3" style="position: relative; height:200px">
                                <canvas id="pie_propostas" width="400" height="400"></canvas>
                            </div>
                            <div class="col-md-6" style="position: relative; height:200px">
                                <canvas id="line_propostas" width="400" height="400"></canvas>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div id="detalhes-clientes" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Exportadores</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-vertical-align">
                        <tr>
                            <th width="7%">ID</th>
                            <th width="33%">Exportador</th>
                            <th width="30%">Endereço</th>
                            <th width="20%">Modalidades</th>
                            <th width="10%">Situação</th>
                        </tr>
                        @forelse($totais['usuarios'] as $usuario)
                            <tr>
                                <td>{{$usuario->ID_USUARIO}}</td>
                                <td>{{$usuario->NM_USUARIO}}</td>
                                <td>{{$usuario->DE_ENDER}} - {{$usuario->DE_CIDADE}} - {{$usuario->CD_UF}}</td>
                                <td>
                                    @if($usuario->ClienteExportador)
                                        @foreach($usuario->ClienteExportador->ModalidadeFinanciamento as $modalidade)
                                            <span class="label label-primary">{{$modalidade->ModalidadeFinanciamento->NO_MODALIDADE_FINANCIAMENTO}}</span>
                                        @endforeach
                                    @endif
                                </td>
                                <td>
                                    <?php
                                    switch($usuario->FL_ATIVO) {
                                        case 0:
                                            echo '<span class="label label-danger">Inativo</span>';
                                            break;
                                        case 1:
                                            echo '<span class="label label-success">Ativo</span>';
                                            break;
                                    }
                                    ?>
                                </td>
                            </tr>
                        @empty
                            #todo
                            nao tem
                        @endforelse
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="detalhes-operacoes" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xgg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Operações</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-vertical-align">
                        <tr>
                            <th width="7%">Número</th>
                            <th width="18%">Exportador</th>
                            <th width="10%">Data</th>
                            <th width="15%">País</th>
                            <th width="20%">Importador</th>
                            <th width="10%">Valor solicitado</th>
                            <th width="15%">Situação</th>
                            <th width="5%"></th>
                        </tr>
                        @forelse($totais['operacoes'] as $operacao)
                            <tr>
                                <td>{{$operacao->COD_UNICO_OPERACAO}}</td>
                                <td>{{$operacao->usuario->NM_USUARIO}}</td>
                                <td>{{\Carbon\Carbon::parse($operacao->DATA_CADASTRO)->format('d/m/Y')}}</td>
                                <td>{{$operacao->RetornaPaisImportadorOperacao->NM_PAIS}}</td>
                                <td>{{$operacao->RAZAO_SOCIAL}}</td>
                                <td>{{$operacao->RetornaMoeda->SIGLA_MOEDA}} {{formatar_valor_sem_moeda($operacao->VL_APROVADO)}}</td>
                                <td>{{$operacao->StatusOper->NM_OPER}}</td>
                                <td><a href="#visualizar-dados-operacao" class="btn btn-primary" data-idoper="{{$operacao->ID_OPER}}" data-toggle="modal">DETALHES</a></td>
                            </tr>
                        @empty
                            #todo
                            nao tem
                        @endforelse
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="visualizar-dados-operacao" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Dados da operação</h4>
                </div>
                <div class="loading">
                    <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div id="detalhes-propostas" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xgg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Propostas</h4>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered table-striped table-vertical-align">
                        <tr>
                            <th width="7%">Número</th>
                            <th width="10%">Data</th>
                            <th width="15%">Prazo para aprovação SUSEP (15 dias)</th>
                            <th width="20%">Taxa do prêmio</th>
                            <th width="10%">Valor do prêmio</th>
                            <th width="15%">Situação</th>
                            <th width="5%"></th>
                        </tr>
                        @forelse($totais['propostas'] as $proposta)
                            <tr>
                                <td>{{$proposta->ID_MPME_PROPOSTA}}</td>
                                <td>{{\Carbon\Carbon::parse($proposta->DT_CADASTRO)->format('d/m/Y')}}</td>
                                <td>

                                    @if ( $proposta->DT_APROVACAO != "" )
                                        <div class="alert alert-success alert-valores alert-prazosusep"><strong>Aprovado: </strong> {{formatar_data_hora($proposta->DT_APROVACAO)}}</div>
                                    @elseif( $proposta->DT_CANCELAMENTO != "" )
                                        <div class="alert alert-danger alert-prazosusep"><strong>Cancelado: </strong> {{formatar_data_hora($proposta->DT_CANCELAMENTO)}}</div>
                                    @else
                                        @php
                                            $diff = retornoPrazoSusep($proposta->DT_ENVIO);
                                        @endphp

                                        @if( $diff <= 15  && $diff > 10 )
                                            <div class="alert alert-success alert-valores alert-prazosusep"><strong>Faltam: </strong> {{$diff}} dias.</div>
                                        @endif
                                        @if( $diff <= 10  && $diff > 5 )
                                            <div class="alert alert-warning alert-prazosusep"><strong>Faltam: </strong> {{$diff}} dias.</div>
                                        @endif
                                        @if( $diff <= 5 )
                                            <div class="alert alert-danger alert-prazosusep"><strong>Faltam: </strong> {{$diff}} dias.</div>
                                        @endif
                                    @endif

                                </td>
                                @if(isset($proposta->mpme_preco_cobertura->PC_COB_TAXA_CARREGAMENTO) )
                                    <td>
                                        @if(!isset($proposta->mpme_preco_cobertura->PC_COB_MANUAL) )
                                            {{formatar_moeda($proposta->mpme_preco_cobertura->PC_COB_TAXA_CARREGAMENTO)}}%
                                        @else
                                            {{formatar_moeda($proposta->mpme_preco_cobertura->PC_COB_MANUAL)}}%
                                        @endif
                                    </td>
                                    <td>
                                        @if(!isset($proposta->mpme_preco_cobertura->PC_COB_MANUAL) )
                                            {{formatar_moeda($proposta->mpme_preco_cobertura->VL_PC_COB_TAXA_CARREGAMENTO)}}
                                        @else
                                            {{formatar_moeda($proposta->mpme_preco_cobertura->VL_PC_COB_MANUAL)}}%
                                        @endif
                                    </td>
                                @else
                                    <td colspan="2">
                                        <div class="alert alert-danger alert-danger-small" role="alert">
                                            <strong>ERRO</strong> ao precificar, Favor tentar novamente
                                        </div>
                                    </td>
                                @endif
                                <td>{{$proposta->mpme_status_proposta->NO_PROPOSTA}}</td>
                                <td><a href="#visualizar-dados-proposta" class="btn btn-primary" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" data-toggle="modal">DETALHES</a></td>
                            </tr>
                        @empty
                            #todo
                            nao tem
                        @endforelse
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="visualizar-dados-proposta" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Dados da proposta</h4>
                </div>
                <div class="loading">
                    <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ------------------------------------------------------------------------------
        // Gráficos de Clientes
        // ------------------------------------------------------------------------------
        var opt_pie_clientes = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        {{count($totais['usuarios']->where('FL_ATIVO',1))}},
                        {{count($totais['usuarios']->where('FL_ATIVO',0))}}
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.blue,
                    ],
                    label: 'Exportadores'
                }],
                labels: [
                    'Ativos',
                    'Inativos'
                ]
            },
            options: {
                maintainAspectRatio: false,
            }
        };
        var ctx_pie_clientes = document.getElementById("pie_clientes").getContext('2d');
        var pie_clientes = new Chart(ctx_pie_clientes, opt_pie_clientes);

        var opt_line_clientes = {
            type: 'line',
            data: {
                labels: ['<?=implode("','",$totais['usuarios_datas']['datas']);?>'],
                datasets: [{
                    label: 'Exportadores cadastrados',
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [{{implode(',',$totais['usuarios_datas']['cadastrados'])}}],
                    fill: false,
                }, {
                    label: 'Exportadores aprovados',
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: [{{implode(',',$totais['usuarios_datas']['aprovacoes'])}}],
                    fill: false
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Total'
                        }
                    }]
                }
            }
        };
        var ctx_line_clientes = document.getElementById("line_clientes").getContext('2d');
        var line_clientes = new Chart(ctx_line_clientes, opt_line_clientes);

        // ------------------------------------------------------------------------------
        // Gráficos de Operações
        // ------------------------------------------------------------------------------
        var opt_pie_operacoes = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        {{count($totais['operacoes']->whereIn('ST_OPER',[1]))}},
                        {{count($totais['operacoes']->whereIn('ST_OPER',[3,12,13,20]))}},
                        {{count($totais['operacoes']->whereIn('ST_OPER',[5]))}},
                        {{count($totais['operacoes']->whereIn('ST_OPER',[9,21]))}},
                        {{count($totais['operacoes']->whereNotIn('ST_OPER',[1,3,12,13,20,5,9,21]))}}
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.purple,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    'Não enviadas',
                    'Aguardando análise',
                    'Deferidas',
                    'Indeferidas',
                    'Outras'
                ]
            },
            options: {
                maintainAspectRatio: false,
            }
        };
        var ctx_pie_operacoes = document.getElementById("pie_operacoes").getContext('2d');
        var pie_operacoes = new Chart(ctx_pie_operacoes, opt_pie_operacoes);

        var opt_line_operacoes = {
            type: 'line',
            data: {
                labels: ['<?=implode("','",$totais['operacoes_datas']['datas']);?>'],
                datasets: [{
                    label: 'Operações cadastradas',
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [{{implode(',',$totais['operacoes_datas']['cadastradas'])}}],
                    fill: false,
                }, {
                    label: 'Operações deferidas',
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: [{{implode(',',$totais['operacoes_datas']['aprovadas'])}}],
                    fill: false,
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Total'
                        }
                    }]
                }
            }
        };
        var ctx_line_operacoes = document.getElementById("line_operacoes").getContext('2d');
        var line_operacoes = new Chart(ctx_line_operacoes, opt_line_operacoes);

        // ------------------------------------------------------------------------------
        // Gráficos de Operações
        // ------------------------------------------------------------------------------
        var opt_pie_propostas = {
            type: 'pie',
            data: {
                datasets: [{
                    data: [
                        {{count($totais['propostas']->whereIn('ID_MPME_STATUS_PROPOSTA',[1]))}},
                        {{count($totais['propostas']->whereIn('ID_MPME_STATUS_PROPOSTA',[2]))}},
                        {{count($totais['propostas']->whereIn('ID_MPME_STATUS_PROPOSTA',[5,8,9,10,11]))}},
                        {{count($totais['propostas']->whereIn('ID_MPME_STATUS_PROPOSTA',[6,7]))}},
                        {{count($totais['propostas']->whereIn('ID_MPME_STATUS_PROPOSTA',[14,15,16]))}},
                    ],
                    backgroundColor: [
                        window.chartColors.red,
                        window.chartColors.orange,
                        window.chartColors.yellow,
                        window.chartColors.green,
                        window.chartColors.blue,
                    ],
                    label: 'Dataset 1'
                }],
                labels: [
                    'Não enviadas',
                    'Em análise',
                    'Aprovadas',
                    'Recusadas',
                    'Concretizadas'
                ]
            },
            options: {
                maintainAspectRatio: false,
            }
        };
        var ctx_pie_propostas = document.getElementById("pie_propostas").getContext('2d');
        var pie_propostas = new Chart(ctx_pie_propostas, opt_pie_propostas);

        var opt_line_propostas = {
            type: 'line',
            data: {
                labels: ['<?=implode("','",$totais['operacoes_datas']['datas']);?>'],
                datasets: [{
                    label: 'Propostas cadastradas',
                    backgroundColor: window.chartColors.red,
                    borderColor: window.chartColors.red,
                    data: [{{implode(',',$totais['propostas_datas']['cadastradas'])}}],
                    fill: false,
                }, {
                    label: 'Propostas aprovadas',
                    backgroundColor: window.chartColors.blue,
                    borderColor: window.chartColors.blue,
                    data: [{{implode(',',$totais['propostas_datas']['aprovadas'])}}],
                    fill: false
                }, {
                    label: 'Apólices enviadas',
                    backgroundColor: window.chartColors.green,
                    borderColor: window.chartColors.green,
                    data: [{{implode(',',$totais['propostas_datas']['apolices'])}}],
                    fill: false
                }]
            },
            options: {
                maintainAspectRatio: false,
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Total'
                        }
                    }]
                }
            }
        };
        var ctx_line_propostas = document.getElementById("line_propostas").getContext('2d');
        var line_propostas = new Chart(ctx_line_propostas, opt_line_propostas);


    </script>
@endsection
