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
            <div class="col-md-3">
                @include('layouts.menu_banco')
            </div>

        <!--CONTEUDO DA PAGINA-->
            <div class="col-md-9">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Lista de Desembolso</h3>
                    </div>
                    <div class="panel-body">
                        <p>
                            @if ( count($listarDesembolso) <= 0 )
                                <a href="javascript:void(0);" data-id_proposta="{{$request->id_proposta}}" class="btn btn-success " data-toggle="modal" data-target="#novo-desembolso"><i class="fa fa-clipboard"></i> Novo desembolso</a>
                            @endif
                        </p>
                        <table class="table table-bordered table-striped" id="tabela_desembolso">
                            <thead>
                            <tr>
                                <th>Código</th>
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
                                    <td>{{formatar_codigo($desembolso->ID_MPME_DESEMBOLSO)}}</td>
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
                                                @if (Auth::User()->PermissoesConferenciaValidador->ativConferente == 1 && $desembolso->ID_MPME_STATUS == 3)
                                                    <li><a href="javascript:void(0);" data-idmpmeproposta="{{$desembolso->ID_MPME_PROPOSTA}}" data-idmpmedesembolso="{{$desembolso->ID_MPME_DESEMBOLSO}}" class="alterar_desembolso" data-toggle="modal" data-target="#alterar_desembolso">Alterar desembolso</a></li>
                                                @endif
                                                @if (Auth::User()->PermissoesConferenciaValidador->ativValidador == 2 && $desembolso->ID_MPME_STATUS == 1)
                                                    <li><a href="javascript:void(0);" data-idmpmedesembolso="{{$desembolso->ID_MPME_DESEMBOLSO}}" data-idproposta="{{$desembolso->ID_MPME_PROPOSTA}}" class="aprovar_desembolso">Aprovar desembolso</a></li>
                                                    @if ($desembolso->ID_MPME_STATUS == 1)
                                                        <li><a href="javascript:void(0);" data-idmpmedesembolso="{{$desembolso->ID_MPME_DESEMBOLSO}}"  class="recusar_desembolso" data-toggle="modal" data-target="#recusar-desembolso">Recusar desembolso</a></li>
                                                    @endif
                                                @endif
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
    <div class="modal fade " id="alterar_desembolso" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLongTitle">Alterar desembolso</h3>
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
                    <button type="button" class="btn btn-primary" id="btnAlterar" name="btnAlterar">Salvar</button>
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
    <div class="modal fade" id="recusar-desembolso" tabindex="-1" role="dialog">

        <input type="hidden" name="id_mpme_desembolso" id="id_mpme_desembolso">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Recusar desembolso</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="loading">
                    <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                </div>
                <div class="modal-body">
                    <textarea id="ds_motivo" name="ds_motivo" style="height: 150px; width: 550px;"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary" id="recusarDesembolso" name="recusarDesembolso">Salvar</button>
                </div>
            </div>
        </div>
    </div>
<script src="{{ asset('js/desembolso/funcoes_desembolso.js') }}"></script>
@endsection
