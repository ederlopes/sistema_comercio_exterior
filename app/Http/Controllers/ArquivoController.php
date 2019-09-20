<?php
namespace App\Http\Controllers;

use App\MpmeArquivo;
use App\MpmeTipoArquivo;
use Auth;
use Barryvdh\DomPDF\PDF;
use Carbon\Carbon;
use DOMDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ArquivoController extends Controller
{

    public function novo(Request $request)
    {
        // Argumentos para o retorno da view
        $compact_args = [
            'request' => $request,
        ];

        return view('arquivos.arquivos-novo', $compact_args);
    }

    public function inserir(Request $request, MpmeTipoArquivo $mpmeTipoArquivo)
    {
        $erro_msg = '';
        /*
         * $regras = [
         * 'no_arquivo' => 'required|file',
         * 'token' => 'required|alpha_num',
         * 'id_mpme_tipo_arquivo' => 'required|numeric',
         * 'id_flex' => 'nullable|numeric',
         * 'pasta' => 'nullable|string',
         * 'container' => 'required|string',
         * 'index_arquivos' => 'required|numeric',
         * 'in_ass_digital' => 'nullable|string|in:N,S,O'
         * ];
         *
         * $mensagens = [
         * 'no_arquivo.required' => 'O arquivo é obrigatório.',
         * ];
         *
         *
         *
         * Validator::make($request->all(), $regras, $mensagens)->validate();
         */
        $tipo_arquivo = $mpmeTipoArquivo->find($request->id_mpme_tipo_arquivo);

        if (!$request->session()->has('datapasta_' . $request->token)) {
            $request->session()->put('datapasta_' . $request->token, Carbon::now()->format('d-m-Y'));
        }
        $data_pasta = $request->session()->get('datapasta_' . $request->token);

        $destino = '/temp/' . $request->pasta . '/' . $data_pasta . '/' . $request->token . '/' . $request->id_mpme_tipo_arquivo;
        $arquivo = $request->no_arquivo;
        $nome_arquivo = strtolower(remove_caracteres($arquivo->getClientOriginalName()));
        $nome_arquivos_originais = array();

        if ($arquivo->move(storage_path('app' . $destino), $nome_arquivo)) {
            $ext_arquivo = extensao_arquivo($nome_arquivo);
            switch ($ext_arquivo) {
                case 'png':
                case 'jpg':
                case 'bmp':
                case 'tif':
                    array_push($nome_arquivos_originais, $nome_arquivo);

                    $imagem = Image::make(storage_path('app' . $destino . '/' . $nome_arquivo));
                    $nome_arquivo = str_replace('.' . $ext_arquivo, '.pdf', $nome_arquivo);
                    $pdf = PDF::loadView('pdf.imagem', compact('imagem'));
                    $pdf->save(storage_path('app' . $destino . '/' . $nome_arquivo));
                    break;
                case 'doc':
                case 'docx':
                case 'xls':
                case 'xlsx':
                case 'ppt':
                case 'pptx':
                case 'pps':
                case 'ppsx':
                    array_push($nome_arquivos_originais, $nome_arquivo);

                    if (env('LIBOFFICE_ENV') == 'local') {
                        $processo = new Process('"C:\Program Files\LibreOffice 4\program\soffice.exe" --headless --invisible --convert-to pdf:writer_pdf_Export --outdir "' . storage_path('app' . $destino) . '/" "' . storage_path('app' . $destino . '/' . $nome_arquivo) . '"');
                    } else {
                        $processo = new Process('sudo libreoffice --headless --invisible --convert-to pdf:writer_pdf_Export --outdir "' . storage_path('app' . $destino) . '/" "' . storage_path('app' . $destino . '/' . $nome_arquivo) . '"');
                    }

                    $processo->run();
                    $nome_arquivo = str_replace('.' . $ext_arquivo, '.pdf', $nome_arquivo);
                    break;
                case 'txt':
                    array_push($nome_arquivos_originais, $nome_arquivo);

                    $conteudo_arquivo = Storage::get($destino . '/' . $nome_arquivo);
                    $nome_arquivo = str_replace('.txt', '.pdf', $nome_arquivo);
                    $pdf = PDF::loadHTML(nl2br(utf8_encode($conteudo_arquivo)));
                    $pdf->save(storage_path('app' . $destino . '/' . $nome_arquivo));
                    break;
                case 'xml':
                    $xsd_file = '';

                    if ($xsd_file != '') {
                        $DOM = new DOMDocument();
                        $DOM->load(storage_path('app' . $destino . '/' . $nome_arquivo));

                        try {
                            $schema_validate = $DOM->schemaValidate($xsd_file);
                        } catch (\Exception $e) {
                            $erro_msg = 'O arquivo XML é inválido. Entre em contato com o administrador do sistema para solicitar o modelo de XML.';
                            if (env('APP_DEBUG') == true) {
                                $erro_msg .= '<br /><br />' . $e->getMessage();
                            }
                            break;
                        }
                    }
                    break;
                default:
                    break;
            }

            if ($request->session()->has('datapasta_' . $request->token)) {
                $arquivos = $request->session()->get('datapasta_' . $request->token);
            } else {
                $arquivos = array();
            }

            if (isset($request->in_ass_digital)) {
                $in_ass_digital = $request->in_ass_digital;
            } else {
                $in_ass_digital = $tipo_arquivo->in_ass_digital;
            }

            $arquivo = [
                'no_arquivo' => $nome_arquivo,
                'no_arquivos_originais' => $nome_arquivos_originais,
                'no_local_arquivo' => $destino,
                'in_ass_digital' => $in_ass_digital,
                'id_mpme_tipo_arquivo' => $request->id_mpme_tipo_arquivo,
                'in_assinado' => 'N',
                'dt_assinado' => null,
                'id_usuario_certificado' => null,
                'no_arquivo_p7s' => null,
                'id_flex' => $request->id_flex,
                'id_oper' => $request->id_oper,
                'container' => $request->container,
            ];

            // $arquivos[$request->index_arquivos] = $arquivo;
            $arquivos = $arquivo;

            $request->session()->put('datapasta_' . $request->token, $arquivos);
        } else {
            $erro_msg = 'Não foi possível inserir o arquivo na pasta de destino.';
        }

        if ($erro_msg != '') {
            DB::rollback();
            $response_json = [
                'message' => $erro_msg,
            ];
            return response()->json($response_json, 400);
        } else {
            DB::commit();
            $response_json = [
                'message' => 'O arquivo foi inserido com sucesso.',
                'arquivo' => $arquivo,
                'token' => $request->token,
            ];
            return response()->json($response_json, 200);
        }
    }

    public function copiar_arquivo($origem_arquivo, $destino_arquivo)
    {
        if (Storage::exists($origem_arquivo)) {
            if (Storage::exists($destino_arquivo)) {
                Storage::delete($destino_arquivo);
            }
            if (Storage::copy($origem_arquivo, $destino_arquivo)) {
                return true;
            } else {
                throw new Exception('Erro ao copiar o arquivo original para o destino.');
            }
        } else {
            throw new Exception('O arquivo original não foi encontrado na origem.');
        }
        return false;
    }

    public function insere_arquivo($arquivo, $destino = '')
    {
        Storage::makeDirectory('/public/' . $destino);

        $origem_arquivo = $arquivo['no_local_arquivo'] . '/' . $arquivo['no_arquivo'];
        $no_arquivo = (isset($arquivo['no_arquivo_alterado']) ? $arquivo['no_arquivo_alterado'] : $arquivo['no_arquivo']);
        $destino_arquivo = '/public/' . $destino . '/' . $no_arquivo;

        $args_novo_arquivo = [
            'id_mpme_tipo_arquivo' => $arquivo['id_mpme_tipo_arquivo'],
            'id_flex' => $arquivo['id_flex'],
            'id_oper' => $arquivo['id_oper'],
            'no_arquivo' => $no_arquivo,
            'no_local_arquivo' => $destino,
            'no_extensao' => extensao_arquivo($arquivo['no_arquivo']),
        ];

        // transformando em objeto
        $dados = (object) $args_novo_arquivo;
        $novo_arquivo = new MpmeArquivo();

        $novo_arquivo->ID_MPME_TIPO_ARQUIVO = $dados->id_mpme_tipo_arquivo;
        $novo_arquivo->ID_OPER = ($dados->id_oper != "") ? $dados->id_oper : null;
        $novo_arquivo->ID_FLEX = ($dados->id_flex != "") ? $dados->id_flex : null;
        $novo_arquivo->NO_DIRETORIO = $dados->no_local_arquivo;
        $novo_arquivo->NO_EXTENSAO = $dados->no_extensao;
        $novo_arquivo->NO_ARQUIVO = $dados->no_arquivo;
        $novo_arquivo->DT_CADASTRO = Carbon::now();
        $novo_arquivo->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        if ($novo_arquivo->save()) {
            $this->copiar_arquivo($origem_arquivo, $destino_arquivo);
            return $novo_arquivo;
        } else {
            return false;
        }
    }

    public function insere_arquivo_sem_sessao($arquivo, $destino = '', $image)
    {
        Storage::makeDirectory('/public/' . $destino);

        $origem_arquivo = $arquivo['no_local_arquivo'] . '/' . $arquivo['no_arquivo'];
        $no_arquivo = (isset($arquivo['no_arquivo_alterado']) ? $arquivo['no_arquivo_alterado'] : $arquivo['no_arquivo']);
        $destino_arquivo = '/public/' . $destino . '/' . $no_arquivo;

        $args_novo_arquivo = [
            'id_mpme_tipo_arquivo' => $arquivo['id_mpme_tipo_arquivo'],
            'id_flex' => $arquivo['id_flex'],
            'id_oper' => $arquivo['id_oper'],
            'no_arquivo' => $no_arquivo,
            'no_local_arquivo' => $destino,
            'no_extensao' => extensao_arquivo($arquivo['no_arquivo']),
        ];

        // transformando em objeto
        $dados = (object) $args_novo_arquivo;
        $novo_arquivo = new MpmeArquivo();

        $novo_arquivo->ID_MPME_TIPO_ARQUIVO = $dados->id_mpme_tipo_arquivo;
        $novo_arquivo->ID_OPER = ($dados->id_oper != "") ? $dados->id_oper : null;
        $novo_arquivo->ID_FLEX = ($dados->id_flex != "") ? $dados->id_flex : null;
        $novo_arquivo->NO_DIRETORIO = $dados->no_local_arquivo;
        $novo_arquivo->NO_EXTENSAO = $dados->no_extensao;
        $novo_arquivo->NO_ARQUIVO = $dados->no_arquivo;
        $novo_arquivo->DT_CADASTRO = Carbon::now();
        $novo_arquivo->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        if ($novo_arquivo->save()) {
            $image->move(storage_path('/app/public/' . $destino), $arquivo['no_arquivo']);
            //$this->copiar_arquivo($origem_arquivo, $destino_arquivo);
            return $novo_arquivo;
        } else {
            return false;
        }
    }

    public static function gerar_arquivo_pdf($arquivo, $modeloRelacional, $objPDF)
    {
        if (!Storage::makeDirectory('/public' . $arquivo['destino_arquivo'])) {
            throw new Exception('Erro ao criar pasta de destino.');
        }

        // criando arquivo fisicamente
        if (!$objPDF->save(storage_path('app/public' . $arquivo['destino_arquivo'] . '/' . $arquivo['no_arquivo']))) {
            throw new Exception('Erro ao salvar arquivo na pasta de destino.');
        }

        $args_novo_arquivo = [
            'id_grupo_produto' => $arquivo['id_grupo_produto'],
            'id_tipo_arquivo_grupo_produto' => $arquivo['id_tipo_arquivo_grupo_produto'],
            'no_arquivo' => $arquivo['no_arquivo'],
            'no_local_arquivo' => $arquivo['destino_arquivo'],
            'no_extensao' => extensao_arquivo($arquivo['no_arquivo']),
            'in_ass_digital' => $arquivo['in_assinado'],
            'nu_tamanho_kb' => 0, // Storage::size($arquivo['destino_arquivo'].'/'.$arquivo['no_arquivo']),
            'no_hash' => null, // hash_file('md5',storage_path('app/public/'.$arquivo['no_local_arquivo'].'/'.$arquivo['no_arquivo'])),
            'dt_ass_digital' => $arquivo['dt_assinado'],
            'id_usuario_certificado' => $arquivo['id_usuario_certificado'],
            'no_arquivo_p7s' => (isset($arquivo['no_arquivo_p7s']) ? $arquivo['no_arquivo_p7s'] : null),
            'no_hash_p7s' => (isset($arquivo['no_hash_p7s']) ? hash_file('md5', storage_path('app/public/' . $arquivo['no_hash_p7s'] . '/' . $arquivo['no_hash_p7s'])) : null),
        ];

        $novo_arquivo_grupo_produto = new arquivo_grupo_produto();

        if (!$novo_arquivo_grupo_produto->insere($args_novo_arquivo)) {
            throw new Exception('Erro ao salvar arquivo.');
        }

        if ($novo_arquivo_grupo_produto) {
            $modeloRelacional->operacao_veiculo->arquivos_grupo()->attach($novo_arquivo_grupo_produto);
        }

        return $novo_arquivo_grupo_produto;
    }

    public static function criar_diretorio($destino_arquivo)
    {
        Storage::makeDirectory($destino_arquivo);
    }

    public function download_arquivo(Request $request)
    {
        $arquivo_grupo_produto = MpmeArquivo::find($request->id_mpme_arquivo);
        if ($arquivo_grupo_produto) {
            $no_arquivo = $arquivo_grupo_produto->NO_ARQUIVO;
            $arquivo = Storage::get('/public/' . $arquivo_grupo_produto->NO_DIRETORIO . '/' . $no_arquivo);
            return response($arquivo, 200)->header('Content-Disposition', 'attachment; filename="' . $no_arquivo . '"');

        } else {
            $response_json = [
                'message' => 'O arquivo não foi encontrado.',
            ];
            return response()->json($response_json, 400);
        }
    }

}
