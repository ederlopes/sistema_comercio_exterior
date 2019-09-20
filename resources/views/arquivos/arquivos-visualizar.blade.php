
@if(in_array($mpmeArquivo->NO_EXTENSAO,array('pdf','jpg','png','bmp','gif')))
    @if($mpmeArquivo->NO_EXTENSAO=='pdf')
        <object data="{{URL::to('/validar/visualizar-arquivo/render/'.$hash_arquivo)}}" type="application/pdf" class="resultado-pdf"  width="100%" height="100%">
            <p>Seu navegador não tem um plugin pra PDF</p>
        </object>
    @else
        <img src="{{URL::to('/validar/visualizar-arquivo/'.$hash_arquivo)}}" class="img-responsive" />
    @endif
@else
    <div class="alert alert-warning single">
        <i class="icon glyphicon glyphicon-exclamation-sign pull-left"></i>
        <div class="menssagem">
            O arquivo não pode ser visualizado, por favor, faça o download.
        </div>
    </div>
@endif