<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Filtrar proposta</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Nº da Proposta</label>
                    <input type="text" maxlength="10" name="id_mpme_proposta" id="id_mpme_proposta" value="{{$request->id_mpme_proposta}}" class="form-control">
                </div>
            </div>
            <div class="col-md-2">
                <div class="form-group">
                    <label>Nº da Operação</label>
                    <input type="text" maxlength="10" name="cod_unico_operacao" id="cod_unico_operacao" value="{{$request->cod_unico_operacao}}" class="form-control">
                </div>
            </div>
            @if(\Auth::user()->ID_PERFIL != 9)
            <div class="col-md-2">
                <div class="form-group">
                    <label>ID da operação</label>
                    <input type="text" maxlength="10" name="id_oper" id="id_oper" value="{{$request->id_oper}}" class="form-control somentenumero">
                </div>
            </div>
            @endif
            <div class="col-md-3">
                <div class="form-group">
                    <label>Status da proposta</label>
                    <select class="form-control input-sm" name="id_mpme_status_proposta" id="id_mpme_status_proposta">
                        <option value="0">Selecione</option>
                        @foreach($rs_status_proposta as $status_proposta)
                            <option value="{{$status_proposta->ID_MPME_STATUS_PROPOSTA}}">{{$status_proposta->NO_PROPOSTA}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {!! menu_total_paginacao($request) !!}
        </div>
        <div class="row">
            <button id="btnPesquisar" name="btnPesquisar" type="submit" class="btn btn-success pull-right" style="margin-right: 10px;">
                <i class="fa fa-filter"></i> Filtrar proposta
            </button>
            <button id="btnReset" name="btnReset" type="reset" class="btn btn-default pull-right" style="margin-right: 10px;" onclick="window.location.href = ' {{Request::url()}} '">
                <i class="fa fa-filter"></i> Limpar filtro
            </button>
        </div>
    </div>
</div>