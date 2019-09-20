<?php
namespace App\Repositories;

use App\ImportadoresModel;
use App\MpmeArquivo;
use App\MpmeNotificacao;
use App\MpmeUsuarioAlcada;
use App\OperacaoCadastroExportador;
use App\User;
use Auth;
use Mail;
use DB;

class MpmeNotificacaoRepository extends Repository
{

    public function __construct()
    {
        $this->setModel(MpmeNotificacao::class);
    }

    public static function salvaNotificacao($request)
    {
        DB::beginTransaction();
        $notificacao = (isset($request->ID_NOTIFICACAO) && trim($request->ID_NOTIFICACAO) != "") ? MpmeNotificacao::where($request->ID_NOTIFICACAO)->where('ID_MPME_ALCADA', '=', $request->ID_MPME_ALCADA)->first() : new MpmeNotificacao();
        $notificacao->ID_STATUS_NOTIFICACAO_FK = $request->ID_STATUS_NOTIFICACAO_FK;
        $notificacao->ID_USUARIO_FK = Auth::User()->ID_USUARIO;
        $notificacao->ID_OPER = (isset($request->ID_OPER)) ? $request->ID_OPER : null;
        $notificacao->DE_NOTIFICACAO = $request->NM_USUARIO . ' - ' . $request->RAZAO_SOCIAL;
        $notificacao->DS_LINK = '';
        $notificacao->ID_MPME_ALCADA = (isset($request->IC_DEVOLVEU_ALCADA_ANTERIOR) && $request->IC_DEVOLVEU_ALCADA_ANTERIOR != 0) ? $request->ID_MPME_ALCADA - 1 : $request->ID_MPME_ALCADA;
        $notificacao->IC_ATIVO = 1;

        if ($notificacao->save()) {
            //notificando usuario por email
            switch ($request->ID_STATUS_NOTIFICACAO_FK) {
                case '500':
                case '501':
                case '502':
                    self::enviarEmailDevolucao($request, $notificacao);
                    break;
                case '503':
                    self::enviarEmailNotificacao($request, $notificacao);
                    break;
                case '504':

                    $temos_uso = MpmeArquivo::where('ID_MPME_TIPO_ARQUIVO', 25)->orderBy('DT_CADASTRO')->first();
                    $cond_geral = MpmeArquivo::where('ID_MPME_TIPO_ARQUIVO', 26)->orderBy('DT_CADASTRO')->first();
                    $cond_part = MpmeArquivo::where('ID_MPME_TIPO_ARQUIVO', 27)->orderBy('DT_CADASTRO')->first();
                    $cond_esp = MpmeArquivo::where('ID_MPME_TIPO_ARQUIVO', 28)->orderBy('DT_CADASTRO')->first();

                    $exportador = OperacaoCadastroExportador::where('ID_OPER', $request->ID_OPER)->first();
                    $dados_importador = ImportadoresModel::where('ID_OPER', $request->ID_OPER)->first();
                    $modalidade = $dados_importador->RetornaModalidadeOperacao->NO_MODALIDADE;

                    $usuario = User::where('ID_USUARIO', '=', $exportador->ID_USUARIO_CAD)->first();

                    $gerar_pdf = gerar_pdf_regras_condicoes($usuario, $exportador, $dados_importador, $modalidade);

                    if(!$gerar_pdf){
                        DB::rollback();
                        return false;
                    }

                    $exportador->ID_MPME_ARQUIVO_TERMOS_USO = $temos_uso->ID_MPME_ARQUIVO;
                    $exportador->ID_MPME_ARQUIVO_COND_GERAIS = $cond_geral->ID_MPME_ARQUIVO;
                    $exportador->ID_MPME_ARQUIVO_COND_PARTICULARES = $cond_part->ID_MPME_ARQUIVO;
                    $exportador->ID_MPME_ARQUIVO_COND_ESPECIAIS =  $cond_esp->ID_MPME_ARQUIVO;
                    if($exportador->save()){
                        DB::commit();
                        self::enviarEmailNotificacaoExportador($request, $notificacao, $exportador, $gerar_pdf);
                    }else{
                        DB::rollback();
                        return false;
                    }


                    break;
                default: //notificar usuario

                    break;
            }

             DB::commit();
            return $notificacao;
        } else {
            DB::rollback();
            return false;
        }

    }

