@extends('layouts.layoutcadastro')

@section('content')
    @php
    $CADASTRO_MODALIDADES = explode(',',env('CADASTRO_MODALIDADES'));
    @endphp
    <div id="Carregando">
        <div class="lds-css ng-scope"><div style="width:100%;height:100%" class="lds-eclipse"><div></div></div></div>
    </div>
    <div class="page-container">
        <div class="page-content">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="glyphicon glyphicon-user"></i> Cadastro MPME
                </div>
                <div class="panel-body">
                    <div id="Cadastro">
                        <form action="{{URL::to('cadastrar')}}" name="frmCadastro" class="form-horizontal form-dados" method="POST" enctype="multipart/form-data">
                         {{ csrf_field() }}
                            <div class="navbar">
                                <div class="navbar-inner">
                                    <ul class="list-unstyled clearfix">
                                        <li class="col-md-3">
                                            <a href="#dadosacesso" class="clearfix" data-toggle="tab">
                                                <figure>1</figure>
                                                <span>Dados de acesso</span>
                                            </a>
                                        </li>
                                        <li class="col-md-3">
                                            <a href="#representante" class="clearfix" data-toggle="tab">
                                                <figure>2</figure>
                                                <span>Representante legal</span>
                                            </a>
                                        </li>
                                        <li class="col-md-3">
                                            <a href="#quadrosocietario" class="clearfix" data-toggle="tab">
                                                <figure>3</figure>
                                                <span>Quadro societário</span>
                                            </a>
                                        </li>
                                        <li class="col-md-3">
                                            <a href="#dadoscadastrais" class="clearfix" data-toggle="tab">
                                                <figure>4</figure>
                                                <span>Dados cadastrais</span>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div id="bar" class="progress">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                            </div>
                            <div class="tab-content">
                                <div class="tab-pane" id="dadosacesso">
                                    <div class="page-header">
                                        <h2>Dados de acesso <small>Informações necessárias para o acesso do exportador.</small></h2>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-md-3">CNPJ da empresa <span class="required">*</span></label>
                                            <div class="col-md-5">
                                                <input type="text" name="NU_CNPJ" id="NU_CNPJ" class="form-control CNPJ" title="Campo Obrigatorio" placeholder="Digite o CNPJ da empresa." required />
                                            </div>
                                        </div>
                                        <div class="mensagem row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-5">
                                                <div class="alert"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-md-3">Usuário <span class="required">*</span></label>
                                            <div class="col-md-5">
                                                <input type="text" name="LOGIN" id="LOGIN" class="form-control text-uppercase" title="Campo Obrigatorio" placeholder="Digite o usuário / login para acesso ao sistema." maxlength="15" minlength="3" required />
                                            </div>
                                        </div>
                                        <div class="mensagem row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-5">
                                                <div class="alert"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-md-3">Senha <span class="required">*</span></label>
                                            <div class="col-md-5">
                                                <input type="password" name="DS_SENHA" id="DS_SENHA" class="form-control" title="Campo Obrigatorio" placeholder="Digite a senha para acesso ao sistema." maxlength="10" required />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-md-3">Confirme a senha <span class="required">*</span></label>
                                            <div class="col-md-5">
                                                <input type="password" name="DS_SENHA_C" id="DS_SENHA_C" class="form-control" title="Campo Obrigatorio" placeholder="Confirme a senha digitada acima." maxlength="10" required />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="representante">
                                    <div class="page-header">
                                        <h2>Representante legal <small>Informações do representante legal da empresa.</small></h2>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-md-3">Nome <span class="required">*</span></label>
                                            <div class="col-md-5">
                                                <input type="text" name="NM_RESPONSAVEL" id="NM_RESPONSAVEL" class="form-control text-uppercase" title="Campo Obrigatorio" placeholder="Digite o nome do responsável." required />
                                            </div>
                                        </div>
                                        <div class="mensagem row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-5">
                                                <div class="alert"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-md-3">E-mail <span class="required">*</span></label>
                                            <div class="col-md-5">
                                                <input type="email" name="EMAIL_RESPONSAVEL" id="EMAIL_RESPONSAVEL" class="form-control" title="Campo Obrigatorio" placeholder="Digite o e-mail do responsável." required />
                                            </div>
                                        </div>
                                        <div class="mensagem row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-5">
                                                <div class="alert"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="row">
                                            <label class="control-label col-md-3">CPF <span class="required">*</span></label>
                                            <div class="col-md-5">
                                                <input type="text" name="CPF_RESPONSAVEL" id="CPF_RESPONSAVEL" class="form-control CPF" title="Campo Obrigatorio" placeholder="Digite o CPF do responsável." required />
                                            </div>
                                        </div>
                                        <div class="mensagem row">
                                            <div class="col-md-3"></div>
                                            <div class="col-md-5">
                                                <div class="alert"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="quadrosocietario">
                                    <div class="page-header clearfix">
                                        <div class="row">
                                            <h2 class="col-md-8">Quadro societário <small>Informações dos sócios da empresa.</small></h2>
                                            <div class="col-md-4 text-right">
                                                <a href="javascript:void(0);" class="btn btn-success adicionar-socio">ADICIONAR SÓCIO</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="divisao-form quadro-socio" id="quadro-socio-1">
                                        <h3>Sócio <span>1</span>:</h3>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Nome do sócio <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="NOME_QUADRO[]" class="form-control text-uppercase NOME_QUADRO" title="Campo Obrigatorio" placeholder="Digite o nome do responsável." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Tipo da pessoa <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <select name="TP_PESSOA_QUADRO[]" class="form-control selectpicker TP_PESSOA_QUADRO" title="Selecione">
                                                        <option value="F">Pessoa física</option>
                                                        <option value="J">Pessoa jurídica</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group quadro-socio-cpf">
                                            <div class="row">
                                                <label class="control-label col-md-3">CPF ou CNPJ <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="CPF_QUADRO[]" class="form-control CPF_QUADRO" title="Campo Obrigatorio" placeholder="Digite o CPF ou CNPJ do responsável." required disabled />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Participação (%) <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="PARTICIPACAO_QUADRO[]" class="form-control PARTICIPACAO_QUADRO PORCP" title="Campo Obrigatorio" placeholder="Digite a participação em (%)." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="dadoscadastrais">
                                    <div class="page-header clearfix">
                                        <h2>Dados cadastrais <small>Informações da empresa.</small></h2>
                                    </div>
                                    <div class="divisao-form">
                                        <h3>Dados da empresa:</h3>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Razão social <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="NM_USUARIO" id="NM_USUARIO" class="form-control text-uppercase" title="Campo Obrigatorio" placeholder="Digite a razão social da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Nome fantasia <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="NM_FANTASIA" id="NM_FANTASIA" class="form-control text-uppercase" title="Campo Obrigatorio" placeholder="Digite o nome fantasia da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Simples nacional? <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <select name="simples_nacional" id="simples_nacional" class="form-control selectpicker" title="Selecione">
                                                        <option value="1">SIM</option>
                                                        <option value="2">NÃO</option>
                                                    </select>
                                                    <span class="help-block small">
                                                        Caso seja enquadrada no simples nacional, selecione SIM
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group" id="tipo_simples" style="display:none">
                                            <div class="row">
                                                <label class="control-label col-md-3">Tipo enquadramento <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <select name="ENQUADRAMENTO_TRIBUTARIO" id="ENQUADRAMENTO_TRIBUTARIO" class="form-control selectpicker" title="Selecione">
                                                        <option value="1">Micro Empresa</option>
                                                        <option value="2">Pequena Empresa</option>
                                                    </select>
                                                    <span class="help-block small">
                                                        Selecione o tipo de enquadramento no simples nacional
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Inscrição estadual <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="NU_INSCR_ESTADUAL" id="NU_INSCR_ESTADUAL" class="form-control" title="Campo Obrigatorio" placeholder="Digite a inscrição estadual da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Inscrição municipal <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="NU_INSCR_MUNICIPAL" id="NU_INSCR_MUNICIPAL" class="form-control" title="Campo Obrigatorio" placeholder="Digite a inscrição estadual da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Capital social <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="CAPITAL_QUADRO" id="CAPITAL_QUADRO" class="form-control REAL" title="Campo Obrigatorio" placeholder="Digite o capital social da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Nº de funcionários <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="NU_FUNCIONARIO_EMPRESA" id="NU_FUNCIONARIO_EMPRESA" class="form-control NUM" title="Campo Obrigatorio" placeholder="Digite o número de funcionários da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>

                                        @foreach($perguntas as $pergunta)
                                            <div class="form-group">
                                                <div class="row">
                                                    <label class="control-label col-md-3">{{$pergunta->NO_PERGUNTA}}<span class="required" >*</span></label>
                                                    <div class="col-md-5">
                                                        @switch($pergunta->NO_TIPO_CAMPO)
                                                            @case('select')
                                                                <select class="form-control perguntas selectpicker" required title="Selecione" name="pergunta[{{$pergunta->ID_MPME_PERGUNTA}}][IDRESP]" id="pergunta_{{$pergunta->ID_MPME_PERGUNTA}}" data-id="{{$pergunta->ID_MPME_PERGUNTA}}">
                                                                    @foreach($pergunta->respostas as $pergunta_resposta)
                                                                        <option data-inoutraresposta="{{$pergunta_resposta->resposta->IN_OUTRA_RESPOSTA}}" value="{{$pergunta_resposta->ID_MPME_PERGUNTA_RESPOSTA}}">{{$pergunta_resposta->resposta->NO_RESPOSTA}}</option>
                                                                    @endforeach
                                                                </select>
                                                                @break
                                                        @endswitch
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group" id="resposta_outros_{{$pergunta->ID_MPME_PERGUNTA}}" style="display:none">
                                                <div class="row">
                                                    <label class="control-label col-md-3"></label>
                                                    <div class="col-md-5">
                                                        <input type="text" class="form-control outros" name="pergunta[{{$pergunta->ID_MPME_PERGUNTA}}][RESP]" id="resposta_'.$pergunta->ID_MPME_PERGUNTA.'" placeholder="{{$pergunta->NO_PERGUNTA}}">
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach


                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Tempo de existência <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <select name="ID_TEMPO" id="ID_TEMPO" class="form-control selectpicker" title="Selecione o tempo de existência da empresa">
                                                        <option value="1">Até 3 anos</option>
                                                        <option value="2">Acima de 3 anos</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="divisao-form">
                                        <h3>Endereço:</h3>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">CEP <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="DE_CEP" id="DE_CEP" class="form-control CEP" title="Campo Obrigatorio" placeholder="Digite o CEP da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Estado <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <select name="CD_UF" id="CD_UF" class="form-control selectpicker" title="Selecione">
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
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Cidade <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="DE_CIDADE" id="DE_CIDADE" class="form-control text-uppercase" title="Campo Obrigatorio" placeholder="Digite a cidade da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Endereço <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="DE_ENDER" id="DE_ENDER" class="form-control text-uppercase" title="Campo Obrigatorio" placeholder="Digite o capital social da empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="divisao-form">
                                        <h3>Contato:</h3>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Nome do contato <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="NM_CONTATO" id="NM_CONTATO" class="form-control text-uppercase" title="Campo Obrigatorio" placeholder="Digite o nome de um contato na empresa." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Cargo <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="DE_CARGO" id="DE_CARGO" class="form-control text-uppercase" title="Campo Obrigatorio" placeholder="Digite o cargo do contato." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Telefone <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="DE_TEL" id="DE_TEL" class="form-control TEL" title="Campo Obrigatorio" placeholder="Digite telefone do contato." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">E-mail <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="DE_EMAIL" id="DE_EMAIL" class="form-control" title="Campo Obrigatorio" placeholder="Digite o email do contato." required />
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="divisao-form">
                                        <h3>Dados financeiros / operacionais:</h3>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Modalidade / Tipo de financ. <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <select name="id_modalidade[]" id="id_modalidade" class="form-control selectpicker" title="Selecione" multiple>
                                                        @foreach ($modalidades as $modalidade)
                                                            <option value="{{$modalidade->ID_MODALIDADE}}#{{$modalidade->ID_FINANCIAMENTO}}#{{$modalidade->ID_MODALIDADE_FINANCIAMENTO}}" @if($modalidade->ID_FINANCIAMENTO == 4) selected @endif>{{$modalidade->NO_MODALIDADE_FINANCIAMENTO}}</option>
                                                        @endforeach
                                                    </select>
                                                    <span class="help-block small">
                                                        Selecione as modalidades que deseja exportar.
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Ano das informações <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <select name="calendario_fiscal" id="calendario_fiscal" class="form-control selectpicker" title="Selecione">
                                                        <option value="{{date('Y')}}">{{date('Y')}}</option>
                                                        <option value="{{ date('Y', strtotime('-1 year')) }}">{{ date('Y', strtotime('-1 year')) }}</option>
                                                        <option value="{{ date('Y', strtotime('-2 year')) }}">{{ date('Y', strtotime('-2 year')) }}</option>
                                                    </select>
                                                    <span class="help-block small">
                                                        Selecione o ano das informações a serem prestadas a seguir
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Faturamento bruto anual <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="FATURAMENTO_BRUTO_ANUAL" id="FATURAMENTO_BRUTO_ANUAL" class="form-control REAL" title="Campo Obrigatorio" placeholder="Digite o faturamento bruto anual da empresa." required />
                                                    <span class="help-block small">
                                                        Faturamento bruto do ano civil selecionado - R$
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Valor de exportação anual <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <input type="text" name="VL_BRUTO_ANUAL" id="VL_BRUTO_ANUAL" class="form-control DOLAR" title="Campo Obrigatorio" placeholder="Digite o valor de exportação anual da empresa." required />
                                                    <span class="help-block small">
                                                        Valor de exportação do ano civil selecionado - US$
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">DRE <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Selecionar DRE</span><span class="fileinput-exists"><span class="fileinput-filename"></span></span><input type="file" id="dre" class="form-control" name="dre" ></span>
                                                        <a href="#" class="fileinput-exists btn btn-danger" data-dismiss="fileinput" style="float: none">&times;</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Comprovante de exportações <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                    <div class="fileinput fileinput-new" data-provides="fileinput">
                                                        <span class="btn btn-default btn-file"><span class="fileinput-new">Selecionar comprovante de exportações</span><span class="fileinput-exists"><span class="fileinput-filename"></span></span><input type="file" id="comprovante_exportacao" class="form-control" name="comprovante_exportacao" ></span>
                                                        <a href="#" class="fileinput-exists btn btn-danger" data-dismiss="fileinput" style="float: none">&times;</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="mensagem row">
                                                <div class="col-md-3"></div>
                                                <div class="col-md-5">
                                                    <div class="alert"></div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>


                                    <div class="divisao-form" id="pre-embarque" style="display:none">
                                        <h3>Pré-embarque:</h3>

                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Instituição Financeira <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                <select name="ID_FINANCIADOR_PRE" id="id_financiador_pre" class="form-control financiador">
                                                    <option value=""></option>
                                                    @foreach ($financiadores as $financiadorPre)
                                                        <option value="{{$financiadorPre->ID_USUARIO}}">{{$financiadorPre->NM_USUARIO}}</option>
                                                    @endforeach
                                                </select>

                                                </div>
                                            </div>
                                        </div>


                                            <div class="form-group gecex">
                                                <div class="row">
                                                    <label class="control-label col-md-3">Gecex <span class="required">*</span></label>
                                                    <div class="col-md-5">
                                                        <select name="ID_GECEX_POS2" id="id_gecex_pos2" class="form-control SelectGecex">
                                                                    <option value=""></option>
                                                                    @foreach ($gecexs as $gecexPre)
                                                                        <option value="{{$gecexPre->ID_USUARIO_FK}}">{{$gecexPre->NO_GECEX}}</option>
                                                                    @endforeach
                                                        </select>
                                                   </div>
                                                </div>
                                            </div>

                                            <div class="OcultarDadosBanco">
                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Agência <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="NO_AGENCIA_PRE" id="no_agencia" required title="Campo Obrigatorio" x-moz-errormessage="Campo Obrigatorio"/>
                                                            <span class="help-block small">Agência do Banco</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">CEP <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="AG_CEP_PRE" id="cep_f" />
                                                            <span class="help-block small">CEP</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Endereço <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control maiusculo" name="AG_ENDERECO_PRE" id="ag_endereco" />
                                                            <span class="help-block small">Endereço do Banco</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Estado <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                                <select name="AG_ESTADO_PRE" id="ag_uf" class="form-control">
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
                                                </div>

                                                 <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Cidade <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                           <input type="text" class="form-control maiusculo" name="AG_CIDADE_PRE" id="ag_cidade"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">CNPJ <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                          <input type="text" class="form-control" name="AG_CNPJ_PRE" id="ag_cnpj" />
                                                                <span class="help-block small">CNPJ do Banco</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Inscr. Est. <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                         <input type="text" class="form-control" name="AG_INSCR_PRE" id="ag_inscr_est" />
                                                                <span class="help-block small">Inscr. Est.</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Contato <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                         <input type="text" class="form-control maiusculo" name="AG_CONTATO_PRE" id="contato_fin" />
                                                                <span class="help-block small">Contato</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Cargo <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                         <input type="text" class="form-control maiusculo" name="AG_CARGO_PRE" id="cargo_fin" />
                                                                <span class="help-block small">Cargo</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Telefone <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                         <input type="text" class="form-control" name="AG_TEL_PRE" id="telefone_f" />
                                                                <span class="help-block small">Telefone</span>

                                                        </div>
                                                    </div>
                                                </div>
                                                 <input type="hidden" class="form-control" name="AG_FAX_PRE" id="fax_f" value="0">

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">E-Mail <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                             <input type="text" class="form-control" name="AG_EMAIL_PRE" id="email_f" />
                                                                <span class="help-block small">E-Mail</span>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div><!-- oculta dados banco -->

                                    </div> <!-- fehca div divisao form -->


                                    <div class="divisao-form" id="pos-embarque" style="display:none">
                                        <h3>Pós-embarque:</h3>

                                        <div class="form-group">
                                            <div class="row">
                                                <label class="control-label col-md-3">Instituição Financeira <span class="required">*</span></label>
                                                <div class="col-md-5">
                                                <select name="ID_FINANCIADOR_POS" id="id_financiador2" class="form-control financiador">
                                                                    <option value=""></option>
                                                                    @foreach ($financiadores as $financiadorPos)
                                                                        <option value="{{$financiadorPos->ID_USUARIO}}">{{$financiadorPos->NM_USUARIO}}</option>
                                                                    @endforeach
                                                                </select>
                                                </div>
                                            </div>
                                        </div>


                                            <div class="form-group gecex">
                                                <div class="row">
                                                    <label class="control-label col-md-3">Gecex <span class="required">*</span></label>
                                                    <div class="col-md-5">
                                                        <select name="ID_GECEX_POS" id="id_gecex_pos" class="form-control SelectGecex">
                                                                    <option value=""></option>
                                                                    @foreach ($gecexs as $gecexPos)
                                                                        <option value="{{$gecexPos->ID_USUARIO_FK}}">{{$gecexPos->NO_GECEX}}</option>
                                                                    @endforeach

                                                                </select>
                                                   </div>
                                                </div>
                                            </div>

                                            <div class="OcultarDadosBanco">

                                                <div class="form-group repetir">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Repetir dados do Pré-embarque ? <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                           <div class="radio-list">
                                                                        <label>
                                                                            <input type="radio" name="RESP1" id="RESP1" value="0" data-title="RESP1"/> SIM
                                                                        </label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Agência <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="NO_AGENCIA_POS" id="no_agencia2"/>
                                                            <span class="help-block small">Agência do Banco</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">CEP <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                           <input type="text" class="form-control" name="AG_CEP_POS" id="cep_f2"/>
                                                            <span class="help-block small">CEP</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Endereço <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                            <input type="text" class="form-control" name="AG_ENDERECO_POS" id="ag_endereco2"/>
                                                            <span class="help-block small">Endereço do Banco</span>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Estado <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                                <select name="AG_ESTADO_POS" id="ag_uf2" class="form-control">
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
                                                </div>

                                                 <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Cidade <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                           <input type="text" class="form-control maiusculo" name="AG_CIDADE_POS" id="ag_cidade2"/>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">CNPJ <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                          <input type="text" class="form-control" name="AG_CNPJ_POS" id="ag_cnpj2"/>
                                                                <span class="help-block small">CNPJ do Banco</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Inscr. Est. <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                         <input type="text" class="form-control" name="AG_INSCR_POS" id="ag_inscr_est2"/>
                                                                <span class="help-block small">Inscr. Est.</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Contato <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                         <input type="text" class="form-control maiusculo" name="AG_CONTATO_POS" id="contato_fin2"/>
                                                                <span class="help-block small">Contato</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Cargo <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                         <input type="text" class="form-control maiusculo" name="AG_CARGO_POS" id="cargo_fin2"/>
                                                                <span class="help-block small">Cargo</span>

                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">Telefone <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                         <input type="text" class="form-control" name="AG_TEL_POS" id="telefone_f2"/>
                                                                <span class="help-block small">Telefone</span>

                                                        </div>
                                                    </div>
                                                </div>
                                                 <input type="hidden" class="form-control" name="AG_FAX_POS" id="fax_f2" value="0"/>

                                                <div class="form-group">
                                                    <div class="row">
                                                        <label class="control-label col-md-3">E-Mail <span class="required">*</span></label>
                                                        <div class="col-md-5">
                                                             <input type="text" class="form-control" name="AG_EMAIL_POS" id="email_f2"/>
                                                                <span class="help-block small">E-Mail</span>

                                                        </div>
                                                    </div>
                                                </div>

                                            </div><!-- oculta dados banco -->

                                    </div> <!-- fehca div divisao form -->


                                    <div class="divisao-form">

                                     <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="alert alert-warning single">
                                                    <div class="bite-checkbox">
                                                        <input name="CHECK_PROMETE_ENVIO" id="CHECK_PROMETE_ENVIO" type="checkbox" value="1">
                                                        <label for="CHECK_PROMETE_ENVIO">
                                                            Aceito que a ABGF poderá solicitar documentos adicionais para avaliação do cadastro após o envio deste formulário. Caso solicitado, concordo que enviarei o mais breve possível.
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="form-group">
                                            <div class="col-md-12">
                                                <div class="alert alert-info single">
                                                    <div class="bite-checkbox">
                                                        <input name="d_autorizar" id="d_autorizar" type="checkbox" value="1">
                                                        <label for="d_autorizar">
                                                            Aceito os <a href="#termos" data-toggle="modal"><b>termos e condições de uso (clique aqui para visualizar)</b></a>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <ul class="pager wizard">
                                    <li class="previous"><a href="#"><i class="fa fa-arrow-left" aria-hidden="true"></i> Voltar</a></li>
                                    <li class="next"><a href="#">Avançar <i class="fa fa-arrow-right" aria-hidden="true"></i></a></li>
                                    <li class="finish"><a href="javascript:;" class="finalizar_cadastro">Finalizar o cadastro</a></li>
                                </ul>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div id="termos" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">TERMOS E CONDIÇÕES DE USO - SISTEMA SCE/MPME</h3>
                </div>
                <div class="modal-body">
                    <p>O presente “Termos e Condições de Uso” (doravante, "<strong>Termos e Condições</strong>") tem como objetivo regulamentar a utilização do Sistema SCE/MPME (doravante, “<strong>Sistema</strong>”) pelos solicitantes de cobertura dos seguros (doravante, “<strong>Usuário</strong>”) oferecidos pela Agência Brasileira Gestora de Fundos Garantidores e Garantias S.A. (doravante, “<strong>ABGF</strong>”) no <em>site</em> <a href="http://www.abgf.gov.br" target="_blank">www.abgf.gov.br</a>.</p>

                    <h4 style="margin-top:30px;"><strong>1. Utilização do Sistema</strong></h4>
                    <p style="margin-left:20px;">1.1. O <strong>Usuário</strong> obriga-se a utilizar o <strong>Sistema</strong> apenas para os fins a que este se propõe, bem como a respeitar e observar este <strong>Termos e Condições</strong> e a legislação aplicável, sob pena de aplicação das sanções cabíveis.</p>
                    <p style="margin-left:20px;">1.2. O <strong>Usuário</strong> deve abster-se de qualquer ação que possa comprometer a segurança do <strong>Sistema</strong>, torná-lo inacessível ou causar danos ao seu conteúdo.</p>
                    <p style="margin-left:20px;">1.3.   Caso discorde de qualquer regra deste <strong>Termos e Condições</strong>, o <strong>Usuário</strong> deve abster-se de utilizar o <strong>Sistema</strong> de forma imediata.</p>

                    <h4 style="margin-top:30px;"><strong>2. Direitos autorais</strong></h4>
                    <p style="margin-left:20px;">2.1. Todos os gráficos, informações, imagens, logos e outros recursos disponíveis no <strong>Sistema</strong> são de propriedade exclusiva da <strong>ABGF</strong> e são protegidos por lei, salvo disposição diversa expressa. É vedada a utilização dos referidos recursos por pessoas, empresas ou outros agentes não autorizados. O uso não autorizado poderá gerar penalidades previstas em lei.</p>

                    <h4 style="margin-top:30px;"><strong>3. Responsabilidades</strong></h4>
                    <p style="margin-left:20px;">3.1. O <strong>Usuário</strong> é responsável por manter sob absoluta confidencialidade seu <em>login</em> e senha, bem como por todos os atos e eventos relacionados à sua utilização do <strong>Sistema</strong>. Qualquer uso não autorizado de <em>login</em> ou senha ou a ocorrência de qualquer fato que constitua ou decorra de falha de segurança deverão ser reportados imediatamente à <strong>ABGF</strong>.</p>
                    <p style="margin-left:20px;">3.2. O <strong>Usuário</strong> declara estar devidamente representado por pessoa com poderes para consentir com a divulgação de seus dados, nos termos de seus atos constitutivos, não havendo qualquer impedimento à aceitação deste <strong>Termos e Condições</strong>.</p>
                    <p style="margin-left:20px;">3.3. O <strong>Usuário</strong> concorda em fornecer informações verdadeiras, atuais e completas, sob pena de aplicação das sanções cabíveis. A omissão de informação e/ou apresentação de declaração falsa e/ou diversa daquela que deveria estar escrita poderá configurar, sem prejuízo de outros enquadramentos, crime previsto no art. 299 do Código Penal Brasileiro (Decreto-Lei nº 2.848, de 7 de dezembro de 1940).</p>

                    <h4 style="margin-top:30px;"><strong>4. Política de Privacidade</strong></h4>
                    <p style="margin-left:20px;">4.1. As informações prestadas pelo <strong>Usuário</strong> têm como principal finalidade prover à <strong>ABGF</strong> meios para a realização da análise de risco referente ao seguro pleiteado pelo <strong>Usuário</strong>. Tais informações serão incorporadas à base de dados da <strong>ABGF</strong> e poderão ser utilizadas, também, para fins estatísticos e gerenciais.</p>
                    <p style="margin-left:20px;">4.2. As referidas informações serão coletadas e mantidas de acordo com padrões rígidos de confidencialidade e segurança e não serão repassadas a terceiros, exceto por força de lei ou na forma dos itens 4.3 e 4.4, abaixo.</p>
                    <p style="margin-left:20px;">4.3. O <strong>Usuário</strong> autoriza a <strong>ABGF</strong> a divulgar as informações prestadas aos órgãos de controle do Governo Federal aos quais a <strong>ABGF</strong>, na qualidade de empresa pública, se submete, bem como à Superintendência de Seguros Privados - SUSEP, na figura de órgão responsável pelo controle e fiscalização dos mercados de seguro, sem que seja necessária qualquer espécie de solicitação específica por parte da <strong>ABGF</strong> nesse sentido.</p>
                    <p style="margin-left:20px;">4.4. As informações detidas pela <strong>ABGF</strong> estão sujeitas à Lei nº 12.527, de 18 de novembro de 2011 (Lei de Acesso à Informação). A restrição de acesso a estas informações só é possível mediante classificação de confidencialidade nos termos da referida lei. Portanto, a <strong>ABGF</strong> deverá ser informada pelo <strong>Usuário</strong> acerca de informações por este prestadas e consideradas como confidenciais, no momento do envio das referidas informações, com as devidas justificativas quanto a necessidade do sigilo, cabendo à <strong>ABGF</strong> avaliar a pertinência da confidencialidade pretendida.</p>
                    <p style="margin-left:20px;">4.5. As informações detidas pela <strong>ABGF</strong> estão sujeitas, ainda, à Circular da SUSEP nº 445/2012, de 2 de julho de 2012.</p>

                    <h4 style="margin-top:30px;"><strong>5. Termos de uso de terceiros</strong></h4>
                    <p style="margin-left:20px;">5.1. O <strong>Sistema</strong> poderá conter links de outras entidades, que poderão possuir seus próprios termos de uso.</p>
                    <p style="margin-left:20px;">5.2. O presente <strong>Termos e Condições</strong> refere-se exclusivamente ao <strong>Sistema</strong>. A <strong>ABGF</strong> não se responsabiliza pelos termos de uso de terceiros.</p>

                    <h4 style="margin-top:30px;"><strong>6. Disposições gerais</strong></h4>
                    <p style="margin-left:20px;">6.1. A <strong>ABGF</strong> poderá, a qualquer momento, suspender ou limitar o acesso ao <strong>Sistema</strong> para fins de modificação de conteúdo, atualizações ou quaisquer outras ações necessárias.</p>
                    <p style="margin-left:20px;">6.2. Este <strong>Termos e Condições</strong> vigerá por prazo indeterminado, a partir do aceite do <strong>Usuário</strong>, e está sujeito a alterações a qualquer tempo, mediante simples comunicação inserida no <strong>Sistema</strong> ou de mensagem para o e-mail indicado no cadastro do <strong>Usuário</strong>. Recomenda-se, portanto, a verificação periódica do texto vigente deste <strong>Termos e Condições</strong>.</p>
                    <p style="margin-left:20px;">6.3. Este <strong>Termos e Condições</strong> constitui o entendimento entre a <strong>ABGF</strong> e o <strong>Usuário</strong> sobre os temas nele regulados e prevalece sobre quaisquer outros entendimentos e compromissos.</p>

                    <h4 style="margin-top:30px;"><strong>7. Foro e direito aplicável</strong></h4>
                    <p style="margin-left:20px;">7.1. O direito aplicável ao presente <strong>Termos e Condições </strong>é o direito brasileiro.</p>
                    <p style="margin-left:20px;">7.2. Qualquer contestação ou litígio resultante da aplicação do presente <strong>Termos e Condições</strong> será submetido à Justiça Federal, Seção Judiciária do Distrito Federal, com exclusão de qualquer outro foro, por mais privilegiado que seja.</p>
                </div>
            </div>
        </div>
    </div>
    @include('flash-message')
@endsection
