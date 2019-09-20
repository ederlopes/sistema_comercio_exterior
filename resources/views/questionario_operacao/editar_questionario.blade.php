@extends('layouts.app')
@php
    $id_modalidade = $rs_buscar_dados->operacao_cadastro_exportador->ID_MODALIDADE;
    $msg = ( $id_modalidade == "2" || $id_modalidade == "3") ? 'IMPORTADOR' : 'EXPORTADOR';
@endphp

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
        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-9">
            <form name="frmQuestionario" id="frmQuestionario" method="post" action="">
                <input type="hidden" name="id_oper" id="id_oper" value="{{$rs_buscar_dados->ID_OPER}}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Alterar Operação: <strong>{{$rs_buscar_dados->ID_OPER}}</strong></h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert" id="containerMsgModalidade" >
                            <div id="msgModalidade">Os dados para alteração devem ser referentes ao <b>{{$msg}}</b></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modalidade da operação</label>
                                    <select class="form-control input-sm" name="id_cliente_exportadores_modalidade" id="id_cliente_exportadores_modalidade">
                                        <option value="">Selecione</option>
                                        @foreach( $rs_modalidade_financiamento as $modalidade_financiamento)
                                            @php
                                                $id_composto_for   = $modalidade_financiamento->ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO.$modalidade_financiamento->ID_MODALIDADE.$modalidade_financiamento->ID_MODALIDADE_FINANCIAMENTO;
                                                $id_composto_banco = $rs_buscar_dados->operacao_cadastro_exportador->ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO.$rs_buscar_dados->operacao_cadastro_exportador->ID_MODALIDADE.$rs_buscar_dados->operacao_cadastro_exportador->ID_FINANCIAMENTO;

                                               if ($id_composto_for == $id_composto_banco)
                                                {
                                                    $selected = ' selected="selected" ';
                                                }else{
                                                    $selected = '';
                                                }
                                            @endphp
                                            <option {{$selected}} value="{{$modalidade_financiamento->ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO}}#{{$modalidade_financiamento->ID_MODALIDADE}}#{{$modalidade_financiamento->ID_MODALIDADE_FINANCIAMENTO}}">{{$modalidade_financiamento->NO_MODALIDADE}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>País</label>
                                    <select class="form-control input-sm" name="id_pais" id="id_pais">
                                        <option value="">Selecione</option>
                                        @foreach( $rs_pais as $paises)
                                            <option @if ( $rs_buscar_dados->ID_PAIS == $paises->ID_PAIS ) selected="selected" @endif value="{{$paises->ID_PAIS}}">{{$paises->NM_PAIS}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Razão Social</label>
                                    <input type="text" class="form-control" name="no_razao_social" id="no_razao_social" value="{{$rs_buscar_dados->RAZAO_SOCIAL}}" readonly="readonly">
                                </div>
                            </div>
                        </div>

                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Natureza Jurídica</label>
                                    <select class="form-control input-sm" name="id_nat_jur" id="id_nat_jur">
                                        <option value="">Selecione</option>
                                        <option @if ( $rs_buscar_dados->NAT_JURIDICA == 1 ) selected="selected" @endif value="1">Privada</option>
                                        <option @if ( $rs_buscar_dados->NAT_JURIDICA == 2 ) selected="selected" @endif value="2">Pública</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Natureza do Risco</label>
                                    @php
                                        $listaOpcoes[1] = array(
                                                                "1" => 'Político e Extraordinário',
                                                                "2" => 'Comercial, Político e Extraordinário',
                                                           );

                                        $listaOpcoes[2] = array(
                                                                "3" => 'Soberano',
                                                                "4" => 'Ordinário',
                                                           );

                                    @endphp
                                    <select class="form-control input-sm" name="id_nat_risco" id="id_nat_risco">
                                        @foreach( $listaOpcoes[$rs_buscar_dados->NAT_JURIDICA] as $key => $natureza_risco )
                                            <option  @if ( $key == $rs_buscar_dados->NAT_RISCO ) selected="selected" @endif value="{{$key}}">{{$natureza_risco}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>E-mail do Contato</label>
                                    <input type="text" name="e_mail" id="e_mail" value="{{$rs_buscar_dados->E_MAIL}}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <input type="text" name="endereco" id="endereco" value="{{$rs_buscar_dados->ENDERECO}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" name="cidade" id="cidade" value="{{$rs_buscar_dados->CIDADE}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contato</label>
                                    <input type="text" name="contato" id="contato" value="{{$rs_buscar_dados->CONTATO}}" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNPJ ou Equivalente</label>
                                    <input type="text" name="cnpj" id="cnpj" value="{{$rs_buscar_dados->CNPJ}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CEP ou Equivalente</label>
                                    <input type="text" name="cep" id="cep" value="{{$rs_buscar_dados->CEP}}" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="text" name="telefone" id="telefone" value="{{$rs_buscar_dados->TELEFONE}}" class="form-control">
                                </div>
                            </div>
                        </div>


                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor Solicitado para Aprovação</label>
                                    <input type="text" name="vl_proposta" id="vl_proposta" value="{{$rs_buscar_dados->VL_APROVADO}}" class="form-control money">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Moeda da Operação</label>
                                    <select class="form-control input-sm" name="id_moeda" id="id_moeda">
                                        <option value="">Selecione</option>
                                        @foreach( $rs_moeda as $moeda)
                                            <option @if ( $rs_buscar_dados->ID_MOEDA == $moeda->MOEDA_ID ) selected="selected" @endif value="{{$moeda->MOEDA_ID}}">{{$moeda->SIGLA_MOEDA}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fax</label>
                                    <input type="text" name="fax" id="fax" value="{{$rs_buscar_dados->FAX}}" class="form-control">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Setor de atividades</label>
                                    <select name="id_setor_atividade[]" id="id_setor_atividade" class="form-control selectpicker" multiple="multiple" data-live-search="true" data-selected-text-format="count > 2" title="Selecione um ou mais motivos" data-placeholder="Selecione um ou mais setores de atividades" >
                                        <option value="">Selecione</option>
                                        @foreach( $rs_setores as $setor)
                                            @php
                                                $attr_restricao = (in_array($setor->ID_SETOR, $listaRestricoesSetores)) ? "data-idrestricao=1" : "data-idrestricao=0";
                                                $selected = (in_array($setor->ID_SETOR, $arraySetoresOperacao)) ? 'selected="selected"' : "";
                                            @endphp
                                            <option {{$attr_restricao}} {{$selected}} value="{{$setor->ID_SETOR}}">{{$setor->NM_SETOR}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="checkbox" name="in_documentacao" id="in_documentacao" value="1" checked="checked" >
                                    <label>A fim de que possamos dar continuidade à analise da operação, solicitamos enviar, se possível com brevidade, os demonstrativos financeiros dos últimos 3 (três) anos.</label>
                                </div>
                            </div>
                        </div>


                        <div class="row no-print margin-t-20">
                            <div class="col-xs-12">
                                <a href="{{URL::to('/questionario_operacao')}}"class="btn btn-default" ><i class="fa fa-arrow-circle-o-left"></i> Voltar</a>
                                <button type="button" class="btn btn-primary pull-right" id="btnCadastrar">
                                    <i class="fa fa-save"></i> Alterar operação
                                </button>
                                <a href="javascript: window.print();" class="btn btn-default pull-right margin-r-5 "><i class="fa fa-print"></i> Imprimir</a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
      </div>
    </section>
  </div>
  <script src="{{ asset('js/questionario/funcoes_questionario.js') }}"></script>
@endsection
