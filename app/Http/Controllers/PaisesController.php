<?php
namespace App\Http\Controllers;

use App\Log;
use App\Pais;
use App\PaisVal;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaisesController extends Controller
{
    public function index_risco(Request $request, Pais $pais)
    {
        $rs_paises_risco = $pais->getPaisRisco();

        $compact_args = [
            'request' => $request,
            'class' => $this,
            'rs_paises_risco' => $rs_paises_risco,

        ];

        return view('paises.index_pais_risco', $compact_args);
    }

    public function gravar_risco(Request $request)
    {
        $arrayIdPaisRisco = $request->id_pais_risco;
        $novoRisco = $request->novo_risco;
        $riscoAtual = $request->risco_atual;

        DB::beginTransaction();

        foreach ($arrayIdPaisRisco as $key => $id_pais_risco) {
            $paises_risco = new PaisVal();
            $paises_risco = $paises_risco->where('ID_PAIS', '=', $id_pais_risco)->whereNull('DT_FIM_VIG')->first();

            if ($paises_risco->count() > 0) {
                $paises_risco->CD_RISCO = $novoRisco[$key];
                $paises_risco->CD_RISCO_AUX = $novoRisco[$key];
                $paises_risco->CD_RISCO_AUX = $novoRisco[$key];

                if (!$paises_risco->save()) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro atualizar cadastro',
                    ));
                }

                //log
                $log = new Log();
                $log->ID_USUARIO = Auth::user()->ID_USUARIO;
                $log->DT_LOG = Carbon::now();
                $log->CD_FUNCAO = 'ATUALIZAR_RISCO';
                $log->TABELA = 'PAISES_VAL';
                $log->DE_SQL = 'RISCO_ATUAL: ' . $riscoAtual[$key] . ' / ' . 'NOVO_RISCO: ' . $novoRisco[$key];
                $log->DATA_CADASTRO = Carbon::now();

                if (!$log->save()) {
                    DB::rollback();
                    return response()->json(array(
                        'status' => 'erro',
                        'recarrega' => 'true',
                        'msg' => 'Erro lancar log',
                    ));
                }

            } else {
                DB::rollback();
                return response()->json(array(
                    'status' => 'erro',
                    'recarrega' => 'true',
                    'msg' => 'Não foi encontrado o registro do país.',
                ));
            }
        }

        DB::commit();
        return response()->json(array(
            'status' => 'sucesso',
            'recarrega' => 'true',
            'msg' => 'Registro(s) atualizados com sucesso.',
        ));

    }

}
