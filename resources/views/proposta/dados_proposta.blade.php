    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Dados de Propostas</h3>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Nº da Operação</label>
                        <input type="text" name="cod_unico_operacao" id="cod_unico_operacao" value="{{$proposta->COD_UNICO_OPERACAO}}" class="form-control"  readonly="readonly">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>ID da Operação</label>
                        <input type="text" name="id_oper" id="id_oper" value="{{$proposta->ID_OPER}}" class="form-control" readonly="readonly">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Tipo de financiamento</label>

                        <select class="form-control input-sm" name="id_cliente_exportadores_modalidade" id="id_cliente_exportadores_modalidade" readonly="readonly">
                            <option value="0">{{$proposta->MpmeClienteExportadorModaliadeFinancimanciamento->ModalidadeFinanciamento->NO_MODALIDADE_FINANCIAMENTO}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>% Down Payment</label>
                        <input type="text" name="va_percentual_dw_payment" id="va_percentual_dw_payment" value="{{$proposta->VL_PERC_DOWPAYMENT}}" class="form-control money" maxlength="5"  readonly="readonly">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Setor de atividades</label>
                        <select class="form-control input-sm" name="id_setor" id="id_setor" readonly="readonly">
                            <option value="0">{{$proposta->mpme_setor_atividade->NM_SETOR}}</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Valor da proposta</label>
                        <input type="text" name="vl_proposta" id="vl_proposta" value="{{formatar_valor_sem_moeda($proposta->VL_PROPOSTA)}}" class="form-control money"  readonly="readonly">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Número da proposta</label>
                        <input type="text" name="nu_proposta" id="nu_proposta" value="{{$proposta->NU_PROPOSTA}}" class="form-control"  readonly="readonly">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Data de envio</label>
                        <input type="text" name="dt_envio" id="dt_envio" value="{{formatar_data($proposta->DT_ENVIO)}}" class="form-control"  readonly="readonly">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Data da aprovação</label>
                        <input type="text" name="dt_aprovacao" id="dt_aprovacao" value="{{formatar_data($proposta->DT_APROVACAO)}}" class="form-control"  readonly="readonly">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Data de assinatura da apólice</label>
                        <input type="text" name="dt_assinatura_apolice" id="dt_assinatura_apolice" value="{{formatar_data($proposta->DT_ASSINATURA_APOLICE)}}" class="form-control"  readonly="readonly">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label>Número da apólice</label>
                        <input type="text" name="nu_apolice" id="nu_apolice" value="{{$proposta->NU_APOLICE}}" class="form-control"  readonly="readonly">
                    </div>
                </div>
                <div class="col-md-3">
                    <label>Aceite nos Títulos de Crédito</label>
                    <select class="form-control input-sm" name="in_aceite" id="in_aceite" readonly="readonly">
                        <option value="">{{$proposta->IN_ACEITE}}</option>
                    </select>
                </div>
            </div>
            <div class="row">
                @if(in_array($proposta->MpmeClienteExportadorModaliadeFinancimanciamento->ModalidadeFinanciamento->ID_MODALIDADE, [2,3]))
                <div class="col-md-2" id="prazo_dias_pos">
                    <div class="form-group">
                        <label>Prazo pós (dias)</label>
                        <input type="text" name="nu_prazo_pos" id="nu_prazo_pos" value="{{$proposta->NU_PRAZO_POS}}" class="form-control somentenumero prazo" readonly="readonly">
                    </div>
                </div>
                @endif
                @if(in_array($proposta->MpmeClienteExportadorModaliadeFinancimanciamento->ModalidadeFinanciamento->ID_MODALIDADE, [1,2]))
                <div class="col-md-2" id="prazo_dias_pre">
                    <div class="form-group">
                        <label>Prazo pré (dias)</label>
                        <input type="text" name="nu_prazo_pre" id="nu_prazo_pre" value="{{$proposta->NU_PRAZO_PRE}}" class="form-control somentenumero prazo"  readonly="readonly">
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
