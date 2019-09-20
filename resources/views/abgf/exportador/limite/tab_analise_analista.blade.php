<?php

$id_mpme_alcada = (int) trim($alcada['ID_MPME_ALCADA']);

$creditAnterior = 0;
$creditAnteriorPre = 0;

if ($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 2) {
    if ($importador->UltimaAlcadaMovimentacao->ID_MPME_ALCADA == 7) {
        $id_mpme_alcada = ($id_mpme_alcada - 1); //retornando o valor da alcada anterior;
    } else {
        if (!isset($dadosCreditScore[$id_mpme_alcada]['VL_AVAL3'])) {
            $id_mpme_alcada = ($id_mpme_alcada - 1); //retornando o valor da alcada anterior;
        }
    }

    //  $creditAnterior = 1;
    //  $creditAnteriorPre = 1;
} else {
    if ($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE != 1) {
        if (!isset($dadosCreditScore[$id_mpme_alcada]['VL_AVAL3'])) {
            $id_mpme_alcada = ($id_mpme_alcada - 1); //retornando o valor da alcada anterior;
            $creditAnterior = 1;
        }
    }

    if ($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE != 3) {
        if (!isset($dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL3'])) {
            $id_mpme_alcada = ($id_mpme_alcada - 1); //retornando o valor da alcada anterior;
            $creditAnteriorPre = 1;
        }
    }
}

$valor = (@trim($importador->creditoConcedido($id_mpme_alcada, $importador->ID_OPER)->VL_CRED_CONCEDIDO) != "") ? @formatar_moeda($importador->creditoConcedido($id_mpme_alcada, $importador->ID_OPER)->VL_CRED_CONCEDIDO) : @formatar_moeda($importador->RetornaMercadoria->VL_TOTAL);
$cotacao = round($cotacao, 6);
$time = time();

?>


<div class="row">
  @if(Session::has('message'))
    <div class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</div>
  @endif
  <div class="col-md-4">
    <div class="bs-callout bs-callout-primary" id="callout-btn-group-tooltips">
      <h4>Cálculo do limite de crédito.</h4><br/>
      <div class="form-group">
        <div class="alert" style="display:none" id="message_calculo_limite_credito"></div>
        @if(VerificaSeuploadFoifeito('upload_calculo_limite_credito',$importador->ID_OPER))
          <div id="upload_upload_calculo_limite_credito_realizado" class="text-center" style=""><p>
              <a href="/uploads/abgf/exportador/limite/upload_calculo_limite_credito/{{$importador->ID_OPER}}/{{$importador->ID_OPER}}.pdf" target="_blank"><button class="btn btn-primary visualizarUpload"><i class="fa fa-file-pdf-o"> Visualizar</i></button></a> <button class="btn btn btn-warning atualiza_upload_calculo_limite_credito reenviarUpload"><i class="fa fa-refresh"> Reenviar</i></button></p>
          </div>
        @endif
        <form method="post" action="{{ route('ajaxupload.uploadCalculoLimiteCredito')}}" id="upload_calculo_limite_credito" enctype="multipart/form-data"
              @if(VerificaSeuploadFoifeito('upload_calculo_limite_credito',$importador->ID_OPER)) style="display:none" @endif
        >
          <input type="hidden" name="ID_OPER" value="{{$importador->ID_OPER}}"/>
          <input type="file" id="select_upload_calculo_limite_credito" name="select_upload_calculo_limite_credito">
          <p class="help-block">Upload cálculo do limite de Crédito.</p>

          {{ csrf_field() }}
        </form>

        <div id="upload_upload_calculo_limite_credito_realizado" class="text-center" style="display:none;"></div>
      </div>
    </div>
  </div>

@if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE !=3)
  <div class="@if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE !=3) @if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE ==1) col-md-4 @else col-md-4 @endif @else col-md-4 @endif">
    <div class="bs-callout bs-callout-primary" id="callout-btn-group-tooltips">
      <h4>Upload do Relatório Nacional.</h4><br/>
      <div class="form-group">
        <div class="alert" style="display:none" id="message_comprovantepg_relatorio"></div>
        @if(VerificaSeuploadFoifeito('comprovante_pg_relatorio',$importador->ID_OPER))
          <div id="upload_comprovantepg_relatorio_realizado" class="text-center" style=""><p>
            <a href="/uploads/abgf/exportador/limite/comprovante_pg_relatorio/{{$importador->ID_OPER}}/{{$importador->ID_OPER}}.pdf" target="_blank"><button class="btn btn-primary visualizarUpload"><i class="fa fa-file-pdf-o"> Visualizar</i></button></a> <button class="btn btn btn-warning atualiza_comprovante_pg_relatorio reenviarUpload"><i class="fa fa-refresh"> Reenviar</i></button></p>
          </div>
         @endif
          <form method="post" action="{{ route('ajaxupload.uploadComprovantePgRelatorio')}}" id="upload_comprovantepg_relatorio" enctype="multipart/form-data"
            @if(VerificaSeuploadFoifeito('comprovante_pg_relatorio',$importador->ID_OPER)) style="display:none" @endif
          >
            <input type="hidden" name="ID_OPER" value="{{$importador->ID_OPER}}"/>
             <input type="file" id="select_comprovantepg_relatorio" name="select_comprovantepg_relatorio">
               <p class="help-block">Upload Comprovante de Pagamento do Relatório.</p>

             {{ csrf_field() }}
          </form>

        <div id="upload_comprovantepg_relatorio_realizado" class="text-center" style="display:none;"></div>
      </div>
    </div>
  </div>
@endif


  <div class="@if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE !=3) @if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE ==1) col-md-4 @else col-md-4 @endif @else col-md-4 @endif">
    <div class="bs-callout bs-callout-primary" id="callout-btn-group-tooltips">
      <h4>Upload relatorio Internacional.</h4><br/>
      <div class="form-group">
          <div class="alert" style="display:none" id="message_relatorio_internacional"></div>
        @if(VerificaSeuploadFoifeito('relatorio_internacional',$importador->ID_OPER))
        <div id="upload_relatorio_internacional_realizado" class="text-center" style=""><p>
          <a href="/uploads/abgf/exportador/limite/relatorio_internacional/{{$importador->ID_OPER}}/{{$importador->ID_OPER}}.pdf" target="_blank"><button class="btn btn-primary visualizarUpload"><i class="fa fa-file-pdf-o"> Visualizar</i></button></a> <button class="btn btn btn-warning atualiza_relatorio_internacional reenviarUpload"><i class="fa fa-refresh"> Reenviar</i></button></p>
        </div>
        @endif
        <form method="post" action="{{ route('ajaxupload.uploadRelatorioInternacional')}}" id="upload_relatorio_internacional" enctype="multipart/form-data"
        @if(VerificaSeuploadFoifeito('relatorio_internacional',$importador->ID_OPER)) style="display:none" @endif
        >
          <input type="hidden" name="ID_OPER" value="{{$importador->ID_OPER}}"/>
           <input type="file" id="select_relatorio_internacional" name="select_relatorio_internacional">
             <p class="help-block">Faça o upload do Relatório Internacional.</p>

           {{ csrf_field() }}
        </form>

        <div id="upload_relatorio_internacional_realizado" class="text-center" style="display:none;"></div>
      </div>
    </div>
  </div>

<form action="{{ route('abgf.exportador.analistaAprovaLimite')}}" method="post" name="frmTbAnaliseAnalista" id="frmTbAnaliseAnalista">
<input type="hidden" name="CODIGO_UNICO_IMPORTADOR" id="CODIGO_UNICO_IMPORTADOR" value="{{$importador->CODIGO_UNICO_IMPORTADOR}}"/>

</div>

  <div class="row" style="margin-top: 20px; margin-bottom: 20px; ">
        @if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE !=3)
          <div class="col-md-6">
              <div class="bs-callout bs-callout-primary" id="callout-btn-group-tooltips">
                <h4>O Exportador apresentou Balanços Patrimoniais completos?</h4><br/>
                <div class="form-group">
                  <div>
                    <label class="radio-inline"><input type="radio" name="resp_pre" class="resp_pre" value="1" @if(@trim($dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL10']) != "") checked="checked" @endif >Sim </label>
                    <label class="radio-inline"><input type="radio" name="resp_pre" class="resp_pre" value="0" @if(@trim($dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL10']) == "") checked="checked" @endif>Não</label>
                  </div>
                </div>
              </div>
          </div>
        @endif

        @if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE !=1)
          <div class="col-md-6">
            <div class="bs-callout bs-callout-primary" id="callout-btn-group-tooltips">
              <h4>O Importador apresentou Balanços Patrimoniais completos?</h4><br/>
              <div class="form-group">
                <div>
                  <label class="radio-inline"><input type="radio" name="resp" class="resp" value="1" @if(@trim($dadosCreditScore[$id_mpme_alcada]['VL_AVAL10']) != "") checked="checked" @endif >Sim </label>
                  <label class="radio-inline"><input type="radio" name="resp" class="resp" value="0" @if(@trim($dadosCreditScore[$id_mpme_alcada]['VL_AVAL10']) == "") checked="checked" @endif>Não</label>
                </div>
              </div>
            </div>
          </div>
        @endif
  </div>       


@if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 2 || $importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 3)
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Alterações no credit-score do importador </h3>
    <div class="box-tools pull-right">
      <!-- Collapse Button -->
      <button type="button" class="btn btn-box-tool" data-widget="collapse">
        <i class="fa fa-minus"></i>
      </button>
    </div>
    <!-- /.box-tools -->
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                  <th scope="col">Alçada</th>
                  <th scope="col">Motivo da alteração</th>
                </thead>
              <tbody>
          
              @foreach($importador->creditScoreImportador as $key => $credit)
                  @if($credit->MOTIVO_ALTERACAO ?? '' != '')
                    <tr>
                      <td>{{$credit->Alcada->NO_ALCADA}}</td>
                      <td>{{$credit->MOTIVO_ALTERACAO}}</td>
                    </tr>
                  @endif
               @endforeach 
               </tbody>
    </table>   
  </div>
  <!-- /.box-body -->

  <!-- box-footer -->
</div>
<!-- /.box -->
@endif

@if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 1 || $importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 2)
<div class="box box-primary">
  <div class="box-header with-border">
    <h3 class="box-title">Alterações no credit-score do Exportador </h3>
    <div class="box-tools pull-right">
      <!-- Collapse Button -->
      <button type="button" class="btn btn-box-tool" data-widget="collapse">
        <i class="fa fa-minus"></i>
      </button>
    </div>
    <!-- /.box-tools -->
  </div>
  <!-- /.box-header -->
  <div class="box-body">
    <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                  <th scope="col">Alçada</th>
                  <th scope="col">Motivo da alteração</th>
                </thead>
              <tbody>
          
              @foreach($importador->creditScoreExportador as $key => $credit)
                  @if($credit->MOTIVO_ALTERACAO ?? '' != '')
                    <tr>
                      <td>{{$credit->Alcada->NO_ALCADA}}</td>
                      <td>{{$credit->MOTIVO_ALTERACAO}}</td>
                    </tr>
                  @endif
               @endforeach 
               </tbody>
    </table>   
  </div>
  <!-- /.box-body -->

  <!-- box-footer -->
</div>
<!-- /.box -->
@endif


<br>
  <input type="hidden" name="ID_OPER" value="{{$importador->ID_OPER}}"/>
  <input type="hidden" name="MOTIVO_ALTERACAO_CREDIT_SCORE" ID="MOTIVO_ALTERACAO_CREDIT_SCORE" />
  <input type="hidden" name="tipo_movimentacao" id="tipo_movimentacao" value="DEBITO"/>
  <input type="hidden" name="id_exportador" value="{{$exportador->ID_USUARIO}}"/>
  <input type="hidden" name="ID_MPME_ALCADA" class="ID_MPME_ALCADA_AT" id="AlcadadaAbaAtual" value="{{ $alcada['ID_MPME_ALCADA'] }}"/>
  <input type="hidden" name="ID_MPME_ALCADA_ANTERIOR" id="ID_MPME_ALCADA_ANTERIOR" value="<?php echo ($id_mpme_alcada != 0) ? $id_mpme_alcada - 1 : '0'; ?>"/>
  <input type="hidden" name="NO_ALCADA" id="NO_ALCADA" value="{{ $alcada['NO_ALCADA'] }}"/>
  <input type="hidden" name="modalidade" id="modalidadeOperacao" value="{{$importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE}}"/>
  <input type="hidden" name="ID_CRITERIO" value="{{@$importador->CriterioOperacao($alcada['ID_MPME_ALCADA'],$importador->ID_OPER)->ID_CRITERIO_OPERACAO}}"/>
  <input type="hidden" name="ID_RECOMENDACAO" value="{{@$importador->recomendacao($alcada['ID_MPME_ALCADA'],$importador->ID_OPER)->ID_RECOMENDACAO}}"/>
  <input type="hidden" name="VL_SOLICITA_USUARIO" value="{{@$importador->RetornaMercadoria->VL_TOTAL}}"/>
  <input type="hidden" name="ID_CREDITO" id="ID_CREDITO" value="{{($creditAnterior !=1) ? @$dadosCreditScore[$id_mpme_alcada]['ID_CREDITO'] : ''}}"/>
  <input type="hidden" name="ID_CREDIT_SCORE" value="{{($creditAnterior !=1) ? @$dadosCreditScore[$alcada['ID_MPME_ALCADA']]['ID_CREDIT_SCORE'] : ''}}"/>
  <input type="hidden" name="ID_CREDIT_SCORE_PRE" value="{{(@$creditAnteriorPre !=1) ? @$dadosCreditScorePre[$alcada['ID_MPME_ALCADA']]['ID_CREDIT_SCORE_EXPORTADORES'] : ''}}"/>
  <input type="hidden" name="ultimaAlcada" id="ultimaAlcada" value="{{$crontroleAlcadas['CONTROLE_APROVACAO']['ULTIMA']}}"/>

  {{ csrf_field() }}



 @if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 1 || $importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 2)
<!--Credit Score do exportador -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Analise do Credit Score do Exportador </h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                  <th scope="col">Tópico/Nível de Risco/Ponderação</th>
                  <th scope="col">Avaliação</th>
                  <th scope="col">Ponderação</th>
                  <th scope="col">Result.</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td align="center"><b>Tópico/Nível de Risco/Ponderação</b></td><td align="center"><b>Avaliação</b></td><td align="center"><b>Ponderação</b></td><td align="center"><b>Result.</b></td>
                </tr>
                <tr>
                  <td align="left"><b>Análise Cadastral</b></td><td align="center"><span id="somatorioCadastral_pre" class="badge bg-yellow">3</span></td><td align="center"><b><span class="badge bg-yellow">2,0</span></b></td><td align="center"><b><span id="somaCadastralPonderado_pre" class="badge bg-yellow">12</span></b></td>
                </tr>
                <!-- -->
                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Dívidas vencidas e não pagas</td><td align="center"><input type="text" size="1" maxlength="1" name="aval1_pre" id="aval1_pre" value="{{@$importador->CreditScoreExportador->where('ID_MPME_ALCADA', $id_mpme_alcada)->first()->VL_AVAL1}}" onkeyup="apenas5(this);"> </td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>

                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Referências negativas</td><td align="center"><input type="text" size="1" maxlength="1" name="aval3_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL3']}}" id="aval3_pre" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Processos trabalhistas em andamento</td><td align="center"><input type="text" size="1" maxlength="1" name="aval4_pre" id="aval4_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL4']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Empresa concordatária</td><td align="center"><input type="text" size="1" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL5']}}" maxlength="1" name="aval5_pre" id="aval5_pre" onkeyup="apenas5(this);"></td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Histórico de sinistro</td><td align="center"><input type="text" size="1" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL6']}}" maxlength="1" name="aval6_pre" id="aval6_pre" onkeyup="apenas5(this);"></td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>


                <!-- -->
                <tr>
                  <td align="left"><b>Qualidade das Informações</b></td><td align="center"><input type="text" size="1" maxlength="1" name="aval7_pre" id="aval7_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL7']}}" onkeyup="apenas5(this);"></td><td align="center"><b><span class="badge bg-yellow">3,0</span></b></td><td align="center"><b><span id="somaAnaQualidadeInfo_pre" class="badge bg-yellow">3</span></b></td>
                </tr>

                <tr>
                  <td align="left"><b>Análise Setorial</b></td><td align="center"><input type="text" size="1" maxlength="1" name="aval8_pre" id="aval8_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL8']}}" onkeyup="apenas5(this);"></td><td align="center"><b><span class="badge bg-yellow">1,0</span></b></td><td align="center"><b><span id="somaAnaSetorial_pre" class="badge bg-yellow">3</span></b></td>
                </tr>

                <tr id="cred31_pre" style="">
                  <td align="left"><b>Análise Financeira</b></td><td align="center"><span id="somatorioIndFinanc_pre" class="badge bg-yellow">2</span></td><td align="center"><b><span class="badge bg-yellow">4,0</span></b></td><td align="center"><b><span id="somaIndFinanc_pre" class="badge bg-yellow">8</span></b></td>
                </tr>
                <tr id="cred32_pre" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Fluxo de Caixa</td><td align="center"><input type="text" size="1" maxlength="1" name="aval9_pre" id="aval9_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL9']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred33_pre" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Necessidade de Capital de Giro</td><td align="center"><input type="text" size="1" maxlength="1" name="aval10_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL10']}}" id="aval10_pre" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred38_pre" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Ciclo Financeiro</td><td align="center"><input type="text" size="1" maxlength="1" name="aval12_pre" id="aval12_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL12']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred34_pre" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Índice de Liquidez</td><td align="center"><input type="text" size="1" maxlength="1" name="aval11_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL11']}}" id="aval11_pre" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred35_pre" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Grau de Endividamento</td><td align="center"><input type="text" size="1" maxlength="1" name="aval13_pre" id="aval13_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL13']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred36_pre" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;PL</td><td align="center"><input type="text" size="1" maxlength="1" name="aval14_pre" id="aval14_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL14']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred37_pre" style="display: none;">
                  <td align="left"><b>Análise Financeira</b></td><td align="center"><input type="text" size="1" maxlength="1" name="aval92_pre" id="aval92_pre" value="{{@$dadosCreditScorePre[$id_mpme_alcada]['VL_AVAL9']}}" onkeyup="apenas5(this);"></td><td align="center"><b>4,0</b></td><td align="center"><b><span id="somaIndFinanc2_pre">xxx</span></b>
                  </td>
                </tr>

                <tr>
                  <td align="left">Total</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"><b><span id="rRR4_pre" class="badge bg-light-blue">26</span></b></td>
                </tr>
                </tbody>
              </table>
              <br>

              <b>Credit Score:</b> <input type="text" name="nota_credit_score_importador_pre" id="r7_pre" value="{{ (trim(@$dadosCreditScorePre[$id_mpme_alcada]['CREDIT_SCORE']) != "") ? @$dadosCreditScorePre[$id_mpme_alcada]['CREDIT_SCORE'] : 'xxx' }}" maxlength="3" max="3" style="width: 25px;">
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>
    </div>
    <div class="panel-footer"><button type="button" class="btn btn-primary" id="btnRecalcularCreditScore_pre" ><i class="fa fa-refresh"></i> Recalcular Credit-Score</button></div>
  </div>

@endif

