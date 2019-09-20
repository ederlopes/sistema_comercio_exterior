@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

            <div class="col-md-2">
                @include('layouts.menu_abgf')
            </div>

            <div class="col-md-10">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Dados da Operação</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12">

                            <h2 class="page-header">
                                <i class="fa fa-globe"></i> MPME: {{$operacao->RAZAO_SOCIAL}}

                            </h2>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Codigo da Operação</label><br>
                                        <span>{{$operacao->ID_OPER}}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>País</label><br>
                                        <span>{{$operacao->RetornaPaisImportadorOperacao['NM_PAIS']}}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Dt. Concessão do crédito</label><br>
                                        <span>{{ date('d/m/Y', strtotime($operacao->RetornaCreditoConcedido['DT_APROVACAO'])) }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Nome do Banco Financiador:</label><br>
                                        <span>@if(isset($operacao->usuario->RetornaBancoFinanciador[0]['NM_USUARIO'])){{$operacao->usuario->RetornaBancoFinanciador[0]['NM_USUARIO']}}@endif - GECEX: @if(isset($operacao->usuario->RetornaBancoFinanciador[0]['NO_GECEX'])){{$operacao->usuario->RetornaBancoFinanciador[0]['NO_GECEX']}}@endif</span>
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Nº Fatura Comercial</label><br>
                                        <span>{{$embarque->NU_FATURA ?? ''}}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Nº Re ou RVS</label><br>
                                        <span>{{$operacao->ControleExportacao('NU_RE')}} @if($operacao->ControleExportacao('NU_RVS') != 0) {{$operacao->ControleExportacao('NU_RVS')}} @endif</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Dt. Vencimento</label><br>
                                        <span>
                            @if($operacao->ControleExportacao('DT_FATURA'))
                                                {{ date('d/m/Y', strtotime($operacao->ControleExportacao('DT_FATURA'))) }}
                                                <input type="hidden" name="dtVencimentoFatura" id="dtVencimento"
                                                       value="{{ date('d/m/Y', strtotime($operacao->ControleExportacao('DT_FATURA'))) }}"/>
                                            @else
                                                {{"-"}}
                                            @endif
                   </span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Moeda</label><br>
                                        <span>{{$operacao->usuario->RetornaMoedaUsuario[0]->SIGLA_MOEDA}}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Valor Concretizado</label><br>
                                        <span>{{number_format($operacao->RetornaGruPrecoCobertura['VL_CONCRETIZADO'], 2, ',', '.') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Valor do Financ./concessão do Crédito</label><br>
                                        <span>{{number_format($operacao->ControleExportacao('VL_CREDITO'), 2, ',', '.') }}
                                            <?php $creditoDevedor = $operacao->ControleExportacao('VL_CREDITO'); ?></span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Fase</label><br>
                                        <span>
                    {{$operacao->FaseOperacao()}}
                  </span>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status</label><br>
                                        <span>
                  {{$operacao->RetornaStatusOperacao($operacao->ControleExportacao('DT_ENVIO'), $operacao->ID_OPER)}}
                  </span>
                                    </div>
                                </div>


                            </div>

                            <a href="#" class="btn btn-primary" data-toggle="modal" data-target="#modal_precificacao">VISUALIZAR
                                PRECIFICAÇÃO</a>

                        </div>
                    </div>
                </div>

                <form action="{{Route('sinistro.salvar')}}" method="post" enctype="multipart/form-data">
                    {!! csrf_field() !!}
                    <input type="hidden" name="id_sinistro" value="{{$sinistro->ID_MPME_SINISTRO ?? ''}}">
                    <input type="hidden" name="ID_OPER" value="{{Request::segment(4)}}">
                    <input type="hidden" name="ID_PROPOSTA" value="{{Request::segment(5)}}">
                    <input type="hidden" name="ID_FINANCIADOR"
                           value="{{($operacao->usuario->FinanciadorPos->ID_USUARIO_FINANCIADOR_FK ?? '' != '') ? $operacao->usuario->FinanciadorPos->ID_USUARIO_FINANCIADOR_FK : $operacao->usuario->FinanciadorPre->ID_USUARIO_FINANCIADOR_FK }}">


                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Ameaça de Sinistro</h3>
                        </div>
                        <div class="panel-body">

                            <div class="row">
                                <!-- Abre col-md-4 -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data da Declaração de Ameaça de Sinistro</label><br>
                                        <div class='input-group date datetimepicker4'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                            <input type="text" id="DT_DAS" class="form-control input-sm datetimepicker4"
                                                   name="DT_DAS"
                                                   value="@if(isset($sinistro->DT_DAS)){{\Carbon\Carbon::parse($sinistro->DT_DAS)->format('d/m/Y') }}@endif"
                                                   placeholder="Data da declaração de ameaça de sinistro">
                                        </div>
                                    </div>
                                </div> <!-- Fecha col-md-4 -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data de caracterização do sinistro</label><br>
                                        <div class='input-group date datetimepicker4'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                            <input type="text" id="DT_CARACTERIZACAO_SINISTRO"
                                                   class="form-control input-sm datetimepicker4"
                                                   name="DT_CARACTERIZACAO_SINISTRO"
                                                   value="@if(isset($sinistro->DT_CARACTERIZACAO_SINISTRO)){{\Carbon\Carbon::parse($sinistro->DT_CARACTERIZACAO_SINISTRO)->format('d/m/Y') }}@endif"
                                                   placeholder="Data de caracterização do sinistro">
                                        </div>
                                    </div>
                                </div> <!-- Fecha col-md-4 -->

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Upload do DAS</label><br>
                                        <div class="fileinput fileinput-new" data-provides="fileinput">
                                            <span class="btn btn-default btn-file"><span class="fileinput-new">Selecionar arquivo</span><span
                                                        class="fileinput-exists"><span
                                                            class="fileinput-filename"></span></span><input type="file"
                                                                                                            id="arquivo"
                                                                                                            class="form-control"
                                                                                                            name="arquivo"></span>
                                            <a href="#" class="fileinput-exists btn btn-danger" data-dismiss="fileinput"
                                               style="float: none">&times;</a>
                                        </div>
                                    </div>
                                </div> <!-- Fecha col-md-4 -->

                            </div>
                            <!-- fecha row -->
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="check01">Fato gerador do sinistro</label><br>
                                        <select class="form-control" name="ID_FATO_GERADOR_SINISTRO">
                                            <option @if($sinistro == '')  selected @endif value="">Selecione</option>
                                            @foreach($fatoGeradorSinistro as $fato)
                                                <option value="{{$fato->ID_FATO_GERADOR_SINISTRO}}"
                                                        @if($sinistro != '' && $fato->ID_FATO_GERADOR_SINISTRO == $sinistro->ID_FATO_GERADOR_SINISTRO) selected @endif> {{$fato->NO_FATO_GERADOR_SINISTRO}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div> <!-- Fecha col-md-3 -->

                                <!-- Abre col-md-4 -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data de envio da Carta de Cobrança</label><br>
                                        <div class='input-group date datetimepicker4'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                            <input type="text" id="DT_ENVIO_CARTA_COBRANCA"
                                                   class="form-control input-sm datetimepicker4"
                                                   name="DT_ENVIO_CARTA_COBRANCA"
                                                   value="@if(isset($sinistro->DT_ENVIO_CARTA_COBRANCA)){{\Carbon\Carbon::parse($sinistro->DT_ENVIO_CARTA_COBRANCA)->format('d/m/Y') }}@endif"
                                                   placeholder="Data de envio da carta de cobrança">
                                        </div>
                                    </div>
                                </div> <!-- Fecha col-md-4 -->

                                <!-- Abre col-md-4 -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Data do DS/PI.</label><br>
                                        <div class='input-group date datetimepicker4'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                            <input type="text" id="DT_ENVIO_DS_PI"
                                                   class="form-control input-sm datetimepicker4" name="DT_ENVIO_DS_PI"
                                                   value="@if(isset($sinistro->DT_ENVIO_DS_PI)){{\Carbon\Carbon::parse($sinistro->DT_ENVIO_DS_PI)->format('d/m/Y') }}@endif"
                                                   placeholder="Data do DS/PI">
                                        </div>
                                    </div>
                                </div> <!-- Fecha col-md-4 -->

                            </div>
                            <!-- fecha row -->

                            <div class="IN_CANCELAMENTO">
                                <div class="alert alert-info">
                                    <label for="IN_CANCELAMENTO">
                                        <input type="checkbox" name="IN_CANCELAMENTO" id="IN_CANCELAMENTO" value="S"
                                               data-toggle="collapse" data-target="#COL_IN_CANCELAMENTO"/> Houve
                                        cancelamento da ameaça de sinistro?
                                    </label>
                                </div>
                                <div class="collapse" id="COL_IN_CANCELAMENTO">
                                    <div class="well">
                                        <div class="row">
                                            <!-- Abre col-md-3 -->
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="check01">Motivo do cancelamento</label><br>
                                                    <select class="form-control dt_dc_sinistro"
                                                            name="ID_MOTIVO_CANCELAMENTO_DAS"
                                                            id="ID_MOTIVO_CANCELAMENTO_DAS">
                                                        <option @if($sinistro == '') selected @endif value="">
                                                            Selecione
                                                        </option>
                                                        @foreach($motivoCancelamento as $cancelamento)
                                                            <option value="{{$cancelamento->ID_MOTIVO_CANCELAMENTO_DAS}}"
                                                                    @if($sinistro != '' && $cancelamento->ID_MOTIVO_CANCELAMENTO_DAS == $sinistro->ID_MOTIVO_CANCELAMENTO_DAS) selected @endif> {{$cancelamento->NO_MOTIVO_CANCELAMENTO_DAS}}</option>
                                                        @endforeach
                                                        <option value="Outros">Outros</option>
                                                    </select>

                                                </div>
                                            </div> <!-- Fecha col-md-3 -->

                                            <!-- Abre col-md-3 -->
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="check01">Data de cancelamento da Declaração de Ameaça de
                                                        Sinistro</label><br>
                                                    <div class='input-group date datetimepicker4'>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>
                                                        <input type="text" id="DT_CANCELAMENTO_DAS"
                                                               class="form-control input-sm dt_dc_sinistro datetimepicker4"
                                                               name="DT_CANCELAMENTO_DAS"
                                                               value="@if(isset($sinistro->DT_CANCELAMENTO_DAS)){{\Carbon\Carbon::parse($sinistro->DT_CANCELAMENTO_DAS)->format('d/m/Y') }}@endif"
                                                               placeholder="Data de cancelamento da Declaração de Ameaça de Sinistro">
                                                    </div>
                                                </div>
                                            </div> <!-- Fecha col-md-3 -->

                                        </div>
                                        <!-- fecha row -->
                                    </div>
                                    <!-- fecha well -->
                                </div> <!-- fecha collapse -->
                            </div> <!-- fecha IN_CANCELAMENTO -->

                            <div class="IN_PAGTO_ATRASO">
                                <div class="alert alert-info">
                                    <label for="IN_PAGTO_ATRASO">
                                        <input type="checkbox" name="IN_PAGTO_ATRASO" id="IN_PAGTO_ATRASO" value="S"
                                               data-toggle="collapse" data-target="#COL_IN_PAGTO_ATRASO"/> Houve
                                        pagamento em atraso?
                                    </label>
                                </div>
                                <div class="collapse" id="COL_IN_PAGTO_ATRASO">
                                    <div class="well">

                                        @if(isset($sinistro))
                                            @forelse($sinistro->RetornaPagamentoEmAtraso as $pgtAtraso)
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="check01">Data do pagamento</label><br>
                                                            <div class='input-group date datetimepicker4'>
                                        <span class="input-group-addon">
                                            <span class="glyphicon glyphicon-calendar"></span>
                                        </span>

                                                                <input type="text" id="DTPGT1"
                                                                       class="form-control input-sm datetimepicker4 DTPGT"
                                                                       name="DTPGT[]"
                                                                       value="@if(isset($pgtAtraso->DT_PAGAMENTO_EM_ATRASO_SINISTRO)){{\Carbon\Carbon::parse($pgtAtraso->DT_PAGAMENTO_EM_ATRASO_SINISTRO)->format('d/m/Y') }}@endif"
                                                                       placeholder="Data do pagamento">

                                                            </div>
                                                        </div>
                                                    </div> <!-- Fecha col-md-4 -->

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="check01">Montante</label><br>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">$</span>
                                                                <input type="text" id="text01"
                                                                       class="form-control input-sm money"
                                                                       name="VLPGT[]"
                                                                       value="@if(isset($pgtAtraso->VA_PAGAMENTO_EM_ATRASO_SINISTRO)){{number_format($pgtAtraso->VA_PAGAMENTO_EM_ATRASO_SINISTRO, 2, ',', '.')}}@endif"
                                                                       placeholder="Montante">
                                                            </div>

                                                        </div>
                                                    </div> <!-- Fecha col-md-4 -->

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label><br>
                                                            <a href="#" class="btn btn-success">ADICIONAR</a>
                                                            <a href="#" class="btn btn-danger">REMOVER</a>
                                                        </div>
                                                    </div> <!-- Fecha col-md-4 -->

                                                </div>
                                                <!-- fecha row -->
                                            @empty

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="check01">Data do pagamento</label><br>
                                                            <div class='input-group date datetimepicker4'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>

                                                                <input type="text" id="DTPGT1"
                                                                       class="form-control input-sm datetimepicker4 DTPGT"
                                                                       name="DTPGT[]"
                                                                       value="@if(isset($pgtAtraso->DT_PAGAMENTO_EM_ATRASO_SINISTRO)){{\Carbon\Carbon::parse($pgtAtraso->DT_PAGAMENTO_EM_ATRASO_SINISTRO)->format('d/m/Y') }}@endif"
                                                                       placeholder="Data do pagamento">

                                                            </div>
                                                        </div>
                                                    </div> <!-- Fecha col-md-4 -->

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="check01">Montante</label><br>
                                                            <div class="input-group">
                                                                <span class="input-group-addon">$</span>
                                                                <input type="text" id="text01"
                                                                       class="form-control input-sm money"
                                                                       name="VLPGT[]"
                                                                       value="@if(isset($pgtAtraso->VA_PAGAMENTO_EM_ATRASO_SINISTRO)){{number_format($pgtAtraso->VA_PAGAMENTO_EM_ATRASO_SINISTRO, 2, ',', '.')}}@endif"
                                                                       placeholder="Montante">
                                                            </div>

                                                        </div>
                                                    </div> <!-- Fecha col-md-4 -->

                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label>&nbsp;</label><br>
                                                            <a href="#" class="btn btn-success">ADICIONAR</a>
                                                            <a href="#" class="btn btn-danger">REMOVER</a>
                                                        </div>
                                                    </div> <!-- Fecha col-md-4 -->

                                                </div>
                                                <!-- fecha row -->


                                            @endforelse
                                        @else


                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="check01">Data do pagamento</label><br>
                                                        <div class='input-group date datetimepicker4'>
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>

                                                            <input type="text" id="DTPGT1"
                                                                   class="form-control input-sm datetimepicker4 DTPGT"
                                                                   name="DTPGT[]"
                                                                   value="@if(isset($pgtAtraso->DT_PAGAMENTO_EM_ATRASO_SINISTRO)){{\Carbon\Carbon::parse($pgtAtraso->DT_PAGAMENTO_EM_ATRASO_SINISTRO)->format('d/m/Y') }}@endif"
                                                                   placeholder="Data do pagamento">

                                                        </div>
                                                    </div>
                                                </div> <!-- Fecha col-md-4 -->

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label for="check01">Montante</label><br>
                                                        <div class="input-group">
                                                            <span class="input-group-addon">$</span>
                                                            <input type="text" id="text01"
                                                                   class="form-control input-sm money" name="VLPGT[]"
                                                                   value="@if(isset($pgtAtraso->VA_PAGAMENTO_EM_ATRASO_SINISTRO)){{number_format($pgtAtraso->VA_PAGAMENTO_EM_ATRASO_SINISTRO, 2, ',', '.')}}@endif"
                                                                   placeholder="Montante">
                                                        </div>

                                                    </div>
                                                </div> <!-- Fecha col-md-4 -->

                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label><br>
                                                        <a href="#" class="btn btn-success">ADICIONAR</a>
                                                        <a href="#" class="btn btn-danger">REMOVER</a>
                                                    </div>
                                                </div> <!-- Fecha col-md-4 -->

                                            </div>
                                            <!-- fecha row -->



                                        @endif

                                    </div>
                                    <!-- fecha well -->
                                </div> <!-- fecha collapse -->
                            </div> <!-- fecha IN_PAGTO_ATRASO -->

                            <div class="IN_REGULACAO">
                                <div class="alert alert-info">
                                    <label for="IN_REGULACAO">
                                        <input type="checkbox" name="IN_REGULACAO" id="IN_REGULACAO" value="S"
                                               data-toggle="collapse" data-target="#COL_IN_REGULACAO"/> Houve regulação
                                        do sinistro?
                                    </label>
                                </div>
                                <div class="collapse" id="COL_IN_REGULACAO">
                                    <div class="well">
                                        <div class="row">

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="check01">Decisão</label><br>
                                                    <select class="form-control" name="ID_REGULACAO_SINISTRO"
                                                            id="ID_REGULACAO_SINISTRO">
                                                        <option @if($sinistro == '')  selected @endif value="">
                                                            Selecione
                                                        </option>
                                                        @foreach($regulacaoSinistro as $regulacao)
                                                            <option value="{{$regulacao->ID_REGULACAO_SINISTRO}}"
                                                                    @if($sinistro != '' && $regulacao->ID_REGULACAO_SINISTRO == $sinistro->ID_REGULACAO_SINISTRO) selected @endif> {{$regulacao->NO_REGULACAO_SINISTRO}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> <!-- Fecha col-md-3 -->

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Data da Regulação do Sinistro</label><br>
                                                    <div class='input-group date datetimepicker4'>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                        <input type="text" id="DT_REGULACAO_SINISTRO"
                                                               class="form-control input-sm datetimepicker4"
                                                               name="DT_REGULACAO_SINISTRO"
                                                               value="@if(isset($sinistro->DT_REGULACAO_SINISTRO)){{\Carbon\Carbon::parse($sinistro->DT_REGULACAO_SINISTRO)->format('d/m/Y') }}@endif"
                                                               placeholder="Data da Regulação do Sinistro">
                                                    </div>
                                                </div>
                                            </div> <!-- Fecha col-md-3 -->

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>Data de Revogação do Sinistro</label><br>
                                                    <div class='input-group date datetimepicker4'>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                        <input type="text" id="DT_REVOGACAO_SINISTRO"
                                                               class="form-control input-sm datetimepicker4"
                                                               name="DT_REVOGACAO_SINISTRO"
                                                               value="@if(isset($sinistro->DT_REVOGACAO_SINISTRO)){{\Carbon\Carbon::parse($sinistro->DT_REVOGACAO_SINISTRO)->format('d/m/Y') }}@endif"
                                                               placeholder="Data de Revogação do Sinistro">

                                                    </div>
                                                </div>
                                            </div> <!-- Fecha col-md-3 -->

                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="check01">Motivo da revogação</label><br>
                                                    <select class="form-control" name="ID_MOTIVO_REVOGACAO_SINISTRO">
                                                        <option @if($sinistro == '') selected @endif value="">
                                                            Selecione
                                                        </option>
                                                        @foreach($motivoRevogacao as $revogacao)
                                                            <option value="{{$revogacao->ID_MOTIVO_REVOGACAO_SINISTRO}}"
                                                                    @if($sinistro != '' && $revogacao->ID_MOTIVO_REVOGACAO_SINISTRO == $sinistro->ID_MOTIVO_REVOGACAO_SINISTRO) selected @endif> {{$revogacao->NO_MOTIVO_REVOGACAO_SINISTRO}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> <!-- Fecha col-md-3 -->

                                        </div>
                                        <!-- fecha row -->
                                    </div>
                                    <!-- fecha well -->
                                </div> <!-- fecha collapse -->
                            </div> <!-- fecha IN_REGULACAO -->

                            <div class="IN_INDENIZACAO">
                                <div class="alert alert-info">
                                    <label for="IN_INDENIZACAO">
                                        <input type="checkbox" name="IN_INDENIZACAO" id="IN_INDENIZACAO" value="S"
                                               data-toggle="collapse" data-target="#COL_IN_INDENIZACAO"/> Houve
                                        indenização?
                                    </label>
                                </div>
                                <div class="collapse" id="COL_IN_INDENIZACAO">
                                    <div class="well">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Data do comunicado de indenização</label><br>
                                                    <div class='input-group date datetimepicker4'>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                        <input type="text" id="DT_ENVIO_COMUNICADO_GESTOR"
                                                               class="form-control input-sm datetimepicker4"
                                                               name="DT_ENVIO_COMUNICADO_GESTOR"
                                                               value="@if(isset($sinistro->DT_ENVIO_COMUNICADO_GESTOR)){{\Carbon\Carbon::parse($sinistro->DT_ENVIO_COMUNICADO_GESTOR)->format('d/m/Y') }}@endif"
                                                               placeholder="Data do comunicado de indenização">

                                                    </div>
                                                </div>
                                            </div> <!-- Fecha col-md-4 -->

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Data de pagto. da indenização</label><br>
                                                    <div class='input-group date datetimepicker4'>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                        <input type="text" id="DT_PAGAMENTO_INDENIZACAO"
                                                               class="form-control input-sm datetimepicker4"
                                                               name="DT_PAGAMENTO_INDENIZACAO"
                                                               value="@if(isset($sinistro->DT_PAGAMENTO_INDENIZACAO)){{\Carbon\Carbon::parse($sinistro->DT_PAGAMENTO_INDENIZACAO)->format('d/m/Y') }}@endif"
                                                               placeholder="Data de pagto. da indenização">

                                                    </div>
                                                </div>
                                            </div> <!-- Fecha col-md-4 -->

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Valor indenizado</label><br>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" id="VA_PAGAMENTO_INDENIZACAO"
                                                               class="form-control input-sm money"
                                                               name="VA_PAGAMENTO_INDENIZACAO"
                                                               value="@if(isset($sinistro->VA_PAGAMENTO_INDENIZACAO)) {{number_format($sinistro->VA_PAGAMENTO_INDENIZACAO, 2, ',', '.')}}@endif"
                                                               placeholder="Valor indenizado">
                                                    </div>

                                                </div>
                                            </div> <!-- Fecha col-md-4 -->

                                        </div>
                                        <!-- fecha row -->
                                    </div>
                                    <!-- fecha well -->
                                </div> <!-- fecha collapse -->
                            </div> <!-- fecha IN_INDENIZACAO -->

                            <div class="IN_RENEGOCIACAO">
                                <div class="alert alert-info">
                                    <label for="IN_RENEGOCIACAO">
                                        <input type="checkbox" name="IN_RENEGOCIACAO" id="IN_RENEGOCIACAO" value="S"
                                               data-toggle="collapse" data-target="#COL_IN_RENEGOCIACAO"/> Houve
                                        renegociação do contrato?
                                    </label>
                                </div>
                                <div class="collapse" id="COL_IN_RENEGOCIACAO">
                                    <div class="well">
                                        <div class="row">

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Data de assinatura da renegociação</label><br>
                                                    <div class='input-group date datetimepicker4'>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                                        <input type="text" id="DT_ASSINATURA_CONTRATO_RENEGOCIACAO"
                                                               class="form-control input-sm datetimepicker4"
                                                               name="DT_ASSINATURA_CONTRATO_RENEGOCIACAO"
                                                               value="@if(isset($sinistro->DT_ASSINATURA_CONTRATO_RENEGOCIACAO)){{\Carbon\Carbon::parse($sinistro->DT_ASSINATURA_CONTRATO_RENEGOCIACAO)->format('d/m/Y') }}@endif"
                                                               placeholder="Data de assinatura da renegociação">

                                                        <input type="hidden" name="recuperada"
                                                               value="@if(isset($sinistro)){{number_format($sinistro->VA_REPACTUACAO_RENEGOCIACAO, 2, ',', '.')}}@endif">
                                                        <input type="hidden" name="harecuperar"
                                                               value="{{number_format($creditoDevedor, 2, ',', '.')}}">


                                                    </div>
                                                </div>
                                            </div> <!-- Fecha col-md-4 -->

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Valor repactuado</label><br>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">$</span>
                                                        <input type="text" id="VA_REPACTUACAO_RENEGOCIACAO"
                                                               class="form-control input-sm money"
                                                               name="VA_REPACTUACAO_RENEGOCIACAO"
                                                               value="@if(isset($sinistro->VA_REPACTUACAO_RENEGOCIACAO)){{number_format($sinistro->VA_REPACTUACAO_RENEGOCIACAO, 2, ',', '.')}}@endif"
                                                               placeholder="Valor repactuado">
                                                    </div>

                                                </div>
                                            </div> <!-- Fecha col-md-4 -->

                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Nº de parcelas</label><br>
                                                    <div class="input-group">
                                                        <span class="input-group-addon">Nº</span>
                                                        <input type="number" id="NU_PARCELA_REPACTUACAO_RENEGOCIACAO"
                                                               class="form-control input-sm"
                                                               name="NU_PARCELA_REPACTUACAO_RENEGOCIACAO"
                                                               value="{{trim($sinistro->NU_PARCELA_REPACTUACAO_RENEGOCIACAO ?? '') }}"
                                                               placeholder="Nº de parcelas">
                                                    </div>

                                                </div>
                                            </div> <!-- Fecha col-md-4 -->

                                        </div>
                                        <!-- fecha row -->
                                    </div>
                                    <!-- fecha well -->
                                </div> <!-- fecha collapse -->
                            </div> <!-- fecha IN_RENEGOCIACAO -->

                        </div> <!-- fecha panel-body -->
                    </div>

                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Recuperação</h3>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="bs-callout bs-callout-primary">
                                        <label>Valor a
                                            recuperar:</label> @if($sinistro->RetornaRecuperacaoSinistro ?? '' != '')
                                            $ {{formatar_moeda($sinistro->RetornaRecuperacaoSinistro->sum('VA_PRINCIPAL_RECUPERACAO_SINISTRO')) }} @endif
                                    </div>
                                </div> <!-- Fecha col-md-4 -->

                                <div class="col-md-4">
                                    <div class="bs-callout bs-callout-primary">
                                        <label>Valor recuperado:</label> R$ 0,00
                                    </div>
                                </div> <!-- Fecha col-md-4 -->

                                <div class="col-md-4">
                                    <div class="bs-callout bs-callout-primary">
                                        <label>Saldo devedor:</label> R$ 0,00
                                    </div>
                                </div> <!-- Fecha col-md-4 -->
                            </div>
                            <!-- fecha row -->
                            <br/>
                            <table class="table table-striped table-bordered" id="tdsRecuperacao">
                                <tr>
                                    <th width="15%">Data de vencimento</th>
                                    <th width="15%">Principal</th>
                                    <th width="10%">Juros</th>
                                    <th width="15%">Data efetiva</th>
                                    <th width="15%">Valor pago</th>
                                    <th width="25%">Observações</th>
                                    <th width="5%">#</th>
                                </tr>

                                @if($sinistro != '')
                                    @forelse($sinistro->RetornaRecuperacaoSinistro as $recuperacaoSinistro)
                                        <tr>
                                            <td>
                                                @if($recuperacaoSinistro->DT_PREVISTA_RECUPERACAO_SINISTRO){{\Carbon\Carbon::parse($recuperacaoSinistro->DT_PREVISTA_RECUPERACAO_SINISTRO)->format('d/m/Y')}}@endif
                                            </td>

                                            <td>
                                                @if($recuperacaoSinistro->VA_PRINCIPAL_RECUPERACAO_SINISTRO != 0.00){{number_format($recuperacaoSinistro->VA_PRINCIPAL_RECUPERACAO_SINISTRO, 2, ',', '.')}}@endif
                                            </td>

                                            <td>
                                                @if($recuperacaoSinistro->VA_JUROS_RECUPERACAO_SINISTRO){{number_format($recuperacaoSinistro->VA_JUROS_RECUPERACAO_SINISTRO, 2, ',', '.')}}@endif
                                            </td>

                                            <td>
                                                @if($recuperacaoSinistro->DT_EFETIVA_RECUPERACAO_SINISTRO){{\Carbon\Carbon::parse($recuperacaoSinistro->DT_EFETIVA_RECUPERACAO_SINISTRO)->format('d/m/Y')}}@endif
                                            </td>

                                            <td>
                                                @if($recuperacaoSinistro->VA_PAGO_RECUPERACAO_SINISTRO){{number_format($recuperacaoSinistro->VA_PAGO_RECUPERACAO_SINISTRO, 2, ',', '.')}}@endif
                                            </td>
                                            <td>
                                                {{$recuperacaoSinistro->DE_OBSERVACAO_RECUPERACAO_SINISTRO}}
                                            </td>
                                            <td>
                                                <button class="btn btn-danger btn-sm removerTrRecumentacao">X</button>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" id="recuperacaoVazia">
                                                <div class="alert alert-info" style="margin-bottom:0">Nenhuma
                                                    recuperação foi inserida.
                                                </div>
                                            </td>
                                        </tr>
                                    @endif
                                    @endforelse
                            </table>
                            <a href="#" class="btn btn-success" data-toggle="modal" data-target="#modal_recuperacao">ADICIONAR
                                RECUPERAÇÃO</a>


                        </div> <!-- fecha panel-body -->

                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-md-6 text-left"><a href="javascrit:history.go(-1);"
                                                                   class="btn btn-default text-left"><i
                                                class="fa fa-arrow-circle-o-left"></i> Voltar</a></div>
                                <div class="col-md-6 text-right">
                                    <button type="submit" class="btn btn-success text-right" id="salvar"><i
                                                class="fa fa-save"></i> Salvar
                                    </button>
                                </div>
                            </div>


                        </div>


                </form>

            </div>
        </div>


        <!-- modais -->

        <!-- Modal -->
        <div class="modal fade modalLoading" id="modal_precificacao" tabindex="-1" role="dialog"
             aria-labelledby="ModalPrecificacao">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="ModalPrecificacao">Precificação</h4>
                    </div>
                    <div class="loading">
                        <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                    </div>
                    <div class="modal-body">
                        <div class="alert alert-success alert-valores">
                            <strong>Percentual de Cobertura</strong>
                            <br> <br>
                            <p><b>RP: 95 %</b></p>
                            <p><b>RC: 90 %</b></p>
                        </div>
                        <div class="alert alert-info">
                            <strong>Valor Coberto</strong>
                            <br> <br>
                            <p><b>RP: 63.214,14</b></p>
                            <p><b>RC: 59.887,08</b></p>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade modalLoading" id="modal_recuperacao" tabindex="-1" role="dialog"
             aria-labelledby="ModalRecuperacao">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="ModalRecuperacao">Recuperação</h4>
                    </div>
                    <div class="loading">
                        <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                    </div>
                    <div class="modal-body">

                        <div class="row">

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Data de vencimento</label><br>
                                    <div class="input-group date datetimepicker4">
                                    <span class="input-group-addon">
                                          <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                        <input type="text" id="modal_dt_prevista"
                                               class="form-control input-sm datetimepicker4" name="modal_dt_prevista"
                                               value="" placeholder="Data de Vencimento">

                                    </div>
                                </div>
                            </div> <!-- Fecha col-md-12 -->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Principal</label><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" id="modal_vl_principal" class="form-control input-sm money"
                                               name="modal_vl_principal" placeholder="Valor principal">
                                    </div>
                                </div>
                            </div> <!-- Fecha col-md-12 -->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Juros</label><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" id="modal_juros" class="form-control input-sm money"
                                               name="modal_juros" placeholder="Valor do Juros">
                                    </div>
                                </div>
                            </div> <!-- Fecha col-md-12 -->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Data efetiva</label><br>
                                    <div class="input-group date datetimepicker4">
                                <span class="input-group-addon">
                                    <span class="glyphicon glyphicon-calendar"></span>
                                </span>
                                        <input type="text" id="modal_dt_efetiva"
                                               class="form-control input-sm datetimepicker4 " name="modal_dt_efetiva"
                                               value="" placeholder="Data Efetiva">
                                    </div>
                                </div>
                            </div> <!-- Fecha col-md-12 -->
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Valor pago</label><br>
                                    <div class="input-group">
                                        <span class="input-group-addon">$</span>
                                        <input type="text" id="modal_vlpago" class="form-control input-sm money"
                                               name="modal_vlpago" placeholder="Valor Pago">
                                    </div>
                                </div>

                            </div> <!-- Fecha col-md-12 -->
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observações</label><br>
                                    <textarea name="modal_obs" class="form-control" id="modal_obs" cols="30" rows="5"
                                              placeholder="Caso haja. Descreva alguma observação sobre esse lançamento"></textarea>
                                </div>
                            </div> <!-- Fecha col-md-12 -->


                        </div> <!-- fecha row -->


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary salvar">Salvar</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- fecha modais -->
        <script src="{{ asset('js/abgf/sinistro/funcoes_sinistrosce.js') }}?time={{time()}}"></script>
    </section>
@endsection
