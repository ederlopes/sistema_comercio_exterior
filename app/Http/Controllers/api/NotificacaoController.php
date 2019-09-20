<?php
namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Notificacoes;

class NotificacaoController extends Controller
{

    public function index()
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
            ->get();

        return response()->json($notificacaoNovoExportador);
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
        return view('notificacaoValidacaoExportador', compact('totalNotificacao', 'notificacaoNovoExportador', 'menu'));
    }
}
