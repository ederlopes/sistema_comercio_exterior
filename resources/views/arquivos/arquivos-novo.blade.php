<input type="hidden" name="token"                id="token"                  value="{{$request->token}}" />
<input type="hidden" name="id_mpme_tipo_arquivo" id="id_mpme_tipo_arquivo"   value="{{$request->id_mpme_tipo_arquivo}}" />
<input type="hidden" name="id_oper"              id="id_oper"                value="{{$request->id_oper}}" />
<input type="hidden" name="id_flex"              id="id_flex"                value="{{$request->id_flex}}" />
<input type="hidden" name="texto"                id="texto"                  value="{{$request->texto}}" />
<input type="hidden" name="pasta"                id="pasta"                  value="{{$request->pasta}}" />
<input type="hidden" name="container"            id="container"              value="{{$request->container}}" />
<input type="hidden" name="index_arquivos"       id="index_arquivos"         value="{{$request->index_arquivos}}" />
<input type="hidden" name="extensoes"            id="extensoes"              value="{{$request->extensoes}}" />
<input type="hidden" name="in_ass_digital"       id="in_ass_digital"         value="{{$request->in_ass_digital}}" />
<input type="hidden" name="index_arquivos"       id="index_arquivos"         value="{{$request->index_arquivos}}" />

<div class="fieldset-group">
    <div class="progresso-upload progress" style="display:none;height: 20px;">
        <div class="progress-bar" style="width: 0%;">0%</div>
    </div>
    <label class="arquivo-upload" for="arquivo-upload">
        <span>
            <figure>
                <i class="fa fa-upload"></i>
            </figure>
            <h4>Selecionar arquivo</h4>
        </span>
        <input type="file" name="no_arquivo" id="arquivo-upload">
    </label>
</div>
<div class="msg-arquivo fieldset-group" style="display:none">
    <div class="alert alert-warning mb-0">
        <i class="icon glyphicon glyphicon-exclamation-sign pull-left"></i>
        <div class="menssagem"></div>
    </div>
</div>