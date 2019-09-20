@extends('layouts.erros')

@section('content')
    <figure><img src="{{asset('imagens/sce_logo_100.png')}}" width="400" alt="MPME" class="center-block"/></figure>
    <div class="default-login">
        <h1 align="center"><i class="icon glyphicon glyphicon-warning-sign" style="color:#a94442; font-size: 10rem;"></i></h1>
        <h2>Você não tem autorização de acessar esta operação</h2>
        <br />
        <div class="alert alert-danger single">
            <i class="icon glyphicon glyphicon-exclamation-sign pull-left"></i>
            <div class="menssagem">
                &nbsp; De acordo com a política de uso do noss sistema você esta tentando acessar uma operação que não lhe
                <br /> pertence isso pode implicar em exclusão do seu cadastro do sistema.
                <a href="{{URL::to('/')}}" class="btn btn-black btn-sm">Voltar para a página inicial</a>
            </div>
        </div>
    </div>
@endsection