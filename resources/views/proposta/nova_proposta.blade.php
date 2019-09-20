@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Controle de Propostas</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          @include('layouts.menu_proposta')
        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
            @if ($is_validade)
                <div class="alert alert-danger">
                    <strong>ATENÇÃO</strong><br />
                    <div>O prazo de validade da operação expirou. Para cadastrar novas propostas será necessário criar uma nova Operação.</div>
                </div>
            @endif

            <form name="frmProposta" id="frmProposta" method="post" action="">
                <input type="hidden" name="vl_aprovado" id="vl_aprovado" value="{{$vl_aprovado_operacao}}">
                <input type="hidden" name="vl_saldo" id="vl_saldo" value="{{converte_float($saldo)}}">
                <input type="hidden" name="id_mpme_status_proposta" id="id_mpme_status_proposta" value="1">
                <input type="hidden" name="id_oper" id="id_oper" value="{{$request->id_oper}}">
                <input type="hidden" name="id_mpme_alcada" id="id_mpme_alcada" value="1">
                <input type="hidden" name="in_decisao" id="in_decisao" value="1">
                <input type="hidden" name="id_aceite_termo_setor_atividade" id="id_aceite_termo_setor_atividade" value="0">

                <div class="panel panel-default ">
                    <div class="panel-body">
                        <div class="col-md-4">
                            <div class="">
                                <div class="alert alert-primary alert-valores" role="alert"><h4><i class="fa fa-sitemap"></i> <strong>Operação: </strong> {{$request->id_oper}}</h4></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <div class="alert alert-success alert-valores" role="alert"><h4><i class="fa fa-money"></i> <strong>Valor Aprovado: {{$operacao->RetornaMoeda->SIGLA_MOEDA}}</strong> {{formatar_valor_sem_moeda($vl_aprovado_operacao)}}</h4></div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="">
                                <div class="alert alert-info " role="alert"><h4><i class="fa fa-money"></i> <strong>Saldo: {{$operacao->RetornaMoeda->SIGLA_MOEDA}}</strong> {{$saldo}}</h4></div>
                            </div>
                        </div>


                    </div>
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Cadastro de Propostas</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                        <div class="col-md-3">
                                <div class="form-group">
                                    <label>Data de embarque </label>
                                    <input type="text" name="dt_embarque" id="dt_embarque" value="" class="form-control datepicker">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Setor de atividades</label>
                                    <select class="form-control  selectpicker" data-live-search="true" name="id_setor" id="id_setor" data-container="body">
                                        <option value="0">Selecione</option>
                                        @foreach( $setores as $setor)
                                            @php
                                                $attr_restricao = (in_array($setor->ID_SETOR, $lista_restricoes)) ? "data-idrestricao=1" : "data-idrestricao=0";
                                            @endphp
                                            <option {{$attr_restricao}} value="{{$setor->ID_SETOR}}">{{$setor->NM_SETOR}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo de financiamento</label>
                                    <select class="form-control input-sm" name="id_cliente_exportadores_modalidade" id="id_cliente_exportadores_modalidade">
                                        <option value="0">Selecione</option>
                                        @foreach( $rs_modalidade_financiamento as $modalidade_financiamento)
                                            <option value="{{$modalidade_financiamento->ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO}}#{{$modalidade_financiamento->ID_MODALIDADE}}#{{$modalidade_financiamento->ID_MODALIDADE_FINANCIAMENTO}}">{{$modalidade_financiamento->NO_MODALIDADE_FINANCIAMENTO}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div id="aceite" class="col-md-2">
                                <label>Aceite Títulos de Crédito</label>
                                <select class="form-control input-sm" name="in_aceite" id="in_aceite">
                                    <option value="">Selecione</option>
                                    <option value="SIM">SIM</option>
                                    <option value="NAO">NÃO</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div id="downpayment" class="col-md-3">
                                <div class="form-group">
                                    <label>% Down Payment</label>
                                    <input type="text" name="va_percentual_dw_payment" id="va_percentual_dw_payment" value="" class="form-control money" maxlength="5">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Valor da proposta {{$operacao->RetornaMoeda->SIGLA_MOEDA}}</label>
                                    <input type="text" name="vl_proposta" id="vl_proposta" value="" class="form-control money">
                                </div>
                            </div>
                            <div class="col-md-2" id="prazo_dias_pre">
                                <div class="form-group">
                                    <label>Prazo pré (dias)</label>
                                    <input type="text" name="nu_prazo_pre" id="nu_prazo_pre" value="" class="form-control somentenumero prazo">
                                </div>
                            </div>
                            <div class="col-md-2" id="prazo_dias_pos">
                                <div class="form-group">
                                    <label>Prazo pós (dias)</label>
                                    <input type="text" name="nu_prazo_pos" id="nu_prazo_pos" value="" class="form-control somentenumero prazo">
                                </div>
                            </div>
                            
                        </div>

                        <div class="col-md-12" id="termo_setor_atividade" style="display: none">
                            <div class="form-group">
                                <input type="checkbox" name="in_aceite_restricoes" id="in_aceite_restricoes" value="S" checked="checked">
                                <label for="in_aceite_restricoes">Aceitar condições de restrições.</label>
                            </div>
                        </div>


                        <div class="row no-print">
                            <div class="col-xs-12">
                                <a href="#" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
                                @can('NOVA_PROPOSTA')
                                    <button disabled="disabled" id="btnCadastrar" name="btnCadastrar" type="button" class="btn btn-primary pull-right" style="margin-right: 5px;" @if ($is_validade) disabled="disabled" @endif >
                                        <i class="fa fa-save"></i> Cadastrar
                                    </button>
                                @endcan
                                @can('PRECIFICACAO_SIMULACAO')
                                    <button id="btnSimular" name="btnSimular" type="button" class="btn btn-success pull-right" style="margin-right: 5px;" >
                                        <i class="fa fa-calculator"></i> Simular precificação
                                    </button>
                                @endcan

                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
      </div>
    </section>
  </div>

    <!-- Modal -->
    <div class="modal fade" id="nova-precificacao-simulacao" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Simulacão de Precificação</h5>
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

    <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}?<?=time();?>">
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}?<?=time();?>"></script>
    <script src="{{ asset('js/proposta/funcoes_proposta.js') }}?<?=time();?>"></script>
    <script>
        $(document).ready(function () {

           $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $('.datepicker').datepicker({
                startDate: '0d',
                endDate:'+15d',
                datesDisabled: '+15d',
            });

        })
    </script>

@endsection
