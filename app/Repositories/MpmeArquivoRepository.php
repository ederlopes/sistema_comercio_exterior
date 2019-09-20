<?php
namespace App\Repositories;

use App\MpmeArquivo;
use App\MpmeTipoArquivo;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Auth;

class MpmeArquivoRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(MpmeArquivo::class);
    }

    public function inserirRegistro($dados)
    {

        $this->ID_MPME_TIPO_ARQUIVO = $dados->id_mpme_tipo_arquivo;
        $this->ID_OPER = ($dados->id_oper != "") ? $dados->id_oper : null;
        $this->ID_FLEX = ($dados->id_flex != "") ? $dados->id_flex : null;
        $this->NO_DIRETORIO = $dados->no_local_arquivo;
        $this->NO_EXTENSAO = $dados->no_extensao;
        $this->NO_ARQUIVO = $dados->no_arquivo;
        $this->DT_CADASTRO = Carbon::now();
        $this->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

        if ($this->save()) {
            return true;
        } else {
            return false;
        }
    }

    public function isArquivo($id_oper, $id_mpme_tipo_arquivo, $id_flex = null)
    {
        $selecionaArquivo = MpmeArquivo::where("ID_OPER", '=', $id_oper)
            ->where('ID_MPME_TIPO_ARQUIVO', '=', $id_mpme_tipo_arquivo);

        if ($id_flex != null) {
            $selecionaArquivo = $selecionaArquivo->where("ID_FLEX", '=', $id_flex);
        }

        return $selecionaArquivo->first();
    }

    public function getArquivo($id_mpme_arquivo)
    {
        return MpmeArquivo::where('ID_MPME_ARQUIVO', '=', $id_mpme_arquivo)->first();
    }

    public static function UploadEInsere($request)
    {
        DB::beginTransaction();

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
        $tipo_arquivo = MpmeTipoArquivo::find($request->id_mpme_tipo_arquivo);

        $destino = $request->pasta . '/' . $request->id_mpme_tipo_arquivo;
        $arquivo = $request->no_arquivo;
        $nome_arquivo = strtolower(remove_caracteres($arquivo->getClientOriginalName()));
        $nome_arquivos_originais = array();

        if ($arquivo->move(storage_path('app/public/' . $destino), $nome_arquivo)) {
            $ext_arquivo = extensao_arquivo($nome_arquivo);
            switch ($ext_arquivo) {
                case 'png':
                case 'jpg':
                case 'bmp':
                case 'tif':
                    array_push($nome_arquivos_originais, $nome_arquivo);

                    $imagem = Image::make(storage_path('app/public/' . $destino . '/' . $nome_arquivo));
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

            $arquivos = array();

            if (isset($request->atualizar) && $request->atualizar == 1) {

                $colunas = array(
                    'ID_MPME_TIPO_ARQUIVO' => $request->id_mpme_tipo_arquivo,
                    'NO_DIRETORIO' => $destino,
                    'NO_ARQUIVO' => $nome_arquivo,
                    'DT_CADASTRO' => Carbon::now(),
                    'ID_USUARIO_CAD' => $request->ID_USUARIO,
                    'NO_EXTENSAO' => $arquivo->getClientOriginalExtension(),
                );


                DB::table('MPME_ARQUIVO')->where('ID_MPME_ARQUIVO', $request->ID_MPME_ARQUIVO)->update($colunas);
            } else {
                $colunas = array(
                    array(
                        'ID_MPME_TIPO_ARQUIVO' => $request->id_mpme_tipo_arquivo,
                        'NO_DIRETORIO' => $destino,
                        'NO_ARQUIVO' => $nome_arquivo,
                        'DT_CADASTRO' => Carbon::now(),
                        'ID_USUARIO_CAD' => $request->ID_USUARIO,
                        'NO_EXTENSAO' => $arquivo->getClientOriginalExtension(),
                    ),

                );


                DB::table('MPME_ARQUIVO')->insert($colunas);
            }
        } else {
            $erro_msg = 'Não foi possível inserir o arquivo na pasta de destino.';
        }

        if ($erro_msg != '') {
            DB::rollback();
            return false;
        } else {

            DB::commit();
            return true;
        }
    }
}
