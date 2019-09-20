<?php
namespace App\Http\Controllers;

use App\ImportadoresModel;
use App\ModalidadeModel;
use App\MpmePrecoCobertura;
use App\Pais;
use App\Repositories\PrecificacaoRepository;
use Illuminate\Http\Request;
use App\User;
use DB;
use Illuminate\Support\Facades\Auth;

class PrecificacaoController extends Controller
{

    private $importadores;

    public function __construct(Request $request, ImportadoresModel $importadoresModel)
    {
        $this->importadores = $importadoresModel->find($request->id_oper);
    }


    public function nova_simulacao_site(Request $request, ModalidadeModel $modalidadeModel, Pais $pais)
    {
        $rs_modalidade      = $modalidadeModel::all();
        $rs_paises_risco    = $pais->getPaisRisco();

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'rs_modalidade' => $rs_modalidade,
            'rs_paises_risco' => $rs_paises_risco,
            'disabled_operacao' => false,
        );

        return view('precificacao.simulacao-site', $compact_args);
    }


    public function precificarValor(Request $request)
    {
        $dadosAlimentarCalculadora = PrecificacaoRepository::processarCalculadora($request);

        $dadosOperacao =$this->importadores;

        $respostaCalculadora = [
            'id_oper'                    => $request->id_oper,
            'id_mpme_proposta'           => $request->id_mpme_proposta,
            'SIGLA_MOEDA'                => $dadosOperacao->RetornaMoeda->SIGLA_MOEDA,
            'VL_SOLICITADO'              => number_format($dadosAlimentarCalculadora['VL_DOWNPAYMENT'],2,',','.'),
            'VL_COBERTURA_IMP_FORMATADO' => $dadosAlimentarCalculadora['VL_COBERTURA_IMP_FORMATDO'],
            'PC_COB_MIN'                 => round(($dadosAlimentarCalculadora['VL_PC_COB'] / $dadosAlimentarCalculadora['VL_DOWNPAYMENT']) * 100,2)
        ];

        return response()->json(array(
            'status' => 'sucesso',
            'resposta' => $respostaCalculadora
        ));
    }

    public function precificarValorSimulacao(Request $request)
    {
        $request->session()->forget('resposta_calculadora');

        $dadosAlimentarCalculadora = PrecificacaoRepository::processarCalculadoraSimulacao($request);

        $dadosOperacao =$this->importadores;

        $respostaCalculadora = [
            'id_oper'                    => $request->id_oper,
            'id_mpme_proposta'           => $request->id_mpme_proposta,
            'SIGLA_MOEDA'                => $dadosOperacao->RetornaMoeda->SIGLA_MOEDA,
            'VL_SOLICITADO'              => number_format($dadosAlimentarCalculadora['VL_DOWNPAYMENT'],2,',','.'),
            'VL_COBERTURA_IMP_FORMATADO' => $dadosAlimentarCalculadora['VL_COBERTURA_IMP_FORMATDO'],
            'PC_COB_MIN'                 => $dadosAlimentarCalculadora['PC_COB_MIN']
        ];

        if ($dadosAlimentarCalculadora == false)
        {
            return response()->json(array(
                'status' => 'erro',
                'resposta' => $respostaCalculadora
            ));
        }

        $request->session()->put('resposta_calculadora', $dadosAlimentarCalculadora);


        return response()->json(array(
            'status' => 'sucesso',
            'resposta' => $respostaCalculadora
        ));
    }

    public function precificarValorSimulacaoSite(Request $request)
    {



        $dadosPrecificacao = PrecificacaoRepository::processarCalculadoraSimulacaoSite($request);

        $compact_args = array(
            'request' => $request,
            'dadosPrecificacao' => $dadosPrecificacao,
        );

        return view('precificacao.simulacao-retorno', $compact_args);
    }

    public function testeCalculadora()
    {
        $VL_COBERTURA_IMP = 80000.00;
        $VL_COBERTURA_IMP = PrecificacaoRepository::calcular_taxas_precificacao($VL_COBERTURA_IMP);

        echo $VL_COBERTURA_IMP;
    }

}
