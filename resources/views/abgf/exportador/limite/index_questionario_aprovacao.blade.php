@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Controle de Operações
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

          <div class="col-md-2">
              @include('layouts.menu_abgf')
          </div>

          <div class="col-md-10">
              <form name="form-filtro" method="post" action="{{route('abgf.exportador.listaquestionarioaprovacao')}}" class="clearfix">
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
                    <table class="table table-bordered table-striped table-condensed">
                        <thead>
                            <tr>
                                <th>Nº da Operação</th>
                                {{--<th>ID da Operação</th>--}}
                                <th>Data da Solicitação</th>
                                <th>Data de Envio para ABGF</th>
                                <th>País</th>
                                <th>Razão Social</th>
                                <th>Valor solicitado para Aprovação</th>
                                <th>Modalidade da Operação</th>
                                <th>Status</th>
                                <th class="col-xs-1">&nbsp;</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rsImportadores as $importadores)
                                <tr>
                                    <td>{{$importadores->COD_UNICO_OPERACAO ?? 'N/D'}}</td>
                                    {{--<td>{{$importadores->ID_OPER}}</td>--}}
                                    <td>{{formatar_data_hora($importadores->DATA_CADASTRO_OPERACAO)}}</td>
                                    <td>{{formatar_data_hora($importadores->DT_ENVIO_ABGF)}}</td>
                                    <td>{{$importadores->NM_PAIS}} - <small><b>Risco {{$importadores->CD_RISCO}}/7</b></td>
                                    <td>{{$importadores->RAZAO_SOCIAL}}</td>
                                    <td>{{$importadores->SIGLA_MOEDA}} {{formatar_valor_sem_moeda($importadores->VL_TOTAL)}}</td>
                                    <td>{{$importadores->NO_MODALIDADE}}</td>
                                    <td>{{$importadores->NM_OPER}}</td>
                                    <td>
                                        <!-- Single button -->
                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                @can('VIS_DADOS_OPERARACAO')
                                                    <li><a href="javascript:void(0);" data-idoper="{{$importadores->ID_OPER}}" class="dados_questionario_operacao" data-toggle="modal" data-target="#visualizar-dados-operacao">Dados da Operação</a></li>
                                                @endcan
                                                @if (in_array($importadores->ST_OPER, [3]) || is_object($importadores->mpme_arquivo_boleto_relatorio) == true && is_object($importadores->mpme_arquivo_comprovante_boleto) == true)
                                                <li>
                                                  @can('ANALISAR_OPERACAO')
                                                    <a href="{{URL::to('/abgf/exportador/analisalimite/')}}/{{$importadores->ID_OPER}}/{{$importadores->ID_USUARIO}}" data-idoper="{{$importadores->ID_OPER}}">Analisar Operação</a>
                                                  @endcan
                                                </li>
                                                @endif
                                                @if(in_array($importadores->ST_OPER, [20]))
                                                  @can('LIMITE_OPERACIONAL')
                                                    <li><a href="javascript:void(0);" data-idoper="{{$importadores->ID_OPER}}"  class="limite_operacional" data-toggle="modal" data-target="#limite_operacional">Limite operacional</a></li>
                                                  @endcan
                                                @endif
                                                @if(in_array($importadores->ST_OPER, [3,12,13]))
                                                    @can('EXCLUIR_OPERACAO')
                                                        <li><a href="javascript:void(0);" data-idoper="{{$importadores->ID_OPER}}"  class="excluir_questionario" data-toggle="modal">Excluir operação</a></li>
                                                    @endcan
                                                @endif
                                                @if(in_array($importadores->ST_OPER, [12]))
                                                  @can('UPLOAD_BR_DEB')
                                                    <li><a href="javascript:void(0);" data-idoper="{{$importadores->ID_OPER}}"  data-idmpmetipoarquivo="1" data-extensoes="pdf" data-idflex="{{$importadores->ID_OPER}}" data-token="{{$token}}" data-limite="1" data-container="div#arquivos-boleto" data-pasta="boleto_relatorio" data-inassdigital="N" class="novo-arquivo" data-toggle="modal" data-target="#novo-arquivo">Upload boleto relatório</a></li>
                                                  @endcan
                                                @endif
                                                    @if(is_object($importadores->mpme_arquivo_boleto_relatorio) == true)
                                                    <li><a href="javascript:void(0);" data-noarquivo="{{$importadores->mpme_arquivo_boleto_relatorio->NO_ARQUIVO}}" data-idmpmearquivo="{{$importadores->mpme_arquivo_boleto_relatorio->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download boleto relatório</a></li>
                                                @endif
                                                @if (is_object($importadores->mpme_arquivo_comprovante_boleto) == true)
                                                    <li><a href="javascript:void(0);" data-noarquivo="{{$importadores->mpme_arquivo_comprovante_boleto->NO_ARQUIVO}}" data-idmpmearquivo="{{$importadores->mpme_arquivo_comprovante_boleto->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download comprovante relatório</a></li>
                                               @endif

                                            </ul>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="9">Nenhum registro encontrado</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                    <div class="col-md-12">
                        Exibindo <b>{{count($rsImportadores)}}</b> de <b>{{$rsImportadores->total()}}</b> {{($rsImportadores->total()>1?'operações':'operação')}}.
                    </div>
                    <div align="center">
                        {{$rsImportadores->fragment('table_operacoes')->render()}}
                    </div>
                </div>

            </div>
        </div>
      </div>
    </section>
  </div>

    <div class="modal fade" id="limite_operacional" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-xg" role="document">
            <div class="modal-content carregando">
                <div class="modal-carregando"></div>
                <div class="modal-header">
                    <h3 class="modal-title">Análise de Limite operacional<span></span></h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="loading">
                    <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer text-center">
                    @can('GRAVAR_ANALISE_OPERACIONAL')
                        <a class="btn btn-success pull-right btnGravarLimiteOperacional" id="btnGravarLimiteOperacional" ><i class="glyphicon glyphicon-floppy-disk"></i> Gravar</a>
                    @endcan
                    <button type="button" class="btn btn-outline-danger pull-left" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="visualizar-dados-operacao" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-xg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title" id="exampleModalLongTitle">Dados da operação</h3>
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
    <script src="{{ asset('js/upload.js') }}"></script>
    <script src="{{ asset('js/abgf/exportador/limite/questionario_analise_limite.js') }}"></script>
    @include('arquivos.arquivos-modais')
@endsection
