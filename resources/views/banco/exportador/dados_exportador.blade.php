@extends('layouts.app')
@section('content')
<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>
        Informações do Exportador
    </h1>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Informações do Exportador</li>
    </ol>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-3">
            <a href="#" class="btn btn-primary btn-block margin-bottom">Suporte Tecnico</a>


            <!-- /. box -->
            @include('layouts.menu_banco')
            <!-- /.box -->
        </div>


        <!-- /.col -->
        <div class="col-md-9">
            <div>

                <!-- /.box-header -->
                <div class="box-body no-padding">

                    @if(@$tipoPermissao == 'V')
                    <div class="callout callout-danger">
                        <p>Aguardando validação.</p>
                    </div>
                    @endif

                    @if(@trim($notificacao->MT_DEV_DADOS) != "" && @$tipoPermissao != 'V')
                    <div class="alert alert-danger">
                        Motivo da devolução:<br>
                        <p>
                            {{$notificacao->MT_DEV_DADOS}}
                        </p>
                    </div>
                    @endif
                    <input type="hidden" name="DE_MOTIVO_DEVOLUCAO" id="DE_MOTIVO_DEVOLUCAO" value=""/>
                    <input type="hidden" name="ID_NOTIFICACAO" class="ID_NOTIFICACAO" value="{{@$notificacao->ID_NOTIFICACAO}}">

                    
                    <!-- /.mailbox-read-info -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li><a href="#dadosExportador" data-toggle="tab">Dados do Exportador</a></li>
                            <li class="active"><a href="#dadosInstituiacaoFinanceira" data-toggle="tab">Dados da Instituição Financeira</a></li>
                            <li><a href="#infoexportador" data-toggle="tab">Informações Adicionais da Instituição
                                    Financeira</a></li>

                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane" id="dadosExportador">
                                <!-- Dados Exportador -->
                                <div>
                                    <div class="box-body">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Modalidade do Financiamento</label>
                                                @foreach($dadosExportador->ClienteExportador
                                                ->ModalidadeFinanciamento as
                                                $clienteModalidadeFinanciamentos)

                                                <span class="label label-primary">

                                                    {{$clienteModalidadeFinanciamentos
                                                                ->ModalidadeFinanciamento->NO_MODALIDADE_FINANCIAMENTO}}
                                                </span> &nbsp;
                                                @endforeach
                                            </div>
                                        </div>

                                    </div>
                                    <div class="box-body col-md-6">
                                        <!-- group -->
                                        <div class="form-group">
                                            <label>Razão Social</label>
                                            <input type="text" name='NM_USUARIO' disabled
                                                   value="{{$dadosExportador->NM_USUARIO}}" class="form-control ">
                                        </div>
                                        <!-- group -->

                                        <!-- group -->
                                        <div class="form-group">
                                            <label>CNPJ da Empresa</label>
                                            <input type="text" name='NU_CNPJ' disabled
                                                   value="{{$dadosExportador->NU_CNPJ}}"
                                                   class="form-control ">
                                        </div>
                                        <!-- group -->

                                        <!-- group -->
                                        <div class="form-group">
                                            <label>Endereço</label>
                                            <input type="text" name='DE_ENDER' disabled
                                                   value="{{utf8_encode($dadosExportador->DE_ENDER)}}"
                                                   class="form-control ">
                                        </div>
                                        <!-- group -->

                                        <!-- group -->
                                        <div class="form-group">
                                            <label>CEP</label>
                                            <input type="text" name='DE_CEP' disabled
                                                   value="{{$dadosExportador->DE_CEP}}"
                                                   class="form-control ">
                                        </div>
                                        <!-- group -->

                                        <!-- group -->
                                        <div class="form-group">
                                            <label>Nome do Contato</label>
                                            <input type="text" name='NM_CONTATO' disabled
                                                   value="{{$dadosExportador->NM_CONTATO}}" class="form-control ">
                                        </div>
                                        <!-- group -->
                                        <!-- group -->
                                        <div class="form-group">
                                            <label>Cargo do Contato</label>
                                            <input type="text" name='DE_CARGO' disabled
                                                   value="{{$dadosExportador->DE_CARGO}}" class="form-control ">
                                        </div>
                                        <!-- group -->

                                        <!-- group -->
                                        <div class="form-group">
                                            <label>Telefone</label>
                                            <input type="text" name='DE_TEL' disabled
                                                   value="{{$dadosExportador->DE_TEL}}"
                                                   class="form-control ">
                                        </div>
                                        <!-- group -->

                                        <!-- group -->
                                        <div class="form-group">
                                            <label>E-Mail do Contato</label>
                                            <input type="text" name='DE_EMAIL' disabled
                                                   value="{{$dadosExportador->DE_EMAIL}}" class="form-control ">
                                        </div>
                                        <!-- group -->

                                        <!-- group -->

                                        <!-- group -->


                                    </div>

                                    <div class="box-body col-md-6">

                                        <!-- group -->
                                        <div class="form-group">
                                            <label>Tempo de Existência da empresa</label>
                                            <select name="ID_TEMPO" id="ID_TEMPO" disabled
                                                    class="form-control select2"
                                                    style="width: 100%;">
                                                <option value="1"
                                                        @if($dadosExportador->ID_TEMPO == 1) selected @endif >Até 3
                                                        anos
                                            </option>
                                            <option value="2"
                                                    @if($dadosExportador->ID_TEMPO == 2) selected @endif >Acima
                                                    de 3 anos
                                        </option>
                                    </select>
                                </div>
                                <!-- group -->

                                <!-- group -->
                                <div class="form-group">
                                    <label>(*) Valor de Exportação do ano civil anterior</label>
                                    <input type="text" name='VL_BRUTO_ANUAL' disabled
                                           value="{{$dadosExportador->VL_BRUTO_ANUAL}}"
                                           class="form-control ">
                                </div>
                                <!-- group -->

                                <!-- group -->
                                <div class="form-group">
                                    <label>Faturamento Bruto do ano civil anterior</label>
                                    <input type="text" name='VL_EXP_BRUTA' disabled
                                           value="{{$dadosExportador->VL_EXP_BRUTA}}" class="form-control ">
                                </div>
                                <!-- group -->

                                <!-- group -->
                                <div class="form-group">
                                    <label>Inscr. Est.</label>
                                    <input type="text" name='NU_INSCR_EST' disabled
                                           value="{{$dadosExportador->NU_INSCR_EST}}" class="form-control ">
                                </div>
                                <!-- group -->

                                <!-- group -->
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" name='DE_CIDADE' disabled
                                           value="{{utf8_encode($dadosExportador->DE_CIDADE)}}"
                                           class="form-control ">
                                </div>
                                <!-- group -->

                                <!-- group -->
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="CD_UF" id="CD_UF" disabled
                                            class="form-control select2"
                                            style="width: 100%;">

                                        <option value="AC"
                                                @if($dadosExportador->CD_UF == 'AC') selected @endif>Acre
                                    </option>
                                    <option value="AL"
                                            @if($dadosExportador->CD_UF == 'AL') selected @endif>Alagoas
                                </option>
                                <option value="AM"
                                        @if($dadosExportador->CD_UF == 'AM') selected @endif>
                                        Amazonas
                            </option>
                            <option value="AP"
                                    @if($dadosExportador->CD_UF == 'AP') selected @endif>Amapá
                        </option>
                        <option value="BA"
                                @if($dadosExportador->CD_UF == 'BA') selected @endif>Bahia
                    </option>
                    <option value="CE"
                            @if($dadosExportador->CD_UF == 'CE') selected @endif>Ceará
                </option>
                <option value="DF"
                        @if($dadosExportador->CD_UF == 'DF') selected @endif>
                        Distrito Federal
            </option>
            <option value="ES"
                    @if($dadosExportador->CD_UF == 'ES') selected @endif>
                    Espirito Santo
        </option>
        <option value="GO"
                @if($dadosExportador->CD_UF == 'GO') selected @endif>Goiás
    </option>
    <option value="MA"
            @if($dadosExportador->CD_UF == 'MA') selected @endif>
            Maranhão
