<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use File;
use Auth;
use App\MpmeArquivo;

class AjaxUploadController extends Controller
{

    public function uploadComprovantePgRelatorio(Request $request)
    {
       
        $mensagemPersonalizada = [
            'required' => 'O campo de upload não pode ser vazio',
            'mimes' => 'Só é permitido o envio de arquivo .PDF',
            'max' => 'O tamanho maximo do PDF é de 2MB'
        ];
        $validacao = Validator::make($request->all(), [
            'select_comprovantepg_relatorio' => 'required|mimes:pdf'
        ], $mensagemPersonalizada);

        if ($validacao->passes()) {

            $pasta = public_path('/uploads/abgf/exportador/limite/comprovante_pg_relatorio/' . $request->ID_OPER . '/');

            if (! File::exists($pasta)) {
                File::makeDirectory($pasta, 0777, true, true);
            }

            $arquivo = $request->file('select_comprovantepg_relatorio');
            $novo_nome = $request->ID_OPER . '.' . $arquivo->getClientOriginalExtension();
            $arquivo->move(public_path('/uploads/abgf/exportador/limite/comprovante_pg_relatorio/' . $request->ID_OPER), $novo_nome);

            $uploadDreBanco = new MpmeArquivo();
            $uploadDreBanco->ID_MPME_TIPO_ARQUIVO = 4; // relatorio nascional;
            $uploadDreBanco->NO_DIRETORIO = $pasta;
            $uploadDreBanco->NO_ARQUIVO = $novo_nome;
            $uploadDreBanco->DT_CADASTRO = date('Y-m-d');
            $uploadDreBanco->ID_USUARIO_CAD = Auth::User()->ID_USUARIO;
            $uploadDreBanco->NO_EXTENSAO = $arquivo->getClientOriginalExtension();
            $uploadDreBanco->save();

            return response()->json([
                'message' => 'Upload realizado com sucesso!',
                'upload_comprovantepg_relatorio_realizado' => "<p><a href=\"/uploads/abgf/exportador/limite/comprovante_pg_relatorio/$request->ID_OPER/$novo_nome\" target=\"_blank\">Clique aqui</a> para visualizar o arquivo</p>",
                'class_name' => 'alert alert-info'
            ]);
        } else {
            return response()->json([
                'message' => $validacao->errors()
                    ->all(),
                'upload_comprovantepg_relatorio_realizado' => '',
                'class_name' => 'alert alert-danger'
            ]);
        }
    }

    public function uploadCalculoLimiteCredito(Request $request)
    {

        $mensagemPersonalizada = [
            'required' => 'O campo de upload não pode ser vazio',
            'mimes' => 'Só é permitido o envio de arquivo .PDF',
            'max' => 'O tamanho maximo do PDF é de 2MB'
        ];
        $validacao = Validator::make($request->all(), [
            'select_upload_calculo_limite_credito' => 'required|mimes:pdf'
        ], $mensagemPersonalizada);

        if ($validacao->passes()) {

            $pasta = public_path('/uploads/abgf/exportador/limite/upload_calculo_limite_credito/' . $request->ID_OPER . '/');

            if (! File::exists($pasta)) {
                File::makeDirectory($pasta, 0777, true, true);
            }

            $arquivo = $request->file('select_upload_calculo_limite_credito');
            $novo_nome = $request->ID_OPER . '.' . $arquivo->getClientOriginalExtension();
            $arquivo->move(public_path('/uploads/abgf/exportador/limite/upload_calculo_limite_credito/' . $request->ID_OPER), $novo_nome);

            $uploadDreBanco = new MpmeArquivo();
            $uploadDreBanco->ID_MPME_TIPO_ARQUIVO = 22; // cálculo do Limite de Crédito
            $uploadDreBanco->NO_DIRETORIO = $pasta;
            $uploadDreBanco->NO_ARQUIVO = $novo_nome;
            $uploadDreBanco->DT_CADASTRO = date('Y-m-d');
            $uploadDreBanco->ID_USUARIO_CAD = Auth::User()->ID_USUARIO;
            $uploadDreBanco->NO_EXTENSAO = $arquivo->getClientOriginalExtension();
            $uploadDreBanco->save();

            return response()->json([
                'message' => 'Upload realizado com sucesso!',
                'upload_upload_calculo_limite_credito_realizado' => "<p><a href=\"/uploads/abgf/exportador/limite/upload_calculo_limite_credito/$request->ID_OPER/$novo_nome\" target=\"_blank\">Clique aqui</a> para visualizar o arquivo</p>",
                'class_name' => 'alert alert-info'
            ]);
        } else {
            return response()->json([
                'message' => $validacao->errors()
                    ->all(),
                'upload_upload_calculo_limite_credito_realizado' => '',
                'class_name' => 'alert alert-danger'
            ]);
        }
    }

    public function uploadRelatorioInternacional(Request $request)
    {
        $mensagemPersonalizada = [
            'required' => 'O campo de upload não pode ser vazio',
            'mimes' => 'Só é permitido o envio de arquivo .PDF',
            'max' => 'O tamanho maximo do PDF é de 2MB'
        ];
        $validacao = Validator::make($request->all(), [
            'select_relatorio_internacional' => 'required|mimes:pdf,png'
        ], $mensagemPersonalizada);

        if ($validacao->passes()) {

            $pasta = public_path('/uploads/abgf/exportador/limite/relatorio_internacional/' . $request->ID_OPER . '/');

            if (! File::exists($pasta)) {
                File::makeDirectory($pasta, 0777, true, true);
            }

            $arquivo = $request->file('select_relatorio_internacional');
            $novo_nome = $request->ID_OPER . '.' . $arquivo->getClientOriginalExtension();
            $arquivo->move(public_path('/uploads/abgf/exportador/limite/relatorio_internacional/' . $request->ID_OPER), $novo_nome);

            $uploadDreBanco = new MpmeArquivo();
            $uploadDreBanco->ID_MPME_TIPO_ARQUIVO = 7; // relatorio internacional;
            $uploadDreBanco->NO_DIRETORIO = $pasta;
            $uploadDreBanco->NO_ARQUIVO = $novo_nome;
            $uploadDreBanco->DT_CADASTRO = date('Y-m-d');
            $uploadDreBanco->ID_USUARIO_CAD = Auth::User()->ID_USUARIO;
            $uploadDreBanco->NO_EXTENSAO = $arquivo->getClientOriginalExtension();
            $uploadDreBanco->save();

            return response()->json([
                'message' => 'Upload realizado com sucesso!',
                'upload_relatorio_internacional_realizado' => "<p><a href=\"/uploads/abgf/exportador/limite/relatorio_internacional/$request->ID_OPER/$novo_nome\" target=\"_blank\">Clique aqui</a> para visualizar o arquivo</p>",
                'class_name' => 'alert alert-info'
            ]);
        } else {
            return response()->json([
                'message' => $validacao->errors()
                    ->all(),
                'upload_relatorio_internacional_realizado' => '',
                'class_name' => 'alert alert-danger'
            ]);
        }
    }

    public function UploadAntiCorrupcao(Request $request)
    {
        $mensagemPersonalizada = [
            'required' => 'O campo de upload não pode ser vazio',
            'mimes' => 'Só é permitido o envio de arquivo .PDF',
            'max' => 'O tamanho maximo do PDF é de 2MB'
        ];
        $validacao = Validator::make($request->all(), [
            'select_doc_anticorrupcao' => 'required|mimes:pdf,png'
        ], $mensagemPersonalizada);

        if ($validacao->passes()) {

            $pasta = public_path('/docs/anti-corrupcao/' . Auth::User()->ID_USUARIO . '/');

            if (! File::exists($pasta)) {
                File::makeDirectory($pasta, 0777, true, true);
            }

            $arquivo = $request->file('select_doc_anticorrupcao');
            $novo_nome = Auth::User()->ID_USUARIO.'.' . $arquivo->getClientOriginalExtension();
            $arquivo->move(public_path('/docs/anti-corrupcao/' . Auth::User()->ID_USUARIO), $novo_nome);
            return response()->json([
                'message' => 'Upload realizado com sucesso!',
                'upload_anticorrupcao_realizado' => "<p><a href=\"/docs/anti-corrupcao/" . Auth::User()->ID_USUARIO . "/$novo_nome\" target=\"_blank\">Clique aqui</a> para visualizar o arquivo</p>",
                'class_name' => 'alert alert-info'
            ]);
        } else {
            return response()->json([
                'message' => $validacao->errors()
                    ->all(),
                'upload_anticorrupcao_realizado' => '',
                'class_name' => 'alert alert-danger'
            ]);
        }
    }
}