    public static function NotificaProximaAlcada($request)
    {
        $notificacao = (isset($request->ID_NOTIFICACAO) && trim($request->ID_NOTIFICACAO) != "") ? MpmeNotificacao::where($request->ID_NOTIFICACAO)->where('ID_MPME_ALCADA', '=', $request->ID_MPME_ALCADA)->first() : new MpmeNotificacao();
        $notificacao->ID_STATUS_NOTIFICACAO_FK = $request->ID_STATUS_NOTIFICACAO_FK;
        $notificacao->ID_USUARIO_FK = Auth::User()->ID_USUARIO;
        $notificacao->ID_OPER = (isset($request->ID_OPER)) ? $request->ID_OPER : null;
        $notificacao->DE_NOTIFICACAO = $request->NM_USUARIO . ' - ' . $request->RAZAO_SOCIAL;
        $notificacao->DS_LINK = '';
        $notificacao->ID_MPME_ALCADA = (isset($request->IC_DEVOLVEU_ALCADA_ANTERIOR) && $request->IC_DEVOLVEU_ALCADA_ANTERIOR != 0) ? $request->ID_MPME_ALCADA - 1 : $request->ID_MPME_ALCADA_PROXIMA;
        $notificacao->IC_ATIVO = 1;

        if ($notificacao->save()) {
            //notificando usuario por email
            switch ($request->ID_STATUS_NOTIFICACAO_FK) {
                case '500':
                case '501':
                case '502':
                case '503':
                    self::enviarEmailNotificacao($request, $notificacao);
                    break;

                default: //notificar usuario

                    break;
            }

            return true;
        } else {
            return false;
        }
    }

    public static function desativaNotificacao($request)
    {
        $atualizaNotif = MpmeNotificacao::where('ID_OPER', '=', $request->ID_OPER)->update(['IC_ATIVO' => 0]);
        if ($atualizaNotif) {
            return true;
        } else {
            return false;
        }

    }

    public static function desativaNotificacaoPorAlcada($request, $ID_MPME_ALCADA)
    {
        $atualizaNotif = MpmeNotificacao::where('ID_OPER', '=', $request->ID_OPER)->where('ID_MPME_ALCADA', '=', $ID_MPME_ALCADA)->update(['IC_ATIVO' => 0]);
        if ($atualizaNotif) {
            return true;
        } else {
            return false;
        }

    }

    public static function indeferir($request)
    {
        $notificacao = (isset($request->ID_NOTIFICACAO) && trim($request->ID_NOTIFICACAO) != "") ? MpmeNotificacao::where($request->ID_NOTIFICACAO)->where('ID_MPME_ALCADA', '=', $request->ID_MPME_ALCADA)->first() : new MpmeNotificacao();
        $notificacao->ID_STATUS_NOTIFICACAO_FK = $request->ID_STATUS_NOTIFICACAO_FK;
        $notificacao->ID_USUARIO_FK = Auth::User()->ID_USUARIO;
        $notificacao->ID_OPER = (isset($request->ID_OPER)) ? $request->ID_OPER : null;
        $notificacao->DE_NOTIFICACAO = $request->NM_USUARIO . ' - ' . $request->RAZAO_SOCIAL;
        $notificacao->DS_LINK = '';
        $notificacao->ID_MPME_ALCADA = $request->ID_MPME_ALCADA;
        $notificacao->IC_ATIVO = 1;

        if ($notificacao->save()) {
            //notificando usuario por email
            switch ($request->ID_STATUS_NOTIFICACAO_FK) {
                case '505':
                    self::enviarEmailIndeferir($request, $notificacao);
                    break;

                default: //notificar usuario

                    break;
            }

            return true;
        } else {
            return false;
        }
    }

    public static function CriaNotificacaoCGCondGerais($request)
    {
        // Verifica se ja existe esse tipo de notificacao para o usuario
        //caso exista atualiza ela, caso contrario cria uma nova
        $notificacao = (isset($request->ID_USUARIO) &&
            trim($request->ID_USUARIO) != "") ?
        MpmeNotificacao::where('ID_USUARIO_FK',
            '=', $request->ID_USUARIO)
            ->where('ID_STATUS_NOTIFICACAO_FK', '=', 506)
            ->first()
        : new MpmeNotificacao();
        $notificacao = (isset($notificacao) && !empty($notificacao)) ? $notificacao : new MpmeNotificacao();
        $notificacao->ID_STATUS_NOTIFICACAO_FK = 506; // CGC COND. GERAIS
        $notificacao->ID_USUARIO_FK = $request->ID_USUARIO;
        $notificacao->DE_NOTIFICACAO = 'CGC - CONDIÇÕES GERAIS GERADO';
        $notificacao->IC_ATIVO = 1;
        $notificacao->ID_USUARIO_EXPORTADOR = $request->ID_USUARIO;
        $notificacao->DS_LINK = '/abgf/cgc';
        $notificacao->ID_MPME_ALCADA = 1; //CLIENTE

        if ($notificacao->save()) {
            return true;
        } else {
            return false;
        }

    }

    public static function enviarEmailNotificacao($request, $notificacao)
    {

        $usuario_alcada = new MpmeUsuarioAlcada();
        $usuario_alcada_selecioando = $usuario_alcada->where("ID_MPME_ALCADA", '=', $notificacao->ID_MPME_ALCADA)
            ->where("IN_PODE_SALVAR", '=', 'S')
            ->get();
        if (count($usuario_alcada_selecioando) > 0) {
            foreach ($usuario_alcada_selecioando as $usuarios_alcada) {
                Mail::send('mail.notificacoes.notificacao', ['notificacao' => $notificacao, 'usuarios_alcada' => $usuarios_alcada], function ($mail) use ($request, $usuarios_alcada) {
                    $mail->to($usuarios_alcada->usuario->DE_EMAIL, $usuarios_alcada->usuario->NM_USUARIO)
                        ->from('sistemas@abgf.gov.br', 'SCE MPME')
                        ->subject('Solicitação de Analíse');
                });
            }
        }

    }

    public static function enviarEmailNotificacaoExportador($request, $notificacao, $exportador, $id_arquivo_reg)
    {

        $usuario = User::where('ID_USUARIO', '=', $exportador->ID_USUARIO_CAD)->first();


        if ($usuario->count() > 0) {

                Mail::send('mail.regras_condicoes.limite_aprovado', ['notificacao' => $notificacao, 'usuario' => $usuario, 'operacao' => $exportador, 'id_arquivo_reg' => $id_arquivo_reg], function ($mail) use ($request, $usuario, $exportador, $id_arquivo_reg)
                    {
                        $mail->to($usuario->DE_EMAIL, $usuario->NM_USUARIO)
                            ->from('sistemas@abgf.gov.br', 'SCE MPME')
                            ->subject('Solicitação de Analíse Aprovada');
                    }
                );
            }
    }

    public static function enviarEmailDevolucao($request, $notificacao)
    {

        $usuario_alcada = new MpmeUsuarioAlcada();
        $usuario_alcada_selecioando = $usuario_alcada->where("ID_MPME_ALCADA", '=', $notificacao->ID_MPME_ALCADA)
            ->where("IN_PODE_SALVAR", '=', 'S')
            ->get();
        if (count($usuario_alcada_selecioando) > 0) {
            foreach ($usuario_alcada_selecioando as $usuarios_alcada) {
                Mail::send('mail.notificacoes.devolucao', ['notificacao' => $notificacao, 'usuarios_alcada' => $usuarios_alcada], function ($mail) use ($request, $usuarios_alcada) {
                    $mail->to($usuarios_alcada->usuario->DE_EMAIL, $usuarios_alcada->usuario->NM_USUARIO)
                        ->from('sistemas@abgf.gov.br', 'SCE MPME')
                        ->subject('Devolução da Analíse');
                });
            }
        }

    }

    public static function enviarEmailIndeferir($request, $notificacao)
    {
        $usuarios_alcada = User::where('ID_USUARIO', '=', $request->ID_NOVO_USUARIO)->first();
        Mail::send('mail.notificacoes.indeferir', ['notificacao' => $notificacao, 'usuarios_alcada' => $usuarios_alcada], function ($mail) use ($request, $usuarios_alcada) {
            $mail->to($usuarios_alcada->DE_EMAIL, $usuarios_alcada->NM_USUARIO)
                ->from('sistemas@abgf.gov.br', 'SCE MPME')
                ->subject('Limite não aprovado');
        });

    }

    public static function atualizaNotificacaoDadosExportador($request)
    {
        $notificacao = MpmeNotificacao::find($request->ID_NOTIFICACAO);
        $notificacao->TIPO_VALIDACAO = (isset($request->tipoPermissao) && $request->tipoPermissao == 'V') ? (isset($request->ATUALIZACAO_CADASTRAL) && $request->ATUALIZACAO_CADASTRAL == 'ATUALIZACAO_CADASTRAL') ? 'U' : 'A' : 'V';
        $notificacao->MT_DEV_DADOS = '';
        if ($notificacao->save()) {
            return true;
        } else {
            return false;
        }

    }

    public static function devolveParaValidador($request)
    {
        $notificacao = MpmeNotificacao::find($request->ID_NOTIFICACAO);
        $notificacao->TIPO_VALIDACAO = 'C';
        $notificacao->MT_DEV_DADOS = $request->motivo;
        if ($notificacao->save()) {
            return true;
        } else {
            return false;
        }
    }

}
