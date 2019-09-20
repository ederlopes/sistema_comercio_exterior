@php
 $pathDeclaracaoAntiCorrupcao = public_path('/docs/anti-corrupcao/').Auth::User()->ID_USUARIO.'/'.Auth::User()->ID_USUARIO.'.pdf';
@endphp

@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exportador
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

         @include('layouts.menu_cliente')

          <div class="col-md-10">
              <form name="form-filtro" method="post" action="questionario_operacao#table_operacoes" class="clearfix">
                  {{csrf_field()}}
                  @include('questionario_operacao.filtro_questionario')
              </form>
          </div>

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de operações</h3>
                </div>
                <div class="panel-body">
                    <p>
                        @can('NOVA_OPERACAO')
                        <a href="{{URL::to('/questionario_operacao/novo')}}" class="btn btn-success " id="operacao"><i class="fa fa-clipboard"></i> Nova operação</a>
                        @endcan
                        @if (File::exists($pathDeclaracaoAntiCorrupcao))
                            <a href="{{URL::to('/docs/anti-corrupcao/')}}/{{Auth::User()->ID_USUARIO}}/{{Auth::User()->ID_USUARIO}}.pdf" class="btn btn-info " target="_blank"><i class="fa fa-file-pdf-o"></i> Baixar Documento</a>
                        @else
                           @can('UPLOAD_DAC')
                            <a href="{{route('declaracaocompromisso')}}" class="btn btn-primary" id="declaracao"><i class="fa fa-clipboard"></i> Upload da Declaração de compromisso</a>
                           @endcan
                        @endif
                    </p>

                    <table class="table table-bordered table-striped table-condensed" id="questionario">
                        <!--<colgroup>
                            <col class="col-xs-1">
                            <col class="col-xs-1">
                        </colgroup>-->
                        <thead>
                        <tr>
                            @if(\Auth::user()->ID_PERFIL != 9)
                            <th width="3%">ID da Operação </th>
                            @endif
                            <th width="7%">Nº da Operação</th>
                            <th class="col-md-1">Data da Solicitação</th>
                            <th class="col-md-1">País</th>
                            <th class="col-md-1">Razão Social</th>
                            <th class="col-md-1">Valor solicitado para Aprovação</th>
                            <th class="col-md-1">Modalidade da Operação</th>
                            <th class="col-md-1">Status</th>
                            <th class="col-xs-1">&nbsp;</th>
                        </tr>
                        </thead>
                        <tbody>
                            @forelse($rsImportadores as $importadores)
                                <tr>
                                    @if(\Auth::user()->ID_PERFIL != 9)
                                    <td>{{formatar_codigo($importadores->ID_OPER)}}</td>
                                    @endif
                                    <td>{{$importadores->COD_UNICO_OPERACAO ?? 'N/D'}}</td>
                                    <td>{{formatar_data_hora($importadores->DATA_CADASTRO)}}</td>
                                    <td>{{$importadores->NM_PAIS}} - <small><b>Risco {{$importadores->CD_RISCO}}/7</b></small> </td>
                                    <td>{{$importadores->RAZAO_SOCIAL}}</td>
                                    <td>{{$importadores->SIGLA_MOEDA}} {{formatar_valor_sem_moeda($importadores->VL_TOTAL)}}</td>
                                    <td>{{$importadores->NO_MODALIDADE}}</td>
                                    <td>
                                        @if (in_array($importadores->ST_OPER, [9,21]))
                                            <div class="alert alert-danger" role="alert">
                                                <strong>{{$importadores->NM_OPER}}</strong>
                                            </div>
                                        @else
                                            {{$importadores->NM_OPER}}
                                        @endif
                                    </td>
                                    <td>
                                        <!-- Single button -->
                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu listaopcoes">
                                                @if($importadores->IC_ENVIADO == 0)
                                                    @can('EDITAR_OPERACAO')
                                                        <li><a href="{{URL::to('/questionario_operacao/editar/')}}/{{$importadores->ID_OPER}}" data-idoper="{{$importadores->ID_OPER}}">Editar</a></li>
                                                    @endcan
                                                    @can('ENVIAR_OPERACAO')
                                                        <li class="enviarAbgf" data-idoper="{{$importadores->ID_OPER}}"><a href="javascript:void(0);" >Enviar p/ ABGF</a></li>
                                                    @endcan
                                                    @can('EXCLUIR_OPERACAO')
                                                        <li><a href="javascript:void(0);" data-idoper="{{$importadores->ID_OPER}}" class="excluir_questionario" >Excluir operação</a></li>
                                                    @endcan
                                                @endif
                                                @if(in_array($importadores->ST_OPER, [5]))
                                                    @can('NOVA_PROPOSTA')
                                                        <li><a href="{{URL::to('/proposta/nova/')}}/{{$importadores->ID_OPER}}" data-idoper="{{$importadores->ID_OPER}}">Nova Proposta</a></li>
                                                    @endcan
                                                    @can('LISTA_PROPOSTA')
                                                        <li><a href="{{URL::to('/proposta/')}}/{{$importadores->ID_OPER}}" data-idoper="{{$importadores->ID_OPER}}">Lista de propostas</a></li>
                                                    @endcan
                                                @endif
                                                   <!--<li><a href="javascript:void(0);" data-idoper="{{$importadores->ID_OPER}}"  class="historico_aprovacao" data-toggle="modal" data-target="#historico-aprovacao">Histórico Aprovação</a></li>-->
                                                @if (is_object($importadores->mpme_arquivo_boleto_relatorio) == true)
                                                        <li><a href="javascript:void(0);" data-noarquivo="{{$importadores->mpme_arquivo_boleto_relatorio->NO_ARQUIVO}}" data-idmpmearquivo="{{$importadores->mpme_arquivo_boleto_relatorio->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download boleto relatório</a></li>
                                                @if(is_object($importadores->mpme_arquivo_comprovante_boleto) == true)
                                                <li><a href="javascript:void(0);" data-noarquivo="{{$importadores->mpme_arquivo_comprovante_boleto->NO_ARQUIVO}}" data-idmpmearquivo="{{$importadores->mpme_arquivo_comprovante_boleto->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download comprovante relatório</a></li>
                                                @else
                                                    @can('UPLOAD_CB_DEB')
                                                        <li><a href="javascript:void(0);" data-idoper="{{$importadores->ID_OPER}}"  data-idmpmetipoarquivo="4" data-extensoes="pdf" data-idflex="{{$importadores->ID_OPER}}" data-token="{{$token}}" data-limite="1" data-container="div#arquivos-boleto" data-pasta="boleto_relatorio" data-inassdigital="N" class="novo-arquivo" data-toggle="modal" data-target="#novo-arquivo">Upload comprovante relatório</a></li>
                                                    @endcan
                                                @endif

                                                @endif
                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8"><div class="alert alert-warning">Não existem operações</div></td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="col-md-12">
                        Exibindo <b>{{count($rsImportadores)}}</b> de <b>{{$rsImportadores->total()}}</b> {{($rsImportadores->total()>1?'operações':'operação')}}.
                    </div>
                    <div align="center">
                        {{$rsImportadores->fragment('table_operacoes')->render()}}
                    </div>
                    <br><br>
                    <b>Obs.: Os valores dos relatórios nacionais e internacionais, utilizados para subsidiar as análises das operações de exportação, deverão ser pagos pela MPME, observado:
                        </br>a) Na fase Pré-Embarque, a empresa deve pagar o relatório nacional somente em sua 1ª (primeira) operação com ACC (Adiantamento sobre Contrato de Câmbio) durante o período de 12 meses.
                        </br>b) Na fase Pós-Embarque, a empresa deve pagar o relatório internacional somente na 1ª (primeira) operação de exportação para uma determinada empresa importadora durante o período de 12 meses.
                    </b>

                </div>
            </div>
        </div>
      </div>
    </section>
    <!-- Modal -->
    <div class="modal fade " id="excluir_questionario" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
        <input type="hidden" id="id_oper_excluir" name="id_oper_excluir" value="">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Excluir operação</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="loading">
                    <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                </div>
                <div class="modal-body">
                    <label>Motivo: </label>
                    <textarea id="ds_motivo" class="form-control" name="ds_motivo" style="height: 150px; width: 800px;"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="button" class="btn btn-primary btnExcluir" data-dismiss="modal" >Salvar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade " id="historico-aprovacao" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Histórico</h5>
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
    <script src="{{ asset('js/upload.js') }}"></script>
    <script src="{{ asset('js/questionario/funcoes_questionario.js') }}"></script>


    <script>
        $(document).ready(function(){

            // Instance the tour
            var tour = new Tour({
                backdrop: true,
                storage: window.localStorage,
                steps: [
                    {
                        element: "#operacao",
                        title: "Nova Operação",
                        content: "Através dessa opção você poderá cadastrar suas operações"
                    },
                    {
                        element: "#declaracao",
                        title: "Declaração de compromisso",
                        content: "Através dessa opção você poderá enviar a declaração de compromisso do exportador. <br><br> Você terá no <b>maximo 15 dias </b> para enviar a declaração após o cadastro da primeira operação."
                    }
                ]});

// Initialize the tour
            tour.init();

// Start the tour
            tour.start();



        });



    </script>

    @include('arquivos.arquivos-modais')
@endsection
