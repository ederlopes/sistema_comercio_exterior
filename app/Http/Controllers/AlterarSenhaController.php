<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Notifications\ResetarSenhaNotification;
use App\Notifications\ResetarSenhaSucesso;
use App\ResetarSenha;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AlterarSenhaController extends Controller
{
    /**
     * criar token do reserte do e-mail
     *
     * @param  [string] email
     * @return [string] mensagem
     */

    public function resetarSenha(Request $request)
    {
        $validacao = Validator::make($request->all(), [
            'email' => 'required|string|email',
        ]);

        if ($validacao->fails()) {
            return back()->with('error', 'Campo de e-mail invalido!');
        }

        $user = User::where('DE_EMAIL', strtolower($request->email))->first();

        if (!$user) {
            return back()->with('error', 'E-mail não cadastrado!');
        }

        $resetarSenha = ResetarSenha::updateOrCreate(
            ['EMAIL' => strtolower($user->DE_EMAIL)],
            [
                'EMAIL' => strtolower($user->DE_EMAIL),
                'TOKEN' => str_random(60),
                'DATA_SOLICITACAO' => carbon::now(),
            ]
        );
        if ($user && $resetarSenha) {
            $user->notify(
                new ResetarSenhaNotification($resetarSenha->TOKEN)
            );
        }

        return redirect('login')->with('success', 'Um link para redefinição de senha foi enviado para seu e-mail!');
    }

    public function resetar(Request $request)
    {

        return view('auth.passwords.reset', compact('request'));
    }
    /**
     * Busca Token para alterar a senha
     *
     * @param  [string] $token
     * @return [string] mensagem
     * @return [message] objecto alteracao de senha
     */
    public function token(Request $request)
    {

        $mensagens = [
            'token.required' => 'Você precisa de um token para alterar a senha!',
            'password.required' => 'Você não digitou uma senha!',
            'password.min' => 'Sua senha deve ter pelo menos 6 caracteres!',
            'password.max' => 'Sua senha deve ter no máximo 10 caracteres!',
            'password_confirmation.same' => 'Sua confirmação de senha não conferece com a senha!',
            'password_confirmation.required' => 'Você não digitou a confirmação da senha!',
            'password_confirmation.min' => 'A confirmação da senha deve ter pelo menos 6 caracteres!',
            'password_confirmation.max' => 'A confirmação da senha deve ter no máximo 10 caracteres!',

        ];

        $validacao = Validator::make($request->all(), [
            'token' => 'required',
            'password' => 'min:6|max:10',
            'password_confirmation' => 'required_with:password|same:password|min:6|max:10',
        ], $mensagens);

        if ($validacao->fails()) {
            $erros = "";
            foreach ($validacao->errors()->all() as $erro) {
                $erros = $erros . '<\br>' . $erro;
            }
            return back()->with('error', $erros);
        }

        $resetarSenha = ResetarSenha::where('TOKEN', $request->token)
            ->first();
        if (!$resetarSenha) {
            return back()->with('error', 'seu token é inválido');
        }

        if (Carbon::parse($resetarSenha->DATA_SOLICITACAO)->addMinutes(720)->isPast()) {
            $resetarSenha->delete();
            return back()->with('error', 'seu tocken está expirado!');
        } else {
            $email = strtolower($request->email);
            $resetarSenha = ResetarSenha::where([
                ['TOKEN', $request->token],
                ['EMAIL', $email],
            ])->first();

            if (!$resetarSenha) {
                return back()->with('error', 'seu token é inválido!');
            }

            $user = User::where('DE_EMAIL', strtolower($resetarSenha->EMAIL))->first();

            if (!$user) {
                return back()->with('error', 'Nenhum usuário encontrado com esse e-mail!');
            }

            $user->CD_SENHA = Encripta(strtoupper($request->password));
            $user->save();
            $resetarSenha->delete();
            $user->notify(new ResetarSenhaSucesso($resetarSenha));
            return redirect('login')->with('success', 'Senha alterada com sucesso!');
        }
    }
}
