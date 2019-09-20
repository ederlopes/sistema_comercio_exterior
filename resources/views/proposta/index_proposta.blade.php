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

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">

            <div class="panel panel-default ">
                <div class="panel-body">

                    <div class="col-md-4">
                        <div class="">
                            <div class="alert alert-primary alert-valores" role="alert"><h4><i class="fa fa-sitemap"></i> <strong>Operação: </strong> {{formatar_codigo($request->id_oper)}}</h4></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="">
                            <div class="alert alert-success alert-valores" role="alert"><h4><i class="fa fa-money"></i> <strong>Valor Aprovado: {{$operacao->RetornaMoeda->SIGLA_MOEDA}}</strong>  {{formatar_valor_sem_moeda($vl_aprovado_operacao)}}</h4></div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="">
                            <div class="alert alert-info" role="alert"><h4><i class="fa fa-money"></i> <strong>Saldo: {{$operacao->RetornaMoeda->SIGLA_MOEDA}} </strong>{{$saldo}}</h4></div>
                        </div>
                    </div>
                    <input type="hidden" name="vl_aprovado" id="vl_aprovado" value="{{$vl_aprovado_operacao}}">
                    <input type="hidden" name="vl_saldo" id="vl_saldo" value="{{$saldo}}">

                </div>
            </div>

            <!--FILTRO DA PAGINA-->

            <form name="form-filtro" method="post" action="/proposta/{{$request->id_oper}}" class="clearfix">
                {{csrf_field()}}
                @include('proposta.filtros-proposta')
            </form>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Propostas</h3>
                </div>
                <div class="panel-body">
                    <p>
                       @can('NOVA_PROPOSTA')
                            <a href="{{URL::to('/proposta/nova')}}/{{$request->id_oper}}" class="btn btn-success "><i class="fa fa-clipboard"></i> Nova proposta</a>
                       @endcan
                    </p>
                    <table class="table table-bordered table-striped">
                        <!--<colgroup>
                            <col class="col-xs-1">
                            <col class="col-xs-1">
                        </colgroup>-->
                        <thead>
                        <tr>
                            <th>Nº da proposta</th>
                            <th>Nº da operação</th>
                            @if(\Auth::user()->ID_PERFIL != 9)
                            <th>ID da operação</th>
                            @endif
                            <th>Valor da proposta {{$operacao->RetornaMoeda->SIGLA_MOEDA}}</th>
                            <th>Prazo pré (dias)</th>
                            <th>Prazo pós (dias)</th>
                            <th>Down Payment</th>
                            <th>Data <br>de Cadastro</th>
                            <th  class="col-xs-1">Taxa <br>do prêmio</th>
                            <th class="col-xs-1">Valor <br>do prêmio {{$operacao->RetornaMoeda->SIGLA_MOEDA}}</th>
                            <th>Modalidade <br>do financiamento</th>
                            <th>Status</th>
                            <th class="col-xs-1">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $vl_proposta_total = 0;
                                $vl_proposta_total_dw = 0;
                            @endphp
                            @foreach( $rs_proposta as $proposta )
                                <tr>
                                    <td>{{formatar_codigo($proposta->ID_MPME_PROPOSTA)}}</td>
                                    <td>{{$proposta->COD_UNICO_OPERACAO ?? 'N/D'}}</td>
                                    @if(\Auth::user()->ID_PERFIL != 9)
                                    <td>{{formatar_codigo($proposta->ID_OPER)}}</td>
                                    @endif
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
                                            {{$proposta->VL_PERC_DOWPAYMENT}} %
                                        @else
                                            -
                                        @endif
                                    </td>
                                    <td>{{formatar_data_hora($proposta->DT_CADASTRO)}}</td>

                                    @if(isset($proposta->mpme_preco_cobertura->PC_COB_TAXA_CARREGAMENTO) )
                                    <td>
                                        @if(!isset($proposta->mpme_preco_cobertura->PC_COB_MANUAL) )
                                            {{$proposta->mpme_preco_cobertura->PC_COB_TAXA_CARREGAMENTO}}%
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
                                    <td>{{$proposta->NO_MODALIDADE_FINANCIAMENTO}}</td>
                                    <td>{{$proposta->mpme_status_proposta->NO_PROPOSTA}}</td>
                                    <td>
                                        <!-- Single button -->
                                        <div class="btn-group col-md-7">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @include('proposta.lista-acoes-proposta')
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $vl_proposta_total    += $proposta->VL_PROPOSTA;
                                    $vl_proposta_total_dw += calcular_valor_dowpayment($proposta->VL_PROPOSTA, $proposta->VL_PERC_DOWPAYMENT);
                                @endphp
                            @endforeach
                            <tr>
                                <th colspan="2">Total: <br /> Total - Down Payment</th>
                                <th>
                                    {{formatar_valor_sem_moeda($vl_proposta_total)}} <br />
                                    {{formatar_valor_sem_moeda($vl_proposta_total_dw)}}
                                </th>
                                <th colspan="9">&nbsp;</th>
                                <th class="col-xs-1">&nbsp</th>
                            </tr>
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
