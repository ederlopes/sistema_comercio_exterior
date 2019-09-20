@php
  $valor = (@trim($importador->creditoConcedido($id_mpme_alcada,$importador->ID_OPER)->VL_CRED_CONCEDIDO) != "") ? @formatar_moeda($importador->creditoConcedido($id_mpme_alcada,$importador->ID_OPER)->VL_CRED_CONCEDIDO) : @formatar_moeda($importador->RetornaMercadoria->VL_TOTAL);
  $cotacao = round($cotacao, 6);
  $time = time();
@endphp

<script src="{{ asset('js/funcoes.geral.js') }}?time={{$time}}"></script>
<script src="{{ asset('js/questionario/funcoes_limite_operacional.js') }}?time={{$time}}"></script>

<form name="frmControleCapital" id="frmControleCapital" method="post">
  <input type="hidden" name="id_oper" id="id_oper" value="{{$request->id_oper}}">
  <input type="hidden" name="id_alcada" id="id_alcada" value="7">
  <input type="hidden" name="st_oper" id="st_oper" value="12">
<div class="row">
<div class="col-md-12">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title">
            <i class="fa fa-globe"></i> MPME: {{$exportador->NM_USUARIO}}
            <small class="pull-right">Data de Cadastro: {{ date("d/m/Y", strtotime($exportador->DATA_CADASTRO)) }}</small>
        </h3>
      </div>
      <div class="panel-body">
        <div class="col-md-12">

          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Código MPME</label><br />
                <span>{{$exportador->ID_USUARIO}}</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>País</label><br />
                <span>{{$importador->RetornaPaisImportadorOperacao->NM_PAIS}}</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>CNPJ do Exportador</label><br />
                <span>{{$exportador->NU_CNPJ}}</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Regime Tributário</label><br />
                <span>{{$exportador->retornaSimplesNacional()->NO_REGIME_TRIBUTARIO}}</span>
              </div>
            </div>
          </div>


          <div class="row">
            <div class="col-md-3">
              <div class="form-group">
                <label>Tipo</label><br />
                <span>Micro Empresa</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Modalidade da Operação</label><br />
                <span>{{$importador->OperacaoCadastroExportador->modalidade->NO_MODALIDADE}}</span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Importador</label><br />
                <span>{{$importador->RAZAO_SOCIAL}} </span>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group">
                <label>Estado do Exportador</label><br />
                <span>{{$exportador->CD_UF}}</span>
              </div>
            </div>
          </div>



        </div>
      </div>
    </div>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
  <div class="panel panel-primary">
    <div class="panel-heading">
      <h3 class="panel-title">Controle de capital</h3>
    </div>
    <div class="panel-body">
      <div class="box-body pad">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Fundo principal para operação</label>
              <select class="form-control" name="id_mpme_fundo_garantia_operacao" id="id_mpme_fundo_garantia_operacao">
                <option value="0">Selecione</option>
                @foreach($dadosFundoGarantia as $fundo)
                  <option
                          @if(@trim($importador->creditoConcedido($id_mpme_alcada,$importador->ID_OPER)->ID_MPME_FUNDO_GARANTIA) == $fundo->ID_MPME_FUNDO_GARANTIA) selected @endif
                  value="{{$fundo->ID_MPME_FUNDO_GARANTIA}}">{{$fundo->NO_FUNDO_GARANTIA}}
                  </option>
                @endforeach
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Moeda da Operacao</label>
              <select class="form-control" name="id_moeda" id="id_moeda">
                <option value="{{$operacao->ID_MOEDA}}">{{$operacao->SIGLA_MOEDA}}</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Valor para Aprovação</label>
              <input type="text" name="vl_cred_concedido" id="vl_cred_concedido" value="{{$valor}}" class="form-control money" readonly="readonly">
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Taxa da cotação (d-1): </label>
              <input type="text" name="tx_cotacao" id="tx_cotacao" value="{{$cotacao}}" class="form-control" readonly="readonly">
            </div>
          </div>


        </div>
        <div class="row">
          <div class="col-md-12">
          <div class="bs-callout bs-callout-" id="callout-btn-group-tooltips" style="height: 160px;">
            @foreach($dadosFundoGarantia as $fundo)
              <div class="col-md-3">
                <div class="form-group">
                  <label>Fundo</label>
                  <select class="form-control" data-fundo="{{$fundo->ID_MPME_FUNDO_GARANTIA}}"  name="id_mpme_fundo_garantia[]" id="id_mpme_fundo_garantia_{{$fundo->ID_MPME_FUNDO_GARANTIA}}">
                    <option value="{{$fundo->ID_MPME_FUNDO_GARANTIA}}">{{$fundo->NO_FUNDO_GARANTIA}}</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>% a ser utilizado do fundo: </label>
                  <input type="text" name="vl_perc_fundo[]" data-fundo="{{$fundo->ID_MPME_FUNDO_GARANTIA}}" id="vl_perc_fundo_{{$fundo->ID_MPME_FUNDO_GARANTIA}}" value="" class="form-control perc_fundo percentual" >
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Valor total em R$: </label>
                  <input type="text" name="vl_total_real[]" data-fundo="{{$fundo->ID_MPME_FUNDO_GARANTIA}}" id="vl_total_real_{{$fundo->ID_MPME_FUNDO_GARANTIA}}" value="" class="form-control" readonly="readonly">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Saldo suficiente</label>
                  <select class="form-control in_saldo_suficiente" data-fundo="{{$fundo->ID_MPME_FUNDO_GARANTIA}}" id="in_saldo_suficiente_{{$fundo->ID_MPME_FUNDO_GARANTIA}}" name="in_saldo_suficiente[]">
                    <option value="0">Selecione</option>
                    <option value="SIM">SIM</option>
                    <option value="NAO">NÃO</option>
                  </select>
                </div>
              </div>
            @endforeach
          </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>


