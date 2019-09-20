<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Filtrar Propostas</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Nº da Proposta</label>
                    <input type="text" maxlength="10" name="ID_MPME_PROPOSTA" id="ID_MPME_PROPOSTA" value="{{$request->ID_MPME_PROPOSTA}}" class="form-control somentenumero">
                </div>
            </div>

            <div class="col-md-2">
                <div class="form-group">
                    <label>Nº da operação</label>
                    <input type="text" maxlength="10" name="cod_unico_operacao" id="cod_unico_operacao" value="{{$request->cod_unico_operacao}}" class="form-control">
                </div>
            </div>

            {{--<div class="col-md-2">
                <div class="form-group">
                    <label>ID da operação</label>
                    <input type="text" maxlength="10" name="ID_OPER" id="ID_OPER" value="{{$request->ID_OPER}}" class="form-control somentenumero">
                </div>
            </div>--}}

            <div class="col-md-3">
                <div class="form-group">
                    <label>Status da Proposta</label>
                    <select class="form-control input-sm" name="ID_MPME_STATUS_PROPOSTA" id="ID_MPME_STATUS_PROPOSTA">
                        <option value="0">Selecione</option>
                        @foreach($status_proposta as $status)
                            <option value="{{$status->ID_MPME_STATUS_PROPOSTA}}" @if($status->ID_MPME_STATUS_PROPOSTA == $request->ID_MPME_STATUS_PROPOSTA) selected @endif>{{$status->NO_PROPOSTA}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Prazo para Aprovação SUSEP</label>
                    <select class="form-control input-sm" name="dias_restantes" id="dias_restantes">
                        <option value="0">Selecione</option>
                        <option value="5">Até 10 dias Restantes</option>
                        <option value="10">Até 5 dias Restantes</option>
                    </select>
                </div>
            </div>
            {{-- {!! menu_total_paginacao($request) !!} --}}
        </div>
        <div class="row">
            <button id="btnPesquisar" name="btnPesquisar" type="submit" class="btn btn-success pull-right" style="margin-right: 10px;">
                <i class="fa fa-filter"></i> Filtrar operação
            </button>
            <button id="btnReset" name="btnReset" type="reset" class="btn btn-default pull-right" style="margin-right: 10px;" onclick="window.location.href = ' {{Route('abgf.exportador.listarpropostas')}} '">
                <i class="fa fa-filter"></i> Limpar filtro
            </button>
        </div>
    </div>
</div>