<!-- Modal -->
<div class="modal fade " id="aterar-senha" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title" id="exampleModalLongTitle">Alterar senha:</h3>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="loading">
                <img src="{{asset('imagens/loading.gif')}}" alt="MPME" class="center-block"/>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Senha atual: <small>(mín: 6 max: 10 dígitos)</small></label>
                            <div>
                                <input type="password" name="no_senha_atual" id="no_senha_atual" class="form-control" min="6" maxlength="10"></input>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nova senha: <small>(mín: 6 max: 10 dígitos)</small> </label>
                            <div>
                                <input type="password" name="no_nova_senha" id="no_nova_senha" class="form-control"  min="6" maxlength="10"></input>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Repetir senha: <small>(mín: 6 max: 10 dígitos)</small></label>
                            <div>
                                <input type="password" name="no_repetir_senha" id="no_repetir_senha" class="form-control"  min="6" maxlength="10"></input>
                            </div>
                        </div>
                    </div>
                </div>
                <dov class="row">
                    <div class="alert alert-info" role="alert">
                        <strong>Atenção!</strong> A senha deve ter entre 6 a 10 dígitos.
                    </div>
                </dov>

            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                <button type="button" class="btn btn-primary btnAlterarSenha" >Salvar</button>
            </div>
        </div>
    </div>
</div>