<!--CONTROLE DE EXPORTACAO-->
<div class="row">
  <div class="col-md-12">
    <div class="panel panel-primary">
      <div class="panel-heading">
        <h3 class="panel-title">Controle do País</h3>
      </div>
      <div class="panel-body">
        <div class="box-body pad">
          <div class="row">
            <div class="col-md-2">
              <div class="form-group">
                <label>País</label>
                <select class="form-control" name="id_pais_exp" id="id_pais_exp">
                  <option value="{{$operacao->ID_PAIS}}">{{$operacao->NM_PAIS}}</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Moeda</label>
                <select class="form-control" name="id_moeda_exp" id="id_moeda_exp">
                  <option value="{{$operacao->ID_MOEDA}}">{{$operacao->SIGLA_MOEDA}}</option>
                </select>
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Valor para Aprovação</label>
                <input type="text" name="vl_cred_concedido_exp" id="vl_cred_concedido_exp" value="{{$valor}}" class="form-control money" readonly="readonly">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Taxa da cotação (d-1): </label>
                <input type="text" name="tx_cotacao_exp" id="tx_cotacao_exp" value="{{$cotacao}}" class="form-control" readonly="readonly">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Valor total em R$: </label>
                <input type="text" name="vl_total_real_exp" id="vl_total_real_exp" value="{{formatar_moeda($cotacao*converte_float($valor))}}" class="form-control money" readonly="readonly">
              </div>
            </div>
            <div class="col-md-2">
              <div class="form-group">
                <label>Saldo suficiente</label>
                <select class="form-control" id="in_saldo_suficiente_exp" name="in_saldo_suficiente_exp">
                  <option value="0">Selecione</option>
                  <option value="SIM">SIM</option>
                  <option value="NAO">NÃO</option>
                </select>
              </div>
            </div>
            <div class="col-md-12">
              <div class="form-group">
                <label>Parecer</label>
                <textarea name="parecer_exp" class="form-control" id="parecer_exp" ></textarea>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</form>