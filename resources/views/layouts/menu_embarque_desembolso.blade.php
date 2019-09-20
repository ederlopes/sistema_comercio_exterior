<!--MENU DA PAGINA-->
<div class="col-md-3">
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
                <li><a href="{{URL::to('/embarque/novo')}}"><i class="fa fa-users"></i>Novo Cadastro</a></li>
                <li><a href="{{URL::to('/embarque')}}"><i class="fa fa-list"></i>Lista de Embarque</a></li>
            </ul>
        </div>
    </div>

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
</div>