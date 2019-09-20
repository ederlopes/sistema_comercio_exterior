<?php
namespace App\Http\Controllers;

use App\MpmeProposta;
use App\MpmeStatus;
use App\Repositories\MpmeHistoricoDesembolsoRepository;
use App\Repositories\MpmeDesembolsoRepository;
use App\Repositories\MpmePropostaRepository;
use Illuminate\Http\Request;
use DB;
use Auth;

class DesembolsoController extends Controller
{

    public function index(Request $request, MpmeDesembolsoRepository $mpmeDesembolsoRepository, MpmePropostaRepository $propostaRepository)
    {

        $listarDesembolso = $mpmeDesembolsoRepository->listarDesembolso($request->id_proposta);

        if ($propostaRepository->validarPropostaOperacao($request->id_oper, $request->id_proposta) <= 0)
        {
            return response(view('erros.401'), 401);
        }


        $compact_args = [
            'request' => $request,
            'class' => $this,
            'listarDesembolso' => $listarDesembolso,

        ];

        return view('desembolso.index_desembolso', $compact_args);
    }

    public function novo_desembolso(Request $request, MpmeProposta $mpmeProposta, MpmeStatus $mpmeStatus)
    {
        $mpmeStatus   = $mpmeStatus->where('NO_ORIGEM_STATUS', 'DESEMBOLSO')->first();
        $mpmeProposta = $mpmeProposta->find($request->id_proposta);

        $compact_args = [
            'request'       => $request,
            'class'         => $this,
            'mpmeProposta'  => $mpmeProposta,
            'mpmeStatus'  => $mpmeStatus,
        ];


        return view('desembolso.novo_desembolso', $compact_args);
    }

    public function salvar(Request $request, MpmeDesembolsoRepository $mpmeDesembolsoRepository)
    {
        $dados      = (object) $request->all();
        $desembolso = $mpmeDesembolsoRepository->salvar_desembolso($dados);

        if (!$desembolso) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº '
            ));
        }

        $msg = ($request->id_mpme_desembolso != "") ? 'O desembolso foi alterado com sucesso.' : 'O desembolso foi inserido com sucesso.';

        return response()->json(array(
            'status'        => 'sucesso',
            'recarrega'     => 'url',
            'url'           => 'banco/desembolso/' . $request->id_oper.'/' . $request->id_mpme_proposta,
            'id_mpme_proposta' => $request->id_mpme_proposta,
            'msg' => $msg
        ));
    }

    public function historico_desembolso(Request $request, MpmeHistoricoDesembolsoRepository $mpmeHistoricoDesembolsoRepository)
    {
        $rsHistoricoAprovacao = $mpmeHistoricoDesembolsoRepository->getAprovacao($request->id_mpme_desembolso);

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rsHistoricoAprovacao' => $rsHistoricoAprovacao
        );

        return view('desembolso.historico_desembolso', $compact_args);
    }

    public function recusar_desembolso(Request $request, MpmeDesembolsoRepository $mpmeDesembolsoRepository)
    {
        $dados      = (object) $request->all();
        $desembolso = $mpmeDesembolsoRepository->recusar_desembolso($dados);

        if (!$desembolso) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº '
            ));
        }

        return response()->json(array(
            'status'        => 'sucesso',
            'recarrega'     => 'true',
            'msg' => 'O desembolso foi recusado com sucesso.'
        ));
    }

    public function aprovar_desembolso(Request $request, MpmeDesembolsoRepository $mpmeDesembolsoRepository)
    {
        $dados      = (object) $request->all();
        $desembolso = $mpmeDesembolsoRepository->aprovar_desembolso($dados);

        if (!$desembolso) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº '
            ));
        }

        return response()->json(array(
            'status'        => 'sucesso',
            'recarrega'     => 'true',
            'msg' => 'O desembolso foi aprovado com sucesso.'
        ));
    }


    public function alterar_desembolso(Request $request, MpmeDesembolsoRepository $mpmeDesembolsoRepository, MpmeStatus $mpmeStatus, MpmeProposta $mpmeProposta)
    {
        $desembolso     = $mpmeDesembolsoRepository->listarDesembolso( $request->id_mpme_proposta, $request->id_mpme_desembolso);
        $mpmeStatus     = $mpmeStatus->where('NO_ORIGEM_STATUS', 'DESEMBOLSO')->first();
        $mpmeProposta   = $mpmeProposta->find($request->id_mpme_proposta);

        $compact_args = [
            'request'       => $request,
            'class'         => $this,
            'mpmeDesembolso'  => $desembolso,
            'mpmeStatus'  => $mpmeStatus,
            'mpmeProposta'  => $mpmeProposta,
        ];

        return view('desembolso.alterar_desembolso', $compact_args);

    }



}