</option>
<option value="MG"
        @if($dadosExportador->CD_UF == 'MG') selected @endif>Minas
        Gerais
</option>
<option value="MS"
        @if($dadosExportador->CD_UF == 'MS') selected @endif>Mato
        Grosso do Sul
</option>
<option value="MT"
        @if($dadosExportador->CD_UF == 'MT') selected @endif>Mato
        Grosso
</option>
<option value="PA"
        @if($dadosExportador->CD_UF == 'PA') selected @endif>Pará
</option>
<option value="PB"
        @if($dadosExportador->CD_UF == 'PB') selected @endif>Paraíba
</option>
<option value="PE"
        @if($dadosExportador->CD_UF == 'PE') selected @endif>
        Pernambuco
</option>
<option value="PI"
        @if($dadosExportador->CD_UF == 'PI') selected @endif>Piauí
</option>
<option value="PR"
        @if($dadosExportador->CD_UF == 'PR') selected @endif>Paraná
</option>
<option value="RJ"
        @if($dadosExportador->CD_UF == 'RJ') selected @endif>Rio de
        Janeiro
</option>
<option value="RN"
        @if($dadosExportador->CD_UF == 'RN') selected @endif>Rio
        Grande do Norte
</option>
<option value="RO"
        @if($dadosExportador->CD_UF == 'RO') selected @endif>
        Rondônia
</option>
<option value="RR"
        @if($dadosExportador->CD_UF == 'RR') selected @endif>Roraima
</option>
<option value="RS"
        @if($dadosExportador->CD_UF == 'RS') selected @endif>Rio
        Grande do Sul
</option>
<option value="SC"
        @if($dadosExportador->CD_UF == 'SC') selected @endif>Santa
        Catarina
</option>
<option value="SE"
        @if($dadosExportador->CD_UF == 'SE') selected @endif>Sergipe
</option>
<option value="SP"
        @if($dadosExportador->CD_UF == 'SP') selected @endif>São
        Paulo
</option>
<option value="TO"
        @if($dadosExportador->CD_UF == 'TO') selected @endif>
        Tocantins
</option>
</select>


</div>
<!-- group -->


<!-- group -->
<div class="form-group">
    <label>Fax</label>
    <input type="text" name='DE_FAX' disabled
           value="{{$dadosExportador->DE_FAX}}"
           class="form-control">
</div>
<!-- group -->

<!-- group -->
<div class="form-group">
    <label>Moeda da Operação</label>
    <select name="ID_MOEDA" id="ID_MOEDA" disabled
            class="form-control select2"
            style="width: 100%;">
        <option value="1"
                @if($dadosExportador->ID_MOEDA == 1) selected @endif >USD
    </option>
    <option value="3"
            @if($dadosExportador->ID_MOEDA == 3) selected @endif >EUR
</option>
</select>
</div>
<!-- group -->

</div>

<!-- /.box-body -->
<div class="box-footer">

    <h5>Informações dos sócios</h5>

    <div class="box-body col-md-6">
        <!-- group -->
        <div class="form-group">
            <label>Nome</label>
            <input type="text" name='NOME_QUADRO' disabled="disabled"
                   value="{{$dadosExportador->NOME_QUADRO}}"
                   class="form-control">
        </div>
        <!-- group -->

        <!-- group -->
        <div class="form-group">
            <label>CPF/CNPJ</label>
            <input type="text" name='CPF_CNPJ_QUADRO' disabled="disabled"
                   value="{{$dadosExportador->CPF_CNPJ_QUADRO}}"
                   class="form-control">
        </div>
        <!-- group -->

        <!-- group -->
        <div class="form-group">
            <label>Participação</label>
            <input type="text" name='PARTICIPACAO_QUADRO' disabled="disabled"
                   value="{{$dadosExportador->PARTICIPACAO_QUADRO}}"
                   class="form-control">
        </div>
        <!-- group -->

    </div>


    @forelse($dadosExportador->QuadroSocietarioExportador as $quadro)
        <div class="box-body col-md-6">
            <!-- group -->
            <div class="form-group">
                <label>Nome</label>
                <input type="text" name='NOME_QUADRO' disabled="disabled"
                       value="{{$quadro->NOME_SOCIO}}" class="form-control">
            </div>
            <!-- group -->

            <!-- group -->
            <div class="form-group">
                <label>CPF/CNPJ</label>
                <input type="text" name='CPF_CNPJ_QUADRO' disabled="disabled"
                       value="{{formatarCnpjCpf($quadro->NU_CPF_CNPJ)}}" class="form-control">
            </div>
            <!-- group -->

            <!-- group -->
            <div class="form-group">
                <label>Participação</label>
                <input type="text" name='PARTICIPACAO_QUADRO' disabled="disabled"
                       value="{{number_format($quadro->PC_PARTICIPACAO, 2, '.', ',')}}" class="form-control">
            </div>
            <!-- group -->
        </div>
    @endforeach

</div>

        <div class="col-md-12">
            <div class="form-group">
                <label>Informar divergências do cadastro p/ ABGF</label>
                <textarea name="ds_divergencia" id="ds_divergencia" class="form-control" style="height: 300px;" @if (@$tipoPermissao == 'V') disabled="true" @endif>{{$dadosExportador->DS_DIVERGENCIA}}</textarea>
            </div>
        </div>

</div>


<!-- Print -->

<!-- this row will not appear when printing -->
<div class="row no-print">
    <div class="col-xs-12">
        <a href="#" target="_blank" class="btn btn-default"><i
                    class="fa fa-print"></i> Imprimir</a>
        @if ($tipoPermissao == 'C')
            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;" name="btnDivergencia" id="btnDivergencia" data-idusuario="{{$dadosExportador->ID_USUARIO}}">
                <i class="fa fa-save"></i> Salvar divergência
            </button>
          @endif
    </div>
</div>

<!-- /.post -->
</div>
<!-- /.tab-pane -->
<div class="active tab-pane" id="dadosInstituiacaoFinanceira">


    @if(@$dadosExportador->FinanciadorPre)
    <!-- Main pre -->
    <form action="{{ route('banco.atualizaFinancPre')}}" method="post"
          name="frmAtualizaDadosFinancPre">
        {{ csrf_field() }}
        <input type="hidden" name="ID_FINANC_PRE"
               value="{{$dadosExportador->FinanciadorPre->ID_FINANC_PRE}}">
        <section id="pre">
            <!-- title row -->
            <div class="row">
                <div class="col-xs-12">
                    <h2 class="page-header">
                        <i class="glyphicon glyphicon-usd"></i> Pré-Embarque
                        <small class="pull-right">Data:
                            {{ @date('d/m/Y', strtotime(@$dadosExportador->DATA_CADASTRO)) }}
                        </small>
                    </h2>
                </div>
                <!-- /.col -->
            </div>
            <!-- info row -->
            <div class="box-body col-md-4">
                <!-- group -->
                <div class="form-group">
                    <label>GECEX</label>
                    <input type="text" name='NO_GECEX' disabled
                           value="{{@$dadosExportador->FinanciadorPre->Gecex->NO_GECEX}}"
                           class="form-control ">

                </div>
                <div class="form-group">

                    <label>ENDEREÇO</label>
                    <input type="text" name='DS_ENDERECO_PRE' id="DS_ENDERECO_PRE"
                           value="{{@$dadosExportador->FinanciadorPre->DS_ENDERECO}}"
                           class="form-control ">
                </div>

                <div class="form-group">

                    <label>NÚMERO DO BANCO</label>
                    <input type="text" name='nu_ag'
                           value="{{$dadosExportador->FinanciadorPre->ID_BANCO}}"
                           class="form-control ">
                </div>

                <div class="form-group">

                    <label>CIDADE</label>
                    <input type="text" name='NO_CIDADE_PRE' id="NO_CIDADE_PRE"
                           value="{{$dadosExportador->FinanciadorPre->NO_CIDADE}}"
                           class="form-control ">
                </div>

                <div class="form-group">

                    <label>ESTADO</label>
                    <select name="NO_ESTADO_PRE" id="NO_ESTADO_PRE"
                            class="form-control"
                            style="width: 100%;">
                        <option value=""> Selecione </option>
                        <option value="AC"
                                @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'AC') selected @endif>Acre
                    </option>
                    <option value="AL"
                            @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'AL') selected @endif>Alagoas
                </option>
                <option value="AM"
                        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'AM') selected @endif>
                        Amazonas
            </option>
            <option value="AP"
                    @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'AP') selected @endif>Amapá
        </option>
        <option value="BA"
                @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'BA') selected @endif>Bahia
    </option>
    <option value="CE"
            @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'CE') selected @endif>Ceará
</option>
<option value="DF"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'DF') selected @endif>
        Distrito Federal
</option>
<option value="ES"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'ES') selected @endif>
        Espirito Santo
</option>
<option value="GO"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'GO') selected @endif>Goiás
</option>
<option value="MA"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'MA') selected @endif>
        Maranhão
</option>
<option value="MG"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'MG') selected @endif>Minas
        Gerais
</option>
<option value="MS"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'MS') selected @endif>Mato
        Grosso do Sul
</option>
<option value="MT"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'MT') selected @endif>Mato
        Grosso
</option>
<option value="PA"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'PA') selected @endif>Pará
</option>
<option value="PB"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'PB') selected @endif>Paraíba
</option>
<option value="PE"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'PE') selected @endif>
        Pernambuco
</option>
<option value="PI"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'PI') selected @endif>Piauí
</option>
<option value="PR"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'PR') selected @endif>Paraná
</option>
<option value="RJ"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'RJ') selected @endif>Rio de
        Janeiro
</option>
<option value="RN"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'RN') selected @endif>Rio
        Grande do Norte
</option>
<option value="RO"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'RO') selected @endif>
        Rondônia
</option>
<option value="RR"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'RR') selected @endif>Roraima
</option>
<option value="RS"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'RS') selected @endif>Rio
        Grande do Sul
</option>
<option value="SC"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'SC') selected @endif>Santa
        Catarina
</option>
<option value="SE"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'SE') selected @endif>Sergipe
</option>
<option value="SP"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'SP') selected @endif>São
        Paulo
</option>
<option value="TO"
        @if($dadosExportador->FinanciadorPre->NO_ESTADO == 'TO') selected @endif>
        Tocantins
</option>
</select>
</div>






</div>

<div class="box-body col-md-4">
    <!-- group -->

    <div class="form-group">

        <label>CEP</label>
        <input type="text" name='NU_CEP_PRE' id="NU_CEP_PRE" data-mask="99999-999"
               value="{{$dadosExportador->FinanciadorPre->NU_CEP}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>CONTATO</label>
        <input type="text" name='NO_CONTATO_PRE'
               value="{{$dadosExportador->FinanciadorPre->NO_CONTATO}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>TELEFONE</label>
        <input type="text" name='NU_TEL_PRE' data-mask="(99) 9999-9999"
               value="{{$dadosExportador->FinanciadorPre->NU_TEL}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>E-MAIL</label>
        <input type="text" name='DS_EMAIL_PRE'
               value="{{$dadosExportador->FinanciadorPre->DS_EMAIL}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>CARGO</label>
        <input type="text" name='NO_CARGO_PRE'
               value="{{$dadosExportador->FinanciadorPre->NO_CARGO}}"
               class="form-control ">
    </div>




</div>
<div class="box-body col-md-4">

    <div class="form-group">
        <label>AGENCIA</label>
        <input type="text" name='ID_AGENCIA_PRE'
               value="{{$dadosExportador->FinanciadorPre->ID_AGENCIA}}"
               class="form-control somentenumero">
    </div>
    <div class="form-group">

        <label>CNPJ</label>
        <input type="text" name='NU_CNPJ_PRE' data-mask="99.999.999/9999-99"
               value="{{$dadosExportador->FinanciadorPre->NU_CNPJ}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>INSC. ESTADUAL</label>
        <input type="text" name='NU_INSCRICAO_PRE'
               value="{{$dadosExportador->FinanciadorPre->NU_INSCRICAO}}"
               class="form-control ">
    </div>
</div>
<!-- /.row -->
<!-- this row will not appear when printing -->
<div class="row no-print">
    <div class="col-xs-12">
        @if($tipoPermissao != 'V')
        <button type="submit" class="btn btn-primary pull-right"
                style="margin-right: 5px;">
            <i class="fa fa-save"></i> Salvar Alterações
        </button>
        @endif
    </div>
</div>

</section>
</form>

@endif
<!-- /.pre -->

<!-- Main pos -->

<form action="{{ route('banco.atualizaFinanc')}}" method="post" name="frmAtualizaDadosFinancPos">
    {{ csrf_field() }}
    <input type="hidden" name="ID_FINANC" value="{{$dadosExportador->FinanciadorPos->ID_FINANC}}">
    <section id="pos">
        <!-- title row -->
        <div class="row">
            <div class="col-xs-12">
                <h2 class="page-header">
                    <i class="glyphicon glyphicon-plane"></i> Pos-Embarque
                    <small class="pull-right">Data: {{ date('d/m/Y', strtotime($dadosExportador->DATA_CADASTRO)) }}</small>

                </h2>
            </div>
            <!-- /.col -->
        </div>
        <!-- info row -->
        <div class="box-body col-md-4">
            <!-- group -->
            <div class="form-group">
                <label>GECEX</label>
                <input type="text" name='NO_GECEX' disabled
                       value="{{$dadosExportador->FinanciadorPos->Gecex->NO_GECEX}}"
                       class="form-control ">

            </div>
            <div class="form-group">

                <label>ENDEREÇO</label>
                <input type="text" name='DS_ENDERECO' id="DS_ENDERECO"
                       value="{{$dadosExportador->FinanciadorPos->DS_ENDERECO}}"
                       class="form-control ">
            </div>

            <div class="form-group">

                <label>NÚMERO DO BANCO</label>
                <input type="text" name='nu_ag_pos'
                       value="{{$dadosExportador->FinanciadorPos->ID_BANCO}}"
                       class="form-control ">
            </div>

            <div class="form-group">

                <label>CIDADE</label>
                <input type="text" name='NO_CIDADE' id="NO_CIDADE"
                       value="{{$dadosExportador->FinanciadorPos->NO_CIDADE}}"
                       class="form-control ">
            </div>

            <div class="form-group">

                <label>ESTADO</label>
                <select name="NO_ESTADO" id="NO_ESTADO"
                        class="form-control"
                        style="width: 100%;">
                    <option value=""> Selecione </option>
                    <option value="AC"
                            @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'AC') selected @endif>Acre
                </option>
                <option value="AL"
                        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'AL') selected @endif>Alagoas
            </option>
            <option value="AM"
                    @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'AM') selected @endif>
                    Amazonas
        </option>
        <option value="AP"
                @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'AP') selected @endif>Amapá
    </option>
    <option value="BA"
            @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'BA') selected @endif>Bahia
</option>
<option value="CE"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'CE') selected @endif>Ceará
</option>
<option value="DF"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'DF') selected @endif>
        Distrito Federal
</option>
<option value="ES"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'ES') selected @endif>
        Espirito Santo
</option>
<option value="GO"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'GO') selected @endif>Goiás
</option>
<option value="MA"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'MA') selected @endif>
        Maranhão
</option>
<option value="MG"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'MG') selected @endif>Minas
        Gerais
</option>
<option value="MS"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'MS') selected @endif>Mato
        Grosso do Sul
</option>
<option value="MT"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'MT') selected @endif>Mato
        Grosso
</option>
<option value="PA"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'PA') selected @endif>Pará
</option>
<option value="PB"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'PB') selected @endif>Paraíba
</option>
<option value="PE"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'PE') selected @endif>
        Pernambuco
</option>
<option value="PI"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'PI') selected @endif>Piauí
</option>
<option value="PR"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'PR') selected @endif>Paraná
</option>
<option value="RJ"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'RJ') selected @endif>Rio de
        Janeiro
</option>
<option value="RN"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'RN') selected @endif>Rio
        Grande do Norte
</option>
<option value="RO"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'RO') selected @endif>
        Rondônia
</option>
<option value="RR"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'RR') selected @endif>Roraima
</option>
<option value="RS"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'RS') selected @endif>Rio
        Grande do Sul
</option>
<option value="SC"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'SC') selected @endif>Santa
        Catarina
</option>
<option value="SE"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'SE') selected @endif>Sergipe
</option>
<option value="SP"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'SP') selected @endif>São
        Paulo
</option>
<option value="TO"
        @if($dadosExportador->FinanciadorPos->NO_ESTADO == 'TO') selected @endif>
        Tocantins
</option>
</select>
</div>
</form>                                                  
</div>

<div class="box-body col-md-4">
    <!-- group -->

    <div class="form-group">

        <label>CEP</label>
        <input type="text" name='NU_CEP' id="NU_CEP" data-mask="99999-999"
               value="{{$dadosExportador->FinanciadorPos->NU_CEP}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>CONTATO</label>
        <input type="text" name='NO_CONTATO'
               value="{{$dadosExportador->FinanciadorPos->NO_CONTATO}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>TELEFONE</label>
        <input type="text" name='NU_TEL' data-mask="(99) 9999-9999"
               value="{{$dadosExportador->FinanciadorPos->NU_TEL}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>E-MAIL</label>
        <input type="text" name='DS_EMAIL'
               value="{{$dadosExportador->FinanciadorPos->DS_EMAIL}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>CARGO</label>
        <input type="text" name='NO_CARGO'
               value="{{$dadosExportador->FinanciadorPos->NO_CARGO}}"
               class="form-control ">
    </div>




