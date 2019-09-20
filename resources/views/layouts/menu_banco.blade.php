

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Analise</h3>

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
                 <li class="@if(\Request::route()->getName() == 'banco.analisa.listarpropostas' ) active @endif"><a href="{{ route('banco.analisa.listarpropostas')}}"><i class="fa fa-circle-o text-red"></i> Listar Propostas</a></li>
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
