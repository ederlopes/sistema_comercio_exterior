<?php

namespace App;

use App\MpmeClienteImportador;
use App\MpmeCreditoConcedido;
use App\MpmeCreditScore;
use App\MpmeCriterioOperacao;
use App\MpmeMovimentacaoControleCapital;
use App\MpmeRecomendacao;
use App\MpmeRespostaIndeferimento;
use App\Repositories\MpmeImportadoresRepository;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ImportadoresModel extends Model
{
    protected $table = 'MPME_IMPORTADORES';
    protected $primaryKey = 'ID_OPER';
    public $timestamps = false;
    protected $guarded = array();
    public $CreditScoreArray;
    public $RecomendacaoArray;

    const ID_MPME_TIPO_ARQUIVO_BOLETO_RELATORIO = 1;
    const ID_MPME_TIPO_ARQUIVO_BOLETO_RELATORIO_COMPROVANTE = 4;
    const ID_MODALIDADE_PRE = 1;

    // Retorna o usuario referente a operação selecionada
    public function usuario()
    {
        return $this->hasOne('App\User', 'ID_USUARIO', 'ID_USUARIO')->with('FinanciadorPos', 'FinanciadorPre');
    }

    public function RetornaFinanc()
    {
        return $this->hasOne('App\FinanciadorPosModel', 'ID_USUARIO', 'ID_USUARIO');
    }

    //Retorna o Controle de Exportação referente a operação
    public function RetornaControleExportacao()
    {
        return $this->hasOne('App\ControleExportacaoModel', 'ID_IMPORTADOR', 'ID_OPER');
    }

    //Retorna o Controle de Exportação tipo recurso proprio , referente a operação
    public function RetornaControleExportacaoDVE()
    {
        return $this->hasOne('App\ControleExportacaoDVEModel', 'ID_IMPORTADOR', 'ID_OPER');
    }

    public function RetornaCreditoConcedido()
    {
        return $this->hasOne('App\CreditoConcedidoModel', 'ID_OPER', 'ID_OPER');
    }

    public function RetornaModalidadeOperacao()
    {
        return $this->hasOne(ModalidadeModel::class, 'ID_MODALIDADE', 'ID_MODALIDADE');
    }

    public function RetornaCreditoConcedidoSain()
    {
        return $this->hasOne('App\CreditoConcedidoModel', 'ID_OPER', 'ID_OPER')
            ->where([
                ['FL_MOMENTO', '=', 'SAI'],
                ['VL_CRED_CONCEDIDO_BANCO', '<>', ''],
            ])
            ->orderBy('ID_CREDITO', 'DESC');
    }

    public function RetornaPaisImportadorOperacao()
    {
        return $this->hasOne('App\Pais', 'ID_PAIS', 'ID_PAIS');
    }

    public function RetornaGruPrecoCobertura()
    {
        return $this->hasOne('App\GruPrecoCoberturaModel', 'ID_OPER', 'ID_OPER');
    }

    public function RetornaMercadoria()
    {
        return $this->hasOne('App\MercadoriasModel', 'ID_OPER', 'ID_OPER');
    }

    public function RetornaMoeda()
    {
        return $this->belongsTo(MoedaModel::class, 'ID_MOEDA', 'MOEDA_ID');
    }

    public function RiscoPolitico()
    {
        return ($this->FaseOperacao() == "PÓS") ? 95 : 90;
    }

    public function operacao_cadastro_exportador()
    {
        return ($this->hasOne('App\OperacaoCadastroExportador', 'ID_OPER'));
    }

    public function ControleExportacao($dados)
    {

        switch ($dados) {
            case 'DT_ENVIO':
                return ($this->RetornaFinanc['IC_PROEX'] == 2) ? $this->RetornaControleExportacaoDVE['DT_ENVIO_DVE'] : $this->RetornaControleExportacao['DT_ENVIO'];
                break;

            case 'NU_FATURA':
                return ($this->RetornaFinanc['IC_PROEX'] == 2) ? $this->RetornaControleExportacaoDVE['NU_FATURA'] : $this->RetornaControleExportacao['NU_FATURA'];
                break;

            case 'NU_RE':
                return ($this->RetornaFinanc['IC_PROEX'] == 2) ? $this->RetornaControleExportacaoDVE['NRE'] : $this->RetornaControleExportacao['NU_RE'];
                break;

            case 'NU_RVS':
                return ($this->RetornaFinanc['IC_PROEX'] == 2) ? $this->RetornaControleExportacaoDVE['N_TIT'] : $this->RetornaControleExportacao['NU_RVS'];
                break;

            case 'DT_FATURA':
                return ($this->RetornaFinanc['IC_PROEX'] == 2) ? $this->RetornaControleExportacaoDVE['DT_FATURA'] : $this->RetornaControleExportacao['DT_FATURA'];
                break;

            case 'VL_CREDITO':
                if ($this->FaseOperacao() == 'PÓS') {

                    if ($this->RetornaFinanc['IC_PROEX'] == 0) {
                        return $this->RetornaControleExportacao['VL_ACE'];
                    } else if ($this->RetornaFinanc['IC_PROEX'] == 1) {
                        return $this->RetornaControleExportacao['VL_PROEX'];
                    } else if ($this->RetornaFinanc['IC_PROEX'] == 2) {
                        return $this->RetornaControleExportacaoDVE['VL_EMBARCADO'];
                    }
                } else if ($this->FaseOperacao() == 'PRÉ') {
                    return $this->RetornaCreditoConcedidoSain['VL_CRED_CONCEDIDO_BANCO'];
                }

                break;
        }
    }

    public function CalculaValorCoberto($RISCO)
    {

        $RC = 90;
        $RP = ($this->FaseOperacao() == "PÓS") ? 95 : 90;
        $VL_CREDITO = $this->ControleExportacao('VL_CREDITO');

        return number_format(($RISCO == 'RP') ? (($VL_CREDITO / 100) * $RP) : (($VL_CREDITO / 100) * $RC), 2, ',', '.');

    }

    public function RetornaStatusOperacao($dtVencimento, $ID_OPER)
    {

        $status = "-";

        //Caso a data do vencimento for maior que a data atual
        if (\Carbon\Carbon::parse($dtVencimento)->gt(\Carbon\Carbon::now())) {
            $status = "A VENCER";
        }
        //Caso a data de vencimento acrescentado 30 dias for maior menor que a data atual
        elseif (\Carbon\Carbon::parse($dtVencimento)->addDays(30)->lt(\Carbon\Carbon::now())) {
            $status = "VENCIDA";
        }

        $IdStatus = MpmeSinistro::where('ID_OPER', '=', $ID_OPER)->first();

        if ($IdStatus['ID_MPME_SINISTRO_STATUS'] != null) {
            $statusAtual = MpmeSinistroStatus::find($IdStatus['ID_MPME_SINISTRO_STATUS']);

            $status = $statusAtual->NO_MPME_SINISTRO_STATUS;
        }

        $tdPrevista = RecuperacaoSinistro::where('ID_MPME_SINISTRO', '=', $IdStatus['ID_MPME_SINISTRO'])->first();

        return $status;

    }

    public function VerificaConcretizada()
    {
        return ($this->RetornaGruPrecoCobertura['VL_CONCRETIZADO']) ? true : false;
    }

    public function FaseOperacao()
    {

        if ($this->usuario->ID_MODALIDADE == 3) {
            if (($this->VerificaConcretizada()) && (($this->ControleExportacao('DT_ENVIO')))) {
                return 'PÓS';
            } else {
                return false;
            }

        } else if ($this->usuario->ID_MODALIDADE == 2) {
            if ($this->VerificaConcretizada()) {
                if (($this->ControleExportacao('DT_ENVIO'))) {
                    return "PÓS";
                } else {
                    return "PRÉ";
                }

            } else {
                return false;
            }
        }
    }

    public function getQuestionarioOperacao($arrayWhere)
    {
        $rsImportadores =
        $this->join("OPERACAO_CADASTRO_EXPORTADOR", "OPERACAO_CADASTRO_EXPORTADOR.ID_OPER", "=", "MPME_IMPORTADORES.ID_OPER")
            ->join("MPME_MERCADORIAS", "MPME_MERCADORIAS.ID_OPER", "=", "MPME_IMPORTADORES.ID_OPER")
            ->join("MODALIDADE", "MODALIDADE.ID_MODALIDADE", "=", "OPERACAO_CADASTRO_EXPORTADOR.ID_MODALIDADE")
            ->join("FINANCIAMENTO", "FINANCIAMENTO.ID_FINANCIAMENTO", "=", "OPERACAO_CADASTRO_EXPORTADOR.ID_FINANCIAMENTO")
            ->join("MPME_CLIENTE_EXPORTADORES", "MPME_CLIENTE_EXPORTADORES.ID_MPME_CLIENTE_EXPORTADORES", "=", "OPERACAO_CADASTRO_EXPORTADOR.ID_MPME_CLIENTE_EXPORTADORES")
            ->join("PAISES", "PAISES.ID_PAIS", "=", "MPME_IMPORTADORES.ID_PAIS")
            ->join('PAISES_VAL', function ($join) {
                $join->on('PAISES.ID_PAIS', '=', 'PAISES_VAL.ID_PAIS')
                    ->whereNull('DT_FIM_VIG');
            })
            ->join("MOEDA", "MOEDA.MOEDA_ID", "=", "MPME_IMPORTADORES.ID_MOEDA")
            ->join("TB_SETORES", "TB_SETORES.ID_SETOR", "=", "MPME_IMPORTADORES.ID_SETOR")
            ->join("STATUSOPER", "STATUSOPER.ST_OPER", "=", "MPME_IMPORTADORES.ST_OPER")
            ->select(
                "MPME_IMPORTADORES.*",
                "MPME_IMPORTADORES.DATA_CADASTRO AS DATA_CADASTRO_OPERACAO",
                "OPERACAO_CADASTRO_EXPORTADOR.*",
                "MPME_MERCADORIAS.*",
                "MODALIDADE.*",
                "FINANCIAMENTO.*",
                "PAISES.*",
                "PAISES_VAL.*",
                "MOEDA.*",
                "TB_SETORES.*",
                "STATUSOPER.*"
            )
            ->selectRaw("(select TOP 1 DT_CADASTRO from MPME_HIST_QUESTIONARIO WHERE ID_OPER = OPERACAO_CADASTRO_EXPORTADOR.ID_OPER AND ST_OPER = 17) AS DT_ENVIO_ABGF");

        if (array_key_exists('ID_USUARIO', $arrayWhere) && !is_null($arrayWhere['ID_USUARIO'])) {
            $rsImportadores->where('MPME_CLIENTE_EXPORTADORES.ID_USUARIO', $arrayWhere['ID_USUARIO']);
        }

        if (array_key_exists('ID_OPER', $arrayWhere) && !is_null($arrayWhere['ID_OPER'])) {
            $rsImportadores->where('MPME_IMPORTADORES.ID_OPER', $arrayWhere['ID_OPER']);
        }

        if (array_key_exists('COD_UNICO_OPERACAO', $arrayWhere) && !is_null($arrayWhere['COD_UNICO_OPERACAO'])) {
            $rsImportadores->where('OPERACAO_CADASTRO_EXPORTADOR.COD_UNICO_OPERACAO', 'like', '%' . $arrayWhere['COD_UNICO_OPERACAO'] . '%');
        }

        if (array_key_exists('ID_MODALIDADE', $arrayWhere) && $arrayWhere['ID_MODALIDADE'] > 0) {
            $rsImportadores->where('MODALIDADE.ID_MODALIDADE', $arrayWhere['ID_MODALIDADE']);
        }

        if (array_key_exists('ST_OPER', $arrayWhere) && $arrayWhere['ST_OPER'] > 0) {
            $rsImportadores->whereIn('MPME_IMPORTADORES.ST_OPER', $arrayWhere['ST_OPER']);
        }

        if (array_key_exists('NOT_ST_OPER', $arrayWhere) && $arrayWhere['NOT_ST_OPER'] > 0) {
            $rsImportadores->whereNotIn('MPME_IMPORTADORES.ST_OPER', $arrayWhere['NOT_ST_OPER']);
        }

        return $rsImportadores;
    }

    public function OperacaoCadastroExportador()
    {
        return $this->hasOne('App\OperacaoCadastroExportador', 'ID_OPER', 'ID_OPER');
    }

    public function MpmeQuestionario()
    {
        return $this->hasMany('App\MpmeQuestionario', 'ID_OPER', 'ID_OPER');
    }

    public function setoresOperacao()
    {
        return $this->hasMany(MpmeSetoresOperacao::class, 'ID_OPER', 'ID_OPER');
    }

    public function StatusOper()
    {
        return $this->hasOne('App\StatusOper', 'ST_OPER', 'ST_OPER');
    }

    public function gravarQuestionarioOperacao($arrayDados)
    {

        $ID_MPME_CALCULADORA = MpmeCalculadora::getCalculadoraVigente();
        $id_oper = (isset($arrayDados['ID_OPER'])) ? $arrayDados['ID_OPER'] : null;

        if ($id_oper > 0) {
            $importadores = $this->where("ID_OPER", '=', $id_oper)->first();
            if (!isset($importadores)) {
                $importadores = new ImportadoresModel();
            }
        } else {
            $importadores = $this;
        }

        $arrayModalidadeFinanciamento = explode("#", $arrayDados['ID_CLIENTE_EXPORTADORES_MODALIDADE']);
        $id_cliente_exportadores_modalidade_financiamento = $arrayModalidadeFinanciamento[0];
        $id_modalideade_exportador = $arrayModalidadeFinanciamento[1];
        $id_financiamento_exportador = $arrayModalidadeFinanciamento[2];

        $VL_PROPOSTA = converte_float($arrayDados['VL_PROPOSTA']);

        DB::beginTransaction();

        $importadores->ID_USUARIO = $arrayDados['ID_USUARIO'];
        $importadores->RAZAO_SOCIAL = $arrayDados['RAZAO_SOCIAL'];
        $importadores->NAT_JURIDICA = $arrayDados['NAT_JURIDICA'];
        $importadores->NAT_RISCO = $arrayDados['NAT_RISCO'];
        $importadores->CNPJ = $arrayDados['CNPJ'];
        $importadores->ENDERECO = $arrayDados['ENDERECO'];
        $importadores->CIDADE = $arrayDados['CIDADE'];
        $importadores->CEP = $arrayDados['CEP'];
        $importadores->ID_PAIS = $arrayDados['ID_PAIS'];
        $importadores->CONTATO = $arrayDados['CONTATO'];
        $importadores->TELEFONE = $arrayDados['TELEFONE'];
        $importadores->FAX = $arrayDados['FAX'];
        $importadores->E_MAIL = $arrayDados['E_MAIL'];
        $importadores->ID_SETOR = $arrayDados['ID_SETOR'];
        $importadores->ID_MODALIDADE = $id_modalideade_exportador;
        $importadores->ID_MOEDA = $arrayDados['ID_MOEDA'];
        $importadores->CODIGO_UNICO_IMPORTADOR = $arrayDados['CODIGO_UNICO_IMPORTADOR'];
        $importadores->F_ATIVO = $arrayDados['FL_ATIVO'];
        $importadores->FL_MOMENTO = $arrayDados['FL_MOMENTO'];
        $importadores->ST_OPER = $arrayDados['ST_OPER'];
        $importadores->IC_VALIDADO = $arrayDados['IC_VALIDADO'];
        $importadores->ID_TEMPO = 0;
        $importadores->IC_ENVIADO = $arrayDados['IC_ENVIADO'];
        $importadores->CHECK_ENVIO = $arrayDados['CHECK_ENVIO'];
        $importadores->DATA_CADASTRO = $arrayDados['DATA_CADASTRO'];
        $importadores->ID_MPME_CALCULADORA = $ID_MPME_CALCULADORA;
        $importadores->ID_QUALIDADE_PRODUTO = $arrayDados['ID_QUALIDADE_PRODUTO'];
        $importadores->TP_COBERTURA_INDENIZACAO = $arrayDados['TP_COBERTURA_INDENIZACAO'];

        $importadores->VL_APROVADO = $VL_PROPOSTA;

        if (!$importadores->save()) {
            DB::rollback();
            return false;
        }

        //ALIMENTANDO A VARIAVEL ID_OPER CASO ELA NAO EXISTA
        $id_oper = $importadores->ID_OPER;

        // Caso exista codigo importador unico e id do mpme cliente, salva

        if ($id_modalideade_exportador == $this::ID_MODALIDADE_PRE) {
            //Instancia cliente importador
            $mpmeClienteImportador = new MpmeClienteImportador();
            $mpmeClienteImportador->ID_MPME_CLIENTE = $arrayDados['id_cliente_mpme'];
            $mpmeClienteImportador->ID_OPER = $id_oper;
            $mpmeClienteImportador->DATA_CADASTRO = Carbon::now();
            $mpmeClienteImportador->CODIGO_UNICO_IMPORTADOR = 0;
            $mpmeClienteImportador->save();

            if (!$mpmeClienteImportador) //se não salvar exibe a msg de erro
            {
                return response()->json(array(
                    'status' => 'erro',
                    'recarrega' => 'false',
                    'msg' => 'Por favor, tente novamente mais tarde. Erro ao salvar importador unico!',
                ));
            }
        } else {
            if ($arrayDados['id_cliente_mpme'] != 0 && $arrayDados['codigo_unico_importador'] != 0) {
                //Instancia cliente importador
                $mpmeClienteImportador = new MpmeClienteImportador();
                $mpmeClienteImportador->ID_MPME_CLIENTE = $arrayDados['id_cliente_mpme'];
                $mpmeClienteImportador->ID_OPER = $id_oper;
                $mpmeClienteImportador->DATA_CADASTRO = Carbon::now();
                $mpmeClienteImportador->CODIGO_UNICO_IMPORTADOR = $arrayDados['codigo_unico_importador'];
                $mpmeClienteImportador->save();

                if (!$mpmeClienteImportador) //se não salvar exibe a msg de erro
                {
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'false',
                        'msg' => 'Por favor, tente novamente mais tarde. Erro ao salvar importador unico!',
                    ));
                }
            }
        }

        /*
         * INSERINDO CADASTRO DE FORNECEDOR
         */

        $dadosRegimeTributarioFornecedor = ClienteExportadoresRegimeTributario::getRegimeTributarioFornecedor();

        if (count($dadosRegimeTributarioFornecedor) <= 0) {
            DB::rollback();
            return false;
        }

        $id_cliente_exportadores_regime_tributario = $dadosRegimeTributarioFornecedor[0]->ID_CLI_EXP_REG_TRIB;
        $id_regime_tributario = $dadosRegimeTributarioFornecedor[0]->ID_REGIME_TRIBUTARIO;
        $id_enquadramento_tributario = $dadosRegimeTributarioFornecedor[0]->ID_ENQUADRAMENTO_TRIBUTARIO;

        $dadosCadFornecedor = [
            'ID_OPER' => $id_oper,
            'ID_MPME_CLIENTE_EXPORTADORES' => Auth::user()->exportador->ID_MPME_CLIENTE_EXPORTADORES,
            'ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO' => $id_cliente_exportadores_modalidade_financiamento,
            'ID_CLIENTE_EXPORTADORES_REGIME_TRIBUTARIO' => $id_cliente_exportadores_regime_tributario,
            'ID_REGIME_TRIBUTARIO' => $id_regime_tributario,
            'ID_MODALIDADE' => $id_modalideade_exportador,
            'ID_FINANCIAMENTO' => $id_financiamento_exportador,
            'ID_ENQUADRAMENTO_TRIBUTARIO' => $id_enquadramento_tributario,
            'ID_PAIS' => $arrayDados['ID_PAIS'],
            'MOEDA_ID' => $arrayDados['ID_MOEDA'],
            'ID_USUARIO_CAD' => Auth::user()->ID_USUARIO,
            'DATA_CADASTRO' => Carbon::now(),
            'IN_ACEITE_RESTRICOES' => $arrayDados['IN_ACEITE_RESTRICOES'],
        ];

        $cadastro_operacao_cadastro_exportador = new OperacaoCadastroExportador();

        if (!$cadastro_operacao_cadastro_exportador->gravarOperacaoCadastroExportador($dadosCadFornecedor)) {
            DB::rollback();
            return false;
        }

        /*
         * INSERINDO QUESTIONARIO DE PERGUNTAS
         */

        $questionario_selecionado = new MpmeQuestionario();
        $questionario_selecionado = $questionario_selecionado->where("ID_OPER", '=', $id_oper)->first();

        if ($questionario_selecionado == null) {
            $perguntas = $arrayDados['PERGUNTA'];

            foreach ($perguntas as $pe) {

                $dadosQuestionario = [
                    'ID_OPER' => $id_oper,
                    'ID_MPME_PERGUNTA_RESPOSTA' => $pe['IDRESP'],
                    'ID_MPME_CLIENTE' => Auth::user()->exportador->ID_MPME_CLIENTE,
                    'IN_QUESTIONARIO_APLICADO' => 'CAD_OPERACAO',
                    'DS_OUTRA_RESPOSTA' => (isset($pe['RESP'])) ? isset($pe['RESP']) : '',
                    'IN_ATIVO' => 'S',
                    'ID_USUARIO' => Auth::user()->ID_USUARIO,
                    'DATA_CADASTRO' => Carbon::now(),
                ];

                $questionarioPergunta = new MpmeQuestionario();

                if (!$questionarioPergunta->gravarQuestionario($dadosQuestionario)) {
                    DB::rollback();
                    return false;
                }

                //limpando array
                $dadosQuestionario = [];
            }
        }

        /*
         * INSERIR CONTRATO COMERCIAL
         */

        $contrato_comercial = new MpmeContratoComercial();

        $dadosContratoComercial = [
            'ID_OPER' => $id_oper,
            'N_EMBARQUES_ANO' => 0,
            'PERIODICIDADE_EMB' => 0,
            'VL_EXP_ANUAL' => $VL_PROPOSTA,
            'PRAZO_OPER_PRE' => 0,
            'PRAZO_OPER_POS' => 0,
            'CONTRATO_EXPORTACAO' => 0,
            'TX_JUROS' => 2,
            'SPREAD' => 2,
        ];

        if (!$contrato_comercial->gravarContratoComercial($dadosContratoComercial)) {
            DB::rollback();
            return false;
        }

        /*
         * INSERIR MERCADORIA DEFAULT NO SISTEMA
         */

        $mercadoria = new MercadoriasModel();

        $dadosMercadoria = [
            'ID_OPER' => $id_oper,
            'NCM' => 0,
            'NM_MERCADORIA' => 0,
            'PC_ANTECIPADO' => 0,
            'VL_TOTAL' => $VL_PROPOSTA,
            'PZ_PAGTO' => 0,
            'PRAZO' => 2,
            'TIPO_VALIDACAO' => 'PI',
            'NU_DOCUMENTO' => 9999999,
            'DS_DOCUMENTO' => 'PRIMEIRA_INSERCAO',
            'DT_CADASTRO' => Carbon::now(),
            'DIVERGENCIA' => 2,
            'OCULTO' => 1,
            'DATA_CADASTRO' => Carbon::now(),
            'DATA_ULTIMA_ALTERACAO' => Carbon::now(),
            'ACEITE_IMPORTADOR' => 'N',
        ];

        if (!$mercadoria->gravarMercadoria($dadosMercadoria)) {
            DB::rollback();
            return false;
        }

        /*
         * INSERIR MPME_APROVACAO_VALOR_ALCADA
         */

        $aprovar_alcada = new MpmeAprovacaoValorAlcada();

        $dadosAprovarAlcada = [
            'ID_OPER' => $id_oper,
            'ID_MPME_ALCADA' => 1,
            'IN_DECISAO' => 1,
            'PC_ANTECIPADO' => 0,
            'VL_APROVADO' => $VL_PROPOSTA,
            'DT_CADASTRO' => Carbon::now(),
            'ID_USUARIO_CAD' => Auth::user()->ID_USUARIO,
        ];

        if (!$aprovar_alcada->gravarAprovacaoAlcada($dadosAprovarAlcada)) {
            DB::rollback();
            return false;
        }

        /*
         * INSERIR SETORES DE ATIVIDADES
         */

        //checando se ja existe dados lançados, caso sim exclui
        MpmeSetoresOperacao::where('ID_OPER', $id_oper)->delete();

        $arraySetores = $arrayDados['ARRAY_SETORES_ATIVIDADES'];
        foreach ($arraySetores as $id_setor_atividade) {
            $mpme_setor_operacao = new MpmeSetoresOperacao();
            $mpme_setor_operacao->ID_OPER = $id_oper;
            $mpme_setor_operacao->ID_SETOR = $id_setor_atividade;
            $mpme_setor_operacao->DT_CADASTRO = Carbon::now();
            $mpme_setor_operacao->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

            if (!$mpme_setor_operacao->save()) {
                DB::rollback();
                return false;
            };
        }

        /*
         * INSERIR LOG DE MOVIMENTACAO DO QUESTIONÁRIO
         */

        $mpme_movimentacao_questionario = new MpmeImportadoresRepository();

        $ds_observacao = ($id_oper == null) ? 'REGISTRO GRAVADO COM SUCESSO' : 'REGISTRO ALTERADO COM SUCESSO';

        $dados = [
            'ID_OPER' => $id_oper,
            'ST_OPER' => $arrayDados['ST_OPER'],
            'DS_OBSERVACAO' => $ds_observacao,
        ];

        if (!$mpme_movimentacao_questionario->registarLogMovimentacaoQuestionario($dados)) {
            DB::rollback();
            return false;
        };

        DB::commit();
        return true;

    }

    public function menuImportadores()
    {
        /* $ID_MPME_CLIENTE     =  Auth::user()->exportador->ID_MPME_CLIENTE;
        $retornoDados       = buscarUltimasOperacoesExportadorGruPaga($ID_MPME_CLIENTE);

        foreach ($retornoDados as $paises)
        {

        $arrayPais[$paises->CD_SIGLA] = strtoupper("'".$paises->CD_SIGLA."'");
        }

        $dadosTratratos = [
        "numero_importadores" => count($retornoDados),
        "paises"              => $arrayPais
        ];

        //dd($dadosTratratos);*/

        $dadosTratratos = [
            "numero_importadores" => 5,
            "paises" => [],
        ];

    }

    public function retornaCreditScore()
    {
        return $this->hasMany(MpmeCreditScore::class, 'ID_OPER', 'ID_OPER')->orderBy('ID_MPME_ALCADA')->get();
    }

    public function creditScoreImportador()
    {
        return $this->hasMany(MpmeCreditScore::class, 'ID_OPER', 'ID_OPER')
            ->with('Alcada', 'RecomendacaoAlcada', 'Arquivo')
            ->orderBy('ID_MPME_ALCADA');
    }

    public function CreditScoreExportador()
    {
        return $this->hasMany(MpmeCreditScoreExportadores::class, 'ID_OPER', 'ID_OPER')
            ->with('Alcada', 'RecomendacaoAlcada', 'Arquivo')
            ->orderBy('ID_MPME_ALCADA');
    }

