<?php

namespace App;

use App\ClienteExportadorModalidadeFinanciamento;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    const PERFIL_ADMINISTRADOR = 8;

    use HasApiTokens, Notifiable;

    protected $table = 'USUARIOS';
    protected $primaryKey = 'ID_USUARIO';
    public $timestamps = false;
    protected $guarded = array();
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'DE_EMAIL',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    // Socrescreve para pegar o e-mail de outra coluna
    public function getEmailAttribute($value)
    {
        return $this->DE_EMAIL; // nova coluna do e-mail
    }

    public function exportador()
    {
        return $this->hasOne(MpmeClienteExportador::class, 'ID_USUARIO');
    }

    /*public function perfil()
    {
    return $this->belongsTo(UsuarioPerfil::class, 'ID_USUARIO');
    }*/

    public function usuario_alcadas()
    {
        return $this->hasMany(MpmeUsuarioAlcada::class, 'ID_USUARIO',
            'ID_USUARIO');
    }

    public function usuario_alcada_principal()
    {
        return $this->hasOne(MpmeUsuarioAlcada::class, 'ID_USUARIO',
            'ID_USUARIO')->where('IN_PODE_SALVAR', '=', 'S');
    }

    public function RetornaDadosExportadorPorId($id)
    {

        $usuario = User::where('ID_USUARIO', '=', $id)->first();

        return $usuario;

    }

    public function RetornaValidacaoExportador($idUsuario, $nuCheck)
    {

        $ck = DB::table('MPME_VALIDA_EXPORTADOR')
            ->where('ID_USUARIO', '=', $idUsuario)
            ->where('NU_CHECK', '=', $nuCheck)
            ->first(['ID_USUARIO', 'NU_CHECK', 'VL_CHECK']);

        return $ck;

    }

    public function RetornaConfirmacaoDadosExportador($idUsuario)
    {

        $ConfirmDados = DB::table('MPME_CONFIRMA_DADOS_EXPORTADOR')
            ->where('ID_USUARIO_FK', '=', $idUsuario)
            ->where('NU_TELA', '=', 1)
            ->first(['IC_STATUS']);

        return $ConfirmDados;

    }

    public function findForPassport($username)
    {
        return $this->where('CD_LOGIN', $username)->first();
    }

    // Retorna os importadores/operações referente ao usuário em questão
    public function RetornaImportadoresEOperacoes()
    {
        return $this->hasMany('App\ImportadoresModel', 'ID_USUARIO', 'ID_USUARIO')->with('propostas');
    }

    public function RetornaUsuarioCGC()
    {
        return $this->hasMany('App\UsuarioCGCModel', 'ID_USUARIO_FK', 'ID_USUARIO');
    }

    public function RetornaUsuarioCGCVigencia()
    {
        return $this->hasMany('App\UsuarioCGCVigenciaModel', 'ID_USUARIO_FK', 'ID_USUARIO');
    }

    public function RetornaMoedaUsuario()
    {
        return $this->hasMany('App\MoedaModel', 'MOEDA_ID', 'ID_MOEDA');
    }

    public function RetornaBancoFinanciador()
    {
        return $this->hasMany('App\FinanciadorPosModel', 'ID_USUARIO', 'ID_USUARIO')
            ->leftJoin('MPME_GECEX_BB', 'MPME_FINANC.ID_USUARIO_FINANCIADOR_FK', '=', 'MPME_GECEX_BB.ID_USUARIO_FK')
            ->join('USUARIOS', 'USUARIOS.ID_USUARIO', '=', 'MPME_FINANC.ID_USUARIO_FINANCIADOR_FK');
    }

    public function RetornaModalidadeFinancimento($ID_USUARIO)
    {
        return
        ClienteExportadorModalidadeFinanciamento::join('MPME_CLIENTE_EXPORTADORES', 'MPME_CLIENTE_EXPORTADORES.ID_MPME_CLIENTE_EXPORTADORES', '=', 'CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO.ID_MPME_CLIENTE_EXPORTADORES')
            ->join('MODALIDADE_FINANCIAMENTO', 'MODALIDADE_FINANCIAMENTO.ID_MODALIDADE_FINANCIAMENTO', '=', 'CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO.ID_MODALIDADE_FINANCIAMENTO')
            ->join('MODALIDADE', 'MODALIDADE.ID_MODALIDADE', '=', 'MODALIDADE_FINANCIAMENTO.ID_MODALIDADE')
            ->where('ID_USUARIO', $ID_USUARIO)
            ->where('CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO.IN_REGISTRO_ATIVO', '=', 'S')
            ->get([
                'MODALIDADE.NO_MODALIDADE',
                'MODALIDADE_FINANCIAMENTO.ID_MODALIDADE',
                'MODALIDADE_FINANCIAMENTO.ID_MODALIDADE_FINANCIAMENTO',
                'MODALIDADE_FINANCIAMENTO.NO_MODALIDADE_FINANCIAMENTO',
                'CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO.ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO',
            ]);
    }

    public function retornaSimplesNacional()
    {
        $id_cliente_exportador = $this->exportador->ID_MPME_CLIENTE_EXPORTADORES;
        return
        ClienteExportadorRegimeTributario::where('ID_MPME_CLIENTE_EXPORTADORES', '=', $id_cliente_exportador)
            ->join('REGIME_TRIBUTARIO', 'REGIME_TRIBUTARIO.ID_REGIME_TRIBUTARIO', '=', 'CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO.ID_REGIME_TRIBUTARIO')
            ->first();
    }

    public function Banco()
    {
        return $this->hasOne('App\MpmeFinanc', 'ID_USUARIO', 'ID_USUARIO')
            ->with('Gecex');
    }

    public function ClienteExportador()
    {
        return $this->hasOne('App\MpmeClienteExportador', 'ID_USUARIO', 'ID_USUARIO')
            ->with('ModalidadeFinanciamento', 'FinanceiroExportador');
    }

    public function FinanciadorPre()
    {
        return $this->hasOne('App\Financpre', 'ID_USUARIO', 'ID_USUARIO')
            ->with('Gecex');
    }

    public function FinanciadorPos()
    {
        return $this->hasOne('App\Financpos', 'ID_USUARIO', 'ID_USUARIO')
            ->with('Gecex');
    }

    public function InfoAdicionalExportador()
    {
        return $this->hasOne('App\MpmeInfAdicionalExportador', 'ID_USUARIO_FK', 'ID_USUARIO');
    }

    public function QuadroSocietarioExportador()
    {
        return $this->hasMany(MpmeQuadroSocietario::class, 'ID_USUARIO', 'ID_USUARIO');
    }

    public function PermissoesConferenciaValidador()
    {
        return $this->hasOne('App\PermissoesAdmin', 'idAdministradores', 'ID_USUARIO');
    }

    public function listaTarefasAnalista()
    {
        return $this->hasMany('App\MpmeValidaExportador', 'ID_USUARIO', 'ID_USUARIO');
    }

    public function TipoPermissao()
    {

        $tipoPermissao = '';

        if ($this->PermissoesConferenciaValidador->ativConferente == 1 && $this->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin == 1) {
            $tipoPermissao = 'C';
        }

        if ($this->PermissoesConferenciaValidador->ativValidador == 2 && $this->PermissoesConferenciaValidador->tipoPermissaoAdmin_idtipoPermissaoAdmin == 1) {
            $tipoPermissao = 'V';
        }

        return $tipoPermissao;

    }

    public function Questionario()
    {
        return $this->hasMany('App\MpmeQuestionario', 'ID_USUARIO', 'ID_USUARIO');
    }

    public function perfil()
    {
        return $this->belongsTo(MpmePerfil::class, 'ID_PERFIL', 'ID_PERFIL');
    }

    public function hasPermission(MpmePermissoes $mpmePermissoes)
    {
        return $this->hasAnyRoles($mpmePermissoes->permisoes_perfil);
    }

    public function hasAnyRoles($roles)
    {
        foreach ($roles as $role) {
            if ($role->perfil->ID_PERFIL == $this->perfil->ID_PERFIL) {
                return true;
            }
        }
        return false;
    }

    public function isSuperAdmin()
    {
        if ($this->ID_PERFIL == $this::PERFIL_ADMINISTRADOR) {
            return true;
        }
        return false;
    }

    public function Responsavel()
    {
        return $this->hasOne(Mpme_Responsav_Assinatura_Cgc::class, 'ID_USUARIO_RESPONSAVEL', 'ID_USUARIO');
    }

    public function Recomendacao()
    {
        return $this->hasOne(MpmeRecomendacaoExp::class, 'ID_USUARIO_FK', 'ID_USUARIO');
    }

}
