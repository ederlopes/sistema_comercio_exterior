@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Gerador de Relatórios</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
      <div class="col-md-2">
          @include('layouts.menu_abgf')
      </div>
        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
            <form name="frmRelatorio" id="frmRelatorio" method="post" target="_blank" action="{{route('abgf.relatorios.gerar_relatorio')}}">
                {!! csrf_field() !!}
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Tabelas de Relacionamento</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tabela_usuarios">
                                        <input type="checkbox" name="ckd_tabela_usuarios" id="ckd_tabela_usuarios" value="S" checked>
                                        Tabela de Usuários
                                    </label>
                                    <select class="form-control  selectpicker" data-live-search="true" name="tabela_usuario[]" id="tabela_usuario"  multiple="multiple" data-actions-box="true">
                                        <option value="">Selecione</option>
                                        @foreach( $tabela_usuarios as $usuarios)
                                            <option value="{{$usuarios->TABLE_NAME}}.{{$usuarios->COLUMN_NAME}}">{{$usuarios->COLUMN_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ckd_tabela_operacoes">
                                        <input type="checkbox" name="ckd_tabela_operacoes" id="ckd_tabela_operacoes" value="S" >
                                        Tabela de Operações
                                    </label>
                                    <select class="form-control  selectpicker" data-live-search="true" name="tabela_operacoes[]" id="tabela_operacoes" data-container="body" multiple="multiple" data-actions-box="true" disabled>
                                        <option value="">Selecione</option>
                                        @foreach( $tabela_operacoes as $operacoes)
                                            <option value="{{$operacoes->TABLE_NAME}}.{{$operacoes->COLUMN_NAME}}">{{$operacoes->COLUMN_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div> 

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ckd_tabela_status_operacao">
                                        <input type="checkbox" name="ckd_tabela_status_operacao" id="ckd_tabela_status_operacao" value="S">
                                        Tabela de status da operacao
                                    </label>
                                    <select class="form-control selectpicker" data-live-search="true" name="tabela_status_operacao[]" id="tabela_status_operacao"  multiple="multiple" data-actions-box="true" disabled>
                                        <option value="">Selecione</option>
                                        @foreach( $tabela_status_operacao as $status_operacao)
                                            <option value="{{$status_operacao->TABLE_NAME}}.{{$status_operacao->COLUMN_NAME}}">{{$status_operacao->COLUMN_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ckd_tabela_proposta">
                                        <input type="checkbox" name="ckd_tabela_proposta" id="ckd_tabela_proposta" value="S">
                                        Tabela de proposta
                                    </label>
                                    <select class="form-control  selectpicker" data-live-search="true" name="tabela_proposta[]" id="tabela_proposta"  multiple="multiple" data-actions-box="true" disabled>
                                        <option value="">Selecione</option>
                                        @foreach( $tabela_proposta as $propostas)
                                            <option value="{{$propostas->TABLE_NAME}}.{{$propostas->COLUMN_NAME}}">{{$propostas->COLUMN_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ckd_tabela_status_proposta">
                                        <input type="checkbox" name="ckd_tabela_status_proposta" id="ckd_tabela_status_proposta" value="S">
                                        Tabela de status da proposta
                                    </label>
                                    <select class="form-control selectpicker" data-live-search="true" name="tabela_status_proposta[]" id="tabela_status_proposta"  multiple="multiple" data-actions-box="true" disabled>
                                        <option value="">Selecione</option>
                                        @foreach( $tabela_status_proposta as $status_proposta)
                                            <option value="{{$status_proposta->TABLE_NAME}}.{{$status_proposta->COLUMN_NAME}}">{{$status_proposta->COLUMN_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ckd_tabela_credit_score_importador">
                                        <input type="checkbox" name="ckd_tabela_credit_score_importador" id="ckd_tabela_credit_score_importador" value="S">
                                        Credit Score Importador
                                    </label>
                                    <select class="form-control selectpicker" data-live-search="true" name="tabela_credit_score_importador[]" id="tabela_credit_score_importador"  multiple="multiple" data-actions-box="true"  disabled>
                                        <option value="">Selecione</option>
                                        @foreach( $tabela_credit_score_importador as $credit_score_importador)
                                            <option value="{{$credit_score_importador->TABLE_NAME}}.{{$credit_score_importador->COLUMN_NAME}}">{{$credit_score_importador->COLUMN_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="ckd_tabela_credit_score_exportador">
                                        <input type="checkbox" name="ckd_tabela_credit_score_exportador" id="ckd_tabela_credit_score_exportador" value="S">
                                        Credit Score Exportador
                                    </label>
                                    <select class="form-control selectpicker" data-live-search="true" name="tabela_credit_score_exportador[]" id="tabela_credit_score_exportador"  multiple="multiple" data-actions-box="true" disabled>
                                        <option value="">Selecione</option>
                                        @foreach( $tabela_credit_score_exportador as $credit_score_exportador)
                                            <option value="{{$credit_score_exportador->TABLE_NAME}}.{{$credit_score_exportador->COLUMN_NAME}}">{{$credit_score_exportador->COLUMN_NAME}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="ckd_sql">
                                        <input type="checkbox" name="ckd_sql" id="ckd_sql" value="S">
                                        Mostrar SQL
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="ckd_pdf">
                                        <input type="checkbox" name="ckd_pdf" id="ckd_pdf" value="S">
                                        Saída em PDF
                                    </label>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Filtros</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Tipo de usuário</label>
                                        <select name="tipo_usuario" id="tipo_usuario" class="form-control">
                                            <option value="">Selecione</option>
                                            <option value="F">Funcionário</option>
                                            <option value="B">Banco</option>
                                            <option value="C">Cliente</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Status do usuário</label>
                                        <select name="fl_ativo" id="fl_ativo" class="form-control">
                                            <option value="">Selecione</option>
                                            <option value="0">Inativo</option>
                                            <option value="1">Ativo</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Perfil do usuário</label>
                                        <select class="form-control input-sm" name="id_perfil_usuario" id="id_perfil_usuario">
                                            <option value="0">Selecione</option>
                                            @foreach($rs_perfil as $perfil)
                                                <option value="{{$perfil->ID_PERFIL}}">{{$perfil->ALCADA}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>      
                            </div>                                
                        </div>  

                        <div class="row">
                            <div class="col-md-12">
                            
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nº Operação</label>
                                            <input type="text" maxlength="10" name="id_oper" id="id_oper" value="{{$request->id_oper}}" class="form-control somentenumero">
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Status da operação</label>
                                            <select class="form-control input-sm" name="st_oper" id="st_oper">
                                                <option value="0">Selecione</option>
                                                @foreach($rs_status_operacao as $status_operacao)
                                                    <option value="{{$status_operacao->ST_OPER}}">{{$status_operacao->NM_OPER}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>  
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Modalidade da operação</label>
                                            <select class="form-control input-sm" name="id_modalidade" id="id_modalidade">
                                                <option value="0">Selecione</option>
                                                @foreach($rs_modalidade as $modalidade)
                                                    <option value="{{$modalidade->ID_MODALIDADE}}">{{$modalidade->NO_MODALIDADE}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                               
                            </div>                                
                        </div>

                          



                        

                        <div class="row no-print margin-t-20">
                            <div class="col-xs-12">
                                <a href="javascript:window.print();" class="btn btn-default pull-left margin-r-5 "><i class="fa fa-print"></i> Imprimir</a>
                                <button type="button" class="btn btn-primary pull-right ajuste_cad_btn_importador" id="btnCadastrar">
                                    <div class="gravar"><i class="fa fa-save"></i> Cadastrar</div>
                                </button>
                                <button type="button" class="btn btn-info pull-right ajuste_cad_btn_importador" id="btnGerarRelatorio" sty>
                                    <div class="gerarRelatorio"><i class="fa fa-save"></i> Gerar Relatório</div>
                                </button>
                            </div>
                        </div>

                        
                    </div>
                </div>
            </form>

            <div  id="resultado_relatorio" style="display:none;" >
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <label>Resultado:</label>
                                            <div class="loading">
                                                <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                                            </div>
                                            <div id="resultado" style="overflow-x: scroll; overflow-y: scroll;">

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
        </div>
      </div>
    </section>
  </div>

    

    <script src="{{ asset('js/relatorios/funcoes_relatorios.js') }}?<?=time();?>"></script>

@endsection
