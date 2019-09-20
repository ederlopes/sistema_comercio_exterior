<div class="modal fade" id="visualizar-dados-operacao" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Dados da operação</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="loading">
                <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="visualizar-dados-proposta" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Dados da proposta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="loading">
                <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="historico_proposta" tabindex="-1" role="dialog">
    <input type="hidden" class="id_mpme_proposta" name="id_mpme_proposta" id="id_mpme_proposta">
    <input type="hidden" class="id_oper" name="id_oper" id="id_oper">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Histórico de aprovações</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="loading">
                <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
            </div>
            <div class="modal-body">

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade " id="excluir_proposta" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <input type="hidden" id="id_proposta_excluir" name="id_proposta_excluir" value="">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Excluir proposta</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="loading">
                <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
            </div>
            <div class="modal-body">
                <label>Motivo: </label>
                <textarea id="ds_motivo" class="form-control" name="ds_motivo" style="height: 150px; width: 800px;"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary btnExcluir" >Salvar</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade " id="dados-apolice" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">

    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLongTitle">Upload da apólice</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="loading">
                <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
            </div>
            <div class="modal-body">
                <form name="form-nova-apolice" id="form-nova-apolice" method="post" action=""   enctype="multipart/form-data">
                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <input type="hidden" class="id_mpme_proposta" name="id_mpme_proposta" id="id_mpme_proposta">
                    <input type="hidden" class="id_oper" name="id_oper" id="id_oper">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Número da apólice</label>
                                <input type="text" name="nu_apolice" id="nu_apolice" value="" class="form-control">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="fileinput fileinput-new " data-provides="fileinput">
                                <span class="btn btn-default btn-file">
                                  <span class="fileinput-new">Selecionar arquivo</span>
                                  <span class="fileinput-exists">
                                     <span class="fileinput-filename"></span>
                                  </span>
                                  <input type="file" data-extensoes="pdf" class="form-control arquivoslancamento" name="arquivo_apolice" id="arquivo_apolice"></span>
                                  <a href="#" class="fileinput-exists btn btn-danger" data-dismiss="fileinput" style="float: none">&times;</a>
                            </div>
                        </div>
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary btnSalvarApolice" id="btnSalvarApolice" name="btnSalvarApolice" >Salvar</button>
            </div>
        </div>
    </div>
</div>
