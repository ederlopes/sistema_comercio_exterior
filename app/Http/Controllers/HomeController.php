<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

use Carbon\Carbon;

use App\User;
use App\ImportadoresModel;
use App\Repositories\MpmePropostaRepository;

class HomeController extends Controller
{
    const ID_MPME_STATUS_EXCLUIDA = 17;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
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
        return view('home');
    }

    public function dashboard(Request $request, User $usuarios, ImportadoresModel $importadores, MpmePropostaRepository $propostas)
    {

        $meses = ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'];

        $ultimos_meses = [];
        for($i=0;$i<4;$i++) {
            $ultimos_meses[$i] = Carbon::now()->startOfMonth()->subMonths($i);
        }
        $ultimos_meses = array_reverse($ultimos_meses);

        $totais['usuarios'] = $usuarios->where('TP_USUARIO','C')
                                       ->get();

        foreach ($ultimos_meses as $mes) {
            $totais_cadastro = $usuarios->where('TP_USUARIO','C')
                                        ->whereMonth('DATA_CADASTRO',$mes->format('m'))
                                        ->whereYear('DATA_CADASTRO',$mes->format('Y'))
                                        ->count();

            $totais_aprovacao = $usuarios->where('TP_USUARIO','C')
                                         ->whereMonth('DT_ATZX',$mes->format('m'))
                                         ->whereYear('DT_ATZX',$mes->format('Y'))
                                         ->count();

            $totais['usuarios_datas']['datas'][] = substr($meses[$mes->format('n')-1],0,3).'/'.$mes->format('Y');
            $totais['usuarios_datas']['cadastrados'][] = $totais_cadastro;
            $totais['usuarios_datas']['aprovacoes'][] = $totais_aprovacao;
        }

        $totais['operacoes'] = $importadores->whereNotIn('ST_OPER',[14])
                                            ->leftJoin('OPERACAO_CADASTRO_EXPORTADOR', 'OPERACAO_CADASTRO_EXPORTADOR.ID_OPER', '=', 'MPME_IMPORTADORES.ID_OPER')
                                            ->get(
                                                ['MPME_IMPORTADORES.*',
                                                 'OPERACAO_CADASTRO_EXPORTADOR.COD_UNICO_OPERACAO'
                                                ]
                                            );

        foreach ($ultimos_meses as $mes) {
            $totais_cadastro = $importadores->whereNotIn('ST_OPER',[14])
                                            ->whereMonth('DATA_CADASTRO',$mes->format('m'))
                                            ->whereYear('DATA_CADASTRO',$mes->format('Y'))
                                            ->count();

            $totais_aprovadas = $importadores->whereNotIn('ST_OPER',[14])
                                             ->join('MPME_IMPORTADORES_APROVACAO', function($join) use ($mes) {
                                                $join->on('MPME_IMPORTADORES.ID_OPER','=','MPME_IMPORTADORES_APROVACAO.ID_OPER_FK')
                                                     ->where('MPME_IMPORTADORES_APROVACAO.IC_INDEFERIDA',0)
                                                     ->where('MPME_IMPORTADORES_APROVACAO.FL_MOMENTO','APV')
                                                     ->whereMonth('MPME_IMPORTADORES_APROVACAO.DT_APROVACAO',$mes->format('m'))
                                                     ->whereYear('MPME_IMPORTADORES_APROVACAO.DT_APROVACAO',$mes->format('Y'));
                                            })
                                            ->count();

            $totais['operacoes_datas']['datas'][] = substr($meses[$mes->format('n')-1],0,3).'/'.$mes->format('Y');
            $totais['operacoes_datas']['cadastradas'][] = $totais_cadastro;
            $totais['operacoes_datas']['aprovadas'][] = $totais_aprovadas;
        }


        $totais['propostas'] = $propostas->whereNotIn('ID_MPME_STATUS_PROPOSTA',[17])
                                         ->get();

        foreach ($ultimos_meses as $mes) {
            $totais_cadastro = $propostas->whereNotIn('ID_MPME_STATUS_PROPOSTA',[17])
                                         ->whereMonth('DT_CADASTRO',$mes->format('m'))
                                         ->whereYear('DT_CADASTRO',$mes->format('Y'))
                                         ->count();

            $totais_aprovadas = $propostas->whereNotIn('ID_MPME_STATUS_PROPOSTA',[17])
                                          ->whereMonth('DT_APROVACAO',$mes->format('m'))
                                          ->whereYear('DT_APROVACAO',$mes->format('Y'))
                                          ->count();

            $totais_apolices = $propostas->whereNotIn('ID_MPME_STATUS_PROPOSTA',[17])
                                         ->whereMonth('DT_ASSINATURA_APOLICE',$mes->format('m'))
                                         ->whereYear('DT_ASSINATURA_APOLICE',$mes->format('Y'))
                                         ->count();

            $totais['propostas_datas']['datas'][] = substr($meses[$mes->format('n')-1],0,3).'/'.$mes->format('Y');
            $totais['propostas_datas']['cadastradas'][] = $totais_cadastro;
            $totais['propostas_datas']['aprovadas'][] = $totais_aprovadas;
            $totais['propostas_datas']['apolices'][] = $totais_apolices;
        }

        $compact_args = array(
            'totais' => $totais,
            'request' => $request,
            'class' => $this,
            'dashboard' => 'true'
        );

        return view('dashboard', $compact_args);
    }
}
