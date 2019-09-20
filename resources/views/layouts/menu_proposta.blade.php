<!--MENU DA PAGINA-->
<div class="col-md-2">
    <div class="box box-solid">
        <div class="box-header with-border">
            <h3 class="box-title">Proposta</h3>
            <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="box-body no-padding">
            <ul class="nav nav-pills nav-stacked">
                <li><a href="{{URL::to('/proposta/nova')}}/{{$request->id_oper}}"><i class="fa fa-users"></i>Novo Cadastro</a></li>
                <li><a href="{{URL::to('/proposta')}}/{{$request->id_oper}}"><i class="fa fa-list"></i>Lista de Propostas</a></li>
            </ul>
        </div>
    </div>
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
                <li><a href="{{URL::to('/questionario_operacao/novo')}}"><i class="fa fa-clipboard"></i>Nova operação</a></li>
                <li><a href="{{URL::to('/questionario_operacao')}}"><i class="fa fa-list"></i>Lista de operações</a></li>
            </ul>
        </div>
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
                <li><a href="{{URL::to('/logout')}}"><i class="fa fa-sign-in"></i> Sair</a></li>
            </ul>
        </div>
    </div>




</div>
