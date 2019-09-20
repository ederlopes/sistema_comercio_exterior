@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Banco: GECEX
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

          <div class="col-md-2">
              @include('layouts.menu_banco')
          </div>

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Usuarios</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-condensed">

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Banco</th>
                            <th>GECEX</th>
                            <th>Modalidade/Financiamento</th>
                            <th>Ação</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        @forelse($notificacoes as $notif)
                            <td>{{$notif->ID_USUARIO_FK}}</td>
                            <td>{{$notif->Exportador->NM_USUARIO}}</td>
                            <td>{{$notif->Banco->Usuario->NM_USUARIO ?? '-'}}</td>
                            <td>{{$notif->Banco->Gecex->NO_GECEX ?? '-'}}</td>
                            <td>
                                    
                                @foreach($notif->ClienteExportador->ModalidadeFinanciamento as
                                            $clienteModalidadeFinanciamentos)
                                        <span class="label label-primary">{{$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->NO_MODALIDADE_FINANCIAMENTO}}</span>
                                @endforeach

                            </td>
                            <td>
                                <div class="btn-group col-md-6">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown">
                                        Lista de ações <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu listaopcoes">
                                        <li><a href="{{route('banco.analisa.exportador',[$notif->ID_USUARIO_FK, $notif->ID_NOTIFICACAO])}}
                                            ">Acessar</a>
                                        </li>

                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @empty
                           <tr><td colspan="6">Nenhum registro encontrado</td></tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
      </div>
    </section>
  </div>
    <script>
        $(function () {
            $('.select2').select2()

        })
    </script>
@endsection
