@extends('layouts.app')
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
            <h1>Controle de Propostas</h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <!--MENU DA PAGINA-->
        <div class="col-md-2">
            @include('layouts.menu_abgf')
        </div>
        
        
        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
            <div class="panel panel-default ">
             <div class="panel-heading">
                    <h3 class="panel-title">Lista de países risco</h3>
                </div>
                <div class="panel-body">
                   <form id="frmRiscoPais" name="frmRiscoPais" action="" method="post" autocomplete="off">
                    <table id="lista_pais" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nome do país</th>
                                    <th>Risco atual</th>
                                    <th>Novo Risco</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ( $rs_paises_risco as $pais )
                                <tr id="{{$pais->ID_PAIS}}">
                                    <td align="center" width="2%">
                                        <div class="bite-checkbox menostop10" align="center">
                                            <input class="inputrisco icheckbox_square-blue" data-idpais="{{$pais->ID_PAIS}}" type="checkbox" id="id_pais_risco_{{$pais->ID_PAIS}}" value="{{$pais->ID_PAIS}}" name="id_pais_risco[]">
                                            <label for="id_pais_risco_{{$pais->ID_PAIS}}"></label>
                                        </div>
                                    </td>
                                    <td>{{$pais->NM_PAIS}}</td>
                                    <td>
                                        {{$pais->CD_RISCO}}
                                        <input class="riscoatual" data-idpais="{{$pais->ID_PAIS}}" type="hidden" id="risco_atual_{{$pais->ID_PAIS}}" value="{{$pais->CD_RISCO}}" name="risco_atual[]" disabled="disabled">
                                    </td>
                                    <td>
                                        <div class="col-md-1">
                                            <input type="text" class="form-control novorisco" id="novo_risco_{{$pais->ID_PAIS}}" name="novo_risco[]" value="" disabled="disabled">
                                        </div>    
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </form>
                    <div class="row no-print margin-t-20">
                            <div class="col-xs-12">
                                <a href="javascript:window.print();" class="btn btn-default pull-left margin-r-5 "><i class="fa fa-print"></i> Imprimir</a>
                                    <button type="button" class="btn btn-primary pull-right" id="btnCadastrar">
                                       <div class="nomebtn"><i class="fa fa-save"></i> Cadastrar</div>
                                    </button>
                            </div>
                        </div>
                </div>
            </div>
        </div>
      </div>
    </section>
    <script src="{{ asset('js/paises/paises-risco.js') }}?<?=time();?>"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.19/js/dataTables.bootstrap.min.js"></script>
  </div>
@endsection
