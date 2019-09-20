<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use User;

class DeclaracaoCompromissoExportador extends Controller
{

    public function index()
    {
        return view('declaracaoCompromisso.declaracao_compromisso');
    }

    public function baixar()
    {
        // Utiliza o modelo do arquivo criado em HTML
        $documento = public_path('/docs/anti-corrupcao/' . 'DECLARACAO_DE_COMPROMISSO_DO_EXPORTADOR.html');
        // Renomeia o arquivo com _ id do exportador
        $docGerado = public_path('/docs/anti-corrupcao/' . 'DECLARACAO_DE_COMPROMISSO_DO_EXPORTADOR' . Auth::User()->ID_USUARIO . '.html');

        // Abre o arquivo em modo de leitura
        $fp = fopen($documento, "r");
        // le o arquivo
        $output = fread($fp, filesize($documento)); // armazena na variavel output o conteudo do arquivo
        fclose($fp); // fecha o arquivo

        // Substitui as tags pelas variveis
        $output = str_replace("<EXPORTADOR>", utf8_decode(Auth::User()->NM_USUARIO), $output); // substitui o <EXPORTADOR> pelo nome do Exportador
        $output = str_replace("<ID_USUARIO>", utf8_decode(Auth::User()->ID_USUARIO), $output); // substitui o <ID_USUARIO> pelo nome do ID_USUARIO
        $output = str_replace("<NM_CONTATO>", utf8_decode(Auth::User()->NM_CONTATO), $output); // substitui o <NM_CONTATO> pelo nome do nome do exportador
        $output = str_replace("<DE_CARGO>", utf8_decode(Auth::User()->DE_CARGO), $output); // substitui o <DE_CARGO> pelo cargo do Exportador
        $output = str_replace("<NU_CNPJ>", utf8_decode(Auth::User()->NU_CNPJ), $output); // substitui o <NU_CNPJ> pelo cnpj do Exportador
        $output = str_replace("<DE_ENDER>", utf8_decode(Auth::User()->DE_ENDER), $output); // substitui o <DE_ENDER> pelo endereço do Exportador
        $output = str_replace("<DE_CEP>", utf8_decode(Auth::User()->DE_CEP), $output); // substitui o <DE_CEP> pelo cep do Exportador
        $output = str_replace("<DE_CIDADE>", utf8_decode(Auth::User()->DE_CIDADE), $output); // substitui o <DE_CIDADE> pela cidade do Exportador
        $output = str_replace("<DE_UF>", utf8_decode(Auth::User()->CD_UF), $output); // substitui o <DE_UF> pelo estado do Exportador

        $arquivoSalvo = fopen($docGerado, "w"); // abre a copia renomeada do arquivo e substitui os textos pelos que vieram do banco de dados;
        fwrite($arquivoSalvo, $output, strlen($output)); // salva as alteracoes
        fclose($arquivoSalvo); // fecha o arquivo

        $nomeArquivo = "DECLARACAO_DE_COMPROMISSO_DO_EXPORTADOR_" . Auth::User()->ID_USUARIO . ".pdf"; // cria o nome e extenção do arquivo a ser baixado
        return PDF::loadFile($docGerado)->download($nomeArquivo); // carrega o arquivo e disponibiliza por donwload
    }
}
