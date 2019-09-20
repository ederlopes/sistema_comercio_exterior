@extends('layouts.app')

@section('content')
  <div class="Conteudo">
    <h1>CONTROLE DE SINISTROS <span class="MensagemTitulo"></span></h1>
    <hr>

    <div>
      <!-- Content Header (Page header) -->
      <section class="content-header">
        <h3>
          Notificações
          <small>Total de {{$totalNotificacao}} Notificações</small>
        </h3>
        <ol class="breadcrumb">
          <li><a href="/"><i class="fa fa-dashboard"></i> Inicio</a></li>
          <li class="active">Notificações</li>
        </ol>
      </section>

      <!-- Main content -->
      <section class="content">
        <div class="row">
          <div class="col-md-3">
            <a href="#" class="btn btn-primary btn-block margin-bottom">Suporte Tecnico</a>


            @include('layouts.menu_notificicacoes')



        </div>
        <!-- /.col -->
        <div class="col-md-9">
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Validação do Exportador</h3>

              <div class="box-tools pull-right">
                <div class="has-feedback">
                  <input type="text" class="form-control input-sm" placeholder="Pesquisar por notificação">
                  <span class="glyphicon glyphicon-search form-control-feedback"></span>
                </div>
              </div>
              <!-- /.box-tools -->
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  {{$notificacaoNovoExportador->currentPage()}}-{{$notificacaoNovoExportador->perPage()}}/{{$notificacaoNovoExportador->total()}}
                  <div class="btn-group">
                    <a href="{{ $notificacaoNovoExportador->previousPageUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                    <a href="{{ $notificacaoNovoExportador->nextPageUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
              <div class="table-responsive mailbox-messages">
                <table class="table table-hover table-striped">
                  <tbody>
                 @forelse ($notificacaoNovoExportador as $NotificNExp)

                          <tr>
                            <td><input type="checkbox"></td>
                            <td class="mailbox-star"><a href="#"><i class="fa fa-star text-yellow"></i></a></td>
                            <td class="mailbox-name">

                                <a href="{{ route('abgf.exportador.validacao',[$NotificNExp->ID_USUARIO]) }}">
                                   {{$NotificNExp->NO_FANTASIA}}
                                </a>

                            </td>
                            <td class="mailbox-subject"><b>{{$NotificNExp->NM_USUARIO}}</b></td>
                            <td class="mailbox-attachment"></td>
                            <td class="mailbox-date">{{ date('d/m/Y', strtotime($NotificNExp->DATA_CADASTRO)) }}</td>
                          </tr>

                @empty

                    <tr>
                    <td><input type="checkbox"></td>
                    <td class="mailbox-star"></td>
                    <td class="mailbox-name"></td>
                    <td class="mailbox-subject"><b>Nenhum Resultado encontrado</b>
                    </td>
                    <td class="mailbox-attachment"></td>
                    <td class="mailbox-date"></td>
                  </tr>

                @endforelse

                  </tbody>
                </table>
                <!-- /.table -->
              </div>
              <!-- /.mail-box-messages -->
            </div>
            <!-- /.box-body -->
            <div class="box-footer no-padding">
              <div class="mailbox-controls">
                <!-- Check all button -->
                <button type="button" class="btn btn-default btn-sm checkbox-toggle"><i class="fa fa-square-o"></i>
                </button>
                <div class="btn-group">
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-trash-o"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-reply"></i></button>
                  <button type="button" class="btn btn-default btn-sm"><i class="fa fa-share"></i></button>
                </div>
                <!-- /.btn-group -->
                <button type="button" class="btn btn-default btn-sm"><i class="fa fa-refresh"></i></button>
                <div class="pull-right">
                  {{$notificacaoNovoExportador->currentPage()}}-{{$notificacaoNovoExportador->perPage()}}/{{$notificacaoNovoExportador->total()}}
                  <div class="btn-group">
                    <a href="{{ $notificacaoNovoExportador->previousPageUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-left"></i></a>
                    <a href="{{ $notificacaoNovoExportador->nextPageUrl() }}" class="btn btn-default btn-sm"><i class="fa fa-chevron-right"></i></a>
                  </div>
                  <!-- /.btn-group -->
                </div>
                <!-- /.pull-right -->
              </div>
            </div>
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    </div></div>
@endsection
