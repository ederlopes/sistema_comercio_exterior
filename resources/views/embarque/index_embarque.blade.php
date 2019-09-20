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
          @include('layouts.menu_cliente')

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Embarque</h3>
                </div>
                <div class="panel-body">
                    <p>
                        @if ( count($listarEmbarque) <= 0 && Auth::User()->TP_USUARIO != 'B')
                            <a href="{{URL::to('/embarque/novo')}}/{{$request->id_oper}}/{{$request->id_proposta}}"  class="btn btn-success " data-toggle="modal" ><i class="fa fa-clipboard"></i> Novo embarque</a>
                        @endif
                    </p>
                    <table class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Nº da proposta</th>
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
                                    <td>{{$embarque->ID_MPME_PROPOSTA}}</td>
                                    <td>{{formatar_data($embarque->DT_EMBARQUE)}}</td>
                                    <td>{{formatar_data($embarque->DT_VENCIMENTO)}}</td>
                                    <td>{{formatar_valor($embarque->VL_EMBARQUE)}}</td>
                                    <td>{{formatar_valor($embarque->VL_FINANCIAMENTO)}}</td>
                                    <td>{{$embarque->NU_FATURA}}</td>
                                    <td>{{$embarque->NU_DUE}}</td>
                                    <td>{{$embarque->NU_RVS}}</td>
                                    <td>{{$embarque->status->NO_STATUS}}</td>


                                @if(Auth::User()->TP_USUARIO == 'C' && $embarque->ID_MPME_STATUS == 9)
                                    <td>
                                        <input type="hidden" name="parecer" id="parecer" />
                                        <input type="hidden" name="motivo_devolucao" id="motivo_devolucao" />
                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{Route('notificacoes.embarque.editar', ['id_oper' => $request->id_oper,'id_proposta' => $embarque->ID_MPME_PROPOSTA, 'id_embarque' => $embarque->ID_MPME_EMBARQUE])}}">Editar</a></li>
                                                <li><a href="#" data-idoper="{{$request->id_oper}}" data-id-mpme-embarque="{{$embarque->ID_MPME_EMBARQUE}}" data-idproposta="{{$embarque->ID_MPME_PROPOSTA}}" class="historico_embarque" data-toggle="modal" data-target="#historico_embarque">Histórico de aprovação</a></li>
                                            </ul>
                                        </div>
                                    </td>

                                @elseif(Auth::User()->TP_USUARIO == 'B')
                                   <td>
                                    <input type="hidden" name="parecer" id="parecer" /> 
                                    <input type="hidden" name="motivo_devolucao" id="motivo_devolucao" />
                                      <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="javascript:void(0);" data-id-mpme-embarque="{{$embarque->ID_MPME_EMBARQUE}}" data-idproposta="{{$embarque->ID_MPME_PROPOSTA}}" class="aprovar_embarque"  data-target="#aprovar_embarque" >Aprovar Embarque</a></li>
                                                <li><a href="javascript:void(0);" data-id-mpme-embarque="{{$embarque->ID_MPME_EMBARQUE}}" data-idproposta="{{$embarque->ID_MPME_PROPOSTA}}" class="devolver_embarque" data-target="#devolver_embarque" data-devolve-exportador="<?php if(Auth::User()->PermissoesConferenciaValidador->ativConferente ?? '' == 1 && Auth::User()->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin ?? '' == 1){ echo '1';}else{ echo '2'; } ?>">Devolver Embarque</a></li>
                                            </ul>
                                        </div>
                                    </td>
                              @else
                                    <td>&nbsp;</td>
                              @endif

                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </section>
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
  <script src="{{ asset('js/banco/embarque/funcoes_embarque.js')}}?<?=time();?>" charset="utf-8"></script>
@endsection
