<!--MENU DA PAGINA-->
<div class="col-md-2">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Operação</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                @can('NOVA_OPERACAO')
                    <li><a href="{{URL::to('/questionario_operacao/novo')}}"><i class="fa fa-clipboard"></i>Nova operação</a></li>
                @endcan
                <li><a href="{{URL::to('/questionario_operacao')}}"><i class="fa fa-list"></i>Lista de operações</a></li>
            </ul>
        </div>
    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Propostas</h3>

            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="{{URL::to('proposta/lista-proposta-usuario')}}"><i class="fa fa-circle-o text-red"></i> Lista de Propostas</a></li>
            </ul>
        </div>
        <!-- /.box-body -->
    </div>

    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Primeiros passos</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="http://www.abgf.gov.br/negocios/sce-micro-pequenas-e-medias-empresas/" target="_blank"><i class="fa fa-clipboard"></i>Sobre o produto</a></li>
                <li><a href="http://www.abgf.gov.br/wp-content/uploads/2016/06/Manual-de-Usuário-Módulo-Exportador.pdf" target="_blank"><i class="fa fa-clipboard"></i>Manual do sistema</a></li>
            </ul>
        </div>
    </div>

    @if ( explode('/', Request::url())[3] == 'embarque')
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Embarque</h3>
                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="{{URL::to('/embarque/novo')}}/{{$request->id_oper}}/{{$request->id_proposta}}"><i class="fa fa-users"></i>Novo Cadastro</a></li>
                    <li><a href="{{URL::to('/embarque')}}/{{$request->id_oper}}/{{$request->id_proposta}}"><i class="fa fa-list"></i>Lista de Embarque</a></li>
                </ul>
            </div>
        </div>
    @endif
    @if ( explode('/', Request::url())[3] == 'desembolso')
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Desembolso</h3>
                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li><a href="{{URL::to('/desembolso/novo')}}"><i class="fa fa-users"></i>Novo Cadastro</a></li>
                    <li><a href="{{URL::to('/desembolso')}}"><i class="fa fa-list"></i>Lista de Desembolso</a></li>
                </ul>
            </div>
        </div>
    @endif

    @can('MENU_SIMULACAO_PRECIFICACAO')
        <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Simulação de precificação</h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li class="@if(\Request::route()->getName() == 'precificacao.nova_simulacao_site' ) active @endif"><a href="{{ route('precificacao.nova_simulacao_site')}}" target="_blank"><i class="fa fa-money "></i> Precificação</a></li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
     @endcan
     <div class="box box-solid">
            <div class="box-header with-border">
                <h3 class="box-title">Atualização Cadastral</h3>

                <div class="box-tools">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                </div>
            </div>
            <div class="box-body no-padding">
                <ul class="nav nav-pills nav-stacked">
                    <li class="@if(\Request::route()->getName() == 'usuario.atualizacao_cadastral' ) active @endif"><a href="{{ route('usuario.atualizacao_cadastral')}}" target="_blank"><i class="fa fa-handshake-o "></i> Solicitar Novas Modalidades</a></li>
                </ul>
            </div>
            <!-- /.box-body -->
        </div>
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Sair do Sistema</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="{{URL::to('/logout')}}"><i class="fa fa-sign-in"></i>Sair</a></li>
            </ul>
        </div>
    </div>





</div>
