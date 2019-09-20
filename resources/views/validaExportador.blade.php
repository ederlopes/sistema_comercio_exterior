@extends('layouts.app')

@section('content')
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Informações do Exportador
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Inicio</a></li>
        <li class="active">Informações do Exportador</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <a href="#" class="btn btn-primary btn-block margin-bottom">Suporte Tecnico</a>


          <!-- /. box -->
    
          <!-- /.box -->
        </div>



        <!-- /.col -->
        <div class="col-md-9">
          <div>

            <!-- /.box-header -->
            <div class="box-body no-padding">

              <!-- /.mailbox-read-info -->
              <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
              <li class="active"><a href="#activity" data-toggle="tab">Dados do Exportador</a></li>
              <li><a href="#lista" data-toggle="tab">Validação</a></li>
            </ul>
            <div class="tab-content">
              <div class="active tab-pane" id="activity">
                <!-- Dados Exportador -->
                    <div>

                          <div class="box-body col-md-6">
                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Razão Social</label>
                                    <input type="text" name='NM_USUARIO' value="{{@$dadosExportador->NM_USUARIO}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>CNPJ da Empresa</label>
                                    <input type="text" name='NU_CNPJ' value="{{@$dadosExportador->NU_CNPJ}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Endereço</label>
                                    <input type="text" name='DE_ENDER' value="{{@utf8_encode($dadosExportador->DE_ENDER)}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>CEP</label>
                                    <input type="text" name='DE_CEP' value="{{@$dadosExportador->DE_CEP}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Nome do Contato</label>
                                    <input type="text" name='NM_CONTATO' value="{{@$dadosExportador->NM_CONTATO}}" class="form-control ">
                                  </div>
                                  <!-- group -->
                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Cargo do Contato</label>
                                    <input type="text" name='DE_CARGO' value="{{@$dadosExportador->DE_CARGO}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="text" name='DE_TEL' value="{{@$dadosExportador->DE_TEL}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>E-Mail do Contato</label>
                                    <input type="text" name='DE_EMAIL' value="{{@$dadosExportador->DE_EMAIL}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Modalidade do Financiamento</label>
                                    <select name="ID_MODALIDADE" id="ID_MODALIDADE" class="form-control select2" style="width: 100%;">
                                          <option value="2" @if(@$dadosExportador->ID_TEMPO == 2) selected @endif >PRÉ+PÓS-EMBARQUE</option>
                                                                  <option value="3" @if(@$dadosExportador->ID_TEMPO == 3) selected @endif >PÓS-EMBARQUE</option>
                                        </select>
                                  </div>
                                  <!-- group -->





                            </div>

                            <div class="box-body col-md-6">

                                  <!-- group -->
                                    <div class="form-group">
                                        <label>Tempo de Existência da empresa</label>
                                        <select name="ID_TEMPO" id="ID_TEMPO" class="form-control select2" style="width: 100%;">
                                          <option value="1" @if(@$dadosExportador->ID_TEMPO == 1) selected @endif >Até 3 anos</option>
                                                                  <option value="2" @if(@$dadosExportador->ID_TEMPO == 2) selected @endif >Acima de 3 anos</option>
                                        </select>
                                    </div>
                                  <!-- group -->

                                 <!-- group -->
                                  <div class="form-group">
                                    <label>(*) Valor de Exportação do ano civil anterior</label>
                                    <input type="text" name='VL_BRUTO_ANUAL' value="{{@$dadosExportador->VL_BRUTO_ANUAL}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>(*) Faturamento Total Estimado de Exportação</label>
                                    <input type="text" name='VL_ESTIMADO' value="{{@$dadosExportador->VL_ESTIMADO}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Faturamento Bruto do ano civil anterior</label>
                                    <input type="text" name='VL_EXP_BRUTA' value="{{@$dadosExportador->VL_EXP_BRUTA}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Inscr. Est.</label>
                                    <input type="text" name='NU_INSCR_EST' value="{{@$dadosExportador->NU_INSCR_EST}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                  <!-- group -->
                                  <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" name='DE_CIDADE' value="{{@utf8_encode($dadosExportador->DE_CIDADE)}}" class="form-control ">
                                  </div>
                                  <!-- group -->

                                   <!-- group -->
                                  <div class="form-group">
                                    <label>Estado</label>
                                    <select name="CD_UF" id="CD_UF" class="form-control select2" style="width: 100%;">

                                                            <option value="AC" @if(@$dadosExportador->CD_UF == 'AC') selected @endif>Acre</option>
                                                            <option value="AL" @if(@$dadosExportador->CD_UF == 'AL') selected @endif>Alagoas</option>
                                                            <option value="AM" @if(@$dadosExportador->CD_UF == 'AM') selected @endif>Amazonas</option>
                                                            <option value="AP" @if(@$dadosExportador->CD_UF == 'AP') selected @endif>Amapá</option>
                                                            <option value="BA" @if(@$dadosExportador->CD_UF == 'BA') selected @endif>Bahia</option>
                                                            <option value="CE" @if(@$dadosExportador->CD_UF == 'CE') selected @endif>Ceará</option>
                                                            <option value="DF" @if(@$dadosExportador->CD_UF == 'DF') selected @endif>Distrito Federal</option>
                                                            <option value="ES" @if(@$dadosExportador->CD_UF == 'ES') selected @endif>Espirito Santo</option>
                                                            <option value="GO" @if(@$dadosExportador->CD_UF == 'GO') selected @endif>Goiás</option>
                                                            <option value="MA" @if(@$dadosExportador->CD_UF == 'MA') selected @endif>Maranhão</option>
                                                            <option value="MG" @if(@$dadosExportador->CD_UF == 'MG') selected @endif>Minas Gerais</option>
                                                            <option value="MS" @if(@$dadosExportador->CD_UF == 'MS') selected @endif>Mato Grosso do Sul</option>
                                                            <option value="MT" @if(@$dadosExportador->CD_UF == 'MT') selected @endif>Mato Grosso</option>
                                                            <option value="PA" @if(@$dadosExportador->CD_UF == 'PA') selected @endif>Pará</option>
                                                            <option value="PB" @if(@$dadosExportador->CD_UF == 'PB') selected @endif>Paraíba</option>
                                                            <option value="PE" @if(@$dadosExportador->CD_UF == 'PE') selected @endif>Pernambuco</option>
                                                            <option value="PI" @if(@$dadosExportador->CD_UF == 'PI') selected @endif>Piauí</option>
                                                            <option value="PR" @if(@$dadosExportador->CD_UF == 'PR') selected @endif>Paraná</option>
                                                            <option value="RJ" @if(@$dadosExportador->CD_UF == 'RJ') selected @endif>Rio de Janeiro</option>
                                                            <option value="RN" @if(@$dadosExportador->CD_UF == 'RN') selected @endif>Rio Grande do Norte</option>
                                                            <option value="RO" @if(@$dadosExportador->CD_UF == 'RO') selected @endif>Rondônia</option>
                                                            <option value="RR" @if(@$dadosExportador->CD_UF == 'RR') selected @endif>Roraima</option>
                                                            <option value="RS" @if(@$dadosExportador->CD_UF == 'RS') selected @endif>Rio Grande do Sul</option>
                                                            <option value="SC" @if(@$dadosExportador->CD_UF == 'SC') selected @endif>Santa Catarina</option>
                                                            <option value="SE" @if(@$dadosExportador->CD_UF == 'SE') selected @endif>Sergipe</option>
                                                            <option value="SP" @if(@$dadosExportador->CD_UF == 'SP') selected @endif>São Paulo</option>
                                                            <option value="TO" @if(@$dadosExportador->CD_UF == 'TO') selected @endif>Tocantins</option>
                                                        </select>


                                  </div>
                                  <!-- group -->


                                   <!-- group -->
                                  <div class="form-group">
                                    <label>Fax</label>
                                    <input type="text" name='DE_FAX' value="{{@$dadosExportador->DE_FAX}}" class="form-control">
                                  </div>
                                  <!-- group -->

                                   <!-- group -->
                                  <div class="form-group">
                                    <label>Moeda da Operação</label>
                                    <select name="ID_MOEDA" id="ID_MOEDA" class="form-control select2" style="width: 100%;">
                                          <option value="1" @if(@$dadosExportador->ID_MOEDA == 1) selected @endif >USD</option>
                                                                  <option value="3" @if(@$dadosExportador->ID_MOEDA == 3) selected @endif >EUR</option>
                                        </select>
                                  </div>
                                  <!-- group -->

                            </div>

                            <!-- /.box-body -->
                            <div class="box-footer">
                            <h5>Informações dos sócios</h5>

                              <div class="box-body col-md-6">
                                    <!-- group -->
                                    <div class="form-group">
                                      <label>Nome</label>
                                      <input type="text" name='NOME_QUADRO' disabled="disabled" value="{{@$dadosExportador->NOME_QUADRO}}" class="form-control">
                                    </div>
                                    <!-- group -->

                                    <!-- group -->
                                    <div class="form-group">
                                      <label>CPF/CNPJ</label>
                                      <input type="text" name='CPF_CNPJ_QUADRO' disabled="disabled" value="{{@$dadosExportador->CPF_CNPJ_QUADRO}}" class="form-control">
                                    </div>
                                    <!-- group -->

                                     <!-- group -->
                                    <div class="form-group">
                                      <label>Participação</label>
                                      <input type="text" name='PARTICIPACAO_QUADRO' disabled="disabled" value="{{@$dadosExportador->PARTICIPACAO_QUADRO}}" class="form-control">
                                    </div>
                                    <!-- group -->

                              </div>

                              <div class="box-body col-md-6">
                                    <!-- group -->
                                    <div class="form-group">
                                      <label>Nome</label>
                                      <input type="text" name='DE_FAX' disabled="disabled" value="{{@$dadosExportador->DE_FAX}}" class="form-control">
                                    </div>
                                    <!-- group -->

                                    <!-- group -->
                                    <div class="form-group">
                                      <label>CPF/CNPJ</label>
                                      <input type="text" name='DE_FAX' disabled="disabled" value="{{@$dadosExportador->DE_FAX}}" class="form-control">
                                    </div>
                                    <!-- group -->

                                     <!-- group -->
                                    <div class="form-group">
                                      <label>Participação</label>
                                      <input type="text" name='DE_FAX' disabled="disabled" value="{{@$dadosExportador->DE_FAX}}" class="form-control">
                                    </div>
                                    <!-- group -->

                              </div>

                            </div>
                    </div>


                    <!-- Print -->

                     <!-- this row will not appear when printing -->
                        <div class="row no-print">
                          <div class="col-xs-12">
                            <a href="#" target="_blank" class="btn btn-default"><i class="fa fa-print"></i> Imprimir</a>
                            <button type="button" class="btn btn-primary pull-right" style="margin-right: 5px;">
                              <i class="fa fa-save"></i> Atualizar Dados Exportador
                            </button>
                          </div>
                        </div>

                <!-- /.post -->
              </div>
              <!-- /.tab-pane -->

             <div class="tab-pane" id="lista">
                <!-- TO DO List -->
          <div class="">

            <!-- /.box-header -->
            <div class="box-body">
              <!-- See dist/js/pages/dashboard.js to activate the todoList plugin -->
              <ul class="todo-list">

          
                <li>
                  <!-- drag handle -->
                  <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <!-- checkbox -->
                  <input type="checkbox" value="1"
                         <!-- todo text -->
                  <span class="text">Verificar informações junto ao Banco do Brasil</span>

                </li>

          
                <li>
                  <!-- drag handle -->
                  <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <!-- checkbox -->
                  <input type="checkbox" value="2"
                    <!-- todo text -->
                  <span class="text">Verificar informações junto à agência de informações creditícias</span>

                </li>


                <li>
                      <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                 <input type="checkbox" value="3" />
                  <span class="text">Informações do MDIC - Perfil das Exportações</span>
                  <small class="label label-info"><a href="#" style="color: #FFF"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                </li>
                <li>
                      <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                 <input type="checkbox" value="4" >

                  <span class="text">Situação de Regularidade do Empregador - FGTS</span>
                  <small class="label label-info"><a href="#" style="color: #FFF"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                </li>
                <li>
                      <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <input type="checkbox" value="5">

                  <span class="text">Certidão Negativa de Débitos - PGFN </span>
                  <small class="label label-info"><a href="#" style="color: #FFF"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                </li>

                <li>
                      <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                 <input type="checkbox" value="6" >

                  <span class="text">Demonstrativo de Resultado - DRE</span>
                  <small class="label label-info"><a href="#" style="color: #FFF"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                </li>

                <li>
                      <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <input type="checkbox" value="7" >

                  <span class="text">Comprovante de Inscrição e de Situação Cadastral - CNPJ</span>
                  <small class="label label-info"><a href="#" style="color: #FFF"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                </li>
                <li>
                      <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <input type="checkbox" value="8"  >

                  <span class="text">Demonstrativo de Resultado - DRE</span>
                    <small class="label label-info"><a href="#" style="color: #FFF"><i class="fa fa-chrome"></i> Clique Aqui </a></small>

                </li>

                <li>
                      <span class="">
                        <i class="fa fa-ellipsis-v"></i>
                        <i class="fa fa-ellipsis-v"></i>
                      </span>
                  <input type="checkbox" value="9" >

                  <span class="text">Solicitar Certidão de Pessoa Física dos sócios da MPME </span>


                </li>
              </ul>
            </div>
            <!-- /.box-body -->
            <div class="box-footer clearfix no-border">
              <button type="button" class="btn btn-primary pull-left"><i class="fa fa-save"></i> Salvar</button>
              <button type="button" class="btn btn-success pull-right"><i class="fa fa-paper-plane-o"></i> Encaminhar</button>
            </div>
          </div>
          <!-- /.box -->

             </div>


              <!-- /.tab-pane -->
            </div>
            <!-- /.tab-content -->
          </div>
              <!-- /.mailbox-read-message -->
            </div>
            <!-- /.box-body -->

            <!-- /.box-footer -->
          </div>
          <!-- /. box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
@endsection