</div>
<div class="box-body col-md-4">

    <div class="form-group">
        <label>AGENCIA</label>
        <input type="text" name='ID_AGENCIA'
               value="{{$dadosExportador->FinanciadorPos->ID_AGENCIA}}"
               class="form-control somentenumero">
    </div>
    <div class="form-group">

        <label>CNPJ</label>
        <input type="text" name='NU_CNPJ' data-mask="99.999.999/9999-99"
               value="{{$dadosExportador->FinanciadorPos->NU_CNPJ}}"
               class="form-control ">
    </div>

    <div class="form-group">

        <label>INSC. ESTADUAL</label>
        <input type="text" name='NU_INSCRICAO'
               value="{{$dadosExportador->FinanciadorPos->NU_INSCRICAO}}"
               class="form-control ">
    </div>

</div>
<!-- /.row -->


<br><br>

<!-- this row will not appear when printing -->
<div class="row no-print">
    <div class="col-xs-12">
        <a href="#" target="_blank" class="btn btn-default"><i
                class="fa fa-print"></i> Imprimir</a>
        @if($tipoPermissao != 'V')

        <button type="submit" class="btn btn-primary pull-right"
                style="margin-right: 5px;">
            <i class="fa fa-save"></i> Salvar Alterações
        </button>

            @if (@count($dadosExportador->FinanciadorPre)>0)
                <button type="button" class="btn btn-success pull-right copiarDadosPre" style="margin-right: 5px;">
                    <i class="fa fa-copy"></i> Copiar dados do pre-embarque
                </button>
            @endif
        @endif
    </div>
</div>
</section>

<!-- /.pos -->

</div>

<!-- /.tab-pane -->


