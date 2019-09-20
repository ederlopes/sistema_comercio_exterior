<?php

use App\MpmeArquivo;
use App\MpmeClienteExportador;
use App\MpmeClienteImportador;
use App\MpmeControleLimiteCliente;
use App\MpmeCreditoConcedido;
use App\OperacaoCadastroExportador;
use App\Repositories\MpmeNotificacaoUsuarioRepository;
use App\Repositories\PrecificacaoRepository;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\MpmeProposta;
use Barryvdh\DomPDF\Facade as PDF;

function converte_float($valor)
{
    $valor = preg_replace('/([^0-9\.,])/i', '', $valor);
    if (!is_numeric($valor)) {
        $valor = str_replace(array('.', ','), array('', '.'), $valor);
        return floatval($valor);
    } else {
        return $valor;
    }
}

function retornaTipoFundoProposta($id_oper)
{
    $fundo = MpmeCreditoConcedido::where('ID_OPER', $id_oper)->first();
    return $fundo->ID_MPME_FUNDO_GARANTIA;
}

function dataMaiorQueOutra($data1, $data2)
{
    if (strtotime($data1) > strtotime($data2)) {
        return true;
    } else {
        return false;
    }
}

function calcular_valor_dowpayment($valor, $percentual)
{
    if ($percentual == 0 || $percentual == "") {
        return $valor;
    }

    $novo_percentual = $percentual / 100;
    $novo_valor = $valor - ($valor * $novo_percentual);

    return $novo_valor;
}

function formatar_valor($value)
{
    $value = (is_numeric($value)) ? $value : 0;
    return 'R$ ' . number_format($value, 2, ',', '.');
}

function formatar_valor_sem_moeda($value)
{
    $value = (is_numeric($value)) ? $value : 0;
    return number_format($value, 2, ',', '.');
}

function formatar_moeda($value)
{
    $value = (is_numeric($value)) ? $value : 0;
    return number_format($value, 2, ',', '.');
}

function formatar_data($value)
{
    if ($value == '' or $value == null) {
        return '-';
    } else {
        return \Carbon\Carbon::parse($value)->format('d/m/Y');
    }
}

function formatar_data_hora($value)
{
    if ($value == '' or $value == null) {
        return '-';
    } else {
        return \Carbon\Carbon::parse($value)->format('d/m/Y H:i:s');
    }
}

function formatar_data_sql($value)
{
    $data = explode("/", $value);
    return $data[2] . '-' . $data[1] . '-' . $data[0];
}

function getClientIp()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_CLIENT_IP'];
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',') !== false) {
            $iplist = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($iplist as $ip) {
                $ip_address = $ip;
            }
        } else {
            $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
    }

    if (!empty($_SERVER['HTTP_X_FORWARDED'])) {
        $ip_address = $_SERVER['HTTP_X_FORWARDED'];
    } elseif (!empty($_SERVER['HTTP_X_CLUSTER_CLIENT_IP'])) {
        $ip_address = $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED_FOR'])) {
        $ip_address = $_SERVER['HTTP_FORWARDED_FOR'];
    } elseif (!empty($_SERVER['HTTP_FORWARDED'])) {
        $ip_address = $_SERVER['HTTP_FORWARDED'];
    } else {
        $ip_address = $_SERVER['REMOTE_ADDR'];
    }
    return $ip_address;
}

function remove_caracteres($text)
{
    $utf8 = array(
        '/[áàâãªä]/u' => 'a',
        '/[ÁÀÂÃÄ]/u' => 'A',
        '/[ÍÌÎÏ]/u' => 'I',
        '/[íìîï]/u' => 'i',
        '/[éèêë]/u' => 'e',
        '/[ÉÈÊË]/u' => 'E',
        '/[óòôõºö]/u' => 'o',
        '/[ÓÒÔÕÖ]/u' => 'O',
        '/[úùûü]/u' => 'u',
        '/[ÚÙÛÜ]/u' => 'U',
        '/ç/' => 'c',
        '/Ç/' => 'C',
        '/ñ/' => 'n',
        '/Ñ/' => 'N',
        '/–/' => '-', // UTF-8 hyphen to "normal" hyphen
        '/[’‘‹›‚]/u' => '', // Literally a single quote
        '/[“”«»„]/u' => '', // Double quote
        '/ /' => '_', // nonbreaking space (equiv. to 0x160)
    );
    return preg_replace(array_keys($utf8), array_values($utf8), $text);
}

