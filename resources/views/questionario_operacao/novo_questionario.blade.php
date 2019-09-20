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
        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-9">
            <form name="frmQuestionario" id="frmQuestionario" method="post" action="">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Cadastro da Operação</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert" id="containerMsgModalidade"  style="display: none">
                            <div id="msgModalidade">Os dados para cadastro devem ser referentes ao <b>IMPORTADOR</b></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modalidade da operação</label>
                                    <select class="form-control selectpicker" name="id_cliente_exportadores_modalidade" id="id_cliente_exportadores_modalidade" data-container="body">
                                        <option value="">Selecione</option>
                                        @php
                                            $id_modalidade = "";
                                        @endphp
                                        @foreach( $rs_modalidade_financiamento as $modalidade_financiamento)
                                           @if ($modalidade_financiamento->ID_MODALIDADE != $id_modalidade)
                                            <option value="{{$modalidade_financiamento->ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO}}#{{$modalidade_financiamento->ID_MODALIDADE}}#{{$modalidade_financiamento->ID_MODALIDADE_FINANCIAMENTO}}">{{$modalidade_financiamento->NO_MODALIDADE}}</option>
                                           @endif
                                           @php
                                               $id_modalidade = $modalidade_financiamento->ID_MODALIDADE;
                                           @endphp
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{--<div class="col-md-4">
                                <div class="form-group">
                                    <label>Setor da Atividade</label>
                                    <select class="form-control input-sm" name="id_setor" id="id_setor">
                                        <option value="">Selecione</option>
                                        @foreach( $rs_setores as $setores)
                                            <option value="{{$setores->ID_SETOR}}">{{$setores->NM_SETOR}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>--}}
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>País</label>
                                    <select class="form-control selectpicker" data-live-search="true" name="id_pais" id="id_pais" data-container="body">
                                        <option value="">Selecione</option>
                                        @foreach( $rs_pais as $paises)
                                            @php
                                                $restrito = ( in_array($paises->ID_PAIS, $arrayPais) ) ? 1 : 0;
                                            @endphp
                                            <option data-idrestrito="{{$restrito}}" value="{{$paises->ID_PAIS}}" data-subtext="<b> - Risco ({{$paises->CD_RISCO}}/7)</b>">{{$paises->NM_PAIS}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Razão Social</label>
                                    <div id="div_no_razao_social_select">
                                        <select class="form-control selectpicker" data-live-search="true" name="no_razao_social_select" id="no_razao_social_select" disabled="disabled" data-container="body">
                                            <option value="">Selecione</option>
                                        </select>
                                    </div>
                                    <div id="div_no_razao_social_input" style="display: none">
                                        <input type="text" name="no_razao_social" id="no_razao_social" maxlength="150" value="" class="form-control">
                                    </div>
                                    <input type="hidden" name="codigo_unico_importador" id="codigo_unico_importador" value="0">
                                    <input type="hidden" name="id_cliente_mpme" id="id_cliente_mpme" value="0">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Natureza Jurídica</label>
                                    <select class="form-control selectpicker" name="id_nat_jur" id="id_nat_jur" data-container="body">
                                        <option value="">Selecione</option>
                                        <option value="1">Privada</option>
                                        <option value="2">Pública</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Natureza do Risco</label>
                                    <select class="form-control selectpicker" name="id_nat_risco" id="id_nat_risco" disabled="disabled" data-container="body">
                                        <option value="">Selecione</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>E-mail do Contato</label>
                                    <input type="text" name="e_mail" id="e_mail" maxlength="50" value="" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <input type="text" name="endereco" id="endereco" maxlength="150" value="" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" name="cidade" id="cidade" value="" maxlength="100" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contato</label>
                                    <input type="text" name="contato" id="contato" value="" maxlength="50" class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNPJ ou Equivalente</label>
                                    <input type="text" name="cnpj" id="cnpj" value="" maxlength="20" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CEP ou Equivalente</label>
                                    <input type="text" name="cep" id="cep" value="" maxlength="10" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="text" name="telefone" id="telefone" value="" maxlength="25" class="form-control">
                                </div>
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor solicitado</label>
                                    <input type="text" name="vl_proposta" id="vl_proposta" value="" class="form-control money">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Moeda da Operação</label>
                                    <select class="form-control selectpicker" name="id_moeda" id="id_moeda" data-container="body">
                                        <option value="">Selecione</option>
                                        @foreach( $rs_moeda as $moeda)
                                            <option value="{{$moeda->MOEDA_ID}}">{{$moeda->SIGLA_MOEDA}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Fax</label>
                                    <input type="text" name="fax" id="fax" value="" maxlength="20" class="form-control">
                                </div>
                            </div>

                        </div>

                        <div class="row">

                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Setor de atividade</label>
                                    <select name="id_setor_atividade[]" id="id_setor_atividade" class="form-control selectpicker" multiple="multiple" data-live-search="true" data-selected-text-format="count > 2" title="Selecione um ou mais motivos" data-placeholder="Selecione um ou mais setores de atividades" data-container="body">
                                        <option value="">Selecione</option>
                                        @foreach( $rs_setores as $setor)
                                            @php
                                                $attr_restricao = (in_array($setor->ID_SETOR, $listaRestricoesSetores)) ? "data-idrestricao=1" : "data-idrestricao=0";
                                            @endphp
                                            <option {{$attr_restricao}} value="{{$setor->ID_SETOR}}">{{$setor->NM_SETOR}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12" id="termo_setor_atividade" style="display: none">
                                <div class="form-group">
                                    <div class="alert alert-warning">
                                        <p>Este setor de atividade esta na lista de restrição da ABGF. No momento do embarque o mesmo deve fazer upload de documentação solicitada pela ABGF.</p><br>
                                        <div>
                                            <input type="checkbox" name="in_aceite_restricoes" id="in_aceite_restricoes" value="S">
                                            <label for="in_aceite_restricoes">Aceitar condições de restrições.</label>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <input type="checkbox" name="in_documentacao" id="in_documentacao" value="0">
                                    <label for="in_documentacao">A fim de que possamos dar continuidade à analise da operação, solicitamos enviar, se possível com brevidade, os demonstrativos financeiros dos últimos 3 (três) anos.</label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <fieldset>
                                        <legend>Informações Complementares:</legend>
                                        @foreach( $rs_pergunta as $pergunta)
                                            <div class="alert alert-info" id="div_pergunta_{{$pergunta->ID_MPME_PERGUNTA}}">
                                            <strong>{{ $pergunta->NO_PERGUNTA }}</strong><br />
                                            @foreach($pergunta->respostas as $respostas)
                                                    <input type="radio" name="pergunta[{{$respostas->ID_MPME_PERGUNTA}}][IDRESP]" id="resposta_id_{{$respostas->ID_MPME_PERGUNTA_RESPOSTA}}" in_outra_resposta="{{$respostas->resposta->IN_OUTRA_RESPOSTA}}" value="{{$respostas->ID_MPME_PERGUNTA_RESPOSTA}}"> <label style="font-weight:normal" for="resposta_id_{{$respostas->ID_MPME_PERGUNTA_RESPOSTA}}">{{$respostas->resposta->NO_RESPOSTA}}</label>
                                            @endforeach
                                            </div>
                                        @endforeach

                                    </fieldset>
                                </div>
                            </div>
                        </div>

                        <div class="alert alert-warning" role="alert">
                            <div>
                                Caso o país que deseje fazer exportação não esteja na lista, favor entrar em contato com ABGF:
                                <strong>Rio de Janeiro -</strong>  +55 (21) 2510-5052 / 5039 / 5024
                            </div>
                        </div>

                        <div class="row no-print margin-t-20">
                            <div class="col-xs-12">
                                <a href="javascript:history.go(-1);"class="btn btn-default" ><i class="fa fa-arrow-circle-o-left"></i> Voltar</a>
                                @can('NOVA_OPERACAO')
                                    <button type="button" class="btn btn-primary pull-right" id="btnCadastrar">
                                       <div class="nomebtn"><i class="fa fa-save"></i> Cadastrar</div>
                                    </button>
                                @endcan
                                <a href="window.print();" class="btn btn-default pull-right margin-r-5 "><i class="fa fa-print"></i> Imprimir</a>
                            </div>
                        </div>

                    </div>
                </div>
            </form>
        </div>
      </div>
    </section>
  </div>
  <script src="{{ asset('js/questionario/funcoes_questionario.js').'?'.time() }}"></script>
@endsection
