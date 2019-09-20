@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Controle de Propostas</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
          @include('layouts.menu_cliente')
        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
           
                <div class="alert alert-danger">
                    <strong>ATENÇÃO</strong><br />
                    <div>A Data da Exportação/ Embarque precisa ser confirmada hoje no sistema eletrônico da ABGF, caso não seja, a
Proposta de Seguro de Crédito à Exportação será recusada</div>
                </div>
         

            <form name="frmPropostaEmbarque" id="frmPropostaEmbarque" method="POST" action="{{route('salva-embarque-proposta')}}">
                <input type="hidden" name="id_proposta" id="id_proposta" value="{{$mpmeProposta->ID_MPME_PROPOSTA}}">
                {{ csrf_field() }}
                <div class="panel panel-default ">
                 
                </div>

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Aceite no embarque</h3>
                    </div>
                    <div class="panel-body">
                        <div class="row">
                        <div class="col-md-12">
                                
                                <div class="form-group">
                                    <label>Data de embarque</label>
                                    <input type="text" name="dt_embarque" id="dt_embarque" class="form-control datepicker" value="{{ date("d/m/Y",strtotime($mpmeProposta->DT_EMBARQUE)) }}">
                                </div>

                                <div class="form-group">
                                    <label>Confirma a data da exportação/embarque? </label>
                                     <label class="radio-inline"><input type="radio" name="confirm_embarque" value="S">Sim</label>
                                     <label class="radio-inline"><input type="radio" name="confirm_embarque" value="N">Não</label>
                                </div>
                            </div>
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-xs-12">
                                     <button type="submit" class="btn btn-success"><i class="fa fa-save"></i> Salvar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
      </div>
    </section>
  </div>

 <link rel="stylesheet" href="{{ asset('css/bootstrap-datepicker.min.css') }}?<?=time();?>">
    <script src="{{ asset('js/bootstrap-datepicker.min.js') }}?<?=time();?>"></script>
    <script src="{{ asset('js/proposta/funcoes_proposta.js') }}?<?=time();?>"></script>
    <script>
        $(document).ready(function () {
            <?php 
                $date1 = \Carbon\Carbon::parse($mpmeProposta->DT_ENVIO)->format('Y-m-d h:m:s');
                        $date2 = \Carbon\Carbon::now();
                 
                    $data_limite = $date2->diffInDays($date1); // saída: 365 dias
            ?>
           $.fn.datepicker.defaults.format = "dd/mm/yyyy";
            $('.datepicker').datepicker({
                startDate: '0d',
                endDate:'+<?php echo $data_limite;?>d',
                datesDisabled: '+<?php echo $data_limite;?>d',
            });

        })
    </script>
    
@endsection
