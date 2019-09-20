<?php

namespace App\Http\Middleware;

use Closure;
use App;
use Auth;
use Session;

class VerificaRedirect {

    public function handle($request, Closure $next, $guard = null){

        if (Auth::guard($guard)->check()) {

            switch (Auth::user()->TP_USUARIO)
            {
                case 'C':
                    return redirect('https://www.abgf.gov.br/abgf-na-midia/abgf-deixa-de-emitir-seguro-de-credito-a-exportacao-com-recursos-proprios/');
                    break;
                case 'F':
                    return $next($request);
                    break;
                case 'B':
                    return redirect('https://www.abgf.gov.br/abgf-na-midia/abgf-deixa-de-emitir-seguro-de-credito-a-exportacao-com-recursos-proprios/');
                    break;
                default:
                    return redirect('/logout');
                    break;

            }

        }

        return $next($request);
    }
}