//
    //    public function retornaUmCreditScore($idAlcada){
    //        return $this->hasOne(MpmeCreditScore::class, 'ID_OPER', 'ID_OPER')->where('MPME_CREDIT_SCORE.ID_MPME_ALCADA','=',$idAlcada)->orderBy('ID_MPME_ALCADA')->first();
    //    }

    public function MpmeCreditScore($ID_OPER)
    {
        $rsCreditScore = MpmeCreditScore::where('ID_OPER', '=', $ID_OPER)
            ->join('MPME_ALCADA', 'MPME_ALCADA.ID_MPME_ALCADA', '=', 'MPME_CREDIT_SCORE.ID_MPME_ALCADA')
            ->orderBy('MPME_CREDIT_SCORE.ID_MPME_ALCADA')->get();
        $arrayDadosCreditScore = [];

        foreach ($rsCreditScore as $credScpre) {
            $arrayDadosCreditScore[$credScpre->ID_MPME_ALCADA] = [
                'VL_AVAL1' => $credScpre->VL_AVAL1,
                'VL_AVAL3' => $credScpre->VL_AVAL3,
                'VL_AVAL4' => $credScpre->VL_AVAL4,
                'VL_AVAL5' => $credScpre->VL_AVAL5,
                'VL_AVAL6' => $credScpre->VL_AVAL6,
                'VL_AVAL7' => $credScpre->VL_AVAL7,
                'VL_AVAL8' => $credScpre->VL_AVAL8,
                'VL_AVAL9' => $credScpre->VL_AVAL9,
                'VL_AVAL10' => $credScpre->VL_AVAL10,
                'VL_AVAL11' => $credScpre->VL_AVAL11,
                'VL_AVAL12' => $credScpre->VL_AVAL12,
                'VL_AVAL13' => $credScpre->VL_AVAL13,
                'VL_AVAL14' => $credScpre->VL_AVAL14,
                'ID_CREDIT_SCORE' => $credScpre->ID_CREDIT_SCORE,
                'ID_CREDITO' => $credScpre->ID_CREDITO,
                'CREDIT_SCORE' => $credScpre->CREDIT_SCORE,
                'DS_PARECER' => $credScpre->DS_PARECER,
                'NO_ALCADA' => $credScpre->NO_ALCADA,
                'ID_MPME_ARQUIVO' => $credScpre->ID_MPME_ARQUIVO,
                'MOTIVO_ALTERACAO' => $credScpre->MOTIVO_ALTERACAO,

            ];
        }

        return $arrayDadosCreditScore;

    }

    public function MpmeCreditScoreExportador($ID_OPER)
    {
        $rsCreditScore = MpmeCreditScoreExportadores::where('ID_OPER', '=', $ID_OPER)
            ->join('MPME_ALCADA', 'MPME_ALCADA.ID_MPME_ALCADA', '=', 'MPME_CREDIT_SCORE_EXPORTADORES.ID_MPME_ALCADA')
            ->orderBy('MPME_CREDIT_SCORE_EXPORTADORES.ID_MPME_ALCADA')->get();
        $arrayDadosCreditScore = [];

        foreach ($rsCreditScore as $credScpre) {
            $arrayDadosCreditScore[$credScpre->ID_MPME_ALCADA] = [
                'VL_AVAL1' => $credScpre->VL_AVAL1,
                'VL_AVAL3' => $credScpre->VL_AVAL3,
                'VL_AVAL4' => $credScpre->VL_AVAL4,
                'VL_AVAL5' => $credScpre->VL_AVAL5,
                'VL_AVAL6' => $credScpre->VL_AVAL6,
                'VL_AVAL7' => $credScpre->VL_AVAL7,
                'VL_AVAL8' => $credScpre->VL_AVAL8,
                'VL_AVAL9' => $credScpre->VL_AVAL9,
                'VL_AVAL10' => $credScpre->VL_AVAL10,
                'VL_AVAL11' => $credScpre->VL_AVAL11,
                'VL_AVAL12' => $credScpre->VL_AVAL12,
                'VL_AVAL13' => $credScpre->VL_AVAL13,
                'VL_AVAL14' => $credScpre->VL_AVAL14,
                'ID_CREDIT_SCORE_EXPORTADORES' => $credScpre->ID_CREDIT_SCORE_EXPORTADORES,
                'ID_CREDITO' => $credScpre->ID_CREDITO,
                'CREDIT_SCORE' => $credScpre->CREDIT_SCORE,
                'DS_PARECER' => $credScpre->DS_PARECER,
                'NO_ALCADA' => $credScpre->NO_ALCADA,

            ];
        }

        return $arrayDadosCreditScore;

    }

    public function MpmeAprovacaoValorAlcada($ID_OPER)
    {
        $mpmeAprovacaoValorAlc = MpmeAprovacaoValorAlcada::where('ID_OPER', '=', $ID_OPER)->orderBy('ID_MPME_ALCADA')->get();

        foreach ($mpmeAprovacaoValorAlc as $mpmeVlAlc) {
            $arrayDadosApVl[$mpmeVlAlc->ID_MPME_ALCADA] = [
                'ID_APROVACAO_VALOR_ALCADA' => $mpmeVlAlc->ID_APROVACAO_VALOR_ALCADA,
                'ID_OPER' => $mpmeVlAlc->ID_OPER,
                'ID_MPME_ALCADA' => $mpmeVlAlc->ID_MPME_ALCADA,
                'VL_APROVADO' => $mpmeVlAlc->VL_APROVADO,
                'TX_OBSERVACAO' => $mpmeVlAlc->TX_OBSERVACAO,
                'IN_DECISAO' => $mpmeVlAlc->IN_DECISAO,
                'DT_DELIBERACAO' => $mpmeVlAlc->DT_DELIBERACAO,
                'IN_DEVOLVIDA' => $mpmeVlAlc->IN_DEVOLVIDA,
                'DT_CADASTRO' => $mpmeVlAlc->DT_CADASTRO,
                'ID_USUARIO_CAD' => $mpmeVlAlc->ID_USUARIO_CAD,
            ];
        }

        return $arrayDadosApVl;

    }

    public function CriterioOperacao($fl_momento = "", $ID_OPER)
    {
        return MpmeCriterioOperacao::where('ID_MPME_ALCADA', '=', $fl_momento)->where('ID_OPER', '=', $ID_OPER)->first();
    }

    public function recomendacao($fl_momento = "", $ID_OPER)
    {
        $RecomendacaoArray = MpmeRecomendacao::where('ID_MPME_ALCADA', '=', $fl_momento)->where('ID_OPER', '=', $ID_OPER)->orderBy('ID_RECOMENDACAO', 'desc')->first();

        return $RecomendacaoArray;
    }

    public function creditoConcedido($fl_momento = "", $ID_OPER)
    {
        return MpmeCreditoConcedido::where('ID_MPME_ALCADA', '=', $fl_momento)->where('ID_OPER', '=', $ID_OPER)->orderBy('ID_CREDITO', 'DESC')->first();
    }

    public function respostaIndeferimento($fl_momento = "", $ID_OPER)
    {
        $array_ids = array();
        $respostas = MpmeRespostaIndeferimento::where('ID_MPME_ALCADA', '=', $fl_momento)->where('ID_OPER', '=', $ID_OPER)->orderBy('ID_MPME_INDEFERIMENTO', 'DESC')->get(['ID_MPME_TIPO_INDEFERIMENTO']);
        if ($respostas != "") {
            foreach ($respostas as $resp) {
                array_push($array_ids, $resp->ID_MPME_TIPO_INDEFERIMENTO);
            }
        }
        return $array_ids;
    }

    public function mpme_arquivo_boleto_relatorio()
    {
        return $this->belongsTo(MpmeArquivo::class, 'ID_OPER', 'ID_FLEX')
            ->where("ID_MPME_TIPO_ARQUIVO", '=', $this::ID_MPME_TIPO_ARQUIVO_BOLETO_RELATORIO);
    }

    public function mpme_arquivo_comprovante_boleto()
    {
        return $this->belongsTo(MpmeArquivo::class, 'ID_OPER', 'ID_FLEX')
            ->where("ID_MPME_TIPO_ARQUIVO", '=', $this::ID_MPME_TIPO_ARQUIVO_BOLETO_RELATORIO_COMPROVANTE);
    }

    public function propostas()
    {
        return $this->hasMany('App\MpmeProposta', 'ID_OPER', 'ID_OPER');
    }

    public function movimentacao_capital()
    {
        return $this->hasMany(MpmeMovimentacaoControleCapital::class, 'ID_OPER', 'ID_OPER')->orderBy('ID_MPME_FUNDO_GARANTIA')->orderByDesc('ID_MPME_MOVIMENTACAO_CONTROLE_CAPITAL');
    }

    public function mpme_movimentacao_controle_capital($id_alcada, $ID_OPER, $ID_MPME_FUNDO_GARANTIA = "")
    {

        $importador = MpmeMovimentacaoControleCapital::where('ID_OPER', $ID_OPER);

        if ($id_alcada == 1 || $id_alcada == 2) { //Caso seja analista pega a alcada pre-analista
            $importador = $importador->whereIn('ID_MPME_ALCADA', [2, 7]);

        } else {
            $importador = $importador->where('ID_MPME_ALCADA', $id_alcada);

        }

        if ($ID_MPME_FUNDO_GARANTIA != "") {
            $importador = $importador->where('ID_MPME_FUNDO_GARANTIA', $ID_MPME_FUNDO_GARANTIA);
        }

        $importador = $importador
            ->orderBy('ID_MPME_MOVIMENTACAO_CONTROLE_CAPITAL', 'desc')->first();

        return $importador;
    }

    public function UltimaAlcadaMovimentacao()
    {
        return $this->hasOne(MpmeMovimentacaoControleCapital::class, 'ID_OPER', 'ID_OPER')->orderByDesc('ID_MPME_MOVIMENTACAO_CONTROLE_CAPITAL');
    }

}
