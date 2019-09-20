<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\ClienteExportadorModalidadeFinanciamento;

class MpmeProposta extends Model
{
    protected $table        = 'MPME_PROPOSTA';
    protected $primaryKey   = 'ID_MPME_PROPOSTA';
    public    $timestamps   = false;
    protected $guarded      = array();

    CONST ID_MPME_TIPO_ARQUIVO_BOLETO       = 7;
    CONST ID_MPME_TIPO_ARQUIVO_CG           = 10;
    CONST ID_MPME_TIPO_ARQUIVO_CG_ASSINADO  = 13;
    CONST ID_MPME_TIPO_ARQUIVO_COMP_BOLETO  = 9;
    CONST ID_MPME_TIPO_ARQUIVO_APOLICE      = 15;
    CONST ID_MPME_TIPO_ARQUIVO_APOLICE_ASS  = 16;

    public function mpme_proposta_aprovacao()
    {
        return $this->hasMany(MpmePropostaAprovacao::class, 'ID_MPME_PROPOSTA', 'ID_MPME_PROPOSTA');
    }

    public function mpme_status_proposta()
    {
        return $this->belongsTo(MpmeStatusProposta::class, 'ID_MPME_STATUS_PROPOSTA', 'ID_MPME_STATUS_PROPOSTA');
    }

    public function mpme_setor_atividade()
    {
        return $this->belongsTo(TbSetores::class, 'ID_SETOR', 'ID_SETOR');
    }

    public function mpme_preco_cobertura()
    {
        return $this->belongsTo(MpmePrecoCobertura::class, 'ID_MPME_PROPOSTA', 'ID_MPME_PROPOSTA');
    }

    public function mpme_arquivo_boleto()
    {
        return $this->belongsTo(MpmeArquivo::class, 'ID_MPME_PROPOSTA', 'ID_FLEX')
                    ->where("ID_MPME_TIPO_ARQUIVO", '=', $this::ID_MPME_TIPO_ARQUIVO_BOLETO);

    }

    public function mpme_arquivo_comprovante_boleto()
    {
        return $this->belongsTo(MpmeArquivo::class, 'ID_MPME_PROPOSTA', 'ID_FLEX')
                    ->where("ID_MPME_TIPO_ARQUIVO", '=', $this::ID_MPME_TIPO_ARQUIVO_COMP_BOLETO);
    }
    
    public function mpme_arquivo_cg()
    {
        return $this->belongsTo(MpmeArquivo::class, 'ID_MPME_PROPOSTA', 'ID_FLEX')
                    ->where("ID_MPME_TIPO_ARQUIVO", '=', $this::ID_MPME_TIPO_ARQUIVO_CG);
    }
    
    public function mpme_arquivo_cg_assinado()
    {
        return $this->belongsTo(MpmeArquivo::class, 'ID_MPME_PROPOSTA', 'ID_FLEX')
                    ->where("ID_MPME_TIPO_ARQUIVO", '=', $this::ID_MPME_TIPO_ARQUIVO_CG_ASSINADO);
    }
    
    public function mpme_arquivo_apolice()
    {
        return $this->belongsTo(MpmeArquivo::class, 'ID_MPME_PROPOSTA', 'ID_FLEX')
                    ->where("ID_MPME_TIPO_ARQUIVO", '=', $this::ID_MPME_TIPO_ARQUIVO_APOLICE);
    }
    
    public function mpme_arquivo_apolice_assinada()
    {
        return $this->belongsTo(MpmeArquivo::class, 'ID_MPME_PROPOSTA', 'ID_FLEX')
                    ->where("ID_MPME_TIPO_ARQUIVO", '=', $this::ID_MPME_TIPO_ARQUIVO_APOLICE_ASS);
    }

    public function operacoes()
    {
        return $this->belongsTo(ImportadoresModel::class, 'ID_OPER', 'ID_OPER');
    }
    
    public function MpmeClienteExportadorModaliadeFinancimanciamento()
    {
        return $this->hasOne('App\ClienteExportadorModalidadeFinanciamento',
                'ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO',
                'ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO')
                ->with('MpmeClienteExportador', 'ModalidadeFinanciamento');
    }


}