function valor_extenso($valor = 0, $bolExibirMoeda = true, $bolPalavraFeminina = false)
{

    $valor = converte_float($valor);

    $singular = null;
    $plural = null;

    if ($bolExibirMoeda) {
        $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
    } else {
        $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
        $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
    }

    $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
    $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
    $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezesete", "dezoito", "dezenove");
    $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

    if ($bolPalavraFeminina) {

        if ($valor == 1) {
            $u = array("", "uma", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
        } else {
            $u = array("", "um", "duas", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");
        }

        $c = array("", "cem", "duzentas", "trezentas", "quatrocentas", "quinhentas", "seiscentas", "setecentas", "oitocentas", "novecentas");
    }

    $z = 0;

    $valor = number_format($valor, 2, ".", ".");
    $inteiro = explode(".", $valor);

    for ($i = 0; $i < count($inteiro); $i++) {
        for ($ii = mb_strlen($inteiro[$i]); $ii < 3; $ii++) {
            $inteiro[$i] = "0" . $inteiro[$i];
        }
    }

    // $fim identifica onde que deve se dar junção de centenas por "e" ou por "," ;)
    $rt = null;
    $fim = count($inteiro) - ($inteiro[count($inteiro) - 1] > 0 ? 1 : 2);
    for ($i = 0; $i < count($inteiro); $i++) {
        $valor = $inteiro[$i];
        $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
        $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
        $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

        $r = $rc . (($rc && ($rd || $ru)) ? " e " : "") . $rd . (($rd && $ru) ? " e " : "") . $ru;
        $t = count($inteiro) - 1 - $i;
        $r .= $r ? " " . ($valor > 1 ? $plural[$t] : $singular[$t]) : "";
        if ($valor == "000") {
            $z++;
        } elseif ($z > 0) {
            $z--;
        }

        if (($t == 1) && ($z > 0) && ($inteiro[0] > 0)) {
            $r .= (($z > 1) ? " de " : "") . $plural[$t];
        }

        if ($r) {
            $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? (($i < $fim) ? ", " : " e ") : " ") . $r;
        }
    }

    $rt = mb_substr($rt, 1);

    return ($rt ? trim($rt) : "zero");
}

function extensao_arquivo($no_arquivo)
{
    $no_extensao = explode(".", $no_arquivo);
    return end($no_extensao);
}

function local_e_data($strLocal = "")
{
    $arrMeses = array(
        "01" => "Janeiro",
        "02" => "Fevereiro",
        "03" => "Março",
        "04" => "Abril",
        "05" => "Maio",
        "06" => "Junho",
        "07" => "Julho",
        "08" => "Agosto",
        "09" => "Setembro",
        "10" => "Outubro",
        "11" => "Novembro",
        "12" => "Dezembro",
    );
    list($d, $m, $y) = explode('-', date('d-m-Y'));
    return sprintf("%s, %s de %s de %s", $strLocal, $d, $arrMeses[$m], $y);
}

function limpar_mascara($valor)
{
    if (!empty($valor)) {
        $valor = preg_replace('/\D+/', '', $valor);
    }

    return $valor;
}

function cpf_cnpj($cnpj_cpf)
{
    if (strlen(preg_replace("/\D/", '', $cnpj_cpf)) === 11) {
        $response = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } else {
        $response = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }
    return $response;
}

function buscarUltimasOperacoesExportadorGruPaga($ID_MPME_CLIENTE, $expordir_por_operacao = 'N')
{

    $id_operacoes = ($expordir_por_operacao == 'N') ? '' : ' OPERACAO_CADASTRO_EXPORTADOR.ID_OPER, ';

    $data = date("Y") - 1;

    $rsExportador = DB::select(
        'SELECT   PAISES.CD_SIGLA, CODIGO_UNICO_IMPORTADOR, NOME_FANTASIA, COUNT(*) AS TOTAL
    FROM MPME_CLIENTE_IMPORTADORES
    INNER JOIN MPME_CLIENTE ON (MPME_CLIENTE.ID_MPME_CLIENTE = MPME_CLIENTE_IMPORTADORES.ID_MPME_CLIENTE)
    INNER JOIN PAISES ON (PAISES.ID_PAIS = MPME_CLIENTE.ID_PAIS)
    INNER JOIN OPERACAO_CADASTRO_EXPORTADOR ON (OPERACAO_CADASTRO_EXPORTADOR.ID_OPER = MPME_CLIENTE_IMPORTADORES.ID_OPER)
    INNER JOIN MPME_GRU_PRECO_COBERTURA ON (MPME_GRU_PRECO_COBERTURA.ID_OPER = OPERACAO_CADASTRO_EXPORTADOR.ID_OPER)
    INNER JOIN MPME_CLIENTE_EXPORTADORES ON (MPME_CLIENTE_EXPORTADORES.ID_MPME_CLIENTE_EXPORTADORES = OPERACAO_CADASTRO_EXPORTADOR.ID_MPME_CLIENTE_EXPORTADORES)
    WHERE MPME_CLIENTE_EXPORTADORES.ID_MPME_CLIENTE = ?
    AND MPME_GRU_PRECO_COBERTURA.VL_PAGO IS NOT NULL
    AND YEAR(MPME_GRU_PRECO_COBERTURA.DT_PAGAMENTO_GRU) = ?
    GROUP BY   PAISES.CD_SIGLA, CODIGO_UNICO_IMPORTADOR, NOME_FANTASIA
    ORDER BY NOME_FANTASIA ASC',
        [$ID_MPME_CLIENTE, $data]
    );

    return $rsExportador;
}

function getValorAprovado($dados)
{

    if (!isset($dados)) {
        return 0.0;
    }

    if (isset($dados['CLI'])) {
        $valor_aprovado = ($dados['CLI']['FL_DESCISAO'] == null) ? 0 : $dados['CLI']['VL_APROVADO'];
    }
    if (isset($dados['ANA'])) {
        $valor_aprovado = ($dados['ANA']['FL_DESCISAO'] == null) ? 0 : $dados['ANA']['VL_APROVADO'];
    }
    if (isset($dados['GER'])) {
        $valor_aprovado = ($dados['GER']['FL_DESCISAO'] == null) ? 0 : $dados['GER']['VL_APROVADO'];
    }
    if (isset($dados['SUP'])) {
        $valor_aprovado = ($dados['SUP']['FL_DESCISAO'] == null) ? 0 : $dados['SUP']['VL_APROVADO'];
    }
    if (isset($dados['ABGF'])) {
        $valor_aprovado = ($dados['ABGF']['FL_DESCISAO'] == null) ? 0 : $dados['ABGF']['VL_APROVADO'];
    }
    return number_format($valor_aprovado, 2, ',', '.');
}

function formatar_codigo($input)
{
    print str_pad($input, 5, "0", STR_PAD_LEFT);
}

function trocaVirgulaPorPonto($valor)
{
    return str_replace(",", ".", str_replace(".", "", $valor));
}

function VerificaSeuploadFoifeito($TipoUpload, $ID_OPER = '')
{
    switch ($TipoUpload) {
        case 'comprovante_pg_relatorio':
            $pasta = public_path('/uploads/abgf/exportador/limite/comprovante_pg_relatorio/' . $ID_OPER . '/');
            if (File::exists($pasta)) {

                return true;
            } else {

                return false;
            }
            break;
        case 'relatorio_internacional':
            $pasta = public_path('/uploads/abgf/exportador/limite/relatorio_internacional/' . $ID_OPER . '/');

            if (File::exists($pasta)) {

                return true;
            } else {

                return false;
            }

            break;
        case 'upload_calculo_limite_credito':
            $pasta = public_path('/uploads/abgf/exportador/limite/upload_calculo_limite_credito/' . $ID_OPER . '/');

            if (File::exists($pasta)) {

                return true;
            } else {

                return false;
            }
            break;
    }
}

function alcadaAtiva($vl_inicial, $vl_final, $vl_operacao, $in_deliberativa)
{

    if ($in_deliberativa == 'N') {
        return 'enabled';
    }

    if ($vl_operacao <= $vl_inicial) {
        return 'disabled';
    } else {
        return 'enabled';
    }
}

function travarAlcadaSuperior($idAlcada = "")
{

    foreach (Auth::user()->usuario_alcadas as $alcada) {
        $alcadas[] = $alcada->ID_MPME_ALCADA;
    }

    if (in_array($idAlcada, $alcadas)) {
        return true;
    }

    return false;
}

function getDecisao($in_decisao)
{
    switch ($in_decisao) {
        case "1":
            return 'Aprovado';
            break;

        case "2":
            return 'Indeferido';
            break;
    }
}

function credScore($CREDIT_SCORE)
{
    switch ($CREDIT_SCORE) {
        case 'A':
        case '1':
            $COD_CREDIT_SCORE = 1;
            break;
        case 'B':
        case '2':
            $COD_CREDIT_SCORE = 2;
            break;
        case 'C':
        case '3':
            $COD_CREDIT_SCORE = 3;
            break;
        case 'D':
        case '4':
            $COD_CREDIT_SCORE = 4;
            break;
        case 'E':
        case '5':
            $COD_CREDIT_SCORE = 5;
            break;
        default:
            $COD_CREDIT_SCORE = (int)$CREDIT_SCORE;
            break;
    }
    return $COD_CREDIT_SCORE;
}

function buscaPercentualCalculadora2($dados)
{
    $url = env('URL_WSCALC');

    $data = array(
        'NO_QUALIDADE_PRODUTO' => utf8_encode($dados['NO_QUALIDADE_PRODUTO']),
        'PC_PRE_COB_COM' => $dados['PC_PRE_COB_COM'],
        'PC_PRE_COB_POL' => $dados['PC_PRE_COB_POL'],
        'PC_POS_COB_COM' => $dados['PC_POS_COB_COM'],
        'PC_POS_COB_POL' => $dados['PC_POS_COB_POL'],
        'NU_FATOR_PI' => $dados['NU_FATOR_PI'],
        'TP_PRODUTO' => utf8_encode($dados['TP_PRODUTO']),
        'NU_PRAZO_PRE' => $dados['NU_PRAZO_PRE'],
        'TP_RATING_EXP_PRE' => $dados['TP_RATING_EXP_PRE'], //rating exportador
        'NU_RATING_BRA_PRE' => $dados['NU_RATING_BRA_PRE'],
        'NU_PRAZO_POS' => $dados['NU_PRAZO_POS'],
        'TP_RATING_IMP_POS' => $dados['TP_RATING_IMP_POS'], //rating importador
        'NU_RATING_PAIS_POS' => $dados['NU_RATING_PAIS_POS'],
    );

    // use key 'http' even if you send the request to https://...
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => 'Content-Type: application/x-www-form-urlencoded',
            'content' => http_build_query($data),
            'timeout' => 360,
        ),
    );

    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);

    $objJSON = json_decode($result);

    return colunaRetornoJSON($dados['CODIGO_MODAL_CALC'], $objJSON);
}

function calcularValorCobertura($percentualCalculadora, $arrayDados)
{
    $VL_COBERTURA = round($percentualCalculadora * 100, 5);

    // Define variáveis utilizadas em calculos
    $VL_EXP_ANUAL_CALC = (float)$arrayDados['VL_EXP_ANUAL'];
    $PC_ANTECIPADO_CALC = (float)$arrayDados['PC_ANTECIPADO'];

    $VL_COBERTURA_IMP = ($VL_EXP_ANUAL_CALC - ($VL_EXP_ANUAL_CALC * ($PC_ANTECIPADO_CALC / 100))) * ($VL_COBERTURA / 100);
    $VL_COBERTURA_RETORNO_CALULADORA1 = $VL_COBERTURA_IMP;
    $VL_PERC_RETORNO_CALULADORA1 = $percentualCalculadora;
    $VL_DOWNPAYMENT = ($VL_EXP_ANUAL_CALC - ($VL_EXP_ANUAL_CALC * ($PC_ANTECIPADO_CALC / 100)));
    $VL_COBERTURA_IMP_FORMATADO = number_format($VL_COBERTURA_IMP, 2, ',', '.');

    //adicionando calculos extras a simulacao

    $DADOSCARREGAMENTO = PrecificacaoRepository::calcular_taxas_precificacao($VL_COBERTURA_IMP);
    $VL_COBERTURA_IMP = $DADOSCARREGAMENTO['TOTAL'];
    $VL_COBERTURA_IMP_FORMATADO = number_format($VL_COBERTURA_IMP, 2, ',', '.');

    $percentual_calculadora_carregada = round(($VL_COBERTURA_IMP / $VL_DOWNPAYMENT) * 100, 4);

    $arrayDados["VL_EXP_ANUAL_CALC"] = $VL_EXP_ANUAL_CALC;
    $arrayDados["PERCENTUAL_CALCULADORA"] = $percentual_calculadora_carregada;
    $arrayDados["VL_COBERTURA_IMP"] = $VL_COBERTURA_IMP;
    $arrayDados["VL_COBERTURA_IMP_FORMATDO"] = $VL_COBERTURA_IMP_FORMATADO;

    $arrayDados["PC_COB"] = $percentual_calculadora_carregada;
    $arrayDados["PC_COB_MIN"] = $percentual_calculadora_carregada;
    $arrayDados["VL_PC_COB"] = $VL_COBERTURA_IMP;
    $arrayDados["VL_PC_COB_MIN"] = $VL_COBERTURA_IMP;
    $arrayDados["PC_COB_MAX"] = $percentual_calculadora_carregada;
    $arrayDados["VL_PC_COB_MAX"] = $VL_COBERTURA_IMP;
    $arrayDados["VL_DOWNPAYMENT"] = $VL_DOWNPAYMENT;
    $arrayDados["DADOS_CARREGAMENTO"] = $DADOSCARREGAMENTO;
    $arrayDados["VL_COBERTURA_RETORNO_CALULADORA1"] = $VL_COBERTURA_RETORNO_CALULADORA1;
    $arrayDados["VL_PERC_RETORNO_CALULADORA1"] = $VL_PERC_RETORNO_CALULADORA1;

    return $arrayDados;
}

function colunaRetornoJSON($tp_produto, $objJSON)
{
    switch ($tp_produto) {
        case '1':
        case 'Pré-Embarque':
            $colunaretorno = $objJSON->C39;
            break;
        case '2':
        case 'Pré+Pós-Embarque':
            $colunaretorno = $objJSON->C41;
            break;
        case '3':
        case 'Pós-Embarque':
            $colunaretorno = $objJSON->C40;
            break;
    }

    $colunaretorno = round(($colunaretorno * 100), 2);

    $colunaretorno = $colunaretorno / 100;

    return $colunaretorno;
}

function retornaStatusPerfilProposta($id_perfil)
{
    switch ($id_perfil) {
        case 1:
            $id_retorno_perfil = 2;
            break;
        case 2:
            $id_retorno_perfil = 3;
            break;
        default:
            $id_retorno_perfil = 0;
            break;
    }

    return $id_retorno_perfil;
}

function retornaStatusPerfilAlcada($id_perfil)
{
    switch ($id_perfil) {
        case 1:
            $id_retorno_perfil = 2;
            break;
        case 2:
            $id_retorno_perfil = 3;
            break;
        default:
            $id_retorno_perfil = 0;
            break;
    }

    return $id_retorno_perfil;
}

function buscar_em_array($paralavra, $array)
{
    foreach ($array as $key => $value) {
        $key_atual = $key;
        if ($paralavra === $value or (is_array($value) && buscar_em_array($paralavra, $value) !== false)) {
            return $key_atual;
        }
    }
    return false;
}

