
@extends('layouts.layoutcadastro')

@section('content')
    <div class="page-container">
        <!-- BEGIN SIDEBAR -->
        <!-- END SIDEBAR -->
        <!-- BEGIN CONTENT -->
        <div>
            <div class="page-content">
                <!-- Trigger the modal with a button -->

                <!-- BEGIN PAGE CONTENT-->
                <div class="row" style="margin-top:20px;">
                    <div class="col-md-12">
                        <div class="portlet box blue" id="form_wizard_1">
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="glyphicon glyphicon-user"></i> Atualizar Informações <span class="step-title">
								</span>
                                </div>

                            </div>
                            <div class="portlet-body form">
                                <form action="/atualizarexportador" name="frmAtualizaExportador" class="form-horizontal form-dados" id="submit_form" method="POST">

                                    <input type="hidden" name="ID_USUARIO" value="{{($usuario->ID_USUARIO)}}" />



                                    <div class="form-wizard">
                                        <div class="form-body">
                                            <ul class="nav nav-pills nav-justified steps">
                                                <li>
                                                    <a href="#tab1" data-toggle="tab" class="step">
                                                <span class="number">
                                                1 </span>
                                                        <span class="desc">
                                                <i class="fa fa-check"></i> Dados Atualizaveis </span>
                                                    </a>
                                                </li>
                                                <li>

                                                </li>
                                                <li>

                                                </li>
                                                <li>
                                                   

                                                </li>

                                            </ul>
                                            <div id="bar" class="progress progress-striped" role="progressbar">
                                                <div class="progress-bar progress-bar-success">
                                                </div>
                                            </div>
                                            <div class="tab-content">
                                                <div class="alert alert-danger display-none">
                                                    <button class="close" data-dismiss="alert"></button>
                                                    Você tem alguns erros no formulario. Verifique abaixo.
                                                </div>
                                                <div class="alert alert-success display-none">
                                                    <button class="close" data-dismiss="alert"></button>
                                                    Seu cadastro foi atualizado com sucesso!
                                                </div>

                                                <div class="tab-pane active" id="tab1">
                                                    <h3 class="block">Atualize seus Dados</h3>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Modalidade / Tipo Financiamento <span class="required">
													* </span>
                                                        </label>
                                                        <div class="col-md-4">
                                                            <select name="ID_MODALIDADE[]" id="id_modalidade"  multiple='multiple'>
                                                                @foreach ($modalidades as $modalidade)
                                                                    <option value="{{$modalidade->ID_MODALIDADE}}#{{$modalidade->ID_FINANCIAMENTO}}#{{$modalidade->ID_MODALIDADE_FINANCIAMENTO}}">{{$modalidade->NO_MODALIDADE_FINANCIAMENTO}}</option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="control-label col-md-3"> Faturamento Bruto Anual <span class="required">* </span></label>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control" name="RE_ANUAL" id="RE_ANUAL" required title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio"/>
                                                            <div id="perfil"><span class="help-block">Faturamento bruto anual - R$</span></div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Valor de exportação <span class="required">
													* </span>
                                                        </label>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control" name="FT_ANUAL" id="FT_ANUAL" required title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio"/>
                                                            <div class="vlExport">
                                                            <span class="help-block">
														 Valor da exportação do ano civil anterior - US$</span>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Ano do calendário fiscal <span class="required" >*</span></label>
                                                        <div class="col-md-4">
                                                            <select class="form-control outros" required="" title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio" name="calendario_fiscal" id="calendario_fiscal" aria-required="true">
                                                                <option data-idperguntaresposta="" value="">::Selecione::</option>
                                                                <option value="{{date('Y')}}">{{date('Y')}}</option>
                                                                <option value="{{ date('Y', strtotime('-1 year')) }}">{{ date('Y', strtotime('-1 year')) }}</option>
                                                                <option value="{{ date('Y', strtotime('-2 year')) }}">{{ date('Y', strtotime('-2 year')) }}</option>
                                                            </select>
                                                            <span class="help-block">Ano relativo ao faturamento bruto e ao valor de exportação</span>
                                                        </div>
                                                    </div>


                                                    <div class="form-group">
                                                        <label class="control-label col-md-3">Total estimado de exportação <span class="required">
													* </span>
                                                        </label>
                                                        <div class="col-md-4">
                                                            <input type="text" class="form-control" name="FT_ANUAL3" id="FT_ANUAL3" required title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio"/>
                                                            <span class="help-block">
														Valor total estimado da exportação - US$</span>
                                                        </div>
                                                    </div>




                                                </div>



                                        </div>
                                        <div class="form-actions">
                                            <div class="row">
                                                <div class="col-md-offset-3 col-md-9">

                                                    <a href="javascript:;" class="btn green atualizardadosexportador">
                                                        Atualizar <i class="m-icon-swapright m-icon-white"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END PAGE CONTENT-->
            </div>
        </div>
        <!-- END CONTENT -->
        <!-- BEGIN QUICK SIDEBAR -->
        <a href="javascript:;" class="page-quick-sidebar-toggler"><i class="icon-close"></i></a>

        <!-- END QUICK SIDEBAR -->
    </div>

    </div>
    </div>


@endsection
