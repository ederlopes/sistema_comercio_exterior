<?php
namespace App\Http\Controllers;

use App\MpmeTipoEmbarque;
use App\Repositories\MpmeEmbarqueRepository;
use App\Repositories\MpmePropostaRepository;
use Illuminate\Http\Request;
use App\Notificacoes;
use App\User;
use App\Pais;
use App\Financpre;
use App\Financpos;
use DB;
use Auth;
use App\MpmeEmbarque;

class EmbarqueController extends Controller
{

    public function convertUtf8($value)
    {
        return mb_detect_encoding($value, mb_detect_order(), true) === 'UTF-8' ? $value : mb_convert_encoding($value, 'UTF-8');
    }

    public function index(Request $request, MpmeEmbarqueRepository $mpmeEmbarqueRepository, MpmePropostaRepository $propostaRepository)
    {
        $listarEmbarque = $mpmeEmbarqueRepository->listarEmbarque($request->id_proposta);

        if ($propostaRepository->validarPropostaOperacao($request->id_oper, $request->id_proposta) <= 0)
        {
            return response(view('erros.401'), 401);
        }
        
        
        $compact_args = [
            'request' => $request,
            'class' => $this,
            'listarEmbarque' => $listarEmbarque,

        ];

        return view('embarque.index_embarque', $compact_args);
    }

    public function novo(Request $request, MpmeTipoEmbarque $mpmeTipoEmbarque, MpmePropostaRepository $propostaRepository, MpmeEmbarqueRepository $mpmeEmbarqueRepository)
    {
        if ($propostaRepository->validarPropostaOperacao($request->id_oper, $request->id_proposta) <= 0)
        {
            return response(view('erros.401'), 401);
        }

        $mpmeTipoEmbarque = $mpmeTipoEmbarque->where('IN_ATIVO', '=', 'S')->get();


        $proposta         = $propostaRepository->getProposta($request->id_oper, $request->id_proposta);
        $proposta         = $proposta[0];

        $mpme_embarque   = $mpmeEmbarqueRepository->listarEmbarque($request->id_proposta)->count();

       if ($mpme_embarque>0){
           return redirect('embarque/'.$request->id_oper.'/'.$request->id_proposta)->with('info', 'Já existe um lançamento de embarque para está proposta');
       }


        $compact_args = [
            'request' => $request,
            'class' => $this,
            'mpmeTipoEmbarque' => $mpmeTipoEmbarque,
            'proposta' => $proposta,
            'mpme_embarque' => $mpme_embarque,
        ];

        return view('embarque.novo_embarque', $compact_args);
    }

    public function salvar(Request $request, MpmeEmbarqueRepository $mpmeEmbarqueRepository)
    {
        $campos = (object) $request->all();
        $processo_embarque = $mpmeEmbarqueRepository->salvar_embarque($campos);

        if (! $processo_embarque ) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº '
            ));
        }

        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'url',
            'url' => 'embarque/' . $request->id_oper.'/'.$request->id_mpme_proposta,
            'id_oper' => $request->id_oper,
            'id_mpme_proposta' => $processo_embarque->ID_MPME_PROPOSTA,
            'msg' => 'O controle de embarque foi inserido com sucesso'
        ));

    }


    public function AprovaEmbarque(Request $request)
    {
        $campos = (object) $request->all();
        $parecerEmbarque = MpmeEmbarqueRepository::AprovaEmbarque($campos);

        if($parecerEmbarque){

          return response()->json(array(
              'status' => 'sucesso',
              'recarrega' => 'url',
              'url' => 'banco/',
              'msg' => 'O controle de embarque aprovado com sucesso'
          ));

        }else{

          return response()->json(array(
              'status' => 'erro',
              'recarrega' => 'false',
              'msg' => 'Por favor, tente novamente mais tarde. Erro nº '
          ));

        }


    }
    
    public function DevolveConferente(Request $request)
    {
        $campos = (object) $request->all();
        $parecerEmbarque = MpmeEmbarqueRepository::DevolveConferente($campos);

        if($parecerEmbarque){

          return response()->json(array(
              'status' => 'sucesso',
              'recarrega' => 'url',
              'url' => 'banco/',
              'msg' => 'O controle de embarque foi devolvido para correção!'
          ));

        }else{

          return response()->json(array(
              'status' => 'erro',
              'recarrega' => 'false',
              'msg' => 'Por favor, tente novamente mais tarde. Erro nº '
          ));

        }


    }


    public function DevolveEmbarque(Request $request)
    {
        $campos = (object) $request->all();
        $parecerEmbarque = MpmeEmbarqueRepository::DevolveEmbarque($campos);

        if($parecerEmbarque){

            return response()->json(array(
                'status' => 'sucesso',
                'recarrega' => 'url',
                'url' => 'banco/',
                'msg' => 'O controle de embarque foi devolvido para correção!'
            ));

        }else{

            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => 'Por favor, tente novamente mais tarde. Erro nº '
            ));

        }


    }


    public function editar(Request $request, MpmeTipoEmbarque $mpmeTipoEmbarque, MpmePropostaRepository $propostaRepository, MpmeEmbarqueRepository $mpmeEmbarqueRepository)
    {
        if ($propostaRepository->validarPropostaOperacao($request->id_oper, $request->id_proposta) <= 0)
        {
            return response(view('erros.401'), 401);
        }

        $mpmeTipoEmbarque = $mpmeTipoEmbarque->where('IN_ATIVO', '=', 'S')->get();

        $proposta         = $propostaRepository->getProposta($request->id_oper, $request->id_proposta)[0];


        $id_mpme_statatus = ($proposta->MpmeClienteExportadorModaliadeFinancimanciamento->ModalidadeFinanciamento->ID_FINANCIAMENTO == 4) ? 8 : 4;

        $embarque = MpmeEmbarque::find($request->id_embarque);

        $id_proposta = $request->id_proposta;



        return view('embarque.editar_embarque', compact('embarque','proposta','mpmeTipoEmbarque','id_mpme_statatus', 'request'));
    }






}
