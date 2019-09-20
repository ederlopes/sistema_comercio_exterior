<div class="no-print">
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

                <li class="@if(\Request::route()->getName() == 'abgf.exportador.listaquestionarioaprovacao' ) active @endif"><a href="{{ route('abgf.exportador.listaquestionarioaprovacao')}}"><i class="fa fa-list"></i> Lista de operações</a></li>
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /. box -->

          @can('MENU_ANALISE_USUARIO')
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Usuários</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">

                <li class="@if(\Request::route()->getName() == 'abgf.exportador.index' ) active @endif"><a href="{{ route('abgf.exportador.index')}}"><i class="fa fa-users"></i> Analise/Validação do Usuário</a></li>
             
                <li class="@if(\Request::route()->getName() == 'abgf.exportador.atualizacao_cadastral' ) active @endif"><a href="{{ route('abgf.exportador.atualizacao_cadastral')}}"><i class="fa fa-user-circle-o"></i> Atualização Cadastral</a></li>

              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          @endcan

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
               <li class="@if(\Request::route()->getName() == 'abgf.exportador.listarpropostas' ) active @endif"><a href="{{ route('abgf.exportador.listarpropostas')}}"><i class="fa fa-circle-o text-red"></i> Lista de Propostas</a></li>
             </ul>
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /. box -->

          <div class="box box-solid">
              <div class="box-header with-border">
                  <h3 class="box-title">Controle de Embarque</h3>

                  <div class="box-tools">
                      <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                      </button>
                  </div>
              </div>
              <div class="box-body no-padding">
                  <ul class="nav nav-pills nav-stacked">
                      <li class="@if(\Request::route()->getName() == 'abgf.exportador.listarpropostasembarque' ) active @endif"><a href="{{ route('abgf.exportador.listarpropostasembarque')}}"><i class="fa fa-circle-o text-red"></i>Lista de Controle de Embarque</a></li>
                  </ul>
              </div>
              <!-- /.box-body -->
          </div>
          <!-- /. box -->

         @can('MENU_SINISTRO')
         <div class="box box-solid">
           <div class="box-header with-border">
             <h3 class="box-title">Sinistros</h3>

             <div class="box-tools">
               <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
               </button>
             </div>
           </div>
           <div class="box-body no-padding">
             <ul class="nav nav-pills nav-stacked">
               <li class="@if(\Request::route()->getName() == 'sinistro.home' ) active @endif"><a href="{{ route('sinistro.home')}}"><i class="fa fa-circle-o text-red"></i> Lista de Sinistros</a></li>
             </ul>
           </div>
           <!-- /.box-body -->
         </div>
         <!-- /. box -->
         @endcan


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
          <!-- /. box -->
          @can('MENU_RELATORIO')
            <div class="box box-solid">
                <div class="box-header with-border">
                    <h3 class="box-title">Relatórios</h3>

                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    </div>
                </div>
                <div class="box-body no-padding">
                    <ul class="nav nav-pills nav-stacked">
                        @can('GERADOR_RELATORIO')
                          <li class="@if(\Request::route()->getName() == 'abgf.relatorios.novo_relatorio' ) active @endif"><a href="{{ route('abgf.relatorios.novo_relatorio')}}"><i class="fa fa-money "></i> Gerador de Relatórios</a></li>
                        @endcan  
                    </ul>
                </div>
                <!-- /.box-body -->
            </div>
          @endcan
          @can('MENU_CADASTRO')
          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">Cadastros</h3>

              <div class="box-tools">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
              </div>
            </div>
            <div class="box-body no-padding">
              <ul class="nav nav-pills nav-stacked">
                @can('ATUALIZAR_RISCO_PAIS')
                  <li class="@if(\Request::route()->getName() == 'abgf.paisesrisco.lista_paises_risco' ) active @endif"><a href="{{ route('abgf.paisesrisco.lista_paises_risco')}}"><i class="fa fa-flag"></i> Atualizar Risco País</a></li>
                @endcan
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          @endcan

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
</div>