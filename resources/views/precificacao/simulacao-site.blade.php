@extends('layouts.app-simulacao')

@section('content')
<form name="frmSimulacaoSite" id="frmSimulacaoSite" method="post">
    <div class="panel panel-default">
        <div class="panel-body">
            <div class="dashitem clearfix">
                <div class="page-header">
                    <h2>Simulação de precificação</h2>
                </div>
            </div>

           <div class="col-md-12">
               <div class="panel panel-default">
                   <div class="panel-heading">
                       <h3 class="panel-title">Dados do Exportador</h3>
                   </div>
                   <div class="panel-body">
                       <div class="row">
                           <div class="col-md-2">
                               <label>Modalidade:</label>
                               <select name="id_modalidade" id="id_modalidade" size="1" class="form-control">
                                   <option value="0">Selecione</option>
                                   @foreach($rs_modalidade as $modalidade)
                                    <option value="{{$modalidade->ID_MODALIDADE}}">{{$modalidade->NO_MODALIDADE}}</option>
                                   @endforeach
                               </select>
                           </div>
                           <div class="col-md-4">
                               <label>País exportador:</label>
                               <select name="id_pais_exportador" id="id_pais_exportador" size="1" class="form-control">
                                   <option value="28" disabled="disabled" selected="selected">Brasil</option>
                               </select>
                           </div>
                           <div class="col-md-4" id="div_importadores">
                               <label>País do importador:</label>
                               <select name="id_pais_importador" id="id_pais_importador" size="1" class="form-control selectpicker" data-live-search="true">
                                   <option value="">Selecione</option>
                                   @foreach($rs_paises_risco as $pais)
                                       <option value="{{$pais->ID_PAIS}}#{{$pais->CD_RISCO}}" data-risco="{{$pais->CD_RISCO}}" data-subtext="<b> - Risco ({{$pais->CD_RISCO}}/7)</b>">{{$pais->NM_PAIS}}</option>
                                   @endforeach
                               </select>
                           </div>
                           <div class="col-md-2">
                               <label>Enquadrada no Simples:</label>
                               <select id="enquadrado_simples" class="form-control" name="enquadrado_simples">
                                   <option value="">Selecione</option>
                                   <option value="SIM">SIM</option>
                                   <option value="NAO">NÃO</option>
                               </select>
                           </div>
                       </div>
                   </div>
               </div>
           </div>
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Dados da Exportação:</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Moeda</label>
                                    <select id="id_moeda" class="form-control" name="id_moeda">
                                        <option value="">Selecione</option>
                                        <option value="USD">USD</option>
                                        <option value="EUR">EUR</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Valor da proposta</label>
                                    <input type="text" name="vl_proposta" id="vl_proposta" value="" class="form-control money">
                                </div>
                            </div>
                            <div id="downpayment" class="col-md-2">
                                <div class="form-group">
                                    <label>% Down Payment</label>
                                    <input type="text" name="va_percentual_dw_payment" id="va_percentual_dw_payment" value="" class="form-control money" maxlength="5">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Valor financiado</label>
                                    <input type="text" name="vl_financiado" id="vl_financiado" value="" class="form-control" readonly="readonly">
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
                    </div>
                </div>
            </div>

            <div class="col-md-12" id="resultado_precificacao" style="display:none;" >
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Resultado da precificacao:</label>
                                <div class="loading">
                                    <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                                </div>
                                <div id="resultado">

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12"  >
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="javascript:window.print();" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
                                <button id="btnSimularSite" name="btnSimularSite" type="button" class="btn btn-success pull-right" style="margin-right: 5px;">
                                    <i class="fa fa-calculator"></i> Simular precificação
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12" id="div_importadores">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <label>Observações:</label>
                               <ul>
                                   <ol>
                                       <li>
                                           Na fase Pré-Embarque, a Tarifa de Análise de Crédito é cobrada na 1ª análise do devedor nacional, sendo válida por 12 meses.
                                       </li>
                                       <li>
                                           Na fase Pós-Embarque, a Tarifa de Análise de Crédito é cobrada na 1ª análise do devedor estrangeiro, sendo válida por 12 meses.
                                       </li>
                                       <li>
                                           Na fase Pré+Pós-Embarque, a Tarifa de Análise de Crédito é cobrada na 1ª análise do devedor nacional e do devedor estrangeiro, sendo válida por 12 meses.
                                       </li>
                                       <li>
                                           A Tarifa em questão deve ser paga pela MPME, à vista e previamente à análise da operação, por meio de boleto bancário emitido pela ABGF.
                                       </li>
                                   </ol>
                               </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="{{ asset('js/precificacao-simulacao/funcoes-simulacao-site.js') }}?<?=time();?>"></script>
        </div>
    </div>
</form>
@endsection
