@can('VIS_DADOS_OPERARACAO')
    <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="dados_questionario_operacao" data-toggle="modal" data-target="#visualizar-dados-operacao">Dados da Operação</a></li>
@endcan
@can('VIS_DADOS_OPERARACAO')
    <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="dados_questionario_operacao" data-toggle="modal" data-target="#visualizar-dados-proposta">Dados da Proposta</a></li>
@endcan
@if(in_array($proposta->ID_MPME_STATUS_PROPOSTA, [1]))
    <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="enviar_proposta">Enviar p/ ABGF</a></li>
    <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="excluir_proposta">Excluir</a></li>
@endif
@if(in_array($proposta->ID_MPME_STATUS_PROPOSTA, [1,2]))
    @can('PRECIFICACAO')
        <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="precificacao" data-toggle="modal" data-target="#nova-precificacao">Precificação</a></li>
    @endcan
@endif

@if(isset($proposta->mpme_preco_cobertura->PC_COB_TAXA_CARREGAMENTO) )

    @if(in_array($proposta->ID_MPME_STATUS_PROPOSTA, [14]) && in_array($proposta->MpmeClienteExportadorModaliadeFinancimanciamento->ModalidadeFinanciamento->ID_MODALIDADE, [2,3]))
        <li><a href="{{URL::to('embarque/novo')}}/{{$proposta->ID_OPER}}/{{$proposta->ID_MPME_PROPOSTA}}">Novo Embarque</a></li>
        <li><a href="{{URL::to('embarque')}}/{{$proposta->ID_OPER}}/{{$proposta->ID_MPME_PROPOSTA}}">Lista Embarque</a></li>
    @endif

    @if(is_object($proposta->mpme_arquivo_boleto) == true)
        <li><a href="javascript:void(0);"  data-noarquivo="{{$proposta->mpme_arquivo_boleto->NO_ARQUIVO}}"  data-idmpmearquivo="{{$proposta->mpme_arquivo_boleto->ID_MPME_ARQUIVO}}" data-toggle="modal" data-target="#visualizar-arquivo">Download boleto do prêmio</a></li>

        @if( is_object($proposta->mpme_arquivo_comprovante_boleto) == true)
            <li><a href="javascript:void(0);" data-noarquivo="{{$proposta->mpme_arquivo_comprovante_boleto->NO_ARQUIVO}}" data-idmpmearquivo="{{$proposta->mpme_arquivo_comprovante_boleto->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download comprovante prêmio</a></li>
        @else
          @can('UPLOAD_CP')
            <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" data-idmpmetipoarquivo="{{$class::ID_MPME_TIPO_ARQUIVO_COMP_BOLETO}}" data-extensoes="pdf" data-idflex="{{$proposta->ID_MPME_PROPOSTA}}" data-token="{{$token}}" data-limite="1" data-container="div#arquivos-comprovante" data-pasta="boleto" data-inassdigital="N" class="novo-arquivo" data-toggle="modal" data-target="#novo-arquivo">Upload comprovante prêmio</a></li>
          @endcan
        @endif
    @endif

    @if(is_object($proposta->mpme_arquivo_cg) == true)
        <li><a href="javascript:void(0);"  data-noarquivo="{{$proposta->mpme_arquivo_cg->NO_ARQUIVO}}"  data-idmpmearquivo="{{$proposta->mpme_arquivo_cg->ID_MPME_ARQUIVO}}" data-toggle="modal" data-target="#visualizar-arquivo">Download do Certificado de Garantia</a></li>

        @if( is_object($proposta->mpme_arquivo_cg_assinado) == true)
            <li><a href="javascript:void(0);" data-noarquivo="{{$proposta->mpme_arquivo_cg_assinado->NO_ARQUIVO}}" data-idmpmearquivo="{{$proposta->mpme_arquivo_cg_assinado->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download Certificado Assinado</a></li>
        @else
            <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" data-idmpmetipoarquivo="{{$class::ID_MPME_TIPO_ARQUIVO_CG_ASSINADO}}" data-extensoes="pdf" data-idflex="{{$proposta->ID_MPME_PROPOSTA}}" data-token="{{$token}}" data-limite="1" data-container="div#arquivos-cg" data-pasta="cg" data-inassdigital="N" class="novo-arquivo" data-toggle="modal" data-target="#novo-arquivo">Upload Certificado Assinado</a></li>
        @endif
    @endif

    @if(is_object($proposta->mpme_arquivo_apolice) == true)
        <li><a href="javascript:void(0);"  data-noarquivo="{{$proposta->mpme_arquivo_apolice->NO_ARQUIVO}}"  data-idmpmearquivo="{{$proposta->mpme_arquivo_apolice->ID_MPME_ARQUIVO}}" data-toggle="modal" data-target="#visualizar-arquivo">Download da Apolice</a></li>

        @if( is_object($proposta->mpme_arquivo_apolice_assinada) == true)
            <li><a href="javascript:void(0);" data-noarquivo="{{$proposta->mpme_arquivo_apolice_assinada->NO_ARQUIVO}}" data-idmpmearquivo="{{$proposta->mpme_arquivo_apolice_assinada->ID_MPME_ARQUIVO}}"  data-toggle="modal" data-target="#visualizar-arquivo">Download da Aplice Assinada</a></li>
        @else
            <li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" data-idmpmetipoarquivo="{{$class::ID_MPME_TIPO_ARQUIVO_APOLICE_ASSINADA}}" data-extensoes="pdf" data-idflex="{{$proposta->ID_MPME_PROPOSTA}}" data-token="{{$token}}" data-limite="1" data-container="div#arquivos-apolice" data-pasta="apolice" data-inassdigital="N" class="novo-arquivo" data-toggle="modal" data-target="#novo-arquivo">Upload Apolice Assinado</a></li>
        @endif
    @endif

@endif
<!--<li><a href="javascript:void(0);" data-idoper="{{$proposta->ID_OPER}}" data-idproposta="{{$proposta->ID_MPME_PROPOSTA}}" class="historico_proposta" data-toggle="modal" data-target="#historico_proposta">Histórico de aprovação</a></li>-->