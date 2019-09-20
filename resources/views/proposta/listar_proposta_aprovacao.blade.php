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
              @include('layouts.menu_abgf')
          </div>

           <div class="col-md-10">
              <form name="form-filtro" method="post" action="{{ Route('abgf.exportador.listarpropostas') }}" class="clearfix">
                  {{csrf_field()}}
                  @include('proposta.filtro_proposta')
              </form>
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
                                {{--<th>ID da operação</th>--}}
                                <th>Valor da proposta</th>
                                <th>Data do envio</th>
                                <th>Prazo para aprovação<br> SUSEP (15 dias) </th>
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
                                <tr>
                                    <td>{{formatar_codigo($proposta->ID_MPME_PROPOSTA)}}</td>
                                    <td>{{$proposta->COD_UNICO_OPERACAO ?? 'N/D'}}</td>
                                    {{--<td>{{formatar_codigo($proposta->ID_OPER)}}</td>--}}
                                    <td>{{$rs_proposta[0]->operacoes->RetornaMoeda->SIGLA_MOEDA}} {{formatar_valor_sem_moeda($proposta->VL_PROPOSTA)}} </td>
                                    <td>{{formatar_data_hora($proposta->DT_ENVIO)}}</td>
                                    <td>
                                        @if ( $proposta->DT_APROVACAO != "" )
                                            <div class="alert alert-success alert-valores alert-prazosusep"><strong>Aprovado: </strong> {{formatar_data_hora($proposta->DT_APROVACAO)}}</div>
                                        @elseif( $proposta->DT_CANCELAMENTO != "" )
                                            <div class="alert alert-danger alert-prazosusep"><strong>Cancelado: </strong> {{formatar_data_hora($proposta->DT_CANCELAMENTO)}}</div>
                                         @else
                                            @php
                                                $diff = $proposta->SALDO_DIAS;
                                            @endphp

                                            @if( $diff <= 15  && $diff > 10 )
                                                <div class="alert alert-success alert-valores alert-prazosusep"><strong>Faltam: </strong> {{$diff}} dias.</div>
                                            @endif
                                            @if( $diff <= 10  && $diff > 5 )
                                                <div class="alert alert-warning alert-prazosusep"><strong>Faltam: </strong> {{$diff}} dias.</div>
                                            @endif
                                            @if( $diff <= 5 )
                                                <div class="alert alert-danger alert-prazosusep"><strong>Faltam: </strong> {{$diff}} dias.</div>
                                            @endif
                                         @endif

                                    </td>
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
                                    <td>{{$proposta->mpme_status_proposta->NO_PROPOSTA}}</td>
                                    <td>
                                    @if(isset($proposta->mpme_preco_cobertura->PC_COB_TAXA_CARREGAMENTO) )
                                        <!-- Single button -->

                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                    @can('VIS_DADOS_OPERARACAO')
                                                    <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="dados_questionario_operacao" data-toggle="modal" data-target="#visualizar-dados-operacao">Dados da Operação</a></li>
                                                    @endcan
                                                    @can('VIS_DADOS_PROPOSTA')
                                                        <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="dados_questionario_operacao" data-toggle="modal" data-target="#visualizar-dados-proposta">Dados da Proposta</a></li>
                                                    @endcan
                                                    @if(in_array($proposta->ID_MPME_STATUS_PROPOSTA, [2,3]))
                                                        @can('APROVAR_PROPOSTA')
                                                            <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="aprovar_proposta">Aprovar proposta</a></li>
                                                        @endcan
                                                        @can('RECUSAR_PROPOSTA')
                                                            <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="recusar_proposta" data-toggle="modal" data-target="#recusar-proposta">Recusar proposta</a></li>
                                                        @endcan
                                                    @endif
                                                    <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="historico_proposta" data-toggle="modal" data-target="#historico_proposta">Histórico de aprovação</a></li>

                                                    @if(is_object($proposta->mpme_arquivo_boleto) == true)
                                                        <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-noarquivo="{{$proposta->mpme_arquivo_boleto->NO_ARQUIVO}}" data-idmpmearquivo="{{$proposta->mpme_arquivo_boleto->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo" class="novo-arquivo">Download boleto do prêmio</a></li>
                                                    @else
                                                      @if(in_array($proposta->ID_MPME_STATUS_PROPOSTA, [8]))
                                                           @can('UPLOAD_PREMIO')
                                                                <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" data-idmpmetipoarquivo="7" data-extensoes="pdf" data-idflex="{{$proposta->ID_MPME_PROPOSTA}}" data-token="{{$token}}" data-limite="1" data-container="div#arquivos-boleto" data-pasta="boleto" data-inassdigital="N" class="novo-arquivo" data-toggle="modal" data-target="#novo-arquivo">Upload boleto do prêmio</a></li>
                                                           @endcan
                                                      @endif
                                                    @endif

                                                    @if( is_object($proposta->mpme_arquivo_comprovante_boleto) == true)
                                                        <li><a href="javascript:void(0);" data-noarquivo="{{$proposta->mpme_arquivo_comprovante_boleto->NO_ARQUIVO}}" data-idmpmearquivo="{{$proposta->mpme_arquivo_comprovante_boleto->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download comprovante prêmio</a></li>
                                                    @endif

                                                    @if(in_array($proposta->ID_MPME_STATUS_PROPOSTA, [10]))
                                                        @can('UPLOAD_APOLICE')
                                                            <li><a href="javascript:void(0);" data-idmpmetipoarquivo="15" data-extensoes="pdf" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" data-token="{{$token}}" data-limite="1" data-container="div#arquivos-apolice" data-pasta="apolice" data-inassdigital="N" class="novo-arquivo" data-toggle="modal" data-target="#dados-apolice">Upload da Apolice</a></li>
                                                        @endcan
                                                    @endif

                                                    @if(in_array($proposta->ID_MPME_STATUS_PROPOSTA, [12]))
                                                        @can('UPLOAD_CG')
                                                            <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" data-idmpmetipoarquivo="10" data-extensoes="pdf" data-idflex="{{$proposta->ID_MPME_PROPOSTA}}" data-token="{{$token}}" data-limite="1" data-container="div#arquivos-cg" data-pasta="cg" data-inassdigital="N" class="novo-arquivo" data-toggle="modal" data-target="#dados_apolice">Upload do Certificado</a></li>
                                                        @endcan
                                                    @endif

                                                    @if( is_object($proposta->mpme_arquivo_cg) == true)
                                                        <li><a href="javascript:void(0);" data-noarquivo="{{$proposta->mpme_arquivo_cg->NO_ARQUIVO}}" data-idmpmearquivo="{{$proposta->mpme_arquivo_cg->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download CG</a></li>
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
                            @endforeach

                        </tbody>
                    </table>
                      <div class="col-md-12">
                        Exibindo <b>{{count($rs_proposta)}}</b> de <b>{{$rs_proposta->total()}}</b> {{($rs_proposta->total()>1?'operações':'operação')}}.
                    </div>
                    <div align="center">
                        {{$rs_proposta->fragment('table_propostas')->render()}}
                    </div>
                </div>
            </div>
            <!-- Modal -->
            @include('proposta.modais-proposta')
            <div class="modal fade" id="recusar-proposta" tabindex="-1" role="dialog">
                <input type="hidden" name="id_mpme_proposta" id="id_mpme_proposta">
                <input type="hidden" name="id_oper" id="id_oper">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Recusar proposta</h5>
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
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                            <button type="button" class="btn btn-primary" id="recusarProposta" name="recusarProposta">Salvar</button>
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