function enquadrarModalidade($dadosFinanceirosdoExportador, $clienteModalidadeFinanciamentos)
{
    $fornecedorEnquadradoModalidade = [];
    if (@count($clienteModalidadeFinanciamentos) == 0) {

        $fornecedorEnquadradoModalidade[] = enquadradado('NAO', 1, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
        $fornecedorEnquadradoModalidade[] = enquadradado('NAO', 2, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
        $fornecedorEnquadradoModalidade[] = enquadradado('NAO', 3, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
        return $fornecedorEnquadradoModalidade;
    }

    $id_modalidade = $clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE;

    switch ($id_modalidade) {
        case "1": //PRÉ-EMBARQUE
            $fornecedorEnquadradoModalidade = validadeModalidePre($dadosFinanceirosdoExportador, $clienteModalidadeFinanciamentos);
            break;
        case "3": //PÓS-EMBARQUE
            $fornecedorEnquadradoModalidade = validadeModalidePos($dadosFinanceirosdoExportador, $clienteModalidadeFinanciamentos);
            break;
    }

    return $fornecedorEnquadradoModalidade;
}

function validadeModalidePre($dadosFinanceirosdoExportador, $clienteModalidadeFinanciamentos)
{

    $ID_MPME_CLIENTE_EXPORTADORES = $dadosFinanceirosdoExportador->ID_MPME_CLIENTE_EXPORTADORES;
    $id_modalidade = $clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE;

    if (
        converte_float($dadosFinanceirosdoExportador->VL_EXP_BRUTO_ANUAL) <= converte_float($clienteModalidadeFinanciamentos->ModalidadeFinanciamento->enquadramento->VL_EXPORTACAO_INI) &&
        converte_float($dadosFinanceirosdoExportador->VL_FAT_BRUTO_ANUAL) <= converte_float($clienteModalidadeFinanciamentos->ModalidadeFinanciamento->enquadramento->VL_FATURAMENTO_INI)
    ) {
        $fornecedorEnquadradoModalidade = enquadradado('SIM', 1, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
    } else {
        $fornecedorEnquadradoModalidade = enquadradado('NAO', 1, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
    }

    return $fornecedorEnquadradoModalidade;
}

function validadeModalidePos($dadosFinanceirosdoExportador, $clienteModalidadeFinanciamentos)
{
    $ID_MPME_CLIENTE_EXPORTADORES = $dadosFinanceirosdoExportador->ID_MPME_CLIENTE_EXPORTADORES;
    $id_modalidade = $clienteModalidadeFinanciamentos->ModalidadeFinanciamento->ID_MODALIDADE;

    //se o FATURAMENTO ANUAL DO EXPORTADOR É <= 300 MILHOES QUE ESTA CADASTRADO NO ENQUADRAMENTO
    if (converte_float($dadosFinanceirosdoExportador->VL_FAT_BRUTO_ANUAL) <= converte_float($clienteModalidadeFinanciamentos->ModalidadeFinanciamento->enquadramento->VL_FATURAMENTO_INI)) {

        //se as Exportacoes anual do Exportador é <= ao valor de exportacoes anual do enquadramento
        if (converte_float($dadosFinanceirosdoExportador->VL_EXP_BRUTO_ANUAL) <= converte_float($clienteModalidadeFinanciamentos->ModalidadeFinanciamento->enquadramento->VL_EXPORTACAO_INI)) {
            $fornecedorEnquadradoModalidade = enquadradado('SIM', 3, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
        } else {
            //perguntando se o valor de exportacao esta entre 3milhoes e 5milhoes

            if (converte_float($dadosFinanceirosdoExportador['VL_EXP_BRUTO_ANUAL']) <= converte_float($clienteModalidadeFinanciamentos->ModalidadeFinanciamento->enquadramento->VL_EXPORTACAO_FIM)) {
                $fornecedorEnquadradoModalidade = enquadradado('SIM', 3, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
            } else {
                $fornecedorEnquadradoModalidade = enquadradado('NAO', 3, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
            }
        }
    } else {
        $fornecedorEnquadradoModalidade = enquadradado('NAO', 3, $ID_MPME_CLIENTE_EXPORTADORES, '', 'NAO');
    }
    return $fornecedorEnquadradoModalidade;
}

function enquadradado($status, $modalidade, $ID_MPME_CLIENTE_EXPORTADORES, $numero_imporadores = "", $modalidade_antiga = "", $numero_pais_cofig = "")
{
    $dados = array(
        'ID_MPME_CLIENTE_EXPORTADORES' => $ID_MPME_CLIENTE_EXPORTADORES,
        'modalidade' => $modalidade,
        'enquaradrado' => $status,
        'numero_imporadores' => $numero_imporadores,
        'modalidade_antiga' => $modalidade_antiga,
        'numero_pais_cofig' => $numero_pais_cofig,
    );

    return $dados;
}

function retornaNomeAlcada($ID_MPME_ALCADA_ATUAL)
{
    $alcada = new \App\Repositories\MpmeAlcadaRepository();
    return $alcada->getAlcada($ID_MPME_ALCADA_ATUAL)->NO_ALCADA;
}

function formatarCnpjCpf($cnpj_cpf)
{
    if (strlen(preg_replace("/\D/", '', $cnpj_cpf)) === 11) {
        $response = preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $cnpj_cpf);
    } else {
        $response = preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $cnpj_cpf);
    }

    return $response;
}

function getNotificacaoEmAbertoPorUsuario()
{
    $mpme_notificacao_usuario_repository = new MpmeNotificacaoUsuarioRepository();

    $mpme_notificacao_usuario_repository = $mpme_notificacao_usuario_repository->getNotificacaoEmAbertoPorUsuario();

    return $mpme_notificacao_usuario_repository;
}

function menu_total_paginacao($request)
{
    $quantidade = [
        10 => "10",
        50 => "50",
        100 => "100",
        500 => "500",
        -1 => "Todas",
    ];

    $menu = '<div class="col-md-2">
              <div class="form-group">
                  <label>Total por página</label>
                  <select class="form-control input-sm" name="total_paginacao" id="total_paginacao">';
    foreach ($quantidade as $key => $value) {
        $selected = ($value == $request->total_paginacao) ? 'selected="selected"' : "";
        $menu = $menu . "<option value='$key' $selected >$value</option>";
    }
    $menu = $menu . '</select>
              </div>
            </div>';
    return $menu;
}

function retornaModalidade($ID_OPER)
{
    $modalidadeOperacao = new OperacaoCadastroExportador();
    $modalidadeOperacao = $modalidadeOperacao->where('ID_OPER', $ID_OPER)->first();
    return $modalidadeOperacao->ID_MODALIDADE;
}

function retornaModalidadeFromIdProposta($ID_PROPOSTA)
{
    $proposta = new MpmeProposta();
    $proposta = $proposta->where('ID_MPME_PROPOSTA', $ID_PROPOSTA)->first();
    $id_oper = $proposta->ID_OPER;

    $modalidadeOperacao = new OperacaoCadastroExportador();
    $modalidadeOperacao = $modalidadeOperacao->where('ID_OPER', $id_oper)->first();
    return $modalidadeOperacao->ID_MODALIDADE;
}

function retornaClienteExportadorPeloIdUsuario($ID_USUARIO)
{
    $cliente = new MpmeClienteExportador();
    $cliente = $cliente->where('ID_USUARIO', $ID_USUARIO)->first();
    return $cliente->ID_MPME_CLIENTE_EXPORTADORES;
}

function retornaClienteExportadorPelaOperacao($ID_OPER)
{
    $operacao = OperacaoCadastroExportador::where('ID_OPER', $ID_OPER)->first();

    return $operacao->ID_MPME_CLIENTE_EXPORTADORES;
}

function retornaClienteImportadorPelaOperacao($ID_OPER)
{
    $operacao = MpmeClienteImportador::where('ID_OPER', $ID_OPER)->where('CODIGO_UNICO_IMPORTADOR', '!=', 0)->first();
    return $operacao->ID_MPME_CLIENTE;
}

function retornaSaldoImportadorExportador($ID_OPER)
{
    $operacao = OperacaoCadastroExportador::where('ID_OPER', $ID_OPER)->first();
    switch ($operacao->ID_MODALIDADE) {
        case 1:
            $cliente = OperacaoCadastroExportador::where('ID_OPER', $ID_OPER)->first()->ID_MPME_CLIENTE_EXPORTADORES ?? '';
            break;
        case 2:
            $cliente = MpmeClienteImportador::where('ID_OPER', $ID_OPER)->where('CODIGO_UNICO_IMPORTADOR', '!=', 0)->first()->ID_MPME_CLIENTE ?? '';
            break;
        case 3:
            $cliente = MpmeClienteImportador::where('ID_OPER', $ID_OPER)->where('CODIGO_UNICO_IMPORTADOR', '!=', 0)->first()->ID_MPME_CLIENTE ?? '';
            break;
    }

    $limite = MpmeControleLimiteCliente::where('ID_MPME_CLIENTE', $cliente)->get()->sum('VL_APROVADO');
    return $limite;
}

function Encripta($info)
{
    $aux = "";
    $chave = "";

    for ($i = 0; $i <= (strlen($info) - 1); $i++) {
        $charaux = substr($info, $i, 1);
        $charaux = dechex(ord($charaux));

        if (strlen($charaux) == 1) {
            $charaux = "0" . $charaux;
        }

        $charaux = $charaux . "F";

        $aux = $aux . $charaux;
    }

    $aux = $aux . $chave;

    return $aux;
}

function Decripta($info)
{
    $aux = "";
    $i = 0;

    while ($i <= (strlen($info) - 1)) {
        $charaux = substr($info, $i, 2);

        $charaux = chr(hexdec($charaux));
        $aux = $aux . $charaux;

        $i = $i + 3;
    }

    return $aux;
}

function retornoPrazoSusep($data)
{
    $data = explode(" ", $data);
    $date = Carbon::parse($data[0]);
    $now  = Carbon::now()->format('Y-m-d');
    $diff = 15 - $date->diffInDays($now);
    return $diff;
}

function limpaCPF_CNPJ($valor)
{
    $valor = trim($valor);
    $valor = str_replace(".", "", $valor);
    $valor = str_replace(",", "", $valor);
    $valor = str_replace("-", "", $valor);
    $valor = str_replace("/", "", $valor);
    return $valor;
}

function indeferirCadastro($dados)
{

    $i = 0;
    foreach ($dados as $arr) {
        if ($arr['enquaradrado'] == 'SIM') {
            $i++;
        }
    }
    return $i;
}

function removerCaractere($caractere, $campo)
{
    return str_replace($caractere, "", trim($campo));
}


function gerar_pdf_regras_condicoes($dados_exportador, $dados_operacao, $dados_importador, $modalidade){

    // Utiliza o modelo do arquivo criado em HTML
    $documento = public_path('/docs/regras_condicoes/' . 'REGRAS_CONDICOES.html');
    // Renomeia o arquivo com _ id do exportador
    $docGerado = public_path('/docs/regras_condicoes/' . 'REGRAS_CONDICOES_' . $dados_operacao->ID_OPER . '.html');

    // Abre o arquivo em modo de leitura
    $fp = fopen($documento, "r");
    // le o arquivo
    $output = fread($fp, filesize($documento)); // armazena na variavel output o conteudo do arquivo
    fclose($fp); // fecha o arquivo

    // Substitui as tags pelas variveis
    $output = str_replace("<EXPORTADOR>", utf8_decode($dados_exportador->NM_USUARIO), $output); // substitui o <EXPORTADOR> pelo nome do Exportador
    $output = str_replace("<ID_USUARIO>", utf8_decode($dados_exportador->ID_USUARIO), $output); // substitui o <ID_USUARIO> pelo nome do ID_USUARIO
    $output = str_replace("<IMPORTADOR>", utf8_decode($dados_operacao->RAZAO_SOCIAL), $output); // substitui o <NM_CONTATO> pelo nome do nome do exportador
    $output = str_replace("<MODALIDADE>", utf8_decode($modalidade), $output); // substitui o <DE_CARGO> pelo cargo do Exportador


    $arquivoSalvo = fopen($docGerado, "w"); // abre a copia renomeada do arquivo e substitui os textos pelos que vieram do banco de dados;
    fwrite($arquivoSalvo, $output, strlen($output)); // salva as alteracoes
    fclose($arquivoSalvo); // fecha o arquivo

    $nomeArquivo = "REGRAS_CONDICOES_" . $dados_operacao->ID_OPER . ".pdf"; // cria o nome e extenção do arquivo a ser baixado

    $pdf =  PDF::loadFile($docGerado)->save(storage_path('app/public/REGRAS_CONDICOES/').$nomeArquivo); // carrega o arquivo e disponibiliza por donwload

    $novo_arquivo = new MpmeArquivo();
    $novo_arquivo->ID_MPME_TIPO_ARQUIVO = '27';
    $novo_arquivo->ID_OPER = ($dados_operacao->ID_OPER != "") ? $dados_operacao->ID_OPER : null;
    $novo_arquivo->ID_FLEX = null;
    $novo_arquivo->NO_DIRETORIO = 'REGRAS_CONDICOES/';
    $novo_arquivo->NO_EXTENSAO = 'pdf';
    $novo_arquivo->NO_ARQUIVO = $nomeArquivo;
    $novo_arquivo->DT_CADASTRO = Carbon::now();
    $novo_arquivo->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

    if($novo_arquivo->save()){
        return $novo_arquivo->ID_MPME_ARQUIVO;
    }else{
        return false;
    }


}