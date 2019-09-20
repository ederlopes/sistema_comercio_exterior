<?php
namespace App\Http\Controllers;

use App\ModalidadeModel;
use App\MpmePerfil;
use App\StatusOper;
use DB;
use PDF;
use Illuminate\Http\Request;

class RelatoriosController extends Controller
{
    public function index(Request $request, StatusOper $statusOper, ModalidadeModel $modalidadeModel, MpmePerfil $mpmePerfil)
    {
        $tabela_usuarios = DB::select("exec sp_columns USUARIOS");
        $tabela_operacoes = DB::select("exec sp_columns MPME_IMPORTADORES");
        $tabela_proposta = DB::select("exec sp_columns MPME_PROPOSTA");
        $tabela_credit_score_importador = DB::select("exec sp_columns MPME_CREDIT_SCORE");
        $tabela_credit_score_exportador = DB::select("exec sp_columns MPME_CREDIT_SCORE_EXPORTADORES");
        $tabela_status_operacao = DB::select("exec sp_columns STATUSOPER");
        $tabela_status_proposta = DB::select("exec sp_columns MPME_STATUS_PROPOSTA");

        $rs_modalidade = $modalidadeModel::all();
        $rs_status_operacao = $statusOper->where('IN_VISUALIZA_INTERNO', '=', 'S')->orderBy('NM_OPER')->get();
        $rs_perfil = $mpmePerfil::all();

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'tabela_operacoes' => $tabela_operacoes,
            'tabela_usuarios' => $tabela_usuarios,
            'tabela_proposta' => $tabela_proposta,
            'tabela_status_operacao' => $tabela_status_operacao,
            'tabela_status_proposta' => $tabela_status_proposta,
            'tabela_credit_score_importador' => $tabela_credit_score_importador,
            'tabela_credit_score_exportador' => $tabela_credit_score_exportador,
            'rs_status_operacao' => $rs_status_operacao,
            'rs_modalidade' => $rs_modalidade,
            'rs_perfil' => $rs_perfil,
        );

        return view('relatorios.novo-relatorio', $compact_args);
    }

    public function gerar_relatorio(Request $request)
    {
        $sql = "SELECT ";

        if ($request->ckd_tabela_usuarios == 'S') {
            $sql .= ' ' . implode(", ", $request->tabela_usuario);
        }

        if ($request->ckd_tabela_operacoes == 'S') {
            $sql .= ' ,' . implode(", ", $request->tabela_operacoes);
        }

        if ($request->ckd_tabela_status_operacao == 'S') {
            $sql .= ' ,' . implode(", ", $request->tabela_status_operacao);
        }

        if ($request->ckd_tabela_proposta == 'S') {
            $sql .= ' ,' . implode(", ", $request->tabela_proposta);
        }

        if ($request->ckd_tabela_status_proposta == 'S') {
            $sql .= ' ,' . implode(", ", $request->tabela_status_proposta);
        }

        if ($request->ckd_tabela_credit_score_importador == 'S') {
            $sql .= ' ,' . implode(", ", $request->tabela_credit_score_importador);
        }

        if ($request->ckd_tabela_credit_score_exportador == 'S') {
            $sql .= ' ,' . implode(", ", $request->tabela_credit_score_exportador);
        }

        $sql .= " from USUARIOS ";

        if ($request->ckd_tabela_operacoes == 'S') {
            $sql .= ' INNER JOIN MPME_IMPORTADORES ON (MPME_IMPORTADORES.ID_USUARIO = USUARIOS.ID_USUARIO) ';
        }

        if ($request->ckd_tabela_proposta == 'S') {
            $sql .= ' INNER JOIN MPME_PROPOSTA ON (MPME_IMPORTADORES.ID_OPER = MPME_PROPOSTA.ID_OPER) ';
        }

        if ($request->ckd_tabela_status_proposta == 'S') {
            $sql .= ' INNER JOIN MPME_STATUS_PROPOSTA ON (MPME_PROPOSTA.ID_MPME_STATUS_PROPOSTA = MPME_STATUS_PROPOSTA.ID_MPME_STATUS_PROPOSTA) ';
        }

        if ($request->ckd_tabela_status_operacao == 'S') {
             $sql .= ' INNER JOIN STATUSOPER ON (MPME_IMPORTADORES.ST_OPER = STATUSOPER.ST_OPER) ';
        }

        if ($request->ckd_tabela_credit_score_importador == 'S') {
            $sql .= ' LEFT JOIN MPME_CREDIT_SCORE ON (MPME_CREDIT_SCORE.ID_OPER = MPME_IMPORTADORES.ID_OPER) ';
        }

        if ($request->ckd_tabela_credit_score_exportador == 'S') {
            $sql .= ' LEFT JOIN MPME_CREDIT_SCORE_EXPORTADORES ON (MPME_CREDIT_SCORE_EXPORTADORES.ID_OPER = MPME_IMPORTADORES.ID_OPER)';
        }

        $sql .= ' WHERE 1=1 ';

        if ($request->tipo_usuario != "") {
            $sql .= " AND USUARIOS.TP_USUARIO = '$request->tipo_usuario' ";
        }

        if ($request->fl_ativo != "") {
            $sql .= " AND USUARIOS.FL_ATIVO = '$request->fl_ativo' ";
        }

        if ($request->id_perfil_usuario != "0") {
            $sql .= ' AND USUARIOS.ID_PERFIL = ' . $request->id_perfil_usuario;
        }

        if ($request->id_oper != "") {
            $sql .= ' AND MPME_IMPORTADORES.ID_OPER = ' . $request->id_oper;
        }

        if ($request->st_oper != "0") {
            $sql .= ' AND MPME_IMPORTADORES.ST_OPER  in (' . $request->st_oper . ')';
        }

        if ($request->id_modalidade != "0") {
            $sql .= ' AND MPME_IMPORTADORES.ID_MODALIDADE  in (' . $request->id_modalidade . ')';
        }

        $rsDados = DB::select($sql);

        $compact_args = array(
            'request' => $request,
            'class' => $this,
            'campos' => $rsDados[0],
            'dados' => $rsDados,
            'sql' => $sql,
        );

        if ( $request->ckd_pdf == 'S'){
            $pdf =  PDF::loadView('relatorios.index-relatorio', $compact_args)->setPaper('a4', 'landscape');
            return $pdf->download('relatorio.pdf');
        }else{
            return view('relatorios.index-relatorio', $compact_args);
        }


    }

}
