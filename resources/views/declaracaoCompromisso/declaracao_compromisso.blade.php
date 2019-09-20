@extends('layouts.app')

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Controle da Exportação
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row">

         @include('layouts.menu_cliente')

        <!--CONTEUDO DA PAGINA-->
        <div class="col-md-10">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">Informações</h3>
                </div>
                <div class="panel-body">
                    <h1></h1>
                    <p class="text-justify">
                        Prezado Exportador:<br>

                        <br>
                        <p style="text-align: justify;">Em cumprimento à determinação da Câmara de Comércio Exterior, através de sua Resolução CAMEX nº 081, de 18 de setembro de 2014, apresentamos, a “Declaração de Compromisso do Exportador”, a qual deverá ser assinada pelo representante legal de sua empresa.</p>
                        <br>
                        <p style="text-align: justify;">
                            Após a devida assinatura, pedimos a gentileza de efetuar o seu up-load no link abaixo, juntamente com os seguintes documentos: Contrato Social/Estatuto Social (conforme o caso), registrados na Junta Comercial do local onde a empresa tem sede; Eleição da Diretoria – que pode ser através de Ata de Reunião de Conselho de Administração / Ata de Assembleia Geral / Ata de Reunião de Sócios / Contrato Social (conforme o caso), registrados na Junta Comercial do local onde a empresa tem sede; Ata de Assembleia Geral que eleger Conselho de Administração (se houver), registrada na Junta Comercial do local onde a empresa tem sede; Procuração com poderes específicos para assinar a "Declaração de Compromisso do Exportador" (apenas nas situações em que a assinatura não seja de diretor com poderes atribuídos no Estatuto Social e/ou no Contrato Social da empresa exportadora/declarante); Certidão Simplificada da Junta Comercial do local onde a empresa tem sede, com até 30 (trinta) dias de emissão; e Documentos de identificação pessoal dos signatários (CI/CPF). 
                        </p> 
                         <br>
                        <p style="text-align: justify;">
                            A via original da referida declaração deverá ser encaminhada para Filial - Rua da Quitanda, nº 86 – 2º andar – Edifício Sul América – Centro – Rio de Janeiro-RJ, RJ, CEP 20091-005.
                        </p>
                    <div class="alert alert-info">
                        <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span>&nbsp; A referida Declaração deve ser enviada em até 15 dias contados da inclusão da 1ª operação no sistema eletrônico, sem o que não poderemos dar continuidade à análise.

                    </div>
                    <br>
                    <p>
                        <a href="{{route('baixardeclaracao')}}" class="btn btn-success " target="_blank"><i class="fa fa-file-pdf-o"></i> Baixar Documento</a>
                        <button class="btn btn btn-warning enviardcanticorrupcao"><i class="fa fa-upload"> Enviar Doc. Assinado</i></button>
                    </p>

                    <br>

                    <div class="col-md-4" id="UploadAntiCorrupcao" style="display: none">
                        <div class="bs-callout bs-callout-primary" id="callout-btn-group-tooltips">
                            <h4>Upload da Declaração de compromisso.</h4><br/>
                            <div class="form-group">
                                <div class="alert" style="display:none" id="message_docanticorrupcao_relatorio"></div>
                                <form method="post" action="{{ route('ajaxupload.uploadAntiCorrupcao')}}" id="upload_anticorrupcao" enctype="multipart/form-data">
                                    {{ csrf_field() }}
                                    <input type="file" id="select_doc_anticorrupcao" name="select_doc_anticorrupcao">
                                    <p class="help-block">Upload documento assinado.</p>
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                </form>
                                <div id="upload_anticorrupcao_realizado" class="text-center" style="display:none;"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <br><br>
                        <a href="javascript:history.go(-1);" class="btn btn-default"><i class="fa fa-arrow-circle-o-left"></i> Voltar</a>
                    </div>
                </div>
            </div>
        </div>
      </div>
    </section>
    <!-- Modal -->
    <div class="modal fade " id="historico-aprovacao" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="loading">
                    <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                </div>
            </div>
        </div>
    </div>
  </div>
    <script src="{{ asset('js/questionario/funcoes_questionario.js') }}"></script>
    <script src="{{ asset('js/questionario/funcoes_anti_corrupcao.js') }}"></script>
@endsection