@if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE !=1)
<!--Credit Score do importador -->
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Analise do Credit Score do Importador</h3>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-sm-12">
          <div class="box">
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                  <th scope="col">Tópico/Nível de Risco/Ponderação</th>
                  <th scope="col">Avaliação</th>
                  <th scope="col">Ponderação</th>
                  <th scope="col">Result.</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                  <td align="center"><b>Tópico/Nível de Risco/Ponderação</b></td><td align="center"><b>Avaliação</b></td><td align="center"><b>Ponderação</b></td><td align="center"><b>Result.</b></td>
                </tr>
                <tr>
                  <td align="left"><b>Análise Cadastral</b></td><td align="center"><span id="somatorioCadastral" class="badge bg-yellow">3</span></td><td align="center"><b><span class="badge bg-yellow">2,0</span></b></td><td align="center"><b><span id="somaCadastralPonderado" class="badge bg-yellow">12</span></b></td>
                </tr>
                <!-- -->
                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Dívidas vencidas e não pagas</td><td align="center"><input type="text" size="1" maxlength="1" name="aval1" id="aval1" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL1']}}" onkeyup="apenas5(this);"> </td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>

                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Referências negativas</td><td align="center"><input type="text" size="1" maxlength="1" name="aval3" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL3']}}" id="aval3" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Processos trabalhistas em andamento</td><td align="center"><input type="text" size="1" maxlength="1" name="aval4" id="aval4" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL4']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Empresa concordatária</td><td align="center"><input type="text" size="1" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL5']}}" maxlength="1" name="aval5" id="aval5" onkeyup="apenas5(this);"></td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr>
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Histórico de sinistro</td><td align="center"><input type="text" size="1" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL6']}}" maxlength="1" name="aval6" id="aval6" onkeyup="apenas5(this);"></td><td align="center">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>


                <!-- -->
                <tr>
                  <td align="left"><b>Qualidade das Informações</b></td><td align="center"><input type="text" size="1" maxlength="1" name="aval7" id="aval7" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL7']}}" onkeyup="apenas5(this);"></td><td align="center"><b><span class="badge bg-yellow">3,0</span></b></td><td align="center"><b><span id="somaAnaQualidadeInfo" class="badge bg-yellow">3</span></b></td>
                </tr>

                <tr>
                  <td align="left"><b>Análise Setorial</b></td><td align="center"><input type="text" size="1" maxlength="1" name="aval8" id="aval8" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL8']}}" onkeyup="apenas5(this);"></td><td align="center"><b><span class="badge bg-yellow">1,0</span></b></td><td align="center"><b><span id="somaAnaSetorial" class="badge bg-yellow">3</span></b></td>
                </tr>

                <tr id="cred31" style="">
                  <td align="left"><b>Análise Financeira</b></td><td align="center"><span id="somatorioIndFinanc" class="badge bg-yellow">2</span></td><td align="center"><b><span class="badge bg-yellow">4,0</span></b></td><td align="center"><b><span id="somaIndFinanc" class="badge bg-yellow">8</span></b></td>
                </tr>
                <tr id="cred32" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Fluxo de Caixa</td><td align="center"><input type="text" size="1" maxlength="1" name="aval9" id="aval9" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL9']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred33" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Necessidade de Capital de Giro</td><td align="center"><input type="text" size="1" maxlength="1" name="aval10" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL10']}}" id="aval10" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred38" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Ciclo Financeiro</td><td align="center"><input type="text" size="1" maxlength="1" name="aval12" id="aval12" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL12']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred34" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Índice de Liquidez</td><td align="center"><input type="text" size="1" maxlength="1" name="aval11" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL11']}}" id="aval11" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred35" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;Grau de Endividamento</td><td align="center"><input type="text" size="1" maxlength="1" name="aval13" id="aval13" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL13']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred36" style="">
                  <td align="left">&nbsp;&nbsp;&nbsp;&nbsp;PL</td><td align="center"><input type="text" size="1" maxlength="1" name="aval14" id="aval14" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL14']}}" onkeyup="apenas5(this);"></td><td align="left">&nbsp;</td><td align="center">&nbsp;</td>
                </tr>
                <tr id="cred37" style="display: none;">
                  <td align="left"><b>Análise Financeira</b></td><td align="center"><input type="text" size="1" maxlength="1" name="aval92" id="aval92" value="{{@$dadosCreditScore[$id_mpme_alcada]['VL_AVAL9']}}" onkeyup="apenas5(this);"></td><td align="center"><b>4,0</b></td><td align="center"><b><span id="somaIndFinanc2">xxx</span></b>
                  </td></tr>

                <tr>
                  <td align="left">Total</td><td align="center">&nbsp;</td><td align="center">&nbsp;</td><td align="center"><b><span id="rRR4" class="badge bg-light-blue">26</span></b></td>
                </tr>
                </tbody>
              </table>
              <br>

              <b>Credit Score:</b> <input type="text" name="nota_credit_score_importador" id="r7" value="{{ (trim(@$dadosCreditScore[$id_mpme_alcada]['CREDIT_SCORE']) != "") ? @$dadosCreditScore[$id_mpme_alcada]['CREDIT_SCORE'] : 'xxx' }}" maxlength="3" max="3" style="width: 25px;">
            </div>
            <!-- /.box-body -->
          </div>
        </div>
      </div>
    </div>
    <div class="panel-footer"><button type="button" class="btn btn-primary" id="btnRecalcularCreditScore" ><i class="fa fa-refresh"></i> Recalcular Credit-Score</button></div>
  </div>
@endif

<br />


<br />


<!-- Limite operacional -->


