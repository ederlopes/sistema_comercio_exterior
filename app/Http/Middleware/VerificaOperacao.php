<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Auth;
use Session;
use App\ImportadoresModel;

class VerificaOperacao {

    public function handle($request, Closure $next) {

        $operacao = new ImportadoresModel();

        $where = [
                    'ID_OPER'       => $request->id_oper,
                    'ID_USUARIO'    => Auth::user()->ID_USUARIO
                 ];

        $verifica = $operacao->getQuestionarioOperacao($where)->count();

        if ($verifica>0) {
            return $next($request);
        } else {
            return response()->view('erros.401');
        }
    }
}
