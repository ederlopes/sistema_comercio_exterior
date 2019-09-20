@extends('layouts.app')

@section('content')

  
 <div class="Conteudo">
            <h1>CONTROLE DE SINISTROS <span class="MensagemTitulo"></span></h1>
            <div class="Form01">
                <div class="table-responsive">
                <table class="table borderless" style="width: 600px;">
                    @if(Session::has('success'))
                        <div class="alert alert-success">
                           <b> {{ Session::get('success') }} </b>
                        </div>
                    @endif
                        <tr>
                            <th  style="border: none">Código da MPME:</th>
                            <td  style="border: none">{{$exportador->ID_USUARIO}}</td>
                        </tr>
                        <tr>
                            <th  style="border: none">MPME:</th>
                            <td  style="border: none">{{$exportador->NM_USUARIO}}</td>
                        </tr>
                      
                        <tr>
                            <th  style="border: none">Modalidade:</th>
                            <td  style="border: none">@if($exportador->ID_MODALIDADE == 2) PRÉ+PÓS-EMBARQUE @endif @if($exportador->ID_MODALIDADE == 3) PÓS-EMBARQUE @endif</td>
                        </tr>
                </table>
                </div>
                <div class="table-responsive">
                   <table class="table  table-hover">
                    <thead>
                      <tr>
                        <th>Nº Operação</th>
                        <th>Devedor - Pós-Embarque</th>
                        <th>País</th>
                       	@if($exportador->ID_MODALIDADE == 3)
                        	<th>Dt. Envio do C. Exportação</th>
                        	<th>Dt. Aprovação</th>
                        @else
                        	<th>Dt. Concessão do crédito</th>
                        	<th>Dt. Envio do C. Exportação</th>
                        @endif
                        <th>Nº Fatura Comercial</th>
                        <th>Nº Re ou RVS</th>
                        <th>Dt. Vencimento</th>
                        <th>Moeda</th>
                        <th>Valor Concretizado</th>
                        <th>Valor do Financ./concessão do Crédito</th>
                          <th colspan="2" style="text-align: center;"><span style="border-bottom: 1px solid #333333;">% de Cobertura </span><br /><br />
                          <table width="100%">
                              <tr>
                                  <td style="text-align: center;">RP</td>
                                  <td style="text-align: center;">RC</td>
                              </tr>
                          </table>
                        </th>
                          <th colspan="2" style="text-align: center;"><span style="border-bottom: 1px solid #333333;">Valor Coberto </span><br /><br />
                            <table width="100%">
                                <tr>
                                    <td style="text-align: center;">RP</td>
                                    <td style="text-align: center;">RC</td>
                                </tr>
                            </table>
                        </th>
                        <th>Fase</th>
                        <th>Status</th>
                      </tr>
                    </thead>
                    <tbody>
                    @foreach($exportador->RetornaImportadoresEOperacoes as $operacao)
                        @if($operacao->VerificaConcretizada() && $operacao->FaseOperacao())
                      <tr class="active" onclick="document.location = '/cadastrar/sinistro/{{$operacao->ID_OPER}}';" style="cursor: pointer">
                        <td>{{$operacao->ID_OPER}}</td>
                        <td>{{$operacao->RAZAO_SOCIAL}}</td>
                        <td>{{$operacao->RetornaPaisImportadorOperacao['NM_PAIS']}}</td>
                        @if($exportador->ID_MODALIDADE == 3)
	                        <td>
                                @if($operacao->ControleExportacao('DT_ENVIO'))
                                    {{ date('d/m/Y', strtotime($operacao->ControleExportacao('DT_ENVIO'))) }}
                                @else
                                    {{"-"}}
                                @endif
                            </td>
	                        <td>{{ date('d/m/Y', strtotime($operacao->RetornaCreditoConcedido['DT_APROVACAO'])) }}</td>
                        @else
                        	<td>{{ date('d/m/Y', strtotime($operacao->RetornaCreditoConcedido['DT_APROVACAO'])) }}</td>
                        	<td>
                                @if($operacao->ControleExportacao('DT_ENVIO'))
                                    {{ date('d/m/Y', strtotime($operacao->ControleExportacao('DT_ENVIO'))) }}
                                @else
                                    {{"-"}}
                                @endif
                            </td>
	                    @endif
                        <td>{{$operacao->ControleExportacao('NU_FATURA')}}</td>
                        <td>{{$operacao->ControleExportacao('NU_RE')}} @if($operacao->ControleExportacao('NU_RVS') != 0) {{$operacao->ControleExportacao('NU_RVS')}} @endif</td>
                        <td>
                            @if($operacao->ControleExportacao('DT_FATURA'))
                                {{ date('d/m/Y', strtotime($operacao->ControleExportacao('DT_FATURA'))) }}
                            @else
                                {{"-"}}
                            @endif
                        </td>
                        <td>{{$exportador->RetornaMoedaUsuario[0]->SIGLA_MOEDA}}</td>
                        <td>{{number_format($operacao->RetornaGruPrecoCobertura['VL_CONCRETIZADO'], 2, ',', '.') }}</td>
                        <td>
                            {{number_format($operacao->ControleExportacao('VL_CREDITO'), 2, ',', '.') }}
					    </td>
                        <td>{{$operacao->RiscoPolitico()}}</td>
                        <td>90</td>
                        <td>{{ $operacao->CalculaValorCoberto('RP') }} </td>
                        <td>{{ $operacao->CalculaValorCoberto('RC') }} </td>


                          <td>{{$operacao->FaseOperacao()}}</td>
                        <td>{{$operacao->RetornaStatusOperacao($operacao->ControleExportacao('DT_ENVIO'), $operacao->ID_OPER)}}</td>

                      </tr>
                      @endif
                    @endforeach

                    </tbody>
                  </table>
                </div>
                    
                 <div id="IndexBotoes" class="Centro ClearFix">
                     <a class="btn btn-default" href="/home"><i class="glyphicon glyphicon-backward"></i> Voltar ao
                         menu
                     </a>
                     <a class="btn btn-default" href="#" onclick="window.history.go(-1); return false;"><i class="glyphicon glyphicon-step-backward"></i>
                         Voltar
                     </a>
                    <button type="button" class="btn btn-default"><i class="glyphicon glyphicon-print"></i> Imprimir</button>
                    
                </div>

            </div> <!-- Fim div Form01 -->

        </div>

@endsection
