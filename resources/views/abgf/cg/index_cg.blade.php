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

        <div class="col-md-3">
            <a href="#" class="btn btn-primary btn-block margin-bottom">Suporte Tecnico</a>


            <!-- /. box -->
            @include('layouts.menu_abgf')
            <!-- /.box -->
        </div>

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-9">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Lista de CG</h3>
                </div>
                <div class="panel-body">
                    <table class="table table-bordered table-striped table-condensed">
                        <!--<colgroup>
                            <col class="col-xs-1">
                            <col class="col-xs-1">
                        </colgroup>-->
                        <thead>
                            <tr>
                                <th>Nº Proposta</th>
                                <th>Data da Solicitação</th>
                                <th>Razão Social</th>
                                <th>Valor solicitado para Aprovação</th>
                                <th>Modalidade</th>
                                <th class="col-xs-1">CG</th>
                            </tr>
                        </thead>
                        <tbody>
                        @forelse ($propostas as $proposta)        
                            <tr>
                                <td>{{$proposta->ID_MPME_PROPOSTA}}</td>
                                <td>{{date( "d/m/Y", strtotime($proposta->DATA_CADASTRO))}}</td>
                                <td>{{
                                    $proposta->MpmeClienteExportadorModaliadeFinancimanciamento->MpmeClienteExportador->Usuario->NM_USUARIO
                                    }}</td>
                                <td>{{$proposta->converteMoeda}}</td>
                                <td>{{$proposta->ID_MPME_PROPOSTA}}</td>

                                <td>
                                     <!-- Single button -->
                                        <div class="btn-group col-md-6">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                                                Lista de ações <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                    <li><a href="{{ route('abgf.gerarcgCondGerais',[$proposta->MpmeClienteExportadorModaliadeFinancimanciamento->MpmeClienteExportador->Usuario->ID_USUARIO, '1111',date("d-m-Y") ]) }}" >Condições Gerais</a></li>
                                                    <li><a href="{{ route('abgf.gerarcgCondGerais',[$proposta->MpmeClienteExportadorModaliadeFinancimanciamento->MpmeClienteExportador->Usuario->ID_USUARIO, '1111',date("d-m-Y") ]) }}" >Condições Especiais</a></li>
                                                    <li><a href="{{ route('abgf.gerarcgCondGerais',[$proposta->MpmeClienteExportadorModaliadeFinancimanciamento->MpmeClienteExportador->Usuario->ID_USUARIO, '1111',date("d-m-Y") ]) }}" >Condições Particulares</a></li>
                                            </ul>
                                        </div>                                    
                                   
                                </td>
                            </tr>
                            @empty    
                                <tr><td colspan="8">Nenhum registro encontrado</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Modal -->
<div class="modal fade " id="historico-aprovacao" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="loading">
                <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
            </div>
            <div class="modal-body">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
</div>
<script src="{{ asset('js/questionario/funcoes_questionario.js') }}"></script>
<script src="{{ asset('js/questionario/funcoes_anti_corrupcao.js') }}"></script>
@endsection
