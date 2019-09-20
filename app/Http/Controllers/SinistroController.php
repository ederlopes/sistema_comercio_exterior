<?php
namespace App\Http\Controllers;

use App\MotivoRevogacaoSinistro;
use App\PagamentoEmAtrasoSinistro;
use App\ValorRecuperadoSinistro;
use App\RecuperacaoSinistro;
use App\RegulacaoSinistro;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\User;
use App\MotivoCancelamentoDas;
use App\FatorGeradorSinistro;
use App\Operacao;
use App\ImportadoresModel;
use App\MpmeSinistro;
use Illuminate\Support\Facades\Auth;
use App\MpmeProposta;
use Illuminate\Support\Facades\Validator;
use App\Repositories\MpmeSinistroRepository;
use App\MpmeEmbarque;

/* Explicações sobre as funções */
/*
 *
 * Funcao find() utilizada para buscar no banco de dados apenas por um registro, exemplo: where CampoID = VarialComID
 *
 * Funcao all() Utilizada para trazer todos os resultados do banco de dados, exemplo: select * from tabela
 *
 * Funcao where() Diferente do find() o whare() procura no banco por outros campos diferente do id possibilitando assim
 * que eu possa especificar qual campo quero retornar, exemplo: select * from tabela where campo = variavelCampo
 *
 *
 *
 */
class SinistroController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {   
        $exportador = User::where('TP_USUARIO','C')->where('FL_ATIVO',1)->get();

        $sinistros = MpmeSinistro::with('Operacao','Status','Embarque')->get();

        return view('abgf.sinistro.index_sinistro',compact('exportador','sinistros'));
    }

    /*
     * Função chamada ao acessar a rota /mpme/idMPME
     * acesso public
     * paramentro $idMPME
     *
     */


    public function consultar(Request $request)
    {
       
        return view('sinistro', compact('exportador'));
    }



    public function consultampme(Request $request)
    {   

       $exportador = User::where('TP_USUARIO','C')->where('FL_ATIVO',1)->get(); 

       $id_exportador = $request->ID_USUARIO;
       $proposta = MpmeProposta::where('ID_OPER',$request->operacao)->get();
       $sinistros = MpmeSinistro::with('Operacao','Status','Embarque')->get();

       return view('abgf.sinistro.index_sinistro', compact('proposta','exportador','id_exportador','sinistros'));
       
    }

    /*
     * Função chamada ao acessar a operação rota /cadastrar/sinistro/idOperacao
     * acesso public
     * paramentro $idOperacao
     * $operacao: recebe o resultado da busca pela operação na tabela de MPME_IMPORTADORES
     * $motivoCancelamento: recebe todos os motivos salvos na tabela de motivo cancelamento das para assim ser exibido em um combo box na tela
     * $fatoGeradorSinistro: recebe todos os fato geradores salvos na tabela fato gerador para assim ser exibido em um combo box na tala
     * $motivoRevogacao : recebe os motivos salvos no banco para assim ser exibido na tela
     * $regulacaoSinistro : busca no banco todas as regulacao ja salvas para assim exibir na tela para usuarios
     * $sinistro retorna apenas 1 ocorrencia do sinistro salvo
     */
    public function cadastrarsinistro(Request $request)
    {
        $operacao = ImportadoresModel::where('ID_OPER',$request->operacao)->with('usuario')->first();
        $motivoCancelamento = MotivoCancelamentoDas::all();
        $fatoGeradorSinistro = FatorGeradorSinistro::all();

        $motivoRevogacao = MotivoRevogacaoSinistro::all();
        $regulacaoSinistro = RegulacaoSinistro::all();
        $embarque = MpmeEmbarque::where('ID_MPME_PROPOSTA',$request->proposta)->first();
        $sinistro = MpmeSinistro::where('ID_PROPOSTA', $request->proposta)->with('Embarque','RetornaPagamentoEmAtraso')->first();

        return view('cadastrarsinistro', compact('operacao', 'motivoCancelamento', 'fatoGeradorSinistro', 'motivoRevogacao', 'regulacaoSinistro', 'sinistro','embarque'));
    }

    /*
     * Função chamada ao acessar a operação rota /downloadcgc/{idmpme}/arquivo/{arquivo}
     * acesso public
     * paramentro $idMPME e $nomeDoArquivo em base64
     * $storagePath: recebe o campinho do diretorio storage concatenado com a pasta /cgc/ e id do mpme, pois ao enviar o arquivo e criado uma pasta com id do mpme
     * $fileName: decodifica a usando base64 o nome do arquivo
     * retorna o arquivo com header de download para assim o usuario baixar o mesmo.
     *
     */
    public function downloadcgc(Request $request)
    {
        $storagePath = storage_path() . '/cgc/' . $request->idmpme;
        $fileName = base64_decode($request->arquivo);

        return \Response::download($storagePath . '/' . $fileName);
    }

    /*
     * Função chamada ao acessar a operação rota /excluircgc/{idmpme}/arquivo/{arquivo}
     * acesso public
     * paramentro $idMPME e $nomeDoArquivo em base64
     * $storagePath: recebe o campinho do diretorio storage concatenado com a pasta /cgc/ e id do mpme, pois ao enviar o arquivo e criado uma pasta com id do mpme
     * $fileName: decodifica a usando base64 o nome do arquivo
     * retorna o sucesso caso tenha sido excluido ou erro caso não consiga excluir.
     *
     */
    public function excluircgc(Request $request)
    {
        $storagePath = storage_path() . '/cgc/' . $request->idmpme;
        $fileName = base64_decode($request->arquivo);

        if (unlink($storagePath . '/' . $fileName)) {
            return response()->json([
                'sucesso' => 1
            ]);
        } else {
            return response()->json([
                'erro' => 1
            ]);
        }
    }

    /*
     * Função chamada ao acessar a operação rota /uploadcgc em metodo post
     * acesso public
     * $file: Recebe o arquivo enviado
     * $userId: Recebe o id do usuario que enviou o arquivo
     * $storagePath: recebe o campinho do diretorio storage concatenado com a pasta /cgc/ e id do mpme, pois ao enviar o arquivo e criado uma pasta com id do mpme
     * $fileName: recebe 'cgc- Id Do usuario que enviou - remove o espaço em branco do arquivo e pega o nome original do arquivo '
     * retorna o sucesso, id do usuario que enviou, nome do arquivo, nome do arquivo criptografado para exibirmos o link para o usuario, diretorio
     *
     */
    public function uploadcgc()
    {
        $file = \Request::file('file');

        $userId = \Request::get('userId');

        $storagePath = storage_path() . '/cgc/' . $userId;

        $fileName = 'cgc-' . $userId . '-' . str_replace(" ", "", $file->getClientOriginalName());

        if ($file->move($storagePath, $fileName)) {

            return response()->json([
                'sucesso' => 1,
                'userId' => $userId,
                'filename' => $fileName,
                'arquivocript' => base64_encode($fileName),
                'diretorio' => $storagePath
            ]);
        } else {

            return response()->json([
                'sucesso' => 0,
                'filename' => $fileName,
                'diretorio' => $storagePath
            ]);
        }
    }

    /*
     * Função chamada ao acessar a operação rota /salvarsinistro em metodo post
     * acesso public
     *
     * $cadSinistro: if inline verificando se existe id do sinistro, caso haja
     * ele faz um find possibilidantando editar o sinistro sem precisar utilizar outra funcao caso contrario ele instancia como novo registro no banco
     *
     * $cadSinistro->ID_OPER : recebe o id de operacao vindo da view
     * $cadSinistro->ID_FINANCIADOR_FK: recebe o funanciado vindo da view
     * $request->ID_REGULACAO_SINISTRO: verifica se o usuario escolheu status de defirir ou indefirir
     * $cadSinistro->ID_MPME_SINISTRO_STATUS: com base no filtro que usuario escolhei no ID_REGULACAO_SINISTRO salva o determinado status
     * $request->DT_PAGAMENTO_INDENIZACAO: verificar se nao esta vazio, caso não esteja definir o status como 8 que é idenizada
     *
     * $request->DT_PREVISTA: verificar se nao esta vazio utilizando tambem a funcao array_filter pois o laravel retorna sempre um array [0]
     * caso nao esteja vazio muda o status pa   ra 9 que é em recuperacao
     *
     *
     */
    public function SalvarSinistro(Request $request, MpmeSinistro $sinistro)
    {

        // Validacao
        $validacao = Validator::make($request->all(), [
            // 'ID_MOTIVO_CANCELAMENTO_DAS' => "nullable|numeric|exists:MOTIVO_CANCELAMENTO_DAS,ID_MOTIVO_CANCELAMENTO_DAS",
            // 'ID_FATO_GERADOR_SINISTRO' => "nullable|numeric|exists:FATO_GERADOR_SINISTRO,ID_FATO_GERADOR_SINISTRO",
            // 'ID_REGULACAO_SINISTRO' => "nullable|numeric|exists:REGULACAO_SINISTRO,ID_REGULACAO_SINISTRO",
            // // 'ID_MOTIVO_REVOGACAO_SINISTRO' => "nullable|numeric|exists:MOTIVO_REVOGACAO_SINISTRO,ID_MOTIVO_REVOGACAO_SINISTRO",
            'DT_DAS' => 'nullable|date_format:"d/m/Y"',
            'DT_CANCELAMENTO_DAS' => 'nullable|date_format:"d/m/Y"',
            'DT_ENVIO_CARTA_COBRANCA' => 'nullable|date_format:"d/m/Y"',
            'DT_CANCELAMENTO_PARECER_TECNICO' => 'nullable|date_format:"d/m/Y"',
            'DT_ENVIO_DS_PI' => 'nullable|date_format:"d/m/Y"',
            'DT_REGULACAO_SINISTRO' => 'nullable|date_format:"d/m/Y"',
            'DT_REVOGACAO_SINISTRO' => 'nullable|date_format:"d/m/Y"',
            'DT_CARACTERIZACAO_SINISTRO' => 'nullable|date_format:"d/m/Y"',
            'DT_ENVIO_COMUNICADO_GESTOR' => 'nullable|date_format:"d/m/Y"',
            'DT_PAGAMENTO_INDENIZACAO' => 'nullable|date_format:"d/m/Y"',
            'DT_ASSINATURA_CONTRATO_RENEGOCIACAO' => 'nullable|date_format:"d/m/Y"',
            'arquivo' => 'nullable|max:2048',
        ]);
       
        if ($validacao->passes()) {

        $cadSinistro = MpmeSinistroRepository::salvarSinistro($request);

        if (! empty(@array_filter($request->DTPGT)) && ! empty(@array_filter($request->VLPGT))) {

            if($request->id_sinistro ?? '' != ""){
                $dellpgtAtraso = PagamentoEmAtrasoSinistro::where('ID_MPME_SINISTRO',$request->id_sinistro)->delete();
            }
            for ($i = 0; $i < @count($request->VLPGT); $i ++) {

                $pgtAtraso = new PagamentoEmAtrasoSinistro();
                $pgtAtraso->ID_MPME_SINISTRO = $cadSinistro->ID_MPME_SINISTRO;
                (! empty(array_filter($request->VLPGT))) ? $pgtAtraso->VA_PAGAMENTO_EM_ATRASO_SINISTRO = $this->Valor($request->VLPGT[$i]) : '';
                (! empty(array_filter($request->DTPGT))) ? $pgtAtraso->DT_PAGAMENTO_EM_ATRASO_SINISTRO = Carbon::createFromFormat('d/m/Y', $request->DTPGT[$i]) : '';
                $pgtAtraso->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                $pgtAtraso->save();
            }
        }

        // Valor Recuperado
        if (! empty(@array_filter($request->DTPGTREC)) && ! empty(@array_filter($request->VLPGTREC))) {

            for ($i = 0; $i < count($request->VLPGTREC); $i ++) {

                (isset($request->ID_VALOR_RECUPERADO_SINISTRO[$i])) ? $vlRecuperadoSinistro = ValorRecuperadoSinistro::find($request->ID_VALOR_RECUPERADO_SINISTRO[$i]) : $vlRecuperadoSinistro = new ValorRecuperadoSinistro();
                $vlRecuperadoSinistro->ID_MPME_SINISTRO = $cadSinistro->ID_MPME_SINISTRO;
                (! empty(@array_filter($request->VLPGTREC))) ? $vlRecuperadoSinistro->VA_VALOR_RECUPERADO_SINISTRO = $this->Valor($request->VLPGTREC[$i]) : '';
                (! empty(@array_filter($request->DTPGTREC))) ? $vlRecuperadoSinistro->DT_VALOR_RECUPERADO_SINISTRO = Carbon::createFromFormat('d/m/Y', $request->DTPGTREC[$i]) : '';
                $vlRecuperadoSinistro->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
                $vlRecuperadoSinistro->save();
            }
        }

        for ($i = 0; $i < @count($request->DT_PREVISTA); $i ++) {

            (isset($request->ID_RECUPERACAO_SINISTRO[$i])) ? $pgtEfetivo = RecuperacaoSinistro::find($request->ID_RECUPERACAO_SINISTRO[$i]) : $pgtEfetivo = new RecuperacaoSinistro();
            (isset($request->ID_RECUPERACAO_SINISTRO[$i])) ? $pgtEfetivo->ID_USUARIO_ALT = Auth::user()->ID_USUARIO : '';
            $pgtEfetivo->ID_MPME_SINISTRO = $cadSinistro->ID_MPME_SINISTRO;
            (! empty(@array_filter($request->DT_PREVISTA))) ? $pgtEfetivo->DT_PREVISTA_RECUPERACAO_SINISTRO = Carbon::createFromFormat('d/m/Y', $request->DT_PREVISTA[$i]) : '';
            (! empty(@array_filter($request->DT_EFETIVA))) ? $pgtEfetivo->DT_EFETIVA_RECUPERACAO_SINISTRO = Carbon::createFromFormat('d/m/Y', $request->DT_EFETIVA[$i]) : '';
            (! empty(@array_filter($request->VL_PRINCIPAL))) ? $pgtEfetivo->VA_PRINCIPAL_RECUPERACAO_SINISTRO = $this->Valor($request->VL_PRINCIPAL[$i]) : null;
            (! empty(@array_filter($request->JUROS))) ? $pgtEfetivo->VA_JUROS_RECUPERACAO_SINISTRO = $this->Valor($request->JUROS[$i]) : null;
            (! empty(@array_filter($request->VLPAGO))) ? $pgtEfetivo->VA_PAGO_RECUPERACAO_SINISTRO = $this->Valor($request->VLPAGO[$i]) : null;
            (! empty(@array_filter($request->OBS))) ? $pgtEfetivo->DE_OBSERVACAO_RECUPERACAO_SINISTRO = $request->OBS[$i] : null;
            $pgtEfetivo->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
            $pgtEfetivo->save();
        }
        }else{
            return back()->withErrors($validacao);
        }

        return back()->with('success', 'Sinistro cadastrado com sucesso!');
    }

    public function motivocancelamento(Request $request, MotivoCancelamentoDas $motivoCancelamentoDas)
    {
        $mtc = $motivoCancelamentoDas;

        $mtc->NO_MOTIVO_CANCELAMENTO_DAS = $request->motivo;
        $mtc->ABV_MOTIVO_CANCELAMENTO_DAS = $request->abvMotivo;
        $mtc->IN_REGISTRO_ATIVO = 'S';
        $mtc->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
        if ($mtc->save()) {
            return response()->json([
                'sucesso' => 1,
                'id_motivo' => $mtc->ID_MOTIVO_CANCELAMENTO_DAS,
                'motivo' => $mtc->NO_MOTIVO_CANCELAMENTO_DAS
            ]);
        } else {
            return response()->json([
                'sucesso' => 0
            ]);
        }
    }

    // Funcao para validar moeda
    function Valor($valor)
    {
        $verificaPonto = ".";
        if (strpos("[" . $valor . "]", "$verificaPonto")) :
            $valor = str_replace('.', '', $valor);
            $valor = str_replace(',', '.', $valor);
        else :
            $valor = str_replace(',', '.', $valor);
        endif;

        return $valor;
    }
}
