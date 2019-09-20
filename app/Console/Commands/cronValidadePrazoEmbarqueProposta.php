<?php

namespace App\Console\Commands;

use App\Repositories\MpmeNotificacaoUsuarioRepository;
use App\Repositories\MpmePropostaRepository;
use Illuminate\Console\Command;
use Carbon\Carbon;

class cronValidadePrazoEmbarqueProposta extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notify:prazo_embarque';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verificando se as propostas tiveram seus embarques confirmados no prazo de 15 dias';

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
            ->whereNotNull('MPME_PROPOSTA.DT_EMBARQUE')
            ->whereNull('MPME_PROPOSTA.DT_APROVACAO')
            ->whereNull('MPME_PROPOSTA.DT_CANCELAMENTO')
            ->get();
        $notificacao                = new MpmeNotificacaoUsuarioRepository();


        foreach ($propostas as $proposta) {

            // Verifica se ja foi confirmado o embarque, caso tenha sido, verifica se a data de embarque é a mesma de hoje
            // caso seja aprova o embarque

            $hoje = Carbon::now()->startOfDay();
            $dt_embarque = Carbon::createFromDate($proposta->DT_EMBARQUE)->startOfDay();

            // Caso o aceite tenha sido realizado, aprova a proposta
            if ($proposta->IN_EMBARQUE_CONFIRMADO == 'S' && $dt_embarque == $hoje) {
                $notificacao->registrar_notificacao([
                    'id_mpme_tipo_notificacao' => 19,
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

            if ($proposta->SALDO_DIAS_EMBARQUE > 0 && $proposta->SALDO_DIAS_EMBARQUE <= 3 && $proposta->IN_EMBARQUE_CONFIRMADO == "") {
                $url = url('/') . "/proposta/embarque-proposta/" . $proposta->ID_MPME_PROPOSTA;
                $notificacao->registrar_notificacao([
                    'id_mpme_tipo_notificacao' => 18,
                    'id_oper' => $proposta->ID_OPER,
                    'id_mpme_proposta' => $proposta->ID_MPME_PROPOSTA,
                    'msg_ext' => "<p>Para confirmar o embarque: <a href=\"$url\">Clique aqui</a></p>"
                ]);
            } else if ($proposta->SALDO_DIAS_EMBARQUE <= 0 && $proposta->IN_EMBARQUE_CONFIRMADO == "") {

                $notificacao->registrar_notificacao([
                    'id_mpme_tipo_notificacao' => 17,
                    'id_oper' => $proposta->ID_OPER,
                    'id_mpme_proposta' => $proposta->ID_MPME_PROPOSTA,
                    'msg_ext' => "<p>A Proposta de Seguro de Crédito à Exportação foi recusada por falta de
                    confirmação da data da exportação/ embarque</p>"

                ]);

                $mpmePropostaRepository = new MpmePropostaRepository();

                $request  =  [
                    'in_aceite'                 => 'N',
                    'id_mpme_proposta'          => $proposta->ID_MPME_PROPOSTA,
                    'ds_motivo'                 => 'CANCELADO AUTOMATICAMENTE PELO SISTEMA',
                    'id_mpme_status_proposta'   => 6,
                    'id_mpme_alcada'            => 1,
                    'nu_proposta'               => null,
                    'DT_CANCELAMENTO'           => Carbon::now(),
                    'id_oper'                   => $proposta->ID_OPER,
                ];

                $request = (object) $request;

                $mpmePropostaRepository->cancelarProposta($request);
            }
        }
    }
}
