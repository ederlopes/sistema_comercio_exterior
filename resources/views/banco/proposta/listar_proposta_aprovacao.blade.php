@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
            <h1>Controle de Propostas</h1>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
          <!--MENU DA PAGINA-->
          <div class="col-md-2">
              @include('layouts.menu_banco')
          </div>

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Propostas</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped">
                      
                        <thead>
                        <tr>
                            <th>Nº da proposta</th>
                            <th>Nº da operação</th>
                            <th>Valor da proposta</th>
                            <th>Prazo pré (dias)</th>
                            <th>Prazo pós (dias)</th>
                            <th>Down Payment</th>
                            <th>Data de Cadastro</th>
                            <th>Taxa do prêmio</th>
                            <th>Valor do prêmio</th>
                            <th>Status</th>
                            <th>&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $vl_proposta_total = 0;
                            @endphp
                            @foreach( $rs_proposta as $proposta )
                               @if($proposta->MpmeClienteExportadorModaliadeFinancimanciamento->MpmeClienteExportador->Usuario->Banco->Gecex->ID_USUARIO_FK ?? '' == Auth::user()->ID_USUARIO )
                                <tr>
                                    <td>{{formatar_codigo($proposta->ID_MPME_PROPOSTA)}}</td>
                                    <td>{{formatar_codigo($proposta->ID_OPER)}}</td>
                                    <td>{{formatar_valor_sem_moeda($proposta->VL_PROPOSTA)}}</td>
                                    <td>
                                        @if (isset($proposta->NU_PRAZO_PRE))
                                            {{$proposta->NU_PRAZO_PRE}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($proposta->NU_PRAZO_POS))
                                            {{$proposta->NU_PRAZO_POS}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($proposta->VL_PERC_DOWPAYMENT))
                                            {{$proposta->VL_PERC_DOWPAYMENT}}
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{formatar_data_hora($proposta->DT_CADASTRO)}}</td>
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
                                    <td>
                                    @if(isset($proposta->mpme_preco_cobertura->PC_COB_TAXA_CARREGAMENTO) )
                                        <!-- Single button -->
                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <!--<li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="historico_proposta" data-toggle="modal" data-target="#historico_proposta">Histórico de aprovação</a></li>-->
                                                @if(in_array($proposta->ID_MPME_STATUS_PROPOSTA, [14,18]))
                                                    @if(in_array($proposta->MpmeClienteExportadorModaliadeFinancimanciamento->ModalidadeFinanciamento->ID_MODALIDADE, [2,3]))
                                                        <li><a href="{{URL::to('banco/embarque')}}/{{$proposta->ID_OPER}}/{{$proposta->ID_MPME_PROPOSTA}}">Lista de Embarque</a></li>
                                                    @endif

                                                    @if(in_array($proposta->MpmeClienteExportadorModaliadeFinancimanciamento->ModalidadeFinanciamento->ID_MODALIDADE, [1,2]))
                                                       <li><a href="{{URL::to('banco/desembolso')}}/{{$proposta->ID_OPER}}/{{$proposta->ID_MPME_PROPOSTA}}">Lista de Desembolso</a></li>
                                                    @endif
                                                @endif
                                            </ul>
                                        </div>
                                        @else
                                            <div class="alert alert-warning alert-danger-small" role="alert">
                                                <strong>ATENÇÃO</strong> Solicitar ao cliente reprocessar a precificação
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                                @php
                                    $vl_proposta_total += $proposta->VL_PROPOSTA;
                                @endphp
                             @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="historico_proposta" tabindex="-1" role="dialog">
                <input type="hidden" name="id_mpme_proposta" id="id_mpme_proposta">
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

            @include('arquivos.arquivos-modais')
        </div>
      </div>
    </section>
    <script src="{{ asset('js/upload.js') }}?<?=time();?>"></script>
    <script src="{{ asset('js/proposta/funcoes_proposta.js') }}?<?=time();?>"></script>
  </div>
@endsection
