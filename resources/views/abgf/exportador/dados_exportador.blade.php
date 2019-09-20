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
            <!-- /. box -->
            @include('layouts.menu_abgf')
            <!-- /.box -->
        </div>



        <!-- /.col -->
        <div class="col-md-9">
            <div>

                <!-- /.box-header -->
                <div class="box-body no-padding">

                    <!-- /.mailbox-read-info -->
                    <div class="nav-tabs-custom">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#dados_exportador" data-toggle="tab">Dados do Exportador</a></li>
                            <li><a href="#dados_financiador" data-toggle="tab">Dados da Instituição Financeira</a></li>
                            <li><a href="#info_adicionais_banco" data-toggle="tab" @if(@$dadosExportador->FinanciadorPos->gecex->NO_GECEX == "") style="display:none" @endif>Informações Adicionais da Instituição Financeira</a></li>
                            <li><a href="#enquadramento" data-toggle="tab">Enquadramento</a></li>
                            @if(@$notificacao->DE_NOTIFICACAO != 'ATUALIZACAO_CADASTRAL')<li><a href="#lista" data-toggle="tab">Validação</a></li>@endif
                            @if(@$notificacao->DE_NOTIFICACAO != 'ATUALIZACAO_CADASTRAL')<li><a href="#liberacao" data-toggle="tab">Liberação de Cadastro</a></li>@endif
                        </ul>
                        <div class="tab-content">
                            <div class="active tab-pane" id="dados_exportador">
                                <!-- Dados Exportador -->
                                <form action="{{ route('abgf.exportador.salvaAlteracoesExportador')}}" method="post" name="salvaAlteracoesExportador">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="ID_USUARIO" value="{{@$dadosExportador->ID_USUARIO}}" id="ID_USUARIO_EXPORTA">
                                    <div>

                                        <div class="box-body col-md-6">
                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Razão Social</label>
                                                <input type="text" name='NM_USUARIO' value="{{$dadosExportador->NM_USUARIO}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>CNPJ da Empresa</label>
                                                <input type="text" name='NU_CNPJ' value="{{$dadosExportador->NU_CNPJ}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Endereço</label>
                                                <input type="text" name='DE_ENDER' value="{{$dadosExportador->DE_ENDER}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>CEP</label>
                                                <input type="text" name='DE_CEP' value="{{$dadosExportador->DE_CEP}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Nome do Contato</label>
                                                <input type="text" name='NM_CONTATO' value="{{$dadosExportador->NM_CONTATO}}" class="form-control ">
                                            </div>
                                            <!-- group -->
                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Cargo do Contato</label>
                                                <input type="text" name='DE_CARGO' value="{{$dadosExportador->DE_CARGO}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Telefone</label>
                                                <input type="text" name='DE_TEL' value="{{$dadosExportador->DE_TEL}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>E-Mail do Contato</label>
                                                <input type="text" name='DE_EMAIL' value="{{$dadosExportador->DE_EMAIL}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Modalidade do Financiamento</label>
                                                <br>

                                                @foreach($dadosExportador->ClienteExportador->ModalidadeFinanciamento as
                                                $CliMod)

                                                <span class="label label-primary">{{$CliMod
                                            ->ModalidadeFinanciamento->NO_MODALIDADE_FINANCIAMENTO}}</span>
                                                @endforeach
                                            </div>
                                            <!-- group -->





                                        </div>

                                        <div class="box-body col-md-6">

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Tempo de Existência da empresa</label>
                                                <select name="ID_TEMPO" id="ID_TEMPO" class="form-control select2" style="width: 100%;">
                                                    <option value="1" @if($dadosExportador->ID_TEMPO == 1) selected @endif >Até 3 anos</option>
                                                    <option value="2" @if($dadosExportador->ID_TEMPO == 2) selected @endif >Acima de 3 anos</option>
                                                </select>
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>(*) Valor de Exportação do ano civil anterior</label>
                                                <input type="text" name='VL_BRUTO_ANUAL' value="{{formatar_valor_sem_moeda($dadosExportador->VL_BRUTO_ANUAL)}}" class="form-control @if($dadosExportador->FL_ATIVO == 0) money @endif"  @if($dadosExportador->FL_ATIVO == 1) readonly="readonly" @endif >
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Faturamento Bruto do ano civil anterior</label>
                                                <input type="text" name='VL_EXP_BRUTA' value="{{formatar_valor_sem_moeda($dadosExportador->VL_EXP_BRUTA)}}" class="form-control @if($dadosExportador->FL_ATIVO == 0) money @endif"   @if($dadosExportador->FL_ATIVO == 1) readonly="readonly" @endif >
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Inscr. Est.</label>
                                                <input type="text" name='NU_INSCR_EST' value="{{$dadosExportador->NU_INSCR_EST}}" class="form-control ">
                                            </div>

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Inscr. Municipal.</label>
                                                <input type="text" name='NU_INSCR_MUNICIPAL' value="{{$dadosExportador->NU_INSCR_MUNICIPAL}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Cidade</label>
                                                <input type="text" name='DE_CIDADE' value="{{$dadosExportador->DE_CIDADE}}" class="form-control ">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Estado</label>
                                                <select name="CD_UF" id="CD_UF" class="form-control select2" style="width: 100%;">

                                                    <option value="AC" @if($dadosExportador->CD_UF == 'AC') selected @endif>Acre</option>
                                                    <option value="AL" @if($dadosExportador->CD_UF == 'AL') selected @endif>Alagoas</option>
                                                    <option value="AM" @if($dadosExportador->CD_UF == 'AM') selected @endif>Amazonas</option>
                                                    <option value="AP" @if($dadosExportador->CD_UF == 'AP') selected @endif>Amapá</option>
                                                    <option value="BA" @if($dadosExportador->CD_UF == 'BA') selected @endif>Bahia</option>
                                                    <option value="CE" @if($dadosExportador->CD_UF == 'CE') selected @endif>Ceará</option>
                                                    <option value="DF" @if($dadosExportador->CD_UF == 'DF') selected @endif>Distrito Federal</option>
                                                    <option value="ES" @if($dadosExportador->CD_UF == 'ES') selected @endif>Espirito Santo</option>
                                                    <option value="GO" @if($dadosExportador->CD_UF == 'GO') selected @endif>Goiás</option>
                                                    <option value="MA" @if($dadosExportador->CD_UF == 'MA') selected @endif>Maranhão</option>
                                                    <option value="MG" @if($dadosExportador->CD_UF == 'MG') selected @endif>Minas Gerais</option>
                                                    <option value="MS" @if($dadosExportador->CD_UF == 'MS') selected @endif>Mato Grosso do Sul</option>
                                                    <option value="MT" @if($dadosExportador->CD_UF == 'MT') selected @endif>Mato Grosso</option>
                                                    <option value="PA" @if($dadosExportador->CD_UF == 'PA') selected @endif>Pará</option>
                                                    <option value="PB" @if($dadosExportador->CD_UF == 'PB') selected @endif>Paraíba</option>
                                                    <option value="PE" @if($dadosExportador->CD_UF == 'PE') selected @endif>Pernambuco</option>
                                                    <option value="PI" @if($dadosExportador->CD_UF == 'PI') selected @endif>Piauí</option>
                                                    <option value="PR" @if($dadosExportador->CD_UF == 'PR') selected @endif>Paraná</option>
                                                    <option value="RJ" @if($dadosExportador->CD_UF == 'RJ') selected @endif>Rio de Janeiro</option>
                                                    <option value="RN" @if($dadosExportador->CD_UF == 'RN') selected @endif>Rio Grande do Norte</option>
                                                    <option value="RO" @if($dadosExportador->CD_UF == 'RO') selected @endif>Rondônia</option>
                                                    <option value="RR" @if($dadosExportador->CD_UF == 'RR') selected @endif>Roraima</option>
                                                    <option value="RS" @if($dadosExportador->CD_UF == 'RS') selected @endif>Rio Grande do Sul</option>
                                                    <option value="SC" @if($dadosExportador->CD_UF == 'SC') selected @endif>Santa Catarina</option>
                                                    <option value="SE" @if($dadosExportador->CD_UF == 'SE') selected @endif>Sergipe</option>
                                                    <option value="SP" @if($dadosExportador->CD_UF == 'SP') selected @endif>São Paulo</option>
                                                    <option value="TO" @if($dadosExportador->CD_UF == 'TO') selected @endif>Tocantins</option>
                                                </select>


                                            </div>
                                            <!-- group -->


                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Fax</label>
                                                <input type="text" name='DE_FAX' value="{{$dadosExportador->DE_FAX}}" class="form-control">
                                            </div>
                                            <!-- group -->

                                            <!-- group -->
                                            <div class="form-group">
                                                <label>Moeda da Operação</label>
                                                <select name="ID_MOEDA" id="ID_MOEDA" class="form-control select2" style="width: 100%;">
                                                    <option value="1" @if($dadosExportador->ID_MOEDA == 1) selected @endif >USD</option>
                                                    <option value="3" @if($dadosExportador->ID_MOEDA == 3) selected @endif >EUR</option>
                                                </select>
                                            </div>
                                            <!-- group -->

                                            <div class="form-group">
                                                <label>Data Cadastro</label>
                                                <br>
                                               <span class="label label-primary">{{formatar_data_hora($dadosExportador->DATA_CADASTRO ?? '') ?? ''}}</span>
                                            </div>
                                            <!-- group -->

                                        </div>

                                        <!-- /.box-body -->
                                        <div class="clear"></div>
                                        <div class="box-footer">
                                            <h4><strong>Informações dos sócios</strong></h4>

                                            <div class="box-body col-md-6">
                                                <!-- group -->
                                                <div class="form-group">
                                                    <label>Nome</label>
                                                    <input type="text" name='NOME_QUADRO' disabled="disabled" value="{{$dadosExportador->NOME_QUADRO}}" class="form-control">
                                                </div>
                                                <!-- group -->

                                                <!-- group -->
                                                <div class="form-group">
                                                    <label>CPF/CNPJ</label>
                                                    <input type="text" name='CPF_CNPJ_QUADRO' disabled="disabled" value="{{$dadosExportador->CPF_CNPJ_QUADRO}}" class="form-control">
                                                </div>
                                                <!-- group -->

                                                <!-- group -->
                                                <div class="form-group">
                                                    <label>Participação</label>
                                                    <input type="text" name='PARTICIPACAO_QUADRO' disabled="disabled" value="{{$dadosExportador->PARTICIPACAO_QUADRO}}" class="form-control">
                                                </div>
                                                <!-- group -->

                                            </div>

                                            <div class="box-body col-md-6">
                                                <!-- group -->
                                                <div class="form-group">
                                                    <label>Nome</label>
                                                    <input type="text" name='DE_FAX' disabled="disabled" value="{{$dadosExportador->DE_FAX}}" class="form-control">
                                                </div>
                                                <!-- group -->

                                                <!-- group -->
                                                <div class="form-group">
                                                    <label>CPF/CNPJ</label>
                                                    <input type="text" name='DE_FAX' disabled="disabled" value="{{$dadosExportador->DE_FAX}}" class="form-control">
                                                </div>
                                                <!-- group -->

                                                <!-- group -->
                                                <div class="form-group">
                                                    <label>Participação</label>
                                                    <input type="text" name='DE_FAX' disabled="disabled" value="{{$dadosExportador->DE_FAX}}" class="form-control">
                                                </div>
                                                <!-- group -->

                                            </div>

                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label>Divergências enviadas pelo Banco</label>
                                                <textarea name="ds_divergencia" id="ds_divergencia" class="form-control" style="height: 300px;" disabled="disabled">{{$dadosExportador->DS_DIVERGENCIA}}</textarea>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Print -->

                                    <!-- this row will not appear when printing -->
                                    <div class="row no-print">
                                        <div class="col-xs-12">
                                            <button onclick="printDiv('dados_exportador')" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</button>
                                            @can('ATUALIZAR_DADOS_EXPORTADOR')
                                                <button type="submit" class="btn btn-primary pull-right" style="margin-right: 5px;">
                                                    <i class="fa fa-save"></i> Atualizar Dados Exportador
                                                </button>
                                            @endcan
                                        </div>
                                    </div>

                                    <!-- /.post -->
                                </form>
                            </div>

                            <!-- /.tab-pane -->
                            <div class="tab-pane" id="dados_financiador">

                                <!-- Main pre -->
                                @if($dadosExportador->FinanciadorPre != '')
                                <section id="pre">
                                    <!-- title row -->
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h2 class="page-header">
                                                <i class="glyphicon glyphicon-usd"></i> Pré-Embarque
                                                <small class="pull-right">Data: {{ date('d/m/Y', strtotime($dadosExportador->DATA_CADASTRO)) }}</small>
                                            </h2>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- info row -->
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">

                                            <address>
                                                <strong>Gecex:</strong>
                                                {{$dadosExportador->FinanciadorPre->Gecex->NO_GECEX}}<br>
                                                <strong>Endereço:</strong> {{str_limit($dadosExportador->FinanciadorPre->DS_ENDERECO, 30)}}<br>
                                                <strong>Numero:</strong> {{$dadosExportador->FinanciadorPre->NU_AG_NOVA}}<br>
                                                <strong>Cidade:</strong> {{$dadosExportador->FinanciadorPre->NO_CIDADE}}<br>
                                                <strong>CEP:</strong> {{$dadosExportador->FinanciadorPre->NU_CEP}}<br>

                                            </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">

                                            <address>

                                                <strong>Agencia:</strong>{{$dadosExportador->FinanciadorPre->ID_AGENCIA}} <br>
                                                <strong>Contato:</strong> {{$dadosExportador->FinanciadorPre->NO_CONTATO}}<br>
                                                <strong>Telefone:</strong> {{$dadosExportador->FinanciadorPre->NU_TEL}}<br>
                                                <strong>E-mail:</strong> {{$dadosExportador->FinanciadorPre->DS_EMAIL}}<br>
                                                <strong>Cargo:</strong> {{$dadosExportador->FinanciadorPre->NO_CARGO}}<br>

                                            </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                            <address>
                                                <!-- radio -->
                                                <strong>CNPJ:</strong> {{$dadosExportador->FinanciadorPre->NU_CNPJ}}<br>
                                                <strong>Inscr. Est.:</strong> @if($dadosExportador->FinanciadorPre->DS_EMAIL != ''){{$dadosExportador->FinanciadorPre->DS_EMAIL}} @else - @endif<br>
                                                <strong>Estado:</strong> {{$dadosExportador->FinanciadorPre->NO_ESTADO}}<br>

                                            </address>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->


                                </section>

                                @endif
                                <!-- /.pre -->

                                <!-- Main pos -->

                                @if(@$dadosExportador->FinanciadorPos != '')
                                <section id="pos">
                                    <!-- title row -->
                                    <div class="row">
                                        <div class="col-xs-12">
                                            <h2 class="page-header">
                                                <i class="glyphicon glyphicon-plane"></i> Pos-Embarque

                                                <small class="pull-right">Data: {{ date('d/m/Y', strtotime($dadosExportador->FinanciadorPos->DATA_CADASTRO ?? '-')) }}</small>

                                            </h2>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- info row -->
                                    <div class="row invoice-info">
                                        <div class="col-sm-4 invoice-col">

                                            <address>
                                                <strong>Gecex:</strong>

                                                {{$dadosExportador->FinanciadorPos->gecex->NO_GECEX ?? '-'}}<br>
                                                <strong>Endereço:</strong> {{str_limit($dadosExportador->FinanciadorPos->DS_ENDERECO ?? '-', 30)}}<br>
                                                <strong>Numero:</strong> {{$dadosExportador->FinanciadorPos->NU_AG_NOVA ?? '-'}}<br>
                                                <strong>Cidade:</strong> {{$dadosExportador->FinanciadorPos->NO_CIDADE ?? '-'}}<br>
                                                <strong>CEP:</strong> {{$dadosExportador->FinanciadorPos->NU_CEP ?? '-'}}<br>


                                            </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">

                                            <address>
                                                <strong>Agencia:</strong> {{$dadosExportador->FinanciadorPos->ID_AGENCIA ?? '-'}}<br>
                                                <strong>Contato:</strong> {{$dadosExportador->FinanciadorPos->NO_CONTATO ?? '-'}}<br>
                                                <strong>Telefone:</strong> {{$dadosExportador->FinanciadorPos->NU_TEL ?? '-'}}<br>
                                                <strong>E-mail:</strong> {{$dadosExportador->FinanciadorPos->DS_EMAIL ?? '-'}}<br>
                                                <strong>Cargo:</strong> {{$dadosExportador->FinanciadorPos->NO_CARGO ?? '-'}}<br>
                                            </address>
                                        </div>
                                        <!-- /.col -->
                                        <div class="col-sm-4 invoice-col">
                                            <address>
                                                <!-- radio -->
                                                <strong>CNPJ:</strong> {{$dadosExportador->FinanciadorPos->NU_CNPJ ?? '-'}}<br>
                                                <strong>Inscr. Est.:</strong> @if($dadosExportador->FinanciadorPos->DS_EMAIL != ''){{$dadosExportador->FinanciadorPos->DS_EMAIL ?? '-'}} @else - @endif<br>
                                                <strong>Estado:</strong> {{$dadosExportador->FinanciadorPos->NO_ESTADO ?? '-'}}<br>

                                            </address>
                                        </div>
                                        <!-- /.col -->
                                    </div>
                                    <!-- /.row -->



                                    <!-- this row will not appear when printing -->
                                    <div class="row no-print">
                                        <div class="col-xs-12">
                                            <button onclick="printDiv('dados_financiador')" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</button>
                                        </div>
                                    </div>
                                </section>

                                @endif
                                <!-- /.pos -->

                            </div>

                            <!-- /.tab-pane -->


                            <div class="tab-pane" id="info_adicionais_banco" @if(@$dadosExportador->FinanciadorPos->gecex->NO_GECEX == "") style="display:none" @endif >

                                <!-- Editor a -->
                                <div class="">
                                    <div class="box-header">
                                        <h3 class="box-title">A) Cliente possui Cadastro atualizado - Tipo Completo e Situação Normal?</h3>

                                        @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP1)) == "")
                                        <small class="label label-success"><i class="fa fa-check"></i> Sim</small>
                                        @else
                                        <small class="label label-danger"><i class="fa fa-close"></i> Não</small>
                                        @endif
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body pad">
                                        <form>
                                            <textarea class="textarea ckeditor respa" disabled placeholder="" name="respa"
                                                      style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                {{@$dadosExportador->InfoAdicionalExportador->DS_RESP1}}

                                            </textarea>

                                        </form>
                                    </div>
                                </div>

                                <!-- /Editor a -->

                                <!-- Editor b -->
                                <div class="">
                                    <div class="box-header">
                                        <h3 class="box-title">B) Cliente possui Limite de Crédito analisado e deferido pelo Banco, na situação Vigente?
                                        </h3>



                                        @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP2)) == "")
                                        <small class="label label-success"><i class="fa fa-check"></i> Sim</small>
                                        @else
                                        <small class="label label-danger"><i class="fa fa-close"></i> Não</small>
                                        @endif

                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body pad">
                                        <form>
                                            <textarea class="textarea ckeditor respb" disabled placeholder="Place some text here" name="respb"
                                                      style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                {{@$dadosExportador->InfoAdicionalExportador->DS_RESP2}}
                                            </textarea>
                                        </form>
                                    </div>
                                </div>

                                <!-- /Editor b -->

                                <!-- Editor c -->
                                <div class="">
                                    <div class="box-header">
                                        <h3 class="box-title">C) Cliente com classificação de risco avaliado pelo Banco como A, B, C ou D?
                                        </h3>

                                        @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP3)) == "")
                                        <small class="label label-success"><i class="fa fa-check"></i> Sim</small>
                                        @else
                                        <small class="label label-danger"><i class="fa fa-close"></i> Não</small>
                                        @endif

                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body pad">
                                        <form>
                                            <textarea class="textarea ckeditor respc" disabled placeholder="Place some text here" name="respc"
                                                      style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                {{@$dadosExportador->InfoAdicionalExportador->DS_RESP3}}
                                            </textarea>
                                        </form>
                                    </div>
                                </div>

                                <!-- /Editor c -->

                                <!-- Editor d -->
                                <div class="">
                                    <div class="box-header">
                                        <h3 class="box-title">D) A MPME possui empresas coligadas? Quais?
                                        </h3>
                                        @if(@trim(strip_tags($dadosExportador->InfoAdicionalExportador->DS_RESP4)) != "")
                                        <small class="label label-success"><i class="fa fa-check"></i> Sim</small>
                                        @else
                                        <small class="label label-danger"><i class="fa fa-close"></i> Não</small>
                                        @endif
                                    </div>
                                    <!-- /.box-header -->
                                    <div class="box-body pad">
                                        <form>
                                            <textarea class="textarea ckeditor respd" disabled placeholder="Place some text here" name="respd"
                                                      style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;">
                                                {{@$dadosExportador->InfoAdicionalExportador->DS_RESP4}}
                                            </textarea>
                                        </form>
                                    </div>
                                </div>

                                <!-- /Editor d -->

                                <div class="callout callout-success text-center">
                                    <p><b>Dados Confirmados pelo Banco.</b></p>
                                </div>



                            </div>

                            <div class="tab-pane" id="lista">
                                <form action="{{ route('abgf.exportador.salvaListaTarefa')}}" method="post" name="salvaListaTarefa">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="ID_USUARIO" value="{{@$dadosExportador->ID_USUARIO}}">
                                    <input type="hidden" name="ID_MPME_CLIENTE_EXPORTADORES" value="{{@$dadosExportador->ClienteExportador->ID_MPME_CLIENTE_EXPORTADORES}}">
                                    <!-- TO DO List -->
                                    <div class="">

                                        <!-- /.box-header -->
                                        <div class="box-body">
                                            <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->


                                            <ul class="todo-list">


                                                <li>
                                                    <!-- drag handle -->
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <!-- checkbox -->
                                                    <input type="checkbox" class="valiacao_arquivos" name="id_check[]" value='1' @if(in_array(1, $listaTarefas)) checked @endif />

                                                           <!-- todo text -->
                                                           <span class="text">Consulta ao SIMPLES NACIONAL</span>
                                                            <small class="label label-info"><a href="http://www8.receita.fazenda.gov.br/simplesnacional/aplicacoes.aspx?id=21" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>


                                                <li>
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input class="valiacao_arquivos" type="checkbox" value="3" name="id_check[]" @if(in_array(3, $listaTarefas)) checked @endif />
                                                           <span class="text">Informações do MDIC - Perfil das Exportações</span>
                                                    <small class="label label-info"><a href="http://www.desenvolvimento.gov.br/sitio/interna/interna.php?area=5&menu=603" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>
                                                <li>
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input  class="valiacao_arquivos" type="checkbox" value="4" name="id_check[]" @if(in_array(4, $listaTarefas)) checked @endif />
                                                           <span class="text">Situação de Regularidade do Empregador - FGTS</span>
                                                    <small class="label label-info"><a href="https://webp.caixa.gov.br/cidadao/Crf/FgeCfSCriteriosPesquisa.asp" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>
                                                <li>
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input class="valiacao_arquivos" type="checkbox" value="5" name="id_check[]" @if(in_array(5, $listaTarefas)) checked @endif />

                                                           <span class="text">Certidão Negativa de Débitos - PGFN </span>
                                                    <small class="label label-info"><a href="http://www.receita.fazenda.gov.br/aplicacoes/ATSPO/certidao/CndconjuntaInter/InformaNICertidao.asp?Tipo=1" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>

                                                <li>
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input class="valiacao_arquivos" type="checkbox" value="6" name="id_check[]" @if(in_array(6, $listaTarefas)) checked @endif />

                                                           <span class="text">Comprovante de Inscrição e de Situação Cadastral - CNPJ</span>
                                                    <small class="label label-info"><a href="http://www.receita.fazenda.gov.br/PessoaJuridica/CNPJ/cnpjreva/Cnpjreva_Solicitacao.asp" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>

                                                <li>
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input class="valiacao_arquivos" type="checkbox" value="8" name="id_check[]" @if(in_array(8, $listaTarefas)) checked @endif />

                                                           <span class="text">Demonstrativo de Resultado - DRE</span>
                                                    <small class="label label-info"><a href="http://www010.dataprev.gov.br/CWS/CONTEXTO/PCND1/PCND1.HTML" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>

                                                <li>
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input class="valiacao_arquivos" type="checkbox" value="9" name="id_check[]" @if(in_array(9, $listaTarefas)) checked @endif />

                                                           <span class="text">Consulta ao CEIS e CNEP </span>
                                                           <small class="label label-info"><a href="http://www.portaltransparencia.gov.br/sancoes" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>

                                                <li>
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input class="valiacao_arquivos" type="checkbox" value="10" name="id_check[]" @if(in_array(10, $listaTarefas)) checked @endif />

                                                           <span class="text">Cadastro de Empregadores que tenham submetido trabalhadores a condições análogas à de escravo </span>
                                                           <small class="label label-info"><a href="http://trabalho.gov.br/fiscalizacao-combate-trabalho-escravo" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>

                                                <li>
                                                    <span class="">
                                                        <i class="fa fa-ellipsis-v"></i>
                                                        <i class="fa fa-ellipsis-v"></i>
                                                    </span>
                                                    <input class="valiacao_arquivos" type="checkbox" value="11" name="id_check[]" @if(in_array(11, $listaTarefas)) checked @endif />

                                                           <span class="text">Consulta de Autuações Ambientais e Embargos </span>
                                                           <small class="label label-info"><a href="https://servicos.ibama.gov.br/ctf/publico/areasembargadas/ConsultaPublicaAreasEmbargadas.php" target="_blank"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                                                </li>

                                            </ul>

                                        </div>
                                        <!-- /.box-body -->
                                        @can('SALVAR_VALIDACAO')
                                            @if($dadosExportador->FL_ATIVO == 0)
                                                <div class="box-footer clearfix no-border">
                                                    <button type="submit" class="btn btn-success pull-right"><i class="fa fa-save"></i> Salvar</button>
                                                </div>
                                            @endif
                                        @endcan
                                    </div>
                                </form>
                                <!-- /.box -->

                            </div>
                            <div class="tab-pane" id="enquadramento">
                                <form method="post" action="{{ route('abgf.exportador.enquadramento')}}" id="frmUploadFixa" enctype="multipart/form-data">
                                 <input type="hidden" name="ATUALIZACAO_CADASTRAL" id="ATUALIZACAO_CADASTRAL" value="{{@$notificacao->DE_NOTIFICACAO}}">
                                 <input type="hidden" name="ID_NOTIFICACAO" id="ID_NOTIFICACAO" value="{{@$idNotificacao}}"> 
                                    {{ csrf_field() }}
                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Dados Financeiros do Exportador</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="box-body pad">
                                                        <div class="row">

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Valor Exportação Anual</label>
                                                                    <div class="input-group date">
                                                                        <span>US$ {{formatar_valor_sem_moeda($dadosExportador->VL_BRUTO_ANUAL)}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Valor Faturamento Anual</label>
                                                                    <div class="input-group date">
                                                                        <span>R$ {{formatar_valor_sem_moeda($dadosExportador->VL_EXP_BRUTA)}}</span>
                                                                    </div>
                                                                </div>
                                                            </div>



                                                        </div>

                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h3 class="panel-title">Modalidades para enquadramento:</h3>
                                                </div>
                                                <div class="panel-body">
                                                    <div class="box-body pad">
                                                        <div class="row">
                                                            <table class="table table-bordered">
                                                                <thead>
                                                                    <th>Nome da modalidade</th>
                                                                    <th>Enquadrada</th>
                                                                    <th>Aprovar modalidade</th>
                                                                </thead>
                                                                <tbody>
                                                                @php $id_modalidade = ''; @endphp

                                                                     @foreach($dadosExportador->ClienteExportador->ModalidadeFinanciamento as
                                                                         $clienteModalidadeFinanciamentos)
                                                                     @if($id_modalidade != $clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE)
                                                                        <tr>
                                                                            <td>{{$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->Modalidade->NO_MODALIDADE}}</td>
                                                                            <td>
                                                                                @if(isset($dados[$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE]['enquaradrado']) && $dados[$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE]['enquaradrado'] == "SIM")
                                                                                    SIM
                                                                                @else
                                                                                    NÃO
                                                                                @endif
                                                                            </td>
                                                                            <td>
                                                                            @if(isset($dados[$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE]['enquaradrado']) && $dados[$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE]['enquaradrado'] == "SIM")
                                                                              @can('APROVAR_ENQUADRAMENTO')
                                                                                <input class="btn btn-primary aprovar_modalidade" type="button" id="aprovar_modalidade_{{$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE}}" onclick="aprovarModalidade('{{$clienteModalidadeFinanciamentos->ID_MPME_CLIENTE_EXPORTADORES}}', '{{$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE}}')" class="btn btn-sucess"  value="<?php echo ( $clienteModalidadeFinanciamentos->IN_REGISTRO_ATIVO == 'N') ? 'APROVAR' : 'APROVADO'?>"  <?php echo ( $clienteModalidadeFinanciamentos->IN_REGISTRO_ATIVO == 'N') ? '' : 'disabled=disabled'?>">
                                                                              @endcan
                                                                            @endif  
                                                                            </td>
                                                                        </tr>

                                                                     @endif
                                                                    @php $id_modalidade = $clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE; @endphp
                                                                      @endforeach
                                                                </tbody>
                                                            </table>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                            

                                </form>

                                @if(@$notificacao->DE_NOTIFICACAO == 'ATUALIZACAO_CADASTRAL')
                                   <div class="box-footer clearfix no-border">
                                        <button type="button" onclick="concluir_atualizacao();" id="concluir" name="concluir_atualizacao" class="btn btn-success pull-right"><i class="fa fa fa-save"></i> Concluir Atualização</button>
                                    </div>

                                                                @endif
                            </div>
              
                        @if(@$notificacao->DE_NOTIFICACAO != 'ATUALIZACAO_CADASTRAL')    
                            <div class="tab-pane" id="liberacao">
                                <form method="post" action="{{ route('abgf.exportador.fichaCadastral')}}" name="frmLiberacaoCadastro" id="frmLiberacaoCadastro" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="hidden" name="ID_USUARIO" value="{{@$dadosExportador->ID_USUARIO}}" id="ID_USUARIO">
                                    <input type="hidden" name="ID_NOTIFICACAO" value="{{@$idNotificacao}}"> 
                                    <input type="hidden" name="ID_MPME_CLIENTE_EXPORTADORES" value="{{@$dadosExportador->ClienteExportador->ID_MPME_CLIENTE_EXPORTADORES}}">

                                    <br>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="panel panel-default">
                                                <div class="panel-heading clearfix">
                                                    <h3 class="panel-title pull-left" style="padding-top: 7.5px;">Parecer Técnico</h3>
                                                    <div class="btn-group pull-right">
                                                    <button class="btn btn-success" style="margin-right: 10px;" data-toggle="modal" data-target="#alteracaoArquivos">Substituir arquivos</button>
                                                        @foreach($arquivos as $arquivo)
                                                         @if($arquivo->ID_MPME_TIPO_ARQUIVO == 21)
                                                            <a href="/abgf/arquivos/download/{{$arquivo->ID_MPME_ARQUIVO}}" class="btn btn-primary pull-right" target="_blank" style="margin-left: 10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Comprovante de Exportação</a>
                                                            <input type="hidden" value="{{$arquivo->ID_MPME_ARQUIVO}}" name="comprovante_exportacao_cad" id="comprovante_exportacao_cad" />
                                                         @endif
                                                         @if($arquivo->ID_MPME_TIPO_ARQUIVO == 20)
                                                         <a href="/abgf/arquivos/download/{{$arquivo->ID_MPME_ARQUIVO}}" class="btn btn-primary pull-right" target="_blank"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Visualizar DRE</a>
                                                         <input type="hidden" value="{{$arquivo->ID_MPME_ARQUIVO}}" name="dre_cad" id="dre_cad" />
                                                         @endif   
                                                        @endforeach
                                                    </div>
                                                </div>

                                                <div class="panel-body">
                                                    <div class="box-body pad">
                                                        <div class="row">

                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Data da recomendação</label>
                                                                    <div class="input-group date">
                                                                        <div class="input-group-addon">
                                                                            <i class="fa fa-calendar"></i>
                                                                        </div>

                                                                        <input type="text" class="form-control pull-right" required="Campo obrigatório" data-provide="datepicker" data-date-format="dd/mm/yyyy" name="data_recomendacao" id="data_recomendacao">
                                                                    </div>
                                                                </div>
                                                            </div>



                                                            <div class="col-md-2">
                                                                <div class="form-group">
                                                                    <label>Recomendação</label>
                                                                    
                                                                    <select name="ds_recomendacao" id="ds_recomendacao"  class="form-control" required="Campo obrigatório">
                                                                        <option value=""></option>
                                                                      @if(indeferirCadastro($dados) > 0)      
                                                                         <option value="1"  >Liberar Cadastro</option>
                                                                      @endif  

                                                                        <option value="2" >Recusar Cadastro</option>
                                                                    </select>
                                                                </div>


                                                            </div>


                                                        </div>

                                                    </div>
                                                    <br>
                                                    <textarea id="ds_parecer" name="ds_parecer" class="ckeditor" rows="10" cols="80" required="Campo obrigatório">

                                                    </textarea>

                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                    @can('LIBERAR_ACESSO')
                                        @if($dadosExportador->FL_ATIVO == 0)
                                            <div class="box-footer clearfix no-border">
                                                <button type="button" onclick="confirmar();" id="liberar_exportador" name="liberar_exportador" class="btn btn-success pull-right"><i class="fa fa fa-save"></i> Enviar notificação ao Exportador</button>
                                            </div>
                                        @endif
                                    @endcan
                                </form>
                            </div>
                            @endif
                        </div>

                    </div>
                                                        
                    <!-- Modal -->
                    <div id="alteracaoArquivos" class="modal fade" role="dialog">
                    <div class="modal-dialog">

                        <!-- Modal content-->
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <h4 class="modal-title">Substituir arquivos</h4>
                        </div>
                        <div class="modal-body">
                            <form action="{{ route('abgf.exportador.substituirArquivos') }}" enctype="multipart/form-data" method="POST">
                                @csrf
                                <input type="hidden" value="" name="comprovante_exportacao_sub_cad" id="comprovante_exportacao_sub_cad" />
                                <input type="hidden" value="" name="dre_sub_cad" id="dre_sub_cad" />
                                <input type="hidden" value="" name="ID_USUARIO_EXP" id="ID_USUARIO_EXP" />
                                                          
                                <div class="form-group">
                                    <label for="dre">Substituir arquivo dre:</label>
                                    <input type="file" name="dre" class="form-control" id="dre">
                                </div>
                                <div class="form-group">
                                    <label for="comprovante_exportacao">Substituir comprovante exportação:</label>
                                    <input type="file" name="comprovante_exportacao" class="form-control" id="comprovante_exportacao">
                                </div>
                               
                                <button type="submit" class="btn btn-primary">Enviar</button>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                        </div>
                        </div>

                    </div>
                    </div>
                    
                                        <!-- /.tab-pane -->
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

<script type="text/javascript">


$( document ).ready(function() {
   $('#alteracaoArquivos').on('shown.bs.modal', function (e) {
       $('#comprovante_exportacao_sub_cad').val('')         
       $('#dre_sub_cad').val('')
       $('#ID_USUARIO_EXP').val('')

       $('#dre_sub_cad').val($('#dre_cad').val())    
       $('#ID_USUARIO_EXP').val($('#ID_USUARIO_EXPORTA').val())     
       $('#comprovante_exportacao_sub_cad').val($('#comprovante_exportacao_cad').val())         
       
                
   })
});

</script>

@endsection


<script type="text/javascript">

    CKEDITOR.instances['respa'].updateElement();
    CKEDITOR.instances['respb'].updateElement();
    CKEDITOR.instances['respc'].updateElement();
    CKEDITOR.instances['respd'].updateElement();


</script>



<script>



function concluir_atualizacao(){

    $.ajax({
			url: "{{route('ajax.concluir_atualizacao')}}",
			type: "POST",
			dataType:"json",
			data: {ID_NOTIFICACAO:$('#ID_NOTIFICACAO').val()},
            beforeSend: function() {
               $("#concluir").attr('disabled', true).text('AGUARDE...');
            },
            success: function(retorno)
			{       
            
                switch (retorno.status) {
                                    case 'error':
                                        var alerta = swal("Erro!",retorno.msg,"error");
                                        break;
                                    case 'success':
                                    
                                        var alerta = swal("Sucesso!",retorno.msg,"success");
                                        break;
                                    case 'warning':
                                        var alerta = swal("Ops!",retorno.msg,"warning");
                                        break;
                                }
                                if (retorno.recarrega=='true') {
                                    alerta.then(function(){
                                        window.history.go(-1);
                                    });
                                }
			},
			error: function (error) {
				alert('Error ao processar informação');
			}
		});

}


    function confirmar(){


        var erros = new Array();
        var i = 0;

        var id_usuario = $('#ID_USUARIO').val();
        $.ajax({
			url: "{{route('ajax.consulta-enquadramento-usuario')}}",
			type: "POST",
			dataType:"HTML",
			data: {ID_USUARIO:id_usuario},
            context:{erros},
            async: false,
			beforeSend: function() {
               $("#liberar_exportador").prop('disabled', true);
            },
			success: function(retorno)
			{
                $("#liberar_exportador").prop('disabled', false);

				if ( parseInt(retorno) != 1 && $('#ds_recomendacao').val() != 2)
				{
                     erros.push('Você precisa enquadrar o usuário em ao menos 1 modalidade!');
				}

			},
			error: function (error) {
				alert('Error ao processar informação [enquadramento]');
			}
		});



        if ( $("#data_recomendacao").val() ==  "")
        {
            erros.push('Favor preencher a data de recomendação!');
        }

        if ( $("#ds_recomendacao").val() == "")
        {
            erros.push('Favor preencher a recomendação!');
        }

        $(".valiacao_arquivos").each(function(){
            if ( $(this).is(':checked') == false){
                i++;
            };
        });

        if (i > 0)
        {
            erros.push('Todos os itens da tela de validação devem ter sido preenchidos!');
        }

        if (erros.length>0)
        {

            swal('Ops!', erros.join('<br />'), 'info' );
            return false;
        }else{
            $("#frmLiberacaoCadastro").submit();
        }

        return true;
    }


    function aprovarModalidade(id_mpme_cliente_exportadores, id_modalidade, objeto)
	{
		$.ajax({
			url: "{{route('ajax.enquadramento-aprovarmodalidade')}}",
			type: "GET",
			dataType:"HTML",
			data: {ID_MOME_CLIENTE_EXPORTADORES: id_mpme_cliente_exportadores, ID_MODALIDADE: id_modalidade, acao: 'aprovar_modalidade'},
			beforeSend: function() {
               $("#aprovar_modalidade_"+id_modalidade).attr('disabled', true).val('AGUARDE...');
            },
			success: function(retorno)
			{
				if ( parseInt(retorno) == 1)
				{
				    if(id_modalidade == 2){
                        $(".aprovar_modalidade").val('APROVADO').prop('disabled',true);
                    }else{
					    $("#aprovar_modalidade_"+id_modalidade).val('APROVADO');
                    }

					swal("Sucesso!", "Registro processado com sucesso!", "success");

				}else{
					$("#aprovar_modalidade_"+id_modalidade).attr('disabled', false).val('APROVAR');
					 swal("Erro", "Erro ao processar registro!", "error");
                                         location.reload();
				}

			},
			error: function (error) {
				alert('Error ao processar informação');
			}
		});
	}


    function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
    }


</script>
