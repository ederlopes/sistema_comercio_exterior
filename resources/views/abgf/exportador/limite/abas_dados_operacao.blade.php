<div class="nav-tabs-custom nav-tabs-dados">
    <ul class="nav nav-tabs" role="tablist">
        <li role="presentation" class="active"><a href="#tab-operacao" aria-controls="home" role="tab" data-toggle="tab">Dados da operação</a></li>
        @if($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE != 1)
            <li role="presentation"><a href="#tab-importador" aria-controls="profile" role="tab" data-toggle="tab">Dados do importador</a></li>
        @endif
        <li role="presentation"><a href="#tab-exportador" aria-controls="messages" role="tab" data-toggle="tab">Dados do exportador</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab-operacao">
            <div class="clearfix">
                <div class="btn-group pull-right">
                    @if(VerificaSeuploadFoifeito('upload_calculo_limite_credito',$operacao->ID_OPER))
                        <a href="{{URL::to('/uploads/abgf/exportador/limite/upload_calculo_limite_credito/')}}/{{$operacao->ID_OPER}}/{{$operacao->ID_OPER}}.pdf" class="btn btn-primary pull-right" target="_blank" style="margin-left: 10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Cálculo do limite de crédito</a>
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Número da operação:</h4>
                        @if($importador->OperacaoCadastroExportador->COD_UNICO_OPERACAO)
                            {{$importador->OperacaoCadastroExportador->COD_UNICO_OPERACAO}}
                        @else
                            <h4><span class="label label-warning">Número não atribuído</span></h4>
                        @endif
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Modalidade da operação:</h4>
                        {{$importador->OperacaoCadastroExportador->modalidade->NO_MODALIDADE}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Valor solicitado:</h4>
                        {{$importador->RetornaMoeda->SIGLA_MOEDA}} {{formatar_valor_sem_moeda($importador->VL_APROVADO)}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Natureza jurídica:</h4>
                        <?php
                        switch ($importador->NAT_JURIDICA) {
                            case '1':
                                echo 'Privada';
                                break;
                            case '2':
                                echo 'Pública';
                                break;
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Natureza do risco:</h4>
                        <?php
                        switch ($importador->NAT_JURIDICA) {
                            case '1':
                                echo 'Político e Extraordinário';
                                break;
                            case '2':
                                echo 'Comercial, Político e Extraordinário';
                                break;
                            case '3':
                                echo 'Soberano';
                                break;
                            case '4':
                                echo 'Ordinário';
                                break;
                        }
                        ?>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Data de cadastro:</h4>
                        {{date("d/m/Y H:i:s", strtotime($importador->DATA_CADASTRO))}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Data de envio para ABGF:</h4>
                        {{formatar_data_hora($operacao->DT_ENVIO_ABGF)}}
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <h4>Setores de atividades</h4>
                        @foreach($importador->setoresOperacao as $setores )
                            <label>{{$setores->setor->NM_SETOR}}</label>
                        @endforeach
                    </div>
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="bs-callout bs-callout-" id="callout-btn-group-tooltips">
                        <h4>Para visualizar as informações complementares da operação.</h4>
                        <a class="btn btn-primary" data-toggle="collapse" href="#datalhes-pergunta" role="button" aria-expanded="false"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Clique Aqui</a>
                        <br /><br />
                        <div class="collapse multi-collapse" id="datalhes-pergunta">
                            @foreach($rs_pergunta as $pergunta)
                                <div class="alert alert-info">
                                    <strong>{{$pergunta->NO_PERGUNTA }}</strong><br />
                                    @foreach($pergunta->respostas as $respostas)
                                        <div class="bite-checkbox inline">
                                            <input id="RESPOSTA_ID_{{$respostas->ID_MPME_PERGUNTA_RESPOSTA}}" type="radio" {{(in_array($respostas->ID_MPME_PERGUNTA_RESPOSTA,$rs_questionario)?'checked="checked"':'')}} disabled>
                                            <label for="RESPOSTA_ID_{{$respostas->ID_MPME_PERGUNTA_RESPOSTA}}">
                                                {{$respostas->resposta->NO_RESPOSTA}}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>


        </div>

        @if (in_array($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE, [2,3]) )
            <div role="tabpanel" class="tab-pane" id="tab-importador">
                <div class="clearfix">
                    <div class="btn-group pull-right">
                        @if(VerificaSeuploadFoifeito('relatorio_internacional',$operacao->ID_OPER))
                            <a href="{{URL::to('/uploads/abgf/exportador/limite/relatorio_internacional/')}}/{{$operacao->ID_OPER}}/{{$operacao->ID_OPER}}.pdf" class="btn btn-primary pull-right" target="_blank" style="margin-left: 10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Relatório internacional</a>
                        @endif
                    </div>
                </div>
                <div class="row" style="margin-top: 20px;">
                    <div class="col-md-3">
                        <div class="form-group">
                            <h4>País:</h4>
                            {{$importador->RetornaPaisImportadorOperacao->NM_PAIS}} (Risco: {{$importador->RetornaPaisImportadorOperacao->RiscoPais->CD_RISCO}}/7)
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <h4>Importador:</h4>
                            {{$importador->RAZAO_SOCIAL}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <h4>Valor acumulado de operações::</h4>
                            {{formatar_valor_sem_moeda(retornaSaldoImportadorExportador($importador->ID_OPER )) ?? ''}}
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <h4>CNPJ ou Equivalente:</h4>
                            {{$importador->CNPJ}}
                        </div>
                    </div>
                </div>


            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Endereço:</h4>
                        {{$importador->ENDERECO}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>País:</h4>
                        {{$importador->RetornaPaisImportadorOperacao->NM_PAIS}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Cidade:</h4>
                        {{$importador->CIDADE}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Nome do contato:</h4>
                        {{$importador->CONTATO}}
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>CNPJ:</h4>
                        {{$importador->CNPJ}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Contato:</h4>
                        {{$importador->CONTATO}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Data de cadastro:</h4>
                        {{formatar_data_hora($importador->DATA_CADASTRO)}}
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <h4>E-mail:</h4>
                        {{$importador->E_MAIL}}
                    </div>
                </div>
            </div>

            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <h4>CEP:</h4>
                        {{$importador->CEP}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Telefone:</h4>
                        {{$importador->TELEFONE}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Fax:</h4>
                        {{$importador->FAX ?? '-'}}
                    </div>
                </div>
            </div>

            @if (trim($importador->CODIGO_UNICO_IMPORTADOR) == 0)
                <div class="row">
                    <div class="form-group">
                        <button class="btn btn-success pull-right ajuste_cad_btn_importador" id="btnCadastrarImnportador" data-idoper="{{$importador->ID_OPER}}"><i class="fa fa-floppy-o" aria-hidden="true"></i> Cadastrar Importador</button>
                        <button class="btn btn-primary pull-right ajuste_cad_btn_importador" id="listarImportadores" data-toggle="modal" data-target="#modalListaImportadores" data-id_pais="{{ $importador->ID_PAIS }}" data-id_oper="{{ $importador->ID_OPER }}"><span class="fa fa-users" aria-hidden="true"></span> Listar Importadores</button>
                    </div>
                </div>

                <div id="modalListaImportadores" class="modal fade" role="dialog">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                                <h4 class="modal-title">Lista de importadores</h4>
                            </div>
                            <div class="modal-body">
                                <input type="text" id="InputImportador" onkeyup="funcaoImportador()" placeholder="Pesquise pela razão social.." title="Procure pelo nome">
                                <table id="TabelaImportador">
                                    <tr class="header">
                                        <th style="width:20%;"># Código</th>
                                        <th>Razão Social</th>
                                        <th>País</th>
                                    </tr>
                                </table>
                                <script>
                                    function funcaoImportador() {
                                        var input, filter, table, tr, td, i, txtValue;
                                        input = document.getElementById("InputImportador");
                                        filter = input.value.toUpperCase();
                                        table = document.getElementById("TabelaImportador");
                                        tr = table.getElementsByTagName("tr");
                                        for (i = 0; i < tr.length; i++) {
                                            td = tr[i].getElementsByTagName("td")[1];
                                            if (td) {
                                                txtValue = td.textContent || td.innerText;
                                                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                                                    tr[i].style.display = "";
                                                } else {
                                                    tr[i].style.display = "none";
                                                }
                                            }
                                        }
                                    }
                                </script>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Fechar</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            @if (in_array($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE, [2,3]) )
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lista de aprovação de Credit Score - <b>IMPORTADOR</b> </h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Alçada</th>
                                            <th>Credit Score</th>
                                            <th>Parecer</th>
                                            <th>PDF Parecer</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($creditScoreImportador as $credit)
                                            @php $credit = (object)$credit; @endphp
                                            <tr>
                                                <td>{{$credit->NO_ALCADA}}</td>
                                                <td>{{$credit->CREDIT_SCORE}}</td>
                                                <td>{!! html_entity_decode($credit->DS_PARECER) !!}</td>
                                                <td>@if($credit->ID_MPME_ARQUIVO ?? '' != '') <a href="{{route('abgf.arquivo.download', [$credit->ID_MPME_ARQUIVO]) }}">Download PDF</a> @else Arquivo Indisponivel @endif</td>
                                            </tr>
                                        @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
        @endif
        <div role="tabpanel" class="tab-pane" id="tab-exportador">
            <div class="clearfix">
                <div class="btn-group pull-right">
                    <a href="{{URL::to('/docs/anti-corrupcao/')}}/{{$operacao->ID_USUARIO}}/{{$operacao->ID_USUARIO}}.pdf" class="btn btn-primary pull-right" target="_blank" style="margin-left: 10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Declaração Anti-corrupção</a>
                    @if(VerificaSeuploadFoifeito('comprovante_pg_relatorio',$operacao->ID_OPER))
                        <a href="{{URL::to('/uploads/abgf/exportador/limite/comprovante_pg_relatorio/')}}/{{$operacao->ID_OPER}}/{{$operacao->ID_OPER}}.pdf" class="btn btn-primary pull-right" target="_blank" style="margin-left: 10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Relatório Nacional</a>
                    @endif

                    @foreach($arquivos as $arquivo)
                        @if($arquivo->ID_MPME_TIPO_ARQUIVO == 21)
                            <a href="/abgf/arquivos/download/{{$arquivo->ID_MPME_ARQUIVO}}" class="btn btn-primary pull-right" target="_blank" style="margin-left: 10px;"><i class="fa fa-file-pdf-o" aria-hidden="true"></i> Comprovante de Exportação</a>
                        @endif
                        @if($arquivo->ID_MPME_TIPO_ARQUIVO == 20)
                            <a href="/abgf/arquivos/download/{{$arquivo->ID_MPME_ARQUIVO}}" class="btn btn-primary pull-right" target="_blank" style="margin-left: 10px;"><i class="fa fa-file-pdf-o" aria-hidden="true" ></i> Visualizar DRE</a>
                        @endif
                    @endforeach
                </div>
            </div>
            <br />
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Exportador:</h4>
                        {{$exportador->NM_USUARIO}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Nome fantasia:</h4>
                        {{$exportador->NO_FANTASIA}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>CNPJ do exportador:</h4>
                        {{$exportador->NU_CNPJ}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Regime tributário:</h4>
                        {{$exportador->retornaSimplesNacional()->NO_REGIME_TRIBUTARIO}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Endereço:</h4>
                        {{$exportador->DE_ENDER}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Cidade:</h4>
                        {{$exportador->DE_CIDADE}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Estado:</h4>
                        {{$exportador->CD_UF}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>CEP:</h4>
                        {{$exportador->DE_CEP}}
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Nome do contato:</h4>
                        {{$exportador->NM_CONTATO}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Cargo:</h4>
                        {{$exportador->DE_CARGO}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Telefone:</h4>
                        ({{$exportador->NU_DDD}}) {{$exportador->DE_TEL}}
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>E-mail:</h4>
                        {{$exportador->DE_EMAIL ?? '-'}}
                    </div>
                </div>
               
            </div>

            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Faturamento Bruto Anual:</h4>
                        R$ {{formatar_valor_sem_moeda($exportador->ClienteExportador->FinanceiroExportador->VL_FAT_BRUTO_ANUAL)}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Valor de exportação:</h4>
                        U$ {{formatar_valor_sem_moeda($exportador->ClienteExportador->FinanceiroExportador->VL_EXP_BRUTO_ANUAL)}}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <h4>Ano do calendário fiscal:</h4>
                        {{$exportador->ClienteExportador->FinanceiroExportador->DT_ANO_FISCAL}}
                    </div>
                </div>
                 <div class="col-md-3">
                    <div class="form-group">
                        <h4>Data de Cadastro:</h4>
                        {{formatar_data_hora($exportador->DATA_CADASTRO ?? '-')}}
                    </div>
                </div>
            </div>

            <br />
            <div class="row">
                <div class="col-md-6">
                    <div class="bs-callout bs-callout-" id="callout-btn-group-tooltips">
                        <h4>Para visualizar outros dados do exportador.</h4>
                        <a class="btn btn-primary" data-toggle="collapse" href="#detalhes-exportador" role="button" aria-expanded="false"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Clique Aqui</a>
                        <br /><br />
                        <div class="collapse multi-collapse" id="detalhes-exportador">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <b>Tempo de existência da empresa:</b><br />
                                        <?php
                                        switch ($exportador->ID_TEMPO) {
                                            case '1':
                                                echo 'Até 3 anos';
                                                break;
                                            case '2':
                                                echo 'Acima de 3 anos';
                                                break;
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <b>Inscrição estadual:</b><br />
                                        {{$exportador->NU_INSCR_EST}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <b>Nome do responsável:</b><br />
                                        {{$exportador->Responsavel->NM_RESPONSAVEL}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <b>CPF do responsável:</b><br />
                                        {{$exportador->Responsavel->CPF_RESPONSAVEL}}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <b>E-mail do responsável:</b><br />
                                        {{$exportador->Responsavel->EMAIL_RESPONSAVEL}}
                                    </div>
                                </div>
                            </div>
                            <h4>Quadro societário:</h4><br />
                            <table class="table table-bordered table-striped">
                                <tr>
                                    <th width="40%">Nome</th>
                                    <th width="40%">CPF / CNPJ</th>
                                    <th width="20%">Participação</th>
                                </tr>
                                <tr>
                                    <td>{{$exportador->NOME_QUADRO}}</td>
                                    <td>{{cpf_cnpj($exportador->CPF_CNPJ_QUADRO)}}</td>
                                    <td>{{formatar_valor_sem_moeda($exportador->PARTICIPACAO_QUADRO)}} %</td>
                                </tr>
                                @if($exportador->QuadroSocietarioExportador)
                                    @foreach($exportador->QuadroSocietarioExportador as $socio)
                                        <tr>
                                            <td>{{$socio->NOME_SOCIO}}</td>
                                            <td>{{cpf_cnpj($socio->NU_CPF_CNPJ)}}</td>
                                            <td>{{formatar_valor_sem_moeda($socio->PC_PARTICIPACAO)}} %</td>
                                        </tr>
                                    @endforeach
                                @endif
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="bs-callout bs-callout-" id="callout-btn-group-tooltips">
                        <h4>Para visualizar o parecer de análise do exportador.</h4>
                        <a class="btn btn-primary" data-toggle="collapse" href="#detalhes-parecer" role="button" aria-expanded="false"> <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Clique Aqui</a>
                        <br /><br />
                        <div class="collapse multi-collapse" id="detalhes-parecer">
                            <h4>Modalidades enquadradas:</h4><br />
                            <table class="table table-bordered">
                                <thead>
                                <th>Nome da modalidade</th>
                                <th>Enquadrada</th>
                                </thead>
                                <tbody>
                                @php $id_modalidade = ''; @endphp
                                @foreach($exportador->ClienteExportador->ModalidadeFinanciamento as $clienteModalidadeFinanciamentos)
                                    @if($id_modalidade != $clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE)
                                        <tr>
                                            <td>{{$clienteModalidadeFinanciamentos->ModalidadeFinanciamento->Modalidade->NO_MODALIDADE}}</td>
                                            <td>
                                                @if($clienteModalidadeFinanciamentos->IN_REGISTRO_ATIVO=='S')
                                                    SIM
                                                @else
                                                    NÃO
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @php $id_modalidade = $clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE; @endphp
                                @endforeach
                                </tbody>
                            </table>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <b>Data da recomendação:</b><br />
                                        {{\Carbon\Carbon::parse($exportador->Recomendacao->DT_RECOMENDACAO_EXP)->format('d/m/Y H:i')}}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <b>Recomendação:</b><br />
                                        Liberar Cadastro
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <b>Parecer:</b><br />
                                        {!!$exportador->Recomendacao->DS_RECOMENDACAO_EXP!!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (in_array($importador->OperacaoCadastroExportador->modalidade->ID_MODALIDADE, [1,2]) )
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <h3 class="panel-title">Lista de aprovação de Credit Score - <b>EXPORTADOR</b> </h3>
                            </div>
                            <div class="panel-body">
                                <div class="col-md-12">
                                    <table class="table table-bordered table-striped">
                                        <thead>
                                        <tr>
                                            <th>Alçada</th>
                                            <th>Credit Score</th>
                                            <th>Parecer</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @forelse($creditScoreExportador as $credit)
                                            @php $credit = (object)$credit; @endphp
                                            <tr>
                                                <td>{{$credit->NO_ALCADA}}</td>
                                                <td>{{$credit->CREDIT_SCORE}}</td>
                                                <td>{!! html_entity_decode($credit->DS_PARECER) !!}</td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3">Sem registros</td>
                                            </tr>
                                        @endforelse
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>
