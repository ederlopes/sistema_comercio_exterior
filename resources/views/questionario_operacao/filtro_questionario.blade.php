<div class="panel panel-default">
    <div class="panel-heading">
        <h3 class="panel-title">Filtrar operação</h3>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-2">
                <div class="form-group">
                    <label>Nº da Operação</label>
                    <input type="text" maxlength="10" name="cod_unico_operacao" id="cod_unico_operacao" value="{{$request->cod_unico_operacao}}" class="form-control">
                </div>
            </div>

            {{--    <div class="col-md-2">
                    <div class="form-group">
                        <label>ID da operação</label>
                        <input type="text" maxlength="10" name="id_oper" id="id_oper" value="{{$request->id_oper}}" class="form-control somentenumero">
                    </div>
                </div>--}}

            <div class="col-md-3">
                <div class="form-group">
                    <label>Status da operação</label>
                    <select class="form-control input-sm" name="st_oper" id="st_oper">
                        <option value="0">Selecione</option>
                        @foreach($rs_status_operacao as $status_operacao)
                            <option value="{{$status_operacao->ST_OPER}}">{{$status_operacao->NM_OPER}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <label>Modalidade da operação</label>
                    <select class="form-control input-sm" name="id_modalidade" id="id_modalidade">
                        <option value="0">Selecione</option>
                        @foreach($rs_modalidade as $modalidade)
                            <option value="{{$modalidade->ID_MODALIDADE}}">{{$modalidade->NO_MODALIDADE}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            {!! menu_total_paginacao($request) !!}
        </div>
        <div class="row">
            <button id="btnPesquisar" name="btnPesquisar" type="submit" class="btn btn-success pull-right" style="margin-right: 10px;">
                <i class="fa fa-filter"></i> Filtrar operação
            </button>
            <button id="btnReset" name="btnReset" type="reset" class="btn btn-default pull-right" style="margin-right: 10px;" onclick="window.location.href = ' {{Request::url()}} '">
                <i class="fa fa-filter"></i> Limpar filtro
            </button>
        </div>
    </div>
</div>