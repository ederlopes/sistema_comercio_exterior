<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Repositories\MpmeArquivoRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Auth;
use Hash;
use Mail;
use Storage;
use Session;
use PDF;
use URL;

class ValidarController extends Controller
{

    public function index()
    {
        return view('validar.validar');
    }

    public function visulizar_arquivo(Request $request, MpmeArquivoRepository $mpmeArquivoRepository)
    {
        if ($request->id_mpme_arquivo > 0) {
            $mpmeArquivo = $mpmeArquivoRepository->getArquivo($request->id_mpme_arquivo);

            $hash_arquivo = str_random(32);
            Session::flash('hash_arquivo', $mpmeArquivo->ID_MPME_ARQUIVO);

            return view('arquivos.arquivos-visualizar', compact('hash_arquivo', 'mpmeArquivo'));
        }
    }

    public function render_arquivo(Request $request, MpmeArquivoRepository $mpmeArquivoRepository)
    {
        if ($request->hash_arquivo != '') {
            $hash_arquivo = Session::get('hash_arquivo');

            Session::flash('hash_arquivo', $hash_arquivo);

            $mpmeArquivo = $mpmeArquivoRepository->getArquivo($hash_arquivo);

            $arquivo = Storage::get('/public/' . $mpmeArquivo->NO_DIRETORIO . '/' . $mpmeArquivo->NO_ARQUIVO);

            $mimes = array(
                'pdf' => 'application/pdf',
                'jpg' => 'image/jpeg',
                'png' => 'image/png',
                'bmp' => 'image/bmp',
                'gif' => 'image/gif'
            );

            return response($arquivo, 200)->header('Content-Type', $mimes[$mpmeArquivo->NO_EXTENSAO])->header('Content-Disposition', 'inline; filename="' . $mpmeArquivo->NO_ARQUIVO . '"');
        }
    }
}
