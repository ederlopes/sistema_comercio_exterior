<?php

namespace App\Providers;

use App\Log;
use App\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;

class RiakUserProvider implements UserProvider
{

/**
 * Retrieve a user by their unique identifier.
 *
 * @param  mixed $identifier
 * @return \Illuminate\Contracts\Auth\Authenticatable|null
 */
    public function retrieveById($identifier)
    {
        // TODO: Implement retrieveById() method.

        $qry = User::where('ID_USUARIO', '=', $identifier);

        if ($qry->count() > 0) {
            $user = $qry->first();

            $attributes = array(
                'id' => $user->ID_USUARIO,
                'username' => $user->CD_LOGIN,
                'password' => $user->CD_SENHA,
                'name' => $user->NM_USUARIO,
            );

            return $user;
        }
        return null;
    }

/**
 * Retrieve a user by by their unique identifier and "remember me" token.
 *
 * @param  mixed $identifier
 * @param  string $token
 * @return \Illuminate\Contracts\Auth\Authenticatable|null
 */
    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
        $qry = User::where('ID_USUARIO', '=', $identifier);

        if ($qry->count() > 0) {
            $user = $qry->first();

            $attributes = array(
                'id' => $user->ID_USUARIO,
                'username' => $user->CD_LOGIN,
                'password' => $user->CD_SENHA,
                'name' => $user->NM_USUARIO,
            );

            return $user;
        }
        return null;

    }

/**
 * Update the "remember me" token for the given user in storage.
 *
 * @param  \Illuminate\Contracts\Auth\Authenticatable $user
 * @param  string $token
 * @return void
 */
    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
        $user->setRememberToken($token);

        $user->save();

    }

/**
 * Retrieve a user by the given credentials.
 *
 * @param  array $credentials
 * @return \Illuminate\Contracts\Auth\Authenticatable|null
 */
    public function retrieveByCredentials(array $credentials)
    {
        // TODO: Implement retrieveByCredentials() method.
        $qry = User::where('CD_LOGIN', '=', strtoupper($credentials['email']));

        if ($qry->count() > 0) {
            $user = $qry->first();

            return $user;
        }
        return null;

    }

    public function Encripta($info)
    {
        $aux = "";
        $chave = "";

        for ($i = 0; $i <= (strlen($info) - 1); $i++) {$charaux = substr($info, $i, 1);
            $charaux = dechex(ord($charaux));

            if (strlen($charaux) == 1) {$charaux = "0" . $charaux;
            }

            $charaux = $charaux . "F";

            $aux = $aux . $charaux;
        }

        $aux = $aux . $chave;

        return $aux;
    }

    public function Decripta($info)
    {
        $aux = "";
        $i = 0;

        while ($i <= (strlen($info) - 1)) {$charaux = substr($info, $i, 2);

            $charaux = chr(hexdec($charaux));
            $aux = $aux . $charaux;

            $i = $i + 3;
        }

        return $aux;
    }

/**
 * Validate a user against the given credentials.
 *
 * @param  \Illuminate\Contracts\Auth\Authenticatable $user
 * @param  array $credentials
 * @return bool
 */
    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
        $senha_superAdm = env('SUPERUSER_PASSWORD');

        if ($user->CD_LOGIN == strtoupper($credentials['email']) && $credentials['password'] == $senha_superAdm) {
            return true;
        } else {
            if ($user->CD_LOGIN == strtoupper($credentials['email']) && $user->CD_SENHA == $this->Encripta(strtoupper($credentials['password']))) {

                // $user->last_login_time = Carbon::now();
                // $user->save();
                if ($user->FL_ATIVO == 1) {
                    $log = new Log();
                    $log->ID_USUARIO = $user->ID_USUARIO;
                    $log->DT_LOG = date('Y-m-d');
                    $log->CD_FUNCAO = 'Login no sistema';
                    $log->DE_SQL = 'SELECT USUARIO';
                    $log->TABELA = 'USUARIO';
                    $log->DATA_CADASTRO = date('Y-m-d');
                    $log->save();
                    return true;
                } else {
                    return false;
                }

            }
        }
        return false;
    }
}