<div class="tab-pane" id="infoexportador">
    <form action="{{ route('banco.atualizaInfoAddExportador')}}" method="post" name="frmatualizaInfoAddExportador" id="frmatualizaInfoAddExportador">
        {{ csrf_field() }}        
        <input type="hidden" name="ID_USUARIO" value="{{@$dadosExportador->ID_USUARIO}}">
        <input type="hidden" name="ATUALIZACAO_CADASTRAL" value="{{@$notificacao->DE_NOTIFICACAO}}">
        <input type="hidden" name="tipoPermissao" value="{{@$tipoPermissao}}">
        <input type="hidden" name="ID_NOTIFICACAO" id="ID_NOTIFICACAO" value="{{@$notificacao->ID_NOTIFICACAO}}">
        <input type="hidden" name="ID_INF_ADICIONAL" value="{{@$dadosExportador->InfoAdicionalExportador->ID_INF_ADICIONAL}}">

        <!-- Editor a -->
        <div class="">
            
            
            <div class="box-header">
                <h3 class="box-title">A) Cliente possui Cadastro atualizado - Tipo Completo
                    e Situação Normal? </h3>
              
                <input type="radio" name="a" value="Sim"
                       @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP1)) == "") 
                       checked              
                       @endif> Sim 
                       <input type="radio" class="a" name="a" value="Nao"
                       @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP1)) != "") 
                       checked              
                       @endif
                       > Não
                       
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">
                <form>
                    <textarea class="textarea ckeditor respa"  id="editor1" placeholder="" @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP1)) == "" && @$tipoPermissao == 'V') disabled="true" @endif 
                              style="width: 100%; height: 200px; font-size: 14px; line-height: 18px;
                              border: 1px solid #dddddd; padding: 10px;" name="respa">{{@$dadosExportador->InfoAdicionalExportador->DS_RESP1}}

                    </textarea>


            </div>
        </div>

        <!-- /Editor a -->

        <!-- Editor b -->
        <div class="">
            <div class="box-header">
                <h3 class="box-title">B) Cliente possui Limite de Crédito analisado e
                    deferido pelo Banco, na situação Vigente?
                </h3>
                <input type="radio" name="b" id="b" value="Sim"
                       @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP2)) == "") 
                       checked              
                       @endif
                       > Sim 
                       <input type="radio" class="b" name="b" id="b" value="Nao"
                       @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP2)) != "") 
                       checked              
                       @endif
                       > Não
            </div>
            <!-- /.box-header -->
            <div class="box-body pad">

                <textarea class="textarea ckeditor respb" id="editor2" placeholder="" @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP2)) == "" && @$tipoPermissao == 'V') disabled="true" @endif
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;" name="respb">
                    {{@$dadosExportador->InfoAdicionalExportador->DS_RESP2}}                                        

                </textarea>

            </div>
        </div>

        <!-- /Editor b -->

        <!-- Editor c -->
        <div class="">
            <div class="box-header">
                <h3 class="box-title">C) Cliente com classificação de risco avaliado pelo
                    Banco como A, B, C ou D?
                </h3>
                <input type="radio" name="c" id="c" value="Sim"
                       @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP3)) == "") 
                       checked              
                       @endif
                       > Sim 
                       <input type="radio" class="c" name="c" id="c" value="Nao"
                       @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP3)) != "") 
                       checked              
                       @endif
                       > Não

            </div>
            <!-- /.box-header -->
            <div class="box-body pad">

                <textarea class="textarea ckeditor respc" placeholder=""  id="editor3" name="respc" @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP3)) == "" && @$tipoPermissao == 'V') disabled="true" @endif
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                    {{@$dadosExportador->InfoAdicionalExportador->DS_RESP3}}                                        



                </textarea>

            </div>
        </div>

        <!-- /Editor c -->

        <!-- Editor d -->
        <div class="">
            <div class="box-header">
                <h3 class="box-title">D) A MPME possui empresas coligadas? Quais?
                </h3>

     <!--- <small class="label label-success"><i class="fa fa-check"></i> Sim</small> --->

                                           <!--- <small class="label label-danger"><i class="fa fa-close"></i> Não</small> --->

                <input type="radio" name="d" id="d" value="Sim"
                       @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP4)) != "") 
                       checked              
                       @endif
                       > Sim 
                       <input type="radio" class="d" name="d" id="d" value="Nao"
                       @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP4)) == "") 
                       checked              
                       @endif
                       > Não                    

            </div>
            <!-- /.box-header -->
            <div class="box-body pad">

                <textarea class="textarea ckeditor respd" placeholder="" name="respd" id="editor4" @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP4)) != "" && @$tipoPermissao == 'V') disabled="true" @endif
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                    {{@$dadosExportador->InfoAdicionalExportador->DS_RESP4}}                               

                </textarea>

            </div>
        </div>

        <!-- /Editor d -->


        <!-- this row will not appear when printing -->
        <div class="row no-print">
            <div class="col-xs-12">
                <a href="#" target="_blank" class="btn btn-default"><i
                        class="fa fa-print"></i> Imprimir</a>
                <button type="submit" class="btn btn-success pull-right salvar"
                        style="margin-right: 5px;">
                    <i class="fa fa-save"></i> Salvar & Enviar
                </button>
            </div>
        </div>    

        

</div>
 @if($tipoPermissao == 'V')
<div class="panel-footer" style="margin-top: 10px;">
            <div class="row">
                <div class="col-md-6 text-left"><a href="javascrit:history.go(-1);"class="btn btn-default text-left" ><i class="fa fa-arrow-circle-o-left"></i> Voltar</a></div>
                <div class="col-md-6 text-right">
                      <button type="button" class="btn btn-warning text-right" id="devolver" ><i class="fa fa-undo"></i> Devolver</button>
                </div>
            </div>

        </div>
@endif
</form>

</div>
<!-- /.tab-content -->
</div>
<!-- /.mailbox-read-message -->
</div>
<!-- /.box-body -->

<!-- /.box-footer -->
</div>
<!-- /. box -->
</div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>
<script type="text/javascript" src="{{ asset('js/banco/exportador/liberacao_cadastro.js').'?'.time() }}"></script>

<script type="text/jasvascript">
   
    CKEDITOR.instances['editor1'].updateElement();
    CKEDITOR.instances['editor2'].updateElement();
    CKEDITOR.instances['editor3'].updateElement();
    CKEDITOR.instances['editor4'].updateElement();

</script>
@endsection
