@extends('layouts.app')

@section('content')

  <style>
  #InputImportador {
    background-image: url('/img/searchicon.png');
    background-position: 10px 10px;
    background-repeat: no-repeat;
    width: 100%;
    font-size: 16px;
    padding: 12px 20px 12px 40px;
    border: 1px solid #ddd;
    margin-bottom: 12px;
  }

  #TabelaImportador {
    border-collapse: collapse;
    width: 100%;
    border: 1px solid #ddd;
    font-size: 18px;
  }

  #TabelaImportador th, #TabelaImportador td {
    text-align: left;
    padding: 12px;
  }

  #TabelaImportador tr {
    border-bottom: 1px solid #ddd;
  }

  #TabelaImportador tr.header, #TabelaImportador tr:hover {
    background-color: #f1f1f1;
  }
  </style>


<!-- Content Header (Page header) -->
<section class="content-header">
  <h1>
    Analise do Limite
  </h1>
  <ol class="breadcrumb">
    <li><a href="{{URL::to('/abgf/exportador/analisalimite')}}"><i class="fa fa-dashboard"></i> Inicio</a></li>
    <li class="active">Analise do Limite</li>
  </ol>
</section>

<!-- Main content -->
<section class="content">
  <div class="row">

    <!--MENU DA PAGINA-->
    <div class="col-md-2">
       @include('layouts.menu_abgf')
    </div>
    <!-- /.col -->


    <form action="{{ route('abgf.exportador.encaminhar')}}" method="POST" name="frmEncaminharAlcada" id="frmEncaminharAlcada">
      <input type="hidden" name="rota" id="rota" value="{{ route('abgf.exportador.encaminhar')}}"/>
      <input type="hidden" name="ID_OPER" id="ID_OPER" value="{{ $importador->ID_OPER }}"/>
      <input type="hidden" name="FL_MOMENTO" id="FL_MOMENTO" value="{{ $importador->ID_OPER }}"/>
      <input type="hidden" name="ID_NOTIF" id="ID_NOTIF" value=""/>
      <input type="hidden" name="ID_NOVO_USUARIO" id="ID_NOVO_USUARIO" value="{{ $exportador->ID_USUARIO }}"/>
      <input type="hidden" name="IC_INDEFERIDA" id="IC_INDEFERIDA" value=""/>
      <input type="hidden" name="DS_RECOMENDACAO" id="DS_RECOMENDACAO" value=""/>
      <input type="hidden" name="DT_RECOMENDACAO" id="DT_RECOMENDACAO" value=""/>
      <input type="hidden" name="VL_CRED_CONCEDIDO" id="VL_CRED_CONCEDIDO" value=""/>
      <input type="hidden" name="CODIGO_UNICO_IMPORTADOR" id="CODIGO_UNICO_IMPORTADOR" value="{{trim($importador->OperacaoCadastroExportador->COD_UNICO_OPERACAO)}}"/>
      <input type="hidden" name="ID_CREDIT" id="ID_CREDIT" value=""/>
      <input type="hidden" name="NM_USUARIO" id="NM_USUARIO" value="{{ $exportador->NM_USUARIO }}"/>
      <input type="hidden" name="OPER_MPME" id="OPER_MPME" value="{{ $exportador->ID_USUARIO }}"/>
      <input type="hidden" name="RAZAO_SOCIAL" id="RAZAO_SOCIAL" value="{{ $importador->RAZAO_SOCIAL }}"/>
      <input type="hidden" name="alcada_atual" id="alcada_atual" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="ID_MPME_ALCADA" id="ID_MPME_ALCADA" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
       <input type="hidden" name="ID_MPME_ALCADA_ATUAL" id="ID_MPME_ALCADA_ATUAL" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="ID_MPME_ALCADA_PROXIMA" id="ID_MPME_ALCADA_PROXIMA" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_PROXIMA'] }}"/>
      <input type="hidden" name="IN_DECISAO" id="IN_DECISAO" value=""/>
      <input type="hidden" name="VL_APROVADO" id="VL_APROVADO" value=""/>
      <input type="hidden" name="NO_ALCADA" id="NO_ALCADA" value=""/>
      <input type="hidden" name="ID_STATUS_NOTIFICACAO_FK" id="ID_STATUS_NOTIFICACAO_FK" value="501"/>
      <input type="hidden" name="ID_MPME_FUNDO_GARANTIA" id="ID_MPME_FUNDO_GARANTIA" value="{{$importador->mpme_movimentacao_controle_capital(7, $importador->ID_OPER)->ID_MPME_FUNDO_PRINCIPAL}}"/>
      @if( @$dadosAProvValorAlc[$crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR']]['IN_DEVOLVIDA'] != 0)
        <input type="hidden" name="ID_APROVACAO_VALOR_ALCADA" id="ID_APROVACAO_VALOR_ALCADA" value="{{@$dadosAProvValorAlc[$crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR']]['ID_APROVACAO_VALOR_ALCADA']}}"/>
      @endif

    </form>
    <form action="{{ route('abgf.exportador.devolver')}}" method="POST" name="frmDevolverAlcada" id="frmDevolverAlcada">
      <input type="hidden" name="rota" id="rota" value="{{ route('abgf.exportador.devolver')}}"/>
      <input type="hidden" name="ID_OPER" id="ID_OPER" value="{{ $importador->ID_OPER }}"/>
      <input type="hidden" name="FL_MOMENTO" id="FL_MOMENTO" value="{{ $importador->ID_OPER }}"/>
      <input type="hidden" name="ID_NOTIF" id="ID_NOTIF" value=""/>
      <input type="hidden" name="ID_NOVO_USUARIO" id="ID_NOVO_USUARIO" value="{{ $exportador->ID_USUARIO }}"/>
      <input type="hidden" name="IC_INDEFERIDA" id="IC_INDEFERIDA" value=""/>
      <input type="hidden" name="DS_RECOMENDACAO" id="DS_RECOMENDACAO" value=""/>
      <input type="hidden" name="DT_RECOMENDACAO" id="DT_RECOMENDACAO" value=""/>
      <input type="hidden" name="VL_CRED_CONCEDIDO" id="VL_CRED_CONCEDIDO" value=""/>
      <input type="hidden" name="ID_CREDIT" id="ID_CREDIT" value=""/>
      <input type="hidden" name="NM_USUARIO" id="NM_USUARIO" value="{{ $exportador->NM_USUARIO }}"/>
      <input type="hidden" name="OPER_MPME" id="OPER_MPME" value="{{ $exportador->ID_USUARIO }}"/>
      <input type="hidden" name="RAZAO_SOCIAL" id="RAZAO_SOCIAL" value="{{ $importador->RAZAO_SOCIAL }}"/>
      <input type="hidden" name="alcada_atual" id="alcada_atual" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="ID_MPME_ALCADA" id="ID_MPME_ALCADA" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="IN_DECISAO" id="IN_DECISAO" value=""/>
      <input type="hidden" name="IC_DEVOLVEU_ALCADA_ANTERIOR" id="IC_DEVOLVEU_ALCADA_ANTERIOR" value="1"/>
      <input type="hidden" name="VL_APROVADO" id="VL_APROVADO" value=""/>
      <input type="hidden" name="NO_ALCADA" id="NO_ALCADA" value=""/>
      <input type="hidden" name="DE_MOTIVO_DEVOLUCAO" id="DE_MOTIVO_DEVOLUCAO" value=""/>
      <input type="hidden" name="ID_STATUS_NOTIFICACAO_FK" id="ID_STATUS_NOTIFICACAO_FK" value="502"/>

    </form>

    <form action="{{ route('abgf.exportador.indeferir')}}" method="POST" name="frmIndeferir" id="frmIndeferir">
      <input type="hidden" name="rota" id="rota" value="{{ route('abgf.exportador.indeferir')}}"/>
      <input type="hidden" name="ID_OPER" id="ID_OPER" value="{{ $importador->ID_OPER }}"/>
      <input type="hidden" name="FL_MOMENTO" id="FL_MOMENTO" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="ID_NOTIF" id="ID_NOTIF" value=""/>
      <input type="hidden" name="ID_NOVO_USUARIO" id="ID_NOVO_USUARIO" value="{{ $exportador->ID_USUARIO }}"/>
      <input type="hidden" name="IC_INDEFERIDA" id="IC_INDEFERIDA" value=""/>
      <input type="hidden" name="DS_RECOMENDACAO" id="DS_RECOMENDACAO" value=""/>
      <input type="hidden" name="DT_RECOMENDACAO" id="DT_RECOMENDACAO" value=""/>
      <input type="hidden" name="VL_CRED_CONCEDIDO" id="VL_CRED_CONCEDIDO" value=""/>
      <input type="hidden" name="ID_CREDIT" id="ID_CREDIT" value=""/>
      <input type="hidden" name="NM_USUARIO" id="NM_USUARIO" value="{{ $exportador->NM_USUARIO }}"/>
      <input type="hidden" name="OPER_MPME" id="OPER_MPME" value="{{ $exportador->ID_USUARIO }}"/>
      <input type="hidden" name="RAZAO_SOCIAL" id="RAZAO_SOCIAL" value="{{ $importador->RAZAO_SOCIAL }}"/>
      <input type="hidden" name="alcada_atual" id="alcada_atual" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="ID_MPME_ALCADA" id="ID_MPME_ALCADA" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="IN_DECISAO" id="IN_DECISAO" value=""/>
      <input type="hidden" name="IC_DEVOLVEU_ALCADA_ANTERIOR" id="IC_DEVOLVEU_ALCADA_ANTERIOR" value="0"/>
      <input type="hidden" name="VL_APROVADO" id="VL_APROVADO" value=""/>
      <input type="hidden" name="NO_ALCADA" id="NO_ALCADA" value=""/>
      <input type="hidden" name="DE_MOTIVO_DEVOLUCAO" id="DE_MOTIVO_DEVOLUCAO" value=""/>
      <input type="hidden" name="ID_STATUS_NOTIFICACAO_FK" id="ID_STATUS_NOTIFICACAO_FK" value="505"/>
      <input type="hidden" name="DS_PARECER" id="DS_PARECER" value=""/>
      <input type="hidden" name="ID_MPME_FUNDO_GARANTIA" id="ID_MPME_FUNDO_GARANTIA" value="{{$importador->mpme_movimentacao_controle_capital(7, $importador->ID_OPER)->ID_MPME_FUNDO_PRINCIPAL}}"/>

    </form>

    <form action="{{ route('abgf.exportador.concluir')}}" method="POST" name="frmConcluir" id="frmConcluir">
      <input type="hidden" name="rota" id="rota" value="{{ route('abgf.exportador.concluir')}}"/>
      <input type="hidden" name="ID_OPER" id="ID_OPER" value="{{ $importador->ID_OPER }}"/>
      <input type="hidden" name="FL_MOMENTO" id="FL_MOMENTO" value="{{ $importador->ID_OPER }}"/>
      <input type="hidden" name="ID_NOTIF" id="ID_NOTIF" value=""/>
      <input type="hidden" name="ID_NOVO_USUARIO" id="ID_NOVO_USUARIO" value="{{ $exportador->ID_USUARIO }}"/>
      <input type="hidden" name="IC_INDEFERIDA" id="IC_INDEFERIDA" value=""/>
      <input type="hidden" name="DS_RECOMENDACAO" id="DS_RECOMENDACAO" value=""/>
      <input type="hidden" name="DT_RECOMENDACAO" id="DT_RECOMENDACAO" value=""/>
      <input type="hidden" name="VL_CRED_CONCEDIDO" id="VL_CRED_CONCEDIDO" value=""/>
      <input type="hidden" name="ID_CREDIT" id="ID_CREDIT" value=""/>
      <input type="hidden" name="NM_USUARIO" id="NM_USUARIO" value="{{ $exportador->NM_USUARIO }}"/>
      <input type="hidden" name="OPER_MPME" id="OPER_MPME" value="{{ $exportador->ID_USUARIO }}"/>
      <input type="hidden" name="RAZAO_SOCIAL" id="RAZAO_SOCIAL" value="{{ $importador->RAZAO_SOCIAL }}"/>
      <input type="hidden" name="alcada_atual" id="alcada_atual" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="ID_MPME_ALCADA" id="ID_MPME_ALCADA" value="{{ $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] }}"/>
      <input type="hidden" name="IN_DECISAO" id="IN_DECISAO" value=""/>
      <input type="hidden" name="IC_DEVOLVEU_ALCADA_ANTERIOR" id="IC_DEVOLVEU_ALCADA_ANTERIOR" value="0"/>
      <input type="hidden" name="VL_APROVADO" id="VL_APROVADO" value=""/>
      <input type="hidden" name="NO_ALCADA" id="NO_ALCADA" value=""/>
      <input type="hidden" name="DE_MOTIVO_DEVOLUCAO" id="DE_MOTIVO_DEVOLUCAO" value=""/>
      <input type="hidden" name="ID_STATUS_NOTIFICACAO_FK" id="ID_STATUS_NOTIFICACAO_FK" value="504"/>

    </form>

    <div class="col-md-10">
        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Dados da operação</h3>
            </div>
            <div class="panel-body">
                <div class="col-md-12">

                    <h2 class="page-header">
                      <i class="fa fa-globe"></i> OPERAÇÃO:
                        @if($importador->OperacaoCadastroExportador->COD_UNICO_OPERACAO)
                            <span class="label label-primary">{{$importador->OperacaoCadastroExportador->COD_UNICO_OPERACAO}}</span>
                        @else
                            <span class="label label-warning">Número não atribuído</span>
                        @endif
                      <small class="pull-right">Data de cadastro: {{date("d/m/Y", strtotime($importador->DATA_CADASTRO))}}</small>
                    </h2>

                    @include('abgf.exportador.limite.abas_dados_operacao')
                </div>
            </div>
        </div>
        @if( @$dadosAProvValorAlc[$crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR']]['IN_DEVOLVIDA'])
            <div class="alert alert-warning">{{@$dadosAProvValorAlc[$crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR']]['TX_OBSERVACAO']}}</div>
        @endif

        <div class="panel panel-default">
            <div class="panel-heading">
              <h3 class="panel-title">Restrições de Países e Setores de atividade que <b>não</b> devem ser aprovadas pela ABGF</h3>
            </div>
            <div class="panel-body">
                <h4>Países</h4>
                @foreach( $listaRestricoesPais as $paises_restritos)
                    <h4 class="pull-left"><span class="label label-danger"><strong>{{ strtoupper($paises_restritos->paises->NM_PAIS) }}</strong></span>&nbsp;&nbsp;</h4>
                @endforeach
                <br/><br/>
                <h4>Setores de atividades</h4>
                @foreach( $listaRestricoesSetores as $setores_restritos)
                    <h4 class="pull-left"><span class="label label-danger"><strong>{{ $setores_restritos->setores->NM_SETOR }}</strong></span>&nbsp;&nbsp;</h4>
                @endforeach
            </div>
        </div>


      <div class="panel panel-default">

        <div class="panel-heading">
          <h3 class="panel-title">Lista de Aprovação</h3>
        </div>
        <div class="panel-body">
          <ul class="timeline timeline-horizontal">
            <!-- if($importador->creditoConcedido('ANA',$importador->ID_OPER)->VL_CRED_CONCEDIDO !='')  @formatar_valor($importador->creditoConcedido('ANA',$importador->ID_OPER)->VL_CRED_CONCEDIDO)  else R$ 00,00 endif -->
            <input type="hidden" name="valor_solicitado_cliente" id="valor_solicitado_cliente" value="{{$crontroleAlcadas['ALCADA'][0]['VL_APROVADO_ALCADA']}}" />
            @foreach($crontroleAlcadas['ALCADA']  as $alcada)
                <li class="timeline-item">
                    <div class="timeline-badge {{$alcada['NO_CLASSE']}} {{$alcada['ALCADA_HABILITADA']}}"><i class="glyphicon @if ($alcada['VL_APROVADO_ALCADA']) glyphicon-check @else glyphicon-unchecked @endif"></i></div>
                    <div class="alcada {{$alcada['ALCADA_HABILITADA']}}">{{$alcada['NO_ALCADA']}}</br><div class="disabled">{{ formatar_valor_sem_moeda($alcada['VL_APROVADO_ALCADA'])}}</div></div>
                </li>
            @endforeach


          </ul>
        </div>
      </div>




      <div class="panel panel-primary">
        <div class="panel-heading">
          <h3 class="panel-title">Aprovação das alçadas</h3>
        </div>
        <div class="panel-body">


          <!-- /.mailbox-read-info -->
         <div class="nav-tabs-custom">
           <ul class="nav nav-tabs">

             @foreach($crontroleAlcadas['ALCADA'] as $alcada)
               @if($alcada['ID_MPME_ALCADA'] != 1 && travarAlcadaSuperior($alcada['ID_MPME_ALCADA'] ))
                 <li data-id-alcada="{{$alcada['ID_MPME_ALCADA']}}" class="tabs_alcadas @if( $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] == $alcada['ID_MPME_ALCADA'] ) active @endif {{($alcada['NO_ALCADA'])}}" ><a href="#{{$alcada['ID_MPME_ALCADA']}}" data-toggle="tab">{{$alcada['NO_ALCADA']}}</a></li>

               @endif
             @endforeach
           </ul>
           <div class="tab-content">
             <!-- tab Analista -->
             @php $key = 0; @endphp
             @foreach($crontroleAlcadas['ALCADA'] as $alcada)
               @if($alcada['ID_MPME_ALCADA'] != 1 && travarAlcadaSuperior($alcada['ID_MPME_ALCADA'] ))
                   <div class="@if( $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] == $alcada['ID_MPME_ALCADA'] ) active @endif tab-pane" id="{{$alcada['ID_MPME_ALCADA']}}">
                     @include('abgf.exportador.limite.tab_analise_analista')
                   </div>
                 @php $key = $key + 1; @endphp
               @endif
            @endforeach
             <!-- Fecha tab analista -->
             <!-- /.tab-pane -->
           </div>
           <!-- /.tab-content -->


          </div>

          <div class="panel-footer">
            <div class="row">
                <div class="col-md-6 text-left"><a href="javascrit:history.go(-1);"class="btn btn-default text-left" ><i class="fa fa-arrow-circle-o-left"></i> Voltar</a></div>
                @if( array_key_exists($crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'], ($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE == 1) ? $dadosCreditScorePre : $dadosCreditScore ))
                <div class="col-md-6 text-right">
                      <button type="button" class="btn btn-danger " id="indeferir" style="display: none"><i class="fa fa-ban"></i> Indeferir</button>

                      @if ( $crontroleAlcadas['CONTROLE_APROVACAO']['ID_MPME_ALCADA_APROVAR'] != 2)
                        <button type="button" class="btn btn-warning text-right" id="devolver" ><i class="fa fa-undo"></i> Devolver</button>
                      @endif

                      @if($crontroleAlcadas['CONTROLE_APROVACAO']['ULTIMA'] != 'SIM')
                        <button type="button" class="btn btn-success text-right" id="encaminhar" ><i class="fa fa-random"></i> Encaminhar</button>
                      @else
                        <button type="button" class="btn btn-success text-right" id="concluir" ><i class="fa fa-check"></i> Concluir</button>
                      @endif
                  </div>
                @endif
            </div>

          </div>


        </div>
      </div>

    </div>
<!-- /.col -->
</div>
<!-- /.row -->
</section>
<!-- /.content -->
</div>

<script type="text/javascript" src="{{ asset('js/abgf/exportador/limite/tab_analise_analista.js').'?'.time() }}"></script>
<script type="text/javascript" src="{{ asset('js/abgf/exportador/limite/importador_unico.js').'?'.time() }}"></script>


<script type="text/javascript">
    function apenas5(obj)
    {

        if(obj.value>5 || obj.value=="" || obj.value==0)
        {
          obj.value="";
        }
    }
</script>
<script>
  $(function () {
    var alcadaAtual =  $('#ID_MPME_ALCADA').val();
    $('#'+alcadaAtual+' .select2').select2()

    //Datemask dd/mm/yyyy
    $('#'+alcadaAtual+' #datemask').inputmask('dd/mm/yyyy', { 'placeholder': 'dd/mm/yyyy' })

  })
</script>

@endsection
