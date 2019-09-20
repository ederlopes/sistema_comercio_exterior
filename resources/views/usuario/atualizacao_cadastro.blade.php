@extends('layouts.app')

@section('content')
 <div id="Carregando">
        <div class="lds-css ng-scope"><div style="width:100%;height:100%" class="lds-eclipse"><div></div></div></div>
</div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Exportador
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

         @include('layouts.menu_cliente')
        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-9">
            <form name="frmAtualizacaoCadastro" id="frmAtualizacaoCadastro" method="post" action="{{Route('usuario.atualizar_cadastro')}}">
                <input type="hidden" id="ID_MPME_FINANC_POS" class="financiador_pos" name="ID_MPME_FINANC_POS" value="{{Auth::User()->FinanciadorPos->ID_FINANC ?? ''}}">
                <input type="hidden" id="ID_MPME_FINANC_PRE" class="financiador_pre" name="ID_MPME_FINANC_PRE" value="{{Auth::User()->FinanciadorPre->ID_FINANC_PRE ?? ''}}">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Atualização cadastral</h3>
                    </div>
                    <div class="panel-body">
                        <div class="alert alert-warning" role="alert" id="containerMsgModalidade"  style="display: none">
                            <div id="msgModalidade">Os dados para cadastro devem ser referentes ao <b>IMPORTADOR</b></div>
                        </div>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Modalidades</label>
                                    <select name="id_modalidade[]" id="id_modalidade" class="form-control selectpicker" title="Selecione" multiple>
                                                    {{-- ->where('ID_MODALIDADE_FINANCIAMENTO',4) --}}
                                                        @foreach ($modalidades as $modalidade) 
                                                            <option value="{{$modalidade->ID_MODALIDADE}}#{{$modalidade->ID_FINANCIAMENTO}}#{{$modalidade->ID_MODALIDADE_FINANCIAMENTO}}" @if($modalidade->ID_FINANCIAMENTO == 4) selected @endif>{{$modalidade->NO_MODALIDADE_FINANCIAMENTO}}</option>
                                                        @endforeach
                                                    </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Instituição Financeira</label>
                                     <select name="ID_FINANCIADOR" id="id_financiador" class="form-control financiador" disabled>
                                            <option value=""></option>
                                            @foreach ($financiadores as $financiadorPre)
                                                <option value="{{$financiadorPre->ID_USUARIO}}">{{$financiadorPre->NM_USUARIO}}</option>
                                            @endforeach
                                     </select>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Gecex</label>
                                    <div id="div_no_razao_social_select">
                                        <select name="ID_GECEX_POS2" id="id_gecex_pos2" class="form-control SelectGecex" disabled="true">
                                                <option value=""></option>
                                                @foreach ($gecexs as $gecexPre)
                                                    <option value="{{$gecexPre->ID_USUARIO_FK}}">{{$gecexPre->NO_GECEX}}</option>
                                                @endforeach
                                        </select>
                                    </div>
                                    <div id="div_no_razao_social_input" style="display: none">
                                        <input type="text" name="no_razao_social" id="no_razao_social" value="" class="form-control">
                                    </div>
                                    <input type="hidden" name="codigo_unico_importador" id="codigo_unico_importador" value="0">
                                    <input type="hidden" name="id_cliente_mpme" id="id_cliente_mpme" value="0">
                                </div>
                            </div>

                        </div>
                    
                    </div>
                    </div>

                    <div class="panel panel-default" id="pre-embarque" style="display:none">
                    <div class="panel-heading">
                        <h3 class="panel-title">Pré-Embarque</h3>
                    </div>
                    <div class="panel-body">
                     
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Agência</label>
                                     <input type="text" class="form-control financiador_pre" name="NO_AGENCIA_PRE" id="no_agencia" required="" title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio" aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CEP</label>
                                    <input type="text" class="form-control CEP financiador_pre" name="AG_CEP_PRE" id="cep_f" aria-invalid="false">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <input type="text" class="form-control maiusculo financiador_pre" name="AG_ENDERECO_PRE" id="ag_endereco">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="AG_ESTADO_PRE" id="ag_uf" class="form-control selectpicker financiador_pre">
                                                                        <option value=""></option>
                                                                        <option value="AC">Acre</option>
                                                                        <option value="AL">Alagoas</option>
                                                                        <option value="AM">Amazonas</option>
                                                                        <option value="AP">Amapá</option>
                                                                        <option value="BA">Bahia</option>
                                                                        <option value="CE">Ceará</option>
                                                                        <option value="DF">Distrito Federal</option>
                                                                        <option value="ES">Espirito Santo</option>
                                                                        <option value="GO">Goiás</option>
                                                                        <option value="MA">Maranhão</option>
                                                                        <option value="MG">Minas Gerais</option>
                                                                        <option value="MS">Mato Grosso do Sul</option>
                                                                        <option value="MT">Mato Grosso</option>
                                                                        <option value="PA">Pará</option>
                                                                        <option value="PB">Paraíba</option>
                                                                        <option value="PE">Pernambuco</option>
                                                                        <option value="PI">Piauí</option>
                                                                        <option value="PR">Paraná</option>
                                                                        <option value="RJ">Rio de Janeiro</option>
                                                                        <option value="RN">Rio Grande do Norte</option>
                                                                        <option value="RO">Rondônia</option>
                                                                        <option value="RR">Roraima</option>
                                                                        <option value="RS">Rio Grande do Sul</option>
                                                                        <option value="SC">Santa Catarina</option>
                                                                        <option value="SE">Sergipe</option>
                                                                        <option value="SP">São Paulo</option>
                                                                        <option value="TO">Tocantins</option>
                                                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" class="form-control maiusculo financiador_pre" name="AG_CIDADE_PRE" id="ag_cidade">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNPJ</label>
                                    <input type="text" class="form-control CNPJ financiador_pre" name="AG_CNPJ_PRE" id="ag_cnpj" aria-invalid="false">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Inscrição Estadual</label>
                                    <input type="text" class="form-control financiador_pre" name="AG_INSCR_PRE" id="ag_inscr_est">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contato</label>
                                    <input type="text" class="form-control maiusculo financiador_pre" name="AG_CONTATO_PRE" id="contato_fin">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cargo</label>
                                   <input type="text" class="form-control maiusculo financiador_pre" name="AG_CARGO_PRE" id="cargo_fin">
                                </div>
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="text" class="form-control financiador_pre" name="AG_TEL_PRE" id="telefone_f" aria-invalid="false">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>E-mail</label>
                                   <input type="text" class="form-control financiador_pre" name="AG_EMAIL_PRE" id="email_f">
                                </div>
                            </div>
                           
                        </div>

                        </div> <!-- fecha panel body -->
                    </div> <!-- fecha panel -->
 
                    <div class="panel panel-default" id="pos-embarque" style="display:none">
                    <div class="panel-heading">
                        <h3 class="panel-title">Pós-Embarque</h3>
                    </div>
                    <div class="panel-body">    




                     <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Agência</label>
                                     <input type="text" class="form-control financiador_pos" name="ID_AGENCIA_POS" id="ID_AGENCIA_POS" required="" title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio" aria-required="true">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CEP</label>
                                    <input type="text" class="form-control CEP financiador_pos" name="AG_CEP_POS" id="cep_f_pos" aria-invalid="false">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Endereço</label>
                                    <input type="text" class="form-control maiusculo financiador_pos" name="AG_ENDERECO_POS" id="ag_endereco_pos">
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Estado</label>
                                    <select name="AG_ESTADO_POS" id="ag_uf_pos" class="form-control selectpicker financiador_pos">
                                                                        <option value=""></option>
                                                                        <option value="AC">Acre</option>
                                                                        <option value="AL">Alagoas</option>
                                                                        <option value="AM">Amazonas</option>
                                                                        <option value="AP">Amapá</option>
                                                                        <option value="BA">Bahia</option>
                                                                        <option value="CE">Ceará</option>
                                                                        <option value="DF">Distrito Federal</option>
                                                                        <option value="ES">Espirito Santo</option>
                                                                        <option value="GO">Goiás</option>
                                                                        <option value="MA">Maranhão</option>
                                                                        <option value="MG">Minas Gerais</option>
                                                                        <option value="MS">Mato Grosso do Sul</option>
                                                                        <option value="MT">Mato Grosso</option>
                                                                        <option value="PA">Pará</option>
                                                                        <option value="PB">Paraíba</option>
                                                                        <option value="PE">Pernambuco</option>
                                                                        <option value="PI">Piauí</option>
                                                                        <option value="PR">Paraná</option>
                                                                        <option value="RJ">Rio de Janeiro</option>
                                                                        <option value="RN">Rio Grande do Norte</option>
                                                                        <option value="RO">Rondônia</option>
                                                                        <option value="RR">Roraima</option>
                                                                        <option value="RS">Rio Grande do Sul</option>
                                                                        <option value="SC">Santa Catarina</option>
                                                                        <option value="SE">Sergipe</option>
                                                                        <option value="SP">São Paulo</option>
                                                                        <option value="TO">Tocantins</option>
                                                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cidade</label>
                                    <input type="text" class="form-control maiusculo financiador_pos" name="AG_CIDADE_POS" id="ag_cidade_pos">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>CNPJ</label>
                                    <input type="text" class="form-control CNPJ financiador_pos" name="AG_CNPJ_POS" id="ag_cnpj_pos" aria-invalid="false">
                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Inscrição Estadual</label>
                                    <input type="text" class="form-control financiador_pos" name="AG_INSCR_POS" id="ag_inscr_est_pos">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Contato</label>
                                    <input type="text" class="form-control maiusculofinanciador_pos " name="AG_CONTATO_POS" id="contato_fin_pos">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Cargo</label>
                                   <input type="text" class="form-control maiusculo financiador_pos" name="AG_CARGO_POS" id="cargo_fin_pos">
                                </div>
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Telefone</label>
                                    <input type="text" class="form-control financiador_pos" name="AG_TEL_POS" id="telefone_f_pos" aria-invalid="false">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>E-mail</label>
                                   <input type="text" class="form-control financiador_pos" name="AG_EMAIL_POS" id="email_f_pos">
                                </div>
                            </div>
                           
                        </div>


                     </div> <!-- fecha panel body -->
                    </div> <!-- fecha panel -->

                    <div class="panel panel-default">
                 
                    <div class="panel-body">    

                        <div class="row no-print margin-t-20">
                            <div class="col-xs-12">
                                <a href="javascript:history.go(-1);"class="btn btn-default" ><i class="fa fa-arrow-circle-o-left"></i> Voltar</a>

                                    <button type="button" class="btn btn-success pull-right atualizar" id="atualizar" disabled>
                                       <div class="nomebtn"><i class="fa fa-save"></i> Atualizar</div>
                                    </button>

                                    <button type="button" class="btn btn-primary pull-right copiar" id="copiar" style="display:none; margin-right:10px;">
                                       <div class="nomebtn"><i class="fa fa-copy"></i> Copiar dados do Pré-embarque</div>
                                    </button>

                                    
                               </div>
                        </div>

                 
            </form>
        </div>
      </div>
    </section>
  </div>
  <script src="{{ asset('inclusoes/js/jQuery.autoNumeric.js?v=2.0').'?'.time() }}"></script>
  <script src="{{ asset('inclusoes/js/jQuery.MaskBrPhone.js?v=2.0').'?'.time() }}"></script>
  <script src="{{ asset('js/atualizacao.cadastral.js').'?'.time() }}"></script>
@endsection
