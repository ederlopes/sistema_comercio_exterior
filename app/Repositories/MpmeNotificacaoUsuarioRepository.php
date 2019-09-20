<?php

namespace App\Repositories;

use App\ImportadoresModel;
use App\MpmeNotificacaoUsuario;
use App\MpmeTipoNotificacao;
use App\MpmeTipoNotificacaoUsuario;
use App\User;
use Auth;
use Carbon\Carbon;
use Mail;

class MpmeNotificacaoUsuarioRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(MpmeNotificacaoUsuario::class);
    }

    public function registrar_notificacao($dados)
    {

        if (!isset($dados['id_mpme_tipo_notificacao'])) {
            return false;
        }

        $arrayIdUsuarios = [];

        $dados_tipo_notificacao = MpmeTipoNotificacao::find($dados['id_mpme_tipo_notificacao']);

        $mpme_importadores = new ImportadoresModel();
        $mpme_importadores = $mpme_importadores->where('ID_OPER', '=', $dados['id_oper'])->first();

        if ($dados_tipo_notificacao->IN_NOTIFICA_EXPORTADOR == 'S') {
            $arrayIdUsuarios[] = [
                'id_usuario' => $mpme_importadores->ID_USUARIO,
                'in_usuario' => 'EXTERNO',
            ];
        }

        if ($dados_tipo_notificacao->IN_NOTIFICA_BANCO == 'S') {
            //FAZER PROCESSO GSEX
            //$arrayIdUsuarios = [];
        }

        //buscar outros usuarios a serem notificados
        //CASO ALGUM USUARIO NAO ESTEJA RECEBENDO NOTIFICACAO PRECISA POPULAR TABELA

        $usuariosASeremNotificados = new MpmeTipoNotificacaoUsuario();

        $usuariosASeremNotificados = $usuariosASeremNotificados
            ->where('ID_MPME_TIPO_NOTIFICACAO', '=', $dados_tipo_notificacao->ID_MPME_TIPO_NOTIFICACAO)
            ->get();

        foreach ($usuariosASeremNotificados as $notificacao) {
            $arrayIdUsuarios[] = [
                'id_usuario' => $notificacao->ID_USUARIO,
                'in_usuario' => $notificacao->IN_USUARIO,
            ];
        }

        //gravar registro de notificacao

        $id_oper = (array_key_exists("id_oper", $dados)) ? $dados['id_oper'] : null;
        $id_mpme_proposta = (array_key_exists("id_mpme_proposta", $dados)) ? $dados['id_mpme_proposta'] : null;
        $id_mpme_embarque = (array_key_exists("id_mpme_embarque", $dados)) ? $dados['id_mpme_embarque'] : null;
        $id_mpme_desembolso = (array_key_exists("id_mpme_desembolso", $dados)) ? $dados['id_mpme_desembolso'] : null;
        $id_mpme_sinistro = (array_key_exists("id_mpme_sinistro", $dados)) ? $dados['id_mpme_sinistro'] : null;

        foreach ($arrayIdUsuarios as $value_usuario) {
            $nova_notificacao = new MpmeNotificacaoUsuario();
            $nova_notificacao->ID_MPME_TIPO_NOTIFICACAO = $dados_tipo_notificacao->ID_MPME_TIPO_NOTIFICACAO;
            $nova_notificacao->ID_USUARIO = $value_usuario['id_usuario'];
            $nova_notificacao->ID_OPER = $id_oper;
            $nova_notificacao->ID_MPME_PROPOSTA = $id_mpme_proposta;
            $nova_notificacao->ID_MPME_EMBARQUE = $id_mpme_embarque;
            $nova_notificacao->ID_MPME_DESEMBOLSO = $id_mpme_desembolso;
            $nova_notificacao->ID_MPME_SINISTRO = $id_mpme_sinistro;
            $nova_notificacao->DT_CADASTRO = Carbon::now();
            $nova_notificacao->ID_USUARIO_CAD = (isset(Auth::user()->ID_USUARIO)) ? Auth::user()->ID_USUARIO : 1;

            if (!$nova_notificacao->save()) {
                return false;
            }

            $msg = ($value_usuario['in_usuario'] == 'EXTERNO') ? $dados_tipo_notificacao->NO_TIPO_NOTIFICACAO_EXTERNA : $dados_tipo_notificacao->NO_TIPO_NOTIFICACAO_INTERNA;

            $dadosEmail = [
                'subject' => (array_key_exists('subject', $dados)) ? $dados['subject'] : $dados_tipo_notificacao->NO_TIPO_NOTIFICACAO,
                'msg' => $msg,
                'msg_ext' => (array_key_exists('msg_ext', $dados)) ? $dados['msg_ext'] : null,
                'id_oper' => (array_key_exists('id_oper', $dados)) ? $dados['id_oper'] : $mpme_importadores->ID_OPER,
                'id_mpme_proposta' => (array_key_exists('id_mpme_proposta', $dados)) ? $dados['id_mpme_proposta'] : null,
                'status' => (array_key_exists('status', $dados)) ? $dados['status'] : null,
                'id_mpme_arquivo' => (array_key_exists('id_mpme_arquivo', $dados)) ? $dados['id_mpme_arquivo'] : null,
                'in_anexo' => $dados_tipo_notificacao->IN_ANEXO,
            ];

            $buscando_dados_usuario = new User();
            $buscando_dados_usuario = $buscando_dados_usuario->where('ID_USUARIO', $value_usuario['id_usuario'])->first();

            //INICIA PROCESSO DE ENVIAR EMAIL
            if ($dados_tipo_notificacao->IN_ENVIO_EMAIL == 'S') {
                $this->enviar_notificacao($buscando_dados_usuario, $dadosEmail);
            }
        }
    }

    public function enviar_notificacao($dados_usuario, $dados)
    {
        if ($dados_usuario->DE_EMAIL != "") {
            Mail::send('mail.notificacoes.principal', ['dados_usuario' => $dados_usuario, 'dados' => $dados], function ($mail) use ($dados_usuario, $dados) {
                if ($dados['in_anexo'] == 'N') {
                    $mail->to($dados_usuario->DE_EMAIL, $dados_usuario->NM_USUARIO)
                        ->from('sistemas@abgf.gov.br', 'SCE MPME')
                        ->subject($dados['id_oper'] . ' - ' . $dados['subject']);
                } else {
                    $mail->to($dados_usuario->DE_EMAIL, $dados_usuario->NM_USUARIO)
                        ->from('sistemas@abgf.gov.br', 'SCE MPME')
                        ->subject($dados['id_oper'] . ' - ' . $dados['subject']);
                    /*
                AGUARDANDO A RESOLUÇÃO DO PROBLEMA
                if (isset($dados['id_mpme_arquivo'])) {
                $rsArquivo = new MpmeArquivoRepository();
                $rsArquivo = $rsArquivo->getArquivo($dados['id_mpme_arquivo']);
                }
                $mail->to($dados_usuario->DE_EMAIL, $dados_usuario->NM_USUARIO)
                ->from('sistemas@abgf.gov.br', 'SCE MPME')
                ->subject($dados['id_oper'] . ' - ' . $dados['subject'])
                ->attach(storage_path('app/public/' . $rsArquivo->NO_DIRETORIO . '/' . $rsArquivo->NO_ARQUIVO), array(
                'as' => $rsArquivo->NO_ARQUIVO . '.' . $rsArquivo->NO_EXTENSAO,
                ));*/
                }
            });
        }
    }

    public function getNotificacaoEmAbertoPorUsuario()
    {
        $nova_notificacao = new MpmeNotificacaoUsuario();
        $nova_notificacao = $nova_notificacao->where('ID_USUARIO', Auth::user()->ID_USUARIO)->whereNull('IN_VISUALIZADA')->orderByDesc('ID_MPME_NOTIFICACAO_USUARIO')->get();
        return $nova_notificacao;
    }

    public function visualizarNotificacao($request)
    {

        $notificacao = new MpmeNotificacaoUsuario();

        if (isset($request->id_mpme_notificacao_usuario)) {
            $notificacao = $notificacao->where('ID_MPME_NOTIFICACAO_USUARIO', '=', $request->id_mpme_notificacao_usuario);
        }

        if (isset($request->id_oper)) {
            $notificacao = $notificacao->where('ID_OPER', '=', $request->id_oper);
        }

        if (isset($request->id_mpme_proposta)) {
            $notificacao = $notificacao->where('ID_MPME_PROPOSTA', '=', $request->id_mpme_proposta);
        }

        if (isset($request->id_mpme_embarque)) {
            $notificacao = $notificacao->where('ID_MPME_EMBARQUE', '=', $request->id_mpme_embarque);
        }

        if (isset($request->id_mpme_desembolso)) {
            $notificacao = $notificacao->where('ID_MPME_DESEMBOLSO', '=', $request->id_mpme_desembolso);
        }

        if (Auth::user()->TP_USUARIO == 'C') {
            $notificacao->where('ID_USUARIO', '=', Auth::user()->ID_USUARIO);
        }

        //obs. RETIRANDO NOTIFICACAO DE TODAS AS CAIXAS DE USUARIOS QUE FORAM NOTIFICADOS. PEDIDO DO DIR. VITOR
        // ANTES ERA POR USUARIO / 28-02-2019

        $notificacao = $notificacao->where('ID_MPME_TIPO_NOTIFICACAO', '=', $request->id_mpme_tipo_notificacao);

        $dados = $notificacao->update(
            [
                'IN_VISUALIZADA' => 'S',
                'DT_VISUALIZACAO' => Carbon::now(),
                'ID_USUARIO_VISUALIZOU' => Auth::user()->ID_USUARIO,
            ]
        );

        if ($dados) {
            return true;
        } else {
            return false;
        }
    }
}