<div class="row">
  <div class="col-md-12">
  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Controle de capital</h3>

    </div>
    <div class="panel-body">
      <div class="box-body pad">
        <div class="row">
          <div class="col-md-3">
            <div class="form-group">
              <label>Fundo principal para operação</label>
              @php // $ID_MPME_FUNDO_PRINCIPAL= $importador->mpme_movimentacao_controle_capital($id_mpme_alcada, $importador->ID_OPER)->ID_MPME_FUNDO_PRINCIPAL;  @endphp




                <select class="form-control" name="id_mpme_fundo_garantia_operacao" id="id_mpme_fundo_garantia_operacao">

                @foreach($dadosFundoGarantia as $fundo)
                        <option value="{{$fundo->ID_MPME_FUNDO_GARANTIA}}"  >{{$fundo->NO_FUNDO_GARANTIA}}</option>
                @endforeach

              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Moeda da Operacao</label>
              <select class="form-control" name="id_moeda" id="id_moeda" readonly="readonly">
                <option value="{{$operacao->ID_MOEDA}}">{{$operacao->SIGLA_MOEDA}}</option>
              </select>
            </div>
          </div>
          <div class="col-md-3">
            <div class="form-group">
              <label>Valor para Aprovação</label>
              <input type="text" name="vl_cred_concedido" id="vl_cred_concedido" value="{{$valor}}" class="form-control money" >
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
          <div class="bs-callout bs-callout-primary" id="callout-btn-group-tooltips" style="height: 160px;">

           @php

              $rsFuncoGarantia = $importador->movimentacao_capital->where('TP_MOVIMENTACAO', '=', 'DEBITO')->where('ID_MPME_ALCADA',($id_mpme_alcada != 1) ? $id_mpme_alcada : 7);

           @endphp



            @foreach($rsFuncoGarantia as $fundoMovimentacao)
              <div class="col-md-3">
                <div class="form-group">
                  <label>Fundo</label>
                  <select class="form-control" data-fundo="{{$fundoMovimentacao->ID_MPME_FUNDO_GARANTIA}}"  name="id_mpme_fundo_garantia_negocio[]" id="id_mpme_fundo_garantia_{{$fundoMovimentacao->ID_MPME_FUNDO_GARANTIA}}">
                    @foreach($dadosFundoGarantia as $fundo)
                      <option @if ( $fundoMovimentacao->ID_MPME_FUNDO_GARANTIA == $fundo->ID_MPME_FUNDO_GARANTIA) selected="selected" @endif value="{{$fundo->ID_MPME_FUNDO_GARANTIA}}"  >{{$fundo->NO_FUNDO_GARANTIA}}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>% a ser utilizado do fundo: </label>
                  <input type="text" name="vl_perc_fundo[]" data-fundo="{{$fundoMovimentacao->ID_MPME_FUNDO_GARANTIA}}" id="vl_perc_fundo_{{$fundoMovimentacao->ID_MPME_FUNDO_GARANTIA}}" value="{{ $fundoMovimentacao->VL_PERC_FUNDO }}" class="form-control perc_fundo percentual">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Valor total em R$: </label>
                  <input type="text" name="vl_total_real[]" data-fundo="{{$fundoMovimentacao->ID_MPME_FUNDO_GARANTIA}}" id="vl_total_real_{{$fundoMovimentacao->ID_MPME_FUNDO_GARANTIA}}" value="{{formatar_valor_sem_moeda($fundoMovimentacao->VL_TOTAL_REAIS) }}" class="form-control" readonly="readonly">
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label>Saldo suficiente</label>
                  <select class="form-control in_saldo_suficiente" data-fundo="{{$fundoMovimentacao->ID_MPME_FUNDO_GARANTIA}}" id="in_saldo_suficiente_{{$fundoMovimentacao->ID_MPME_FUNDO_GARANTIA}}" name="in_saldo_suficiente[]">
                    <option value="0">Selecione</option>
                    <option value="SIM" @if($fundoMovimentacao->IN_SALDO_SUFICIENTE == 'SIM') selected @endif>SIM</option>
                    <option value="NAO" @if($fundoMovimentacao->ID_MPME_FUNDO_GARANTIA->IN_SALDO_SUFICIENTE ?? '' == 'NAO') selected @endif>NÃO</option>
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




<!-- Fecha Limite Operacional -->


<input type="hidden"  name="id_mpme_fundo_garantia" id="id_mpme_fundo_garantia" value="{{$rsFuncoGarantia->first()->ID_MPME_FUNDO_GARANTIA ?? ''}}">

