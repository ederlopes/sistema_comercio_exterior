@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Controle da Exportação
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">
        @include('layouts.menu_banco')

        <!--CONTEUDO DA PAGINA-->
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Lista de Desembolso</h3>
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Data do Desembolso</th>
                                <th>Valor do Desembolso</th>
                                <th>Data do Vencimento</th>
                                <th>Status</th>
                                <th>&nbsp;</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($listarDesembolso as $desembolso)
                                <tr>
                                    <td>{{formatar_data($desembolso->DT_DESEMBOLSO)}}</td>
                                    <td>{{formatar_moeda($desembolso->VL_DESEMBOLSO)}}</td>
                                    <td>{{formatar_data($desembolso->DT_VENCIMENTO)}}</td>
                                    <td>{{$desembolso->status->NO_STATUS}}</td>
                                    <td>
                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="javascript:void(0);" data-iddesembolso="{{$desembolso->ID_MPME_DESEMBOLSO}}" class="aprovar_desembolso" data-toggle="modal" data-target="#aprovar_desembolso">Aprovar desembolso</a></li>
                                                <li><a href="javascript:void(0);" data-iddesembolso="{{$desembolso->ID_MPME_DESEMBOLSO}}" class="devolver_desembolso" data-toggle="modal" data-target="#recusar_desembolso">Devolver desembolso</a></li>
                                                <li><a href="javascript:void(0);" data-iddesembolso="{{$desembolso->ID_MPME_DESEMBOLSO}}" class="historico_desembolso" data-toggle="modal" data-target="#historico_desembolso">Histórico de aprovação</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>

    <!-- Modal -->
    <div class="modal fade " id="novo-desembolso" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLongTitle">Novo desembolso</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="loading">
                    <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="btnSalvar" name="btnSalvar">Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="historico_desembolso" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Histórico de aprovações</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
<script src="{{ asset('js/desembolso/funcoes_desembolso.js') }}"></script>
@endsection
