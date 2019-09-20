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

        <form name="form-filtro" method="post" action="/abgf/exportador" class="clearfix">
            {{csrf_field()}}
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Filtrar de Usuários</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nome do Exportador</label>
                                <input type="text" name="nm_usuario" id="nm_usuario" value="{{$request->nm_usuario}}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Nº do CNPJ</label>
                                <input type="text" name="nu_cnpj" id="nu_cnpj" value="{{$request->nu_cnpj}}" class="form-control cnpj">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="form-group">
                                <label>Cadastros liberados</label>
                                <select class="form-control input-sm" name="fl_ativo" id="fl_ativo">
                                    <option value="">Selecione</option>
                                    <option value="1">SIM</option>
                                    <option value="0">NÃO</option>
                                </select>
                            </div>
                        </div>

                        {{-- {!! menu_total_paginacao($request) !!} --}}
                    </div>
                    <div class="row">
                        <button id="btnPesquisar" name="btnPesquisar" type="submit" class="btn btn-success pull-right" style="margin-right: 10px;">
                            <i class="fa fa-filter"></i> Filtrar operação
                        </button>
                        <button id="btnReset" name="btnReset" type="reset" class="btn btn-default pull-right" style="margin-right: 10px;" onclick="window.location.href = ' {{Route('abgf.exportador.index')}} '">
                            <i class="fa fa-filter"></i> Limpar filtro
                        </button>
                    </div>
                </div>
            </div>
        </form>


            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de Usuários</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-condensed">

                        <thead>
                        <tr>
                            <th>ID</th>
                            <th class="col-md-2">Login do Fornecedor</th>
                            <th class="col-md-2">Nome do Fornecedor</th>
                            <th class="col-md-2">CNPJ do Fornecedor</th>
                            <th class="col-md-3">Email / Telefone</th>
                            <!-- th class="col-md-2">Banco/Gecex</th -->
                            <th class="col-md-2">Modalidade/Financiamento</th>
                            <th class="col-md-1">Ação</th>

                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        @forelse($notificacoes as $notif)
                            <td>{{$notif->ID_USUARIO_FK}}</td>
                            <td>{{$notif->Exportador->CD_LOGIN}}</td>
                            <td>{{$notif->Exportador->NM_USUARIO}}</td>
                            <td>{{$notif->Exportador->NU_CNPJ}}</td>
                            <td>{{$notif->Exportador->DE_EMAIL}} / ({{$notif->Exportador->NU_DDD}}) {{$notif->Exportador->DE_TEL}}</td>
                            {{-- <td>{{$notif->Banco->Usuario->NM_USUARIO ?? ''}} / {{$notif->Banco->Gecex->NO_GECEX ?? ''}}</td> --}}
                            <td>
                               
                                @foreach($notif->ClienteExportador->ModalidadeFinanciamento as $mod)
                                        <span class="label label-primary">{{$mod->ModalidadeFinanciamento->NO_MODALIDADE_FINANCIAMENTO}}</span>
                                @endforeach

                            </td>
                            <td>
                                <div class="btn-group col-md-6">
                                    <button type="button" class="btn btn-primary dropdown-toggle"
                                            data-toggle="dropdown">
                                        Lista de ações <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu listaopcoes">
                                        <li><a href="{{route('abgf.exportador.validacao',[$notif->ID_USUARIO_FK,$notif->ID_NOTIFICACAO])}}
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

@endsection
