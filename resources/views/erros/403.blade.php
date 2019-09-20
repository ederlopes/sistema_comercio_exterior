@extends('layout.erros')

@section('content')
    <figure><img src="{{asset('images/logo06.png')}}" alt="CERI" class="center-block"/></figure>
    <div class="wrapper row">
        <div class="col-md-4 hidden-xs hidden-sm"></div>
        <div class="fix-height-group">
            <div class="box-start col-md-4 col-sm-6 fix-height">
                <div class="default-login">
                    <h2>Erro 403</h2>
                    <br />
                    <div class="alert alert-warning single">
                        <i class="icon glyphicon glyphicon-exclamation-sign pull-left"></i>
                        <div class="menssagem">
                            Para acessar as páginas do sistema, é preciso que você salve o arquivo de certificado A1 nas configurações. <br /><br />
                            <a href="{{URL::to('/configuracoes/certificado')}}" class="btn btn-black btn-sm">Ir para configurações de certificados</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4 hidden-xs hidden-sm"></div>
    </div>
@endsection