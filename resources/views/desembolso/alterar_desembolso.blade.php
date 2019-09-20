<!--CONTEUDO DA PAGINA-->
<script src="{{ asset('js/funcoes.geral.js') }}"></script>
<form name="frmDesembolso" id="frmDesembolso" method="post">
<div class="">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">Cadastro de Desembolso</h3>
            <input type="hidden" value="{{$mpmeProposta->ID_OPER}}" id="id_oper" name="id_oper"/>
            <input type="hidden" value="{{$mpmeProposta->ID_MPME_PROPOSTA}}" id="id_mpme_proposta" name="id_mpme_proposta"/>
            <input type="hidden" value="{{$mpmeDesembolso[0]->ID_MPME_DESEMBOLSO}}" id="id_mpme_desembolso" name="id_mpme_desembolso"/>
            <input type="hidden" value="{{$mpmeProposta->NU_PRAZO_PRE}}" id="nu_prazo_pre" name="nu_prazo_pre"/>
        </div>
        <div class="panel-body">

            <div class="col-md-3">
                <div class="form-group">
                    <label>Data do Desembolso</label>
                    <div class="input-group date datetimepicker4">
                        <input type="text" id="dt_desembolso" class="form-control input-sm datetimepicker4" name="dt_desembolso" id="dt_desembolso" value="{{formatar_data($mpmeDesembolso[0]->DT_DESEMBOLSO)}}">
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Valor do Desembolso</label>
                    <input type="text" name="vl_desembolso" id="vl_desembolso" value="{{converte_float($mpmeDesembolso[0]->VL_DESEMBOLSO)}}" class="form-control money" readonly="readonly">
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Data do Vencimento</label>
                    <div class="input-group date datetimepicker4">
                        <input type="text" id="dt_vencimento" class="form-control input-sm datetimepicker4" name="dt_vencimento" value="{{formatar_data($mpmeDesembolso[0]->DT_VENCIMENTO)}}" readonly="readonly">
                        <span class="input-group-addon">
                           <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="form-group">
                    <label>Status</label>
                    <select name="id_mpme_status" id="id_mpme_status" class="form-control input-sm">
                            <option value="{{$mpmeStatus->ID_MPME_STATUS}}">{{$mpmeStatus->NO_STATUS}}</option>
                    </select>
                </div>
            </div>


        </div>
    </div>
</div>
</form>