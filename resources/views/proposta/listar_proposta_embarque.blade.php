
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

          <div class="col-md-2">
              @include('layouts.menu_abgf')
          </div>

        <!--CONTEUDO DA PAGINA-->
            <div class="col-md-9">
                <form name="form-filtro" method="post" action="listar-proposta-embarque" class="clearfix">
                    {{csrf_field()}}
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Filtrar Embarque</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label>Nº da Proposta</label>
                                        <input type="text" maxlength="10" name="NU_PROPOSTA" id="NU_PROPOSTA" value="{{$request->NU_PROPOSTA}}" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status do Embarque</label>
                                        <select class="form-control input-sm" name="ID_MPME_STATUS" id="ID_MPME_STATUS">
                                            <option value="0">Selecione</option>
                                            @foreach($status_embarque as $status)
                                                <option value="{{$status->ID_MPME_STATUS}}" @if($status->ID_MPME_STATUS == $request->ID_MPME_STATUS) selected @endif>{{$status->NO_STATUS}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>



                            </div>
                            <div class="row">
                                <button id="btnPesquisar" name="btnPesquisar" type="submit" class="btn btn-success pull-right" style="margin-right: 10px;">
                                    <i class="fa fa-filter"></i> Filtrar operação
                                </button>
                                <button id="btnReset" name="btnReset" type="reset" class="btn btn-default pull-right" style="margin-right: 10px;" onclick="window.location.href = ' {{Route('abgf.exportador.listarpropostasembarque')}} '">
                                    <i class="fa fa-filter"></i> Limpar filtro
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Lista de Embarque</h3>
                    </div>
                    <div class="panel-body">

                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th>Nº da proposta</th>
                                <th>ID da proposta</th>
                                <th>Data do Embarque</th>
                                <th>Data do Vencimento</th>
                                <th>Valor do Embarque</th>
                                <th>Valor do Financiamento</th>
                                <th>N.º da Fatura</th>
                                <th>N.º da DU-E</th>
                                <th>N.º do RVS</th>
                                <th>Status</th>
                                <th></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach( $listarEmbarque as $embarque )


                                <tr  data-placement="top" @if($embarque->PARECER ?? '' != '') data-toggle="tooltip" title="{{$embarque->PARECER ?? ''}} @endif">
                                    <td>{{$embarque->NU_PROPOSTA}}</td>
                                    <td>{{$embarque->ID_MPME_PROPOSTA}}</td>
                                    <td>{{formatar_data($embarque->DT_EMBARQUE)}}</td>
                                    <td>{{formatar_data($embarque->DT_VENCIMENTO)}}</td>
                                    <td>{{formatar_valor($embarque->VL_EMBARQUE)}}</td>
                                    <td>{{formatar_valor($embarque->VL_FINANCIAMENTO)}}</td>
                                    <td>{{$embarque->NU_FATURA}}</td>
                                    <td>{{$embarque->NU_DUE}}</td>
                                    <td>{{$embarque->NU_RVS}}</td>
                                    <td>{{$embarque->status->NO_STATUS}}</td>
                                    <td>
                                        <input type="hidden" name="parecer" id="parecer" />
                                        <input type="hidden" name="motivo_devolucao" id="motivo_devolucao" />
                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @can('APROVAR_EMBARQUE')
                                                    <li><a href="javascript:void(0);" data-id-mpme-embarque="{{$embarque->ID_MPME_EMBARQUE}}" data-idproposta="{{$embarque->ID_MPME_PROPOSTA}}" class="aprovar_embarque"  data-target="#aprovar_embarque" data-recurso-proprio='1'>Aprovar Embarque</a></li>
                                                @endcan
                                                @can('DEVOLVER_EMBARQUE')
                                                    <li><a href="javascript:void(0);" data-id-mpme-embarque="{{$embarque->ID_MPME_EMBARQUE}}" data-idproposta="{{$embarque->ID_MPME_PROPOSTA}}" class="devolver_embarque" data-target="#devolver_embarque" data-devolve-exportador="1" data-devolve-exportador-analista='1'>Devolver Embarque</a></li>
                                                @endcan
                                                <li><a href="#" data-idoper="{{$embarque->proposta->ID_OPER}}" data-id-mpme-embarque="{{$embarque->ID_MPME_EMBARQUE}}" data-idproposta="{{$embarque->ID_MPME_PROPOSTA}}" class="historico_embarque" data-toggle="modal" data-target="#historico_embarque">Histórico de aprovação</a></li>
                                            </ul>
                                        </div>
                                    </td>


                                </tr>

                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                @include('arquivos.arquivos-modais')
                <div class="modal fade" id="historico_embarque" tabindex="-1" role="dialog">
                    <input type="hidden" name="id_mpme_embarque" id="id_mpme_embarque">
                    <input type="hidden" name="id_oper" id="id_oper">
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

            </div>
        </div>
    </section>
    </div>
    <script src="{{ asset('js/banco/embarque/funcoes_embarque.js')}}?<?=time();?>" charset="utf-8"></script>
@endsection

