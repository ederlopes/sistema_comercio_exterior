 <!--box-body -->

          {{--<div class="box box-solid">--}}
            {{--<div class="box-header with-border">--}}
              {{--<h3 class="box-title">Exportador</h3>--}}

              {{--<div class="box-tools">--}}
                {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                {{--</button>--}}
              {{--</div>--}}
            {{--</div>--}}
            {{--<div class="box-body no-padding">--}}
              {{--<ul class="nav nav-pills nav-stacked">--}}
                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.index' ) active @endif"><a href="{{ route('notificacoes.index') }}"><i class="fa fa-users"></i> Novo(s) Cadastro(s)--}}
                  {{--<span class="label label-primary pull-right"></span></a></li>--}}
                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.validacao.exportador' ) active @endif"><a href="{{ route('notificacoes.validacao.exportador') }}"><i class="fa fa-thumbs-up"></i> Validação do Exportador <span class="label label-primary pull-right"></span></a></li>--}}
                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.validacao.exportador' ) active @endif"><a href="#"><i class="fa fa-check-square-o"></i> Análise do Exportador</a></li>--}}
                 {{--<li><a href="#"><i class="fa fa-user-times"></i> Cadastro Recusado</a></li>--}}


              {{--</ul>--}}
            {{--</div>--}}

            {{--<!-- /.box-body -->--}}
          {{--</div>--}}
          <!-- /. box -->

          {{--<div class="box box-solid">--}}
            {{--<div class="box-header with-border">--}}
              {{--<h3 class="box-title">Importador</h3>--}}

              {{--<div class="box-tools">--}}
                {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                {{--</button>--}}
              {{--</div>--}}
            {{--</div>--}}
            {{--<div class="box-body no-padding">--}}
              {{--<ul class="nav nav-pills nav-stacked">--}}

                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.validacao.exportador' ) active @endif"><a href="#"><i class="fa fa-file-text-o"></i> Validação do Importador  <span class="label label-warning pull-right">65</span></a>--}}
                {{--</li>--}}

                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.validacao.exportador' ) active @endif"><a href="#"><i class="fa fa-file-text-o"></i> Análise do Importador</a></li>--}}

              {{--</ul>--}}
            {{--</div>--}}
            {{--<!-- /.box-body -->--}}
          {{--</div>--}}
          <!-- /. box -->


          {{--<div class="box box-solid">--}}
            {{--<div class="box-header with-border">--}}
              {{--<h3 class="box-title">Operação</h3>--}}

              {{--<div class="box-tools">--}}
                {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                {{--</button>--}}
              {{--</div>--}}
            {{--</div>--}}
            {{--<div class="box-body no-padding">--}}
              {{--<ul class="nav nav-pills nav-stacked">--}}

                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.validacao.exportador' ) active @endif"><a href="#"><i class="fa fa-file-text-o"></i> Operação Aprovada</a></li>--}}
                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.validacao.exportador' ) active @endif"><a href="#"><i class="fa fa-file-text-o"></i> Operação Indeferida</a></li>--}}

              {{--</ul>--}}
            {{--</div>--}}
            {{--<!-- /.box-body -->--}}
          {{--</div>--}}
          {{--<!-- /. box -->--}}



          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Operações</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">

                <li class="@if(\Request::route()->getName() == 'abgf.exportador.listaquestionarioaprovacao' ) active @endif"><a href="{{ route('abgf.exportador.listaquestionarioaprovacao')}}"><i class="fa fa-list"></i> Listar Operações</a></li>

              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->

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
               <li class="@if(\Request::route()->getName() == 'abgf.exportador.listarpropostas' ) active @endif"><a href="{{ route('abgf.exportador.listarpropostas')}}"><i class="fa fa-circle-o text-red"></i> Listar Propostas</a></li>
             </ul>
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /. box -->



          {{--<div class="box box-solid">--}}
            {{--<div class="box-header with-border">--}}
              {{--<h3 class="box-title">Outros</h3>--}}

              {{--<div class="box-tools">--}}
                {{--<button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
                {{--</button>--}}
              {{--</div>--}}
            {{--</div>--}}
            {{--<div class="box-body no-padding">--}}
              {{--<ul class="nav nav-pills nav-stacked">--}}
                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.validacao.exportador' ) active @endif"><a href="#"><i class="fa fa-circle-o text-yellow"></i> Solic. de Alt. nas Cond. Particulares</a></li>--}}
                {{--<li class="@if(\Request::route()->getName() == 'notificacoes.validacao.exportador' ) active @endif"><a href="#"><i class="fa fa-circle-o text-light-blue"></i> Controle de Exportação enviado </a></li>--}}

              {{--</ul>--}}
            {{--</div>--}}
            {{--<!-- /.box-body -->--}}
          {{--</div>--}}
          {{--<!-- /. box -->--}}



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
              <li><a href="{{route('logout')}}"><i class="fa fa-sign-in"></i> Sair</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
