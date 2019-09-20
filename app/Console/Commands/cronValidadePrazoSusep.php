<?php

namespace App\Console\Commands;

use App\Repositories\MpmeNotificacaoUsuarioRepository;
use App\Repositories\MpmePropostaRepository;
use Illuminate\Console\Command;

class cronValidadePrazoSusep extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:prazo_susep';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificando se as propostas foram aprovadas pelo analista no prazo de 15 dias';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $propostas  = new MpmePropostaRepository();
        $where      = [];
        $propostas  = $propostas->getPropostasPorAlcada(NULL, $where, false)
                                ->whereNotNull('MPME_PROPOSTA.DT_ENVIO')
                                ->whereNull('MPME_PROPOSTA.DT_APROVACAO')
                                ->whereNull('MPME_PROPOSTA.DT_CANCELAMENTO')
                                ->get();
        $notificacao                = new MpmeNotificacaoUsuarioRepository();

        foreach ( $propostas as $proposta)
        {
           if ( $proposta->SALDO_DIAS > 5 &&  $proposta->SALDO_DIAS <= 10 )
           {
               $notificacao->registrar_notificacao([
                   'id_mpme_tipo_notificacao' => 12,
                   'id_oper' => $proposta->ID_OPER,
                   'id_mpme_proposta' => $proposta->ID_MPME_PROPOSTA,
               ]);

           }else if( $proposta->SALDO_DIAS > 1 &&  $proposta->SALDO_DIAS <= 5 ){

               $notificacao->registrar_notificacao([
                   'id_mpme_tipo_notificacao' => 13,
                   'id_oper' => $proposta->ID_OPER,
                   'id_mpme_proposta' => $proposta->ID_MPME_PROPOSTA,
               ]);

           }else if( $proposta->SALDO_DIAS == 1){

               $notificacao->registrar_notificacao([
                   'id_mpme_tipo_notificacao' => 14,
                   'id_oper' => $proposta->ID_OPER,
                   'id_mpme_proposta' => $proposta->ID_MPME_PROPOSTA,
               ]);

           }else if( $proposta->SALDO_DIAS == 0){

               $notificacao->registrar_notificacao([
                   'id_mpme_tipo_notificacao' => 15,
                   'id_oper' => $proposta->ID_OPER,
                   'id_mpme_proposta' => $proposta->ID_MPME_PROPOSTA,
               ]);

               $mpmePropostaRepository = new MpmePropostaRepository();

               $request  =  [
                                'in_aceite'                 => 'S',
                                'id_mpme_proposta'          => $proposta->ID_MPME_PROPOSTA,
                                'ds_motivo'                 => 'APROVACAO AUTOMATICA PELO SISTEMA',
                                'id_mpme_status_proposta'   => 5,
                                'id_mpme_alcada'            => 1,
                                'nu_proposta'               => null,
                                'id_oper'                   => $proposta->ID_OPER,
                            ];

               $request = (object) $request;

               $mpmePropostaRepository->aprovarProposta($request);

           }
        }
    }
}