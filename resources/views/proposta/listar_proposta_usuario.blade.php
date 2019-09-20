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
      @include('layouts.menu_cliente')

      <!--FILTRO DA PAGINA-->
      <div class="col-md-10">
          <form name="form-filtro" method="post" action="lista-proposta-usuario#table_proposta" class="clearfix">
              {{csrf_field()}}
              @include('proposta.filtros-proposta')
          </form>
      </div>

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Propostas</h3>
                </div>
                <div class="panel-body">
                    <table id="table_proposta" class="table table-bordered table-striped">
                        <thead>
                        <tr>
                            <th>Nº da proposta</th>
                            @if(\Auth::user()->ID_PERFIL != 9)
                            <th>ID da operação</th>
                            @endif
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
                            @forelse( $rs_proposta as $proposta )
                                <tr>
                                    <td>{{formatar_codigo($proposta->ID_MPME_PROPOSTA)}}</td>
                                    @if(\Auth::user()->ID_PERFIL != 9)
                                    <td>{{formatar_codigo($proposta->ID_OPER)}}</td>
                                    @endif
                                    <td>{{$proposta->COD_UNICO_OPERACAO ?? 'N/D'}}</td>
                                    <td>{{$rs_proposta[0]->operacoes->RetornaMoeda->SIGLA_MOEDA}} - {{formatar_valor_sem_moeda($proposta->VL_PROPOSTA)}} </td>
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
                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @if(in_array($proposta->operacoes->ST_OPER, [5]))
                                                    @can('NOVA_PROPOSTA')
                                                    <li><a href="{{URL::to('/proposta/nova/')}}/{{$proposta->ID_OPER}}" data-idoper="{{$proposta->ID_OPER}}">Nova Proposta</a></li>
                                                    @endcan
                                                @endif

                                                @include('proposta.lista-acoes-proposta')
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $vl_proposta_total += $proposta->VL_PROPOSTA;
                                @endphp
                            @empty
                                <tr>
                                    <td colspan="11"><div class="alert alert-warning">Não existem propostas lançadas</div></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="col-md-12">
                        Exibindo <b>{{count($rs_proposta)}}</b> de <b>{{$rs_proposta->total()}}</b> {{($rs_proposta->total()>1?'propostas':'proposta')}}.
                    </div>
                    <div align="center">
                        {{$rs_proposta->fragment('table_proposta')->render()}}
                    </div>
                </div>
            </div>
            @include('proposta.modais-proposta')
            @include('proposta.modal-precificacao')
            @include('arquivos.arquivos-modais')
        </div>

      </div>
    </section>
    <script src="{{ asset('js/upload.js') }}?<?=time();?>"></script>
    <script src="{{ asset('js/proposta/funcoes_proposta.js') }}?<?=time();?>"></script>
  </div>
@endsection
