@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Controle da Exportação
        </h1>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="row">

        @include('layouts.menu_cliente')

        <!--CONTEUDO DA PAGINA-->
            <div class="col-md-9">
                <form name="frmEmbarque" id="frmEmbarque" method="post" action="">
                    <input type="hidden" name="id_mpme_status" id="id_mpme_status" value="{{$id_mpme_statatus}}">
                    <input type="hidden" name="id_oper" id="id_oper" value="{{$proposta->ID_OPER}}">
                    <input type="hidden" name="id_mpme_proposta" id="id_mpme_proposta" value="{{$proposta->ID_MPME_PROPOSTA}}">
                    <input type="hidden" name="nu_prazo_pos" id="nu_prazo_pos" value="{{$proposta->NU_PRAZO_POS}}">
                    <input type="hidden" name="id_mpme_embarque" id="id_mpme_embarque" value="{{$embarque->ID_MPME_EMBARQUE}}">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Controle de Embarque</h3>
                        </div>
                        <div class="panel-body">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Tipo de Embarque</label>
                                    <select class="form-control input-sm" name="id_mpme_tipo_embarque" id="id_mpme_tipo_embarque">
                                        @foreach($mpmeTipoEmbarque as $tipo_embarque)
                                            <option value="{{$tipo_embarque->ID_MPME_TIPO_EMBARQUE}}" @if($tipo_embarque->ID_MPME_TIPO_EMBARQUE == $embarque->ID_MPME_TIPO_EMBARQUE ?? '') selected @endif>{{$tipo_embarque->NO_TIPO_EMBARQUE}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor do Embarque</label>
                                    <input type="text" name="vl_embarque" value="{{formatar_valor($proposta->VL_PROPOSTA)}}" class="form-control money" readonly="readonly">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Valor do Financiamento</label>
                                    <input type="text" name="vl_financiamento" value="{{formatar_valor(calcular_valor_dowpayment($proposta->VL_PROPOSTA, $proposta->VL_PERC_DOWPAYMENT))}}" class="form-control money" readonly="readonly">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>N.º da Fatura</label>
                                    <input type="text" name="nu_fatura" id="nu_fatura" value="{{$embarque->NU_FATURA ?? ''}}" class="form-control" maxlength="20">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>N.º da DU-E</label>
                                    <input type="text" name="nu_due" id="nu_due" value="{{$embarque->NU_DUE ?? ''}}" class="form-control" maxlength="50">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>N.º do RVS</label>
                                    <input type="text" name="nu_rvs" id="nu_rvs" value="{{$embarque->NU_RVS ?? ''}}" class="form-control" maxlength="50">
                                </div>
                            </div>


                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data do Embarque</label>
                                    <div class="input-group date datetimepicker4">
                                        <input type="text" id="dt_embarque" class="form-control input-sm datetimepicker4" name="dt_embarque" value="{{date('d/m/Y', strtotime($embarque->DT_EMBARQUE)) ?? ''}}">
                                        <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Data do Vencimento</label>
                                    <div class="input-group date datetimepicker4">
                                        <input type="text" id="dt_vencimento" class="form-control input-sm datetimepicker4" name="dt_vencimento" value="{{date('d/m/Y', strtotime($embarque->DT_VENCIMENTO)) ?? ''}}" readonly="readonly">
                                        <span class="input-group-addon">
                                       <span class="glyphicon glyphicon-calendar"></span>
                                    </span>
                                    </div>
                                </div>
                            </div>

                            <!--LISTA DE MERCADORIAS-->
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title">Cadastro de Mercadorias/Serviços</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table">
                                            <table class="table" id="tabela_mercadoria">
                                                <thead>
                                                <tr>
                                                    <th>NCM/NBS</th>
                                                    <th>Mercadoria/Serviço</th>
                                                    <th>Aceite de Titulo</th>
                                                    <th>Valor Mercadoria/Serviço</th>
                                                    <th>Observações</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody class="fields">
                                                @foreach($embarque->mercadorias as $mecadoria)
                                                    <tr class="fildadd" id="mercadoria_1">
                                                        <td><input type="text" id="ncm_1" class="form-control input-sm ncm somentenumero " name="mercadoria[ncm][]" value="{{$mecadoria->NU_NCM_NBS}}"></td>
                                                        <td><input type="text" id="nm_mercadoria_1" class="form-control input-sm nm_mercadoria" name="mercadoria[nm_mercadoria][]" value="{{$mecadoria->NO_MERCADORIA}}" readonly="readonly"></td>
                                                        <td>
                                                            <select class="form-control in_aceite input-sm" name="mercadoria[in_aceite][]" id="in_aceite_1">
                                                                <option value="">Selecione</option>
                                                                <option value="S" @if($mecadoria->IN_ACEITE == 'S') selected @endif>SIM</option>
                                                                <option value="N" @if($mecadoria->IN_ACEITE == 'N') selected @endif>NÃO</option>
                                                            </select>
                                                        <td><input type="text" class="form-control vl_mercadoria input-sm money" name="mercadoria[vl_mercadoria][]" id="vl_mercadoria_1" value="{{formatar_valor_sem_moeda($mecadoria->VL_MERCADORIA)}}"></td>
                                                        <td><input type="text" class="form-control no_observacao input-sm" name="mercadoria[no_observacao][]" id="no_observacao_1" value="{{$mecadoria->DS_OBSERVACAO}}"></td>
                                                        <td>
                                                            <a href="javascript:void(0);" class="btn btn-success btnAdd">+</a>
                                                            <a href="javascript:void(0);" class="btn btn-danger remover">-</a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row no-print">
                                <div class="col-xs-12">
                                    <a href="javascript:window.print()" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
                                    <button type="button" class="btn btn-primary pull-right" name="btnCadastrar" id="btnCadastrar" style="margin-right: 5px;">
                                        <i class="fa fa-save"></i> Cadastrar
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
    </div>

    <script src="{{ asset('js/embarque/funcoes.js') }}?<?=time();?>"></script>

@endsection


