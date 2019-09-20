@extends('layouts.app')

@section('content')
 <div class="Conteudo">

   <h1>CONTROLE DE SINISTROS <span class="MensagemTitulo"></span></h1>
     <hr>
     <div class="r">
         <div class="lockscreen-logo">
             <a href=""><b>Localizar Exportador / Operação</b></a>
         </div>
         <!-- START LOCK SCREEN ITEM -->
         <div class="lockscreen-item">
     <div class="Form01">

         <div class="row">
             <div class="col-md-2 col-md-offset-1">
                 <form class="form-inline" action="/home" style="width: 500px;" method="post">
                     <input type="hidden" name="_token" value="{!! csrf_token() !!}">


                     <div class="form-group">
                         <select class="form-control" id="opcaoPesquisa" name="opcaoPesquisa">
                            <option id="exportador" value="1">Exportador</option>
                            <option id="operacao" value="2">Operação</option>
                         </select>
                         <input type="text" class="form-control" name="pesquisa" id="pesquisa">
                     </div>

                     <button type="submit" class="btn btn-default">Pesquisar</button>
                 </form>
             </div>
         </div>


     </div> 
     
     <!-- Fim div Form01 -->
     @if(isset($retorno) || isset($retornoOperacao) )
         <hr>
     <div class="table-responsive">

         <table class="table  table-hover">
             <thead>
                 @if(isset($retorno))
                 <tr>
                     <th>Nº MPME</th>
                     <th>Razão Social</th>
                 </tr>
                 @endif

                 @if(isset($retornoOperacao))
                     <tr>
                         <th>Nº Operação</th>
                         <th>Razão Social</th>
                     </tr>
                 @endif
             </thead>

             <tbody>
             @if(isset($retorno))
                 @foreach($retorno as $exportador)
                     <tr style="cursor: pointer" onclick="document.location = '/mpme/{{$exportador->ID_USUARIO}}';">
                     <td>{{$exportador->ID_USUARIO}}</td>
                     <td>{{$exportador->NM_USUARIO}}</td>
                     </tr>
                 @endforeach
             @endif



             @if(isset($retornoOperacao))
                 @foreach($retornoOperacao as $operacao)
                     <tr style="cursor: pointer" onclick="document.location = '/cadastrar/sinistro/{{$operacao->ID_OPER}}';">
                         <td>{{$operacao->ID_OPER}}</td>
                         <td>{{$operacao->RAZAO_SOCIAL}}</td>
                     </tr>
                 @endforeach
             @endif
             </tbody>
         </table>
     </div>
     @endif


    </div>
         <!-- /.lockscreen-item -->
         <div class="help-block text-center">
             Escolha o tipo de busca e clique em pesquisar.
         </div>
         <div class="lockscreen-footer text-center">
             Busque por <b>Exportador ou Operação.</b><br>
        </div>
         <br>
         <br>
    </div>
    </div>
@endsection
