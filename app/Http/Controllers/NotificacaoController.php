<?php
namespace App\Http\Controllers;

use App\Repositories\MpmeNotificacaoUsuarioRepository;
use App\User;
use Illuminate\Http\Request;
use App\Notificacoes;
use Illuminate\Support\Facades\DB;

class NotificacaoController extends Controller
{

    public function index()
    {
        $usuarios = User::where('FL_ATIVO', '=', 0)->get([
            'ID_USUARIO'
        ]);

        $totalNotificacao = Notificacoes::where('MPME_NOTIFICACAO.IC_ATIVO', '=', 1)->whereNotNull('MPME_NOTIFICACAO.ID_AREA_FK')->count();

        $notificacaoNovoExportador = Notificacoes::query();

        foreach ($usuarios as $usuario) {
            $notificacaoNovoExportador->orWhere('DS_LINK', 'LIKE', '%BASE_ID_USUARIO=' . $usuario['ID_USUARIO']);
        }
        $notificacaoNovoExportador = $notificacaoNovoExportador->where('MPME_NOTIFICACAO.IC_ATIVO', '=', 1)
            ->join('MPME_FINANC', 'MPME_NOTIFICACAO.ID_USUARIO_FK', '=', 'MPME_FINANC.ID_USUARIO')
            ->join('USUARIOS', 'MPME_NOTIFICACAO.ID_USUARIO_FK', '=', 'USUARIOS.ID_USUARIO')
            ->join('MPME_CONFIRMA_DADOS_EXPORTADOR', 'MPME_NOTIFICACAO.ID_USUARIO_FK', '=', 'MPME_CONFIRMA_DADOS_EXPORTADOR.ID_USUARIO_FK')
            ->where('MPME_CONFIRMA_DADOS_EXPORTADOR.IC_STATUS', '!=', 1)
            ->where('MPME_CONFIRMA_DADOS_EXPORTADOR.NU_TELA', '=', 1)
            ->whereNotNull('MPME_NOTIFICACAO.ID_AREA_FK')
            ->where('MPME_NOTIFICACAO.ID_STATUS_NOTIFICACAO_FK', '=', 14)
            ->where('MPME_FINANC.IC_PROPRIO_EXPORTADOR', '!=', 1)
            ->where('USUARIOS.FL_ATIVO', '=', 0)
            ->select([
            DB::raw('MAX(MPME_NOTIFICACAO.ID_NOTIFICACAO) as ID_NOTIFICACAO'),
            DB::raw('MAX(MPME_NOTIFICACAO.DT_NOTIFICACAO) as DATA_NOTIFICACAO'),
            'USUARIOS.ID_USUARIO',
            'USUARIOS.NO_FANTASIA',
            'USUARIOS.NM_USUARIO',
            'USUARIOS.DATA_CADASTRO'
        ])
            ->groupBy([
            'USUARIOS.ID_USUARIO',
            'USUARIOS.NO_FANTASIA',
            'USUARIOS.NM_USUARIO',
            'USUARIOS.DATA_CADASTRO'
        ])
            ->orderBy(DB::raw('MAX(MPME_NOTIFICACAO.DT_NOTIFICACAO)'), ",DESC")
            ->paginate();

        $menu = 1;
        return view('abgf.notificacoes.notificacoes', compact('totalNotificacao', 'notificacaoNovoExportador', 'menu'));
    }

    public function ValidacaoExportador()
    {
        $totalNotificacao = Notificacoes::where('MPME_NOTIFICACAO.IC_ATIVO', '=', 1)->whereNotNull('MPME_NOTIFICACAO.ID_AREA_FK')->count();

        $notificacaoNovoExportador = Notificacoes::where('MPME_NOTIFICACAO.IC_ATIVO', '=', 1)->join('MPME_FINANC', 'MPME_NOTIFICACAO.ID_USUARIO_FK', '=', 'MPME_FINANC.ID_USUARIO')
            ->join('USUARIOS', 'MPME_NOTIFICACAO.ID_USUARIO_FK', '=', 'USUARIOS.ID_USUARIO')
            ->whereNotNull('MPME_NOTIFICACAO.ID_AREA_FK')
            ->whereNotNull('MPME_NOTIFICACAO.DE_NOTIFICACAO')
            ->whereNotNull('MPME_NOTIFICACAO.ID_USUARIO_FK')
            ->where('MPME_NOTIFICACAO.ID_STATUS_NOTIFICACAO_FK', '=', 14)
            ->where('MPME_FINANC.IC_PROPRIO_EXPORTADOR', '!=', 1)
            ->where('USUARIOS.FL_ATIVO', '=', 0)
            ->select([
            'USUARIOS.ID_USUARIO',
            'USUARIOS.NO_FANTASIA',
            'USUARIOS.NM_USUARIO',
            'USUARIOS.DATA_CADASTRO'
        ])
            ->paginate();

        $menu = 2;
        return view('abgf.notificacoes.notificacao_validacao_exportador', compact('totalNotificacao', 'notificacaoNovoExportador', 'menu'));
    }

    public function visualizar_notificacao(Request $request, MpmeNotificacaoUsuarioRepository $mpmeNotificacaoUsuarioRepository)
    {
        if ($mpmeNotificacaoUsuarioRepository->visualizarNotificacao($request)){
            return response()->json(array(
                'status' => 'sucesso',
                'recarrega' => 'true',
                'msg' => 'Registro processado com sucesso!'
            ));
        }else{
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Erro ao processar registro'
            ));
        }

    }
}
