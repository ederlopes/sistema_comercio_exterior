<?php

namespace App\Providers;

use App\MpmePermissoes;
use Auth;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Laravel\Passport\Passport;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies();

        Auth::provider('riak', function ($app, array $config) {
            // Return an instance of Illuminate\Contracts\Auth\UserProvider...

            return new RiakUserProvider($app->make('App\Providers\RiakUserProvider'));
        });

        Passport::routes();

        $gate->define('usuario_abgf', function ($user) {
            if ($user->TP_USUARIO == 'F') {
                return true;
            };
        });

        $gate->define('usuario_banco', function ($user) {
            if ($user->TP_USUARIO == 'B') {
                return true;
            };
        });

        $gate->define('usuario_cliente', function ($user) {
            if ($user->TP_USUARIO == 'C') {
                return true;
            };
        });

        $permissoes = MpmePermissoes::with('permisoes_perfil')->get();

        foreach ($permissoes as $permissao) {
            $gate->define($permissao->NO_PERMISSAO, function ($user) use ($permissao) {
                return $user->hasPermission($permissao);
            });
        }

        $gate->before(function ($user) {
            if ($user->isSuperAdmin()) {
                return true;
            }
        });

    }

}