<div class="panel panel-default">
  <div class="panel-heading clearfix">
    <h3 class="panel-title pull-left" style="padding-top: 7.5px;">Parecer Técnico</h3>
    <div class="btn-group pull-right">
        <a href="#" class="btn btn-success pull-right historicoParecer" data-toggle="modal" data-target="#modalParecer{{$alcada['ID_MPME_ALCADA']}}">Historico de Parecer</a>
    </div>
  </div>
  <div class="panel-body">
    <div class="box-body pad">
    <div class="row">
      <div class="col-md-4 ">
        <div class="form-group">
          <label>Data da Aprovação/Indeferimento</label>
          <div class="input-group date">
            <div class="input-group-addon">
              <i class="fa fa-calendar"></i>
            </div>
            <input type="date" class="form-control pull-right DT_RECOMENDACAO" name="DT_RECOMENDACAO" value="{{ @$importador->recomendacao($id_mpme_alcada,$importador->ID_OPER)->DT_RECOMENDACAO}}">
          </div>
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label>Número único da operação</label>
          <input type="text" name="COD_UNICO_OPERACAO" id="COD_UNICO_OPERACAO" class="COD_UNICO_OPERACAO form-control" value="{{$importador->OperacaoCadastroExportador->COD_UNICO_OPERACAO}}">
        </div>
      </div>

      <div class="col-md-4">
        <div class="form-group">
          <label>Recomendação</label>
          <select name="ds_recomendacao" id="ds_recomendacao"  class="form-control">
            <option value=""></option>
            <option value="1" {{( @$importador->recomendacao($id_mpme_alcada,$importador->ID_OPER)->DS_RECOMENDACAO == 1) ? 'selected' : '' }} >Aprovar</option>
            <option value="2" {{( @$importador->recomendacao($id_mpme_alcada,$importador->ID_OPER)->DS_RECOMENDACAO == 2) ? 'selected' : '' }} >Indeferir</option>
          </select>
        </div>
      </div>

      <div class="col-md-12" id="mtIndeferimento" style="{{ ( @trim($importador->recomendacao($id_mpme_alcada,$importador->ID_OPER)->DS_RECOMENDACAO) == '' || @$importador->recomendacao($id_mpme_alcada,$importador->ID_OPER)->DS_RECOMENDACAO == 1) ? 'display:none;' : '' }}">
        <div class="form-group">
          <label>Motivo do indeferimento</label>

            <?php $respostaIndeferimento = $importador->respostaIndeferimento($id_mpme_alcada, $importador->ID_OPER);?>
          <select name="motivo_indeferimento[]" id="motivo_indeferimento" class="form-control select2" multiple="multiple" title="Selecione um ou mais motivos" data-placeholder="Selecione um ou mais motivos" style="width: 100%;">
            <!-- <option value=''></option> -->

            <optgroup label="Pré-Embarque">
              @foreach($tipoIndeferimentos as $tpIndPre)
                @if($tpIndPre->ID_MODALIDADE == 1)
                  <option value="{{$tpIndPre->ID_MPME_TIPO_INDEFERIMENTO}}" @if(@in_array($tpIndPre->ID_MPME_TIPO_INDEFERIMENTO,$respostaIndeferimento)) selected @endif >Pré-Embarque -> {{$tpIndPre->NO_TIPO_INDEFERIMENTO}}</option>
                @endif
              @endforeach
            </optgroup>

            <optgroup label="Pós-Embarque">
              @foreach($tipoIndeferimentos as $tpIndPos)
                @if($tpIndPos->ID_MODALIDADE == 3)
                  <option value="{{$tpIndPos->ID_MPME_TIPO_INDEFERIMENTO}}" @if(@in_array($tpIndPos->ID_MPME_TIPO_INDEFERIMENTO,$respostaIndeferimento)) selected @endif>Pós-Embarque -> {{$tpIndPos->NO_TIPO_INDEFERIMENTO}}</option>
                @endif
              @endforeach
            </optgroup>
          </select>

        </div>
      </div>

    </div>


    <div class="row">
      <div class="col-md-12">
        <div class="bs-callout bs-callout-primary">
          <label>PDF Relatório Parecer</label>

         @if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 1)

          @if($id_mpme_alcada == 1 || ($id_mpme_alcada == 2 && !isset($importador->creditScoreExportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO)))

            <div class="row">
              <div class="col-md-12">

                <div class="fileinput fileinput-new " data-provides="fileinput">
            <span class="btn btn-default btn-file">
              <span class="fileinput-new">Selecionar arquivo</span>
              <span class="fileinput-exists">
                 <span class="fileinput-filename">
                 </span>
              </span>
              <input type="file" class="form-control" name="parecer_pdf"></span>

                  <a href="#" class="fileinput-exists btn btn-danger" data-dismiss="fileinput" style="float: none">&times;</a>
                </div>
              </div>
            </div>

          @else
            <div class="row">
              <div class="col-md-5">
                <label class="radio-inline">
                  <input type="radio" name="optradio" class="manterArquivoAtual" value="manter" checked>Manter arquivo
                </label>
                <label class="radio-inline">
                  <input type="radio" name="optradio" class="subsTituirArquivo" value="substituir" data-dismiss="fileinput">Substituir
                </label>
              </div>
            </div>
            <br>
            <div class="manterArquivo">


              <input type="hidden" name="id_mpme_arquivo"
                     value="{{ (isset($importador->creditScoreExportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO)) ? $importador->creditScoreExportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO : '' }}">

              <input type="hidden" name="nome_arquivo"
                     value="{{ (isset($importador->creditScoreExportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO)) ? $importador->creditScoreExportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->Arquivo->NO_ARQUIVO ?? '': '' }}">


              <a class="btn btn-default"> {{ (isset($importador->creditScoreExportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO) && $importador->creditScoreExportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO) ? $importador->creditScoreExportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->Arquivo->NO_ARQUIVO ?? '' : '' }}
              </a>


            </div>

            <div class="row novoArquivo" style="display: none;">
              <div class="col-md-12">

                <div class="fileinput fileinput-new " data-provides="fileinput">
            <span class="btn btn-default btn-file">
              <span class="fileinput-new">Selecionar arquivo</span>
              <span class="fileinput-exists">
                 <span class="fileinput-filename">
                 </span>
              </span>
              <input type="file" class="form-control" name="parecer_pdf"></span>

                  <a href="#" class="fileinput-exists btn btn-danger" data-dismiss="fileinput" style="float: none">&times;</a>
                </div>
              </div>
            </div>

          @endif

        @else

          {{--Pos e Pre-Pos--}}

         @if($id_mpme_alcada == 1 || ($id_mpme_alcada == 2 && !isset($importador->creditScoreImportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO)))

            <div class="row">
              <div class="col-md-12">

                <div class="fileinput fileinput-new " data-provides="fileinput">
            <span class="btn btn-default btn-file">
              <span class="fileinput-new">Selecionar arquivo</span>
              <span class="fileinput-exists">
                 <span class="fileinput-filename">
                 </span>
              </span>
              <input type="file" class="form-control" name="parecer_pdf"></span>

                  <a href="#" class="fileinput-exists btn btn-danger" data-dismiss="fileinput" style="float: none">&times;</a>
                </div>
              </div>
            </div>

         @else
          <div class="row">
            <div class="col-md-5">
              <label class="radio-inline">
                <input type="radio" name="optradio" class="manterArquivoAtual" value="manter" checked>Manter arquivo
              </label>
              <label class="radio-inline">
                <input type="radio" name="optradio" class="subsTituirArquivo" value="substituir" data-dismiss="fileinput">Substituir
              </label>
            </div>
          </div>
          <br>
          <div class="manterArquivo">

            <input type="hidden" name="id_mpme_arquivo"
                   value="{{ (isset($importador->creditScoreImportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO)) ? $importador->creditScoreImportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO : '' }}">

            <input type="hidden" name="nome_arquivo"
                   value="{{ (isset($importador->creditScoreImportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO)) ? $importador->creditScoreImportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->Arquivo->NO_ARQUIVO  ??  '' : '' }}">


            <a class="btn btn-default"> {{ (isset($importador->creditScoreImportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO) && $importador->creditScoreImportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->ID_MPME_ARQUIVO) ? $importador->creditScoreImportador->where('ID_MPME_ALCADA',$id_mpme_alcada)->first()->Arquivo->NO_ARQUIVO ?? '': '' }}
              </a>


          </div>

          <div class="row novoArquivo" style="display: none;">
            <div class="col-md-12">

              <div class="fileinput fileinput-new " data-provides="fileinput">
            <span class="btn btn-default btn-file">
              <span class="fileinput-new">Selecionar arquivo</span>
              <span class="fileinput-exists">
                 <span class="fileinput-filename">
                 </span>
              </span>
              <input type="file" class="form-control" name="parecer_pdf"></span>

                <a href="#" class="fileinput-exists btn btn-danger" data-dismiss="fileinput" style="float: none">&times;</a>
              </div>
            </div>
          </div>

          @endif

          @endif


        </div>
      </div>





</div>



<br>
        <textarea id="ds_parecer{{ $alcada['ID_MPME_ALCADA'] }}" name="ds_parecer" class="ckeditor ds_parecer_tabs" rows="10" cols="80">
           {{($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE !=1) ? @$dadosCreditScore[$id_mpme_alcada]['DS_PARECER'] : @$dadosCreditScorePre[$id_mpme_alcada]['DS_PARECER']}}
        </textarea>

    </div>





  </div>


</div>
@can('GRAVAR_ANALISE')
  <div class="panel-footer text-right">
    <button type="submit" class="btn btn-primary fa fa-save" id="salvar" > Salvar</button>
  </div>
@endcan
</form>

{{--modal parecer--}}

<!-- Modal -->
<div id="modalParecer{{$alcada['ID_MPME_ALCADA']}}" class="modal fade" role="dialog">
  <div class="modal-dialog modal-lg">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Parecer Técnico</h4>
      </div>
      <div class="modal-body">



        <table class="table table-striped table-bordered" id="tdsRecuperacao">
          <tr>
            <th width="15%">Data do paracer</th>
            <th>Alçada</th>
            <th width="15%">Recomendação</th>
            <th width="15%">PDF</th>
          </tr>
          @if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE ==1)


            @if(isset($importador->creditScoreExportador))
              @foreach($importador->creditScoreExportador as $creditScoreImportador)
                <tr>
                  <td>{{ (isset($creditScoreImportador->DATA_CADASTRO)) ? formatar_data($creditScoreImportador->DATA_CADASTRO) : '' }}</td>
                  <td width="15%">{{ (isset($creditScoreImportador->Alcada)) ? $creditScoreImportador->Alcada->NO_ALCADA : '' }}</td>
                  <td>{{ (isset($creditScoreImportador->RecomendacaoAlcada)) ? ($creditScoreImportador->RecomendacaoAlcada->DS_RECOMENDACAO == 1) ? 'Aprovar' : 'Indeferir' : '' }}</td>
                  <td><a href="{{Route('abgf.arquivo.download', ['ID_MPME_ARQUIVO' => $creditScoreImportador->ID_MPME_ARQUIVO])}}" class="btn btn-success"> <i class="fa fa-download" aria-hidden="true"></i> Download </a> </td>
                </tr>
              @endforeach
            @endif



          @else



        @if(isset($importador->creditScoreImportador))
          @foreach($importador->creditScoreImportador as $creditScoreImportador)
            <tr>
              <td>{{ (isset($creditScoreImportador->DATA_CADASTRO)) ? formatar_data($creditScoreImportador->DATA_CADASTRO) : '' }}</td>
              <td width="15%">{{ (isset($creditScoreImportador->Alcada)) ? $creditScoreImportador->Alcada->NO_ALCADA : '' }}</td>
              <td>{{ (isset($creditScoreImportador->RecomendacaoAlcada)) ? ($creditScoreImportador->RecomendacaoAlcada->DS_RECOMENDACAO == 1) ? 'Aprovar' : 'Indeferir' : '' }}</td>
              <td><a href="{{Route('abgf.arquivo.download', ['ID_MPME_ARQUIVO' => $creditScoreImportador->ID_MPME_ARQUIVO])}}" class="btn btn-success"> <i class="fa fa-download" aria-hidden="true"></i> Download </a> </td>
            </tr>
           @endforeach
        @endif

          @endif

        </table>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
      </div>
    </div>

  </div>
</div>




<script src="{{ asset('js/questionario/funcoes_limite_operacional_tabs.js') }}?time={{$time}}"></script>
