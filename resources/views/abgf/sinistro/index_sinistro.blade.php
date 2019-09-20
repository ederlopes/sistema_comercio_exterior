@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">

    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

          <div class="col-md-2">
              @include('layouts.menu_abgf')
          </div>

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Consultar usuario/operação</h3>
                </div>
                <div class="panel-body">
                        <div class="row">
                            <div class="col-md-7 col-md-offset-1">
                                <form class="form-inline" action="{{Route('sinistro.consultar_sinistro')}}" method="post">
                                    <input type="hidden" name="_token" value="{!! csrf_token() !!}">

                                    <div class="row">

                                       <div class="col-md-9">
                                           <label for="exportador">Exportador:</label>
                                           <select class="exportador selectpicker" name="ID_USUARIO" data-live-search="true">
                                                <option value="#" data-tokens="Selecione">Selecione</option>
                                                @foreach($exportador as $export)
                                                <option value="{{$export->ID_USUARIO}}" @if($export->ID_USUARIO == ($id_exportador ?? '')) selected @endif data-tokens="{{$export->NM_USUARIO}}">{{$export->NM_USUARIO}}</option>
                                                @endforeach
                                            </select>
                                            <div class="divOperacao" style="display:none;">
                                                <select class="operacao selectpicker" name="operacao" style="display:none" data-live-search="true">
                                                    <option value="#">Selecione</option>

                                                </select>
                                            </div>

                                            <button type="submit" class="btn btn-default" id="pesquisar">Pesquisar</button>
                                        </div>



                                    </div>


                                    </form>
                                </div>
                            </div>

                        </div>

                        <!-- Fim div painel 1 -->
@if(isset($proposta))

<hr>
<div class="table-responsive">

    <table class="table  table-hover">
        <thead>

            <tr>
                <th>Nº Proposta</th>
                <th>Nº Questionario</th>               
            </tr>

        </thead>

        <tbody>

            @foreach($proposta as $prop)
                <tr style="cursor: pointer"  data-toggle="collapse">
                    <td onclick="window.location='{{Route('sinistro.cadastrar_sinistro',['ID_OPER' => $prop->ID_OPER, 'ID_PROPOSTA' => $prop->ID_MPME_PROPOSTA])}}'">{{$prop->ID_MPME_PROPOSTA}}</td>
                    <td onclick="window.location='{{Route('sinistro.cadastrar_sinistro',['ID_OPER' => $prop->ID_OPER, 'ID_PROPOSTA' => $prop->ID_MPME_PROPOSTA])}}'">{{$prop->ID_OPER}}</td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <br><br>

</div>
@endif
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">Lista de Sinistros</div>
            <div class="panel-body">
                <table class="table table-striped table-bordered">
                        <tr>
                            <th width="15%"># Proposta</th>
                            <th width="15%"># Questionario</th>
                            <th width="10%">Usuario</th>
                            <th width="15%">Data de Cadastro</th>
                            <th width="15%">Status</th>
                            <th width="5%"></th>
                        </tr>

                @forelse($sinistros as $sinistro)   

                        <tr>
                            <td>{{$sinistro->ID_PROPOSTA}}</td>
                            <td>{{$sinistro->ID_OPER}}</td>
                            <td>{{$sinistro->Operacao->RAZAO_SOCIAL}}</td>
                            <td>{{\Carbon\Carbon::parse($sinistro->DATA_CADASTRO)->format('d/m/Y') }}</td>
                            <td>{{$sinistro->Status->NO_MPME_SINISTRO_STATUS}}</td>
                            <td>
                                    <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{Route('sinistro.cadastrar_sinistro', ['ID_OPER' => $sinistro->ID_OPER, 'ID_PROPOSTA' => $sinistro->ID_PROPOSTA])}}">Acessar</a></li>                                                                                                                                                                                                  
                                            </ul>
                                        </div>
                            </td>
                        </tr>
                @empty

                <tr>    
                    <td colspan="5" id="recuperacaoVazia">
                        <div class="alert alert-info" style="margin-bottom:0">Nenhuma sinistro cadastrado.</div>
                    </td>
                </tr>

                @endforelse        
                </table>        
            </div>
        </div>
        
        </div> 


      </div>

      
    </section>
  </div>

  <script type="text/javascript" src="{{ asset('js/abgf/sinistro/funcoes_sinistro.js').'?'.time() }}"></script>


    <script>
        $(function () {
            $('.select2').select2()

        })
    </script>
@endsection
