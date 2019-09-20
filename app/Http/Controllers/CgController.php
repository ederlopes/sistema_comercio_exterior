<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\MpmeProposta;
use App\Repositories\MpmeNotificacaoRepository;

class CgController extends Controller {

    public function index() {
        
        $propostas = MpmeProposta::where('ID_MPME_STATUS_PROPOSTA','=',5)
                    ->with('MpmeClienteExportadorModaliadeFinancimanciamento')
                    ->get(); 
        
        return view('abgf.cg.index_cg', compact('propostas'));
    }

    public function gerarcgCondGerais(Request $request) {
        
      
        $exportador = User::find($request->ID_USUARIO);
        
        if(MpmeNotificacaoRepository::CriaNotificacaoCGCondGerais($request)) {
        
        // Utiliza o modelo do arquivo criado em HTML
        $documento = public_path('/docs/cg/' . 'cg_cond_especiais_alt.html');
        // Renomeia o arquivo com _ id do exportador
        $docGerado = public_path('/docs/cg/' . 'cg_cond_especiais_alt' .
                $exportador->ID_USUARIO . '.html');

        // Abre o arquivo em modo de leitura
        $fp = fopen($documento, "r");
        // le o arquivo
        $output = fread($fp, filesize($documento)); // armazena na variavel
        //output o conteudo do arquivo
        fclose($fp); // fecha o arquivo
        // Substitui as tags pelas variveis
        
        $output = str_replace("**RAZAOSOCIAL**", utf8_decode($exportador->NM_USUARIO), $output); // substitui o
        //<EXPORTADOR> pela razao social do Exportador
        $output = str_replace("**CIDADE**", utf8_decode($exportador->DE_CIDADE), $output); // substitui o <ID_USUARIO> pelo nome do DE_CIDADE
        $output = str_replace("**ESTADO**", utf8_decode($exportador->CD_UF), $output); // substitui o <ID_USUARIO> pelo nome do CD_UF
        $output = str_replace("**ENDERECO**", utf8_decode($exportador->DE_ENDER), $output); // substitui o <ID_USUARIO> pelo nome do DE_ENDER
        $output = str_replace("**CNPJ**", utf8_decode($exportador->NU_CNPJ), $output); // substitui o <ID_USUARIO> pelo nome do DE_ENDER

        $arquivoSalvo = fopen($docGerado, "w"); // abre a copia renomeada do arquivo e substitui os textos pelos que vieram do banco de dados;
        fwrite($arquivoSalvo, $output, strlen($output)); // salva as alteracoes
        fclose($arquivoSalvo); // fecha o arquivo

        $nomeArquivo = "cg_cond_especiais_alt_" . $exportador->ID_USUARIO . ".pdf"; // cria o nome e extenção do arquivo a ser baixado
        
        return PDF::loadFile($docGerado)->download($nomeArquivo); // carrega o arquivo e disponibiliza por donwload
        
        }else{
           return back()->with('error','Ocorreu um erro ao salvar a notificação, tente novamente!');
        }
        
        
    }

}
