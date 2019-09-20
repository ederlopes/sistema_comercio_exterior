<?php
namespace App\Repositories;
use Carbon\Carbon;
use DB;
use App\ImportadoresModel;
use Auth;
use App\MpmeHistQuestionario;
use App\MpmeVigenciaRelatorio;

class MpmeImportadoresRepository extends Repository{

    const NAO_HA_LIMITE_DISPONIVEL = 21;

  public function __construct()
    {
        $this->setModel(ImportadoresModel::class);
    }


  public static function devolveAlcada($request){
      

    $importadores = ImportadoresModel::find($request->ID_OPER);
    $importadores->FL_MOMENTO   = (isset($request->IC_DEVOLVEU_ALCADA_ANTERIOR) && $request->IC_DEVOLVEU_ALCADA_ANTERIOR != 0) ? $request->ID_MPME_ALCADA - 1 : $request->ID_MPME_ALCADA;

    if($importadores->save()){
      return true;
    }else{
      return false;
    }

  }

  public static function aprovaLimite($request){
      $importadores = ImportadoresModel::find($request->ID_OPER);
      $importadores->FL_MOMENTO = 'APV';
      $importadores->ST_OPER = 5;
      if($importadores->save()){
          return true;
      }else{
          return false;
      }
  }

  public static function indeferir($request){
        $importadores = ImportadoresModel::find($request->ID_OPER);
        $importadores->FL_MOMENTO = 'INDF';
        $importadores->ST_OPER = 9;
        if($importadores->save()){
            return true;
        }else{
            return false;
        }
    }

  public static function getOperacao( $ID_OPER )
  {

      $importador = ImportadoresModel::find($ID_OPER);
      return $importador;

  }


  public function excluir( $ID_OPER )
  {
      $importador           = ImportadoresModel::find($ID_OPER);
      $importador->ST_OPER  = 14;

      if($importador->save()){
          return true;
      }else{
          return false;
      }

  }

  /*
   * Questionario aqui referece a operacao e nao a tabela questionario do sistema
   */
  public function registarLogMovimentacaoQuestionario($dados)
  {
      $movimentacao_questionario_log                    = new  MpmeHistQuestionario();
      $movimentacao_questionario_log->ID_OPER           = $dados['ID_OPER'];
      $movimentacao_questionario_log->ST_OPER           = $dados['ST_OPER'];
      $movimentacao_questionario_log->DT_CADASTRO       = Carbon::now();
      $movimentacao_questionario_log->ID_USUARIO_CAD    = Auth::user()->ID_USUARIO;
      $movimentacao_questionario_log->DS_OBSERVACAO     = strtoupper($dados['DS_OBSERVACAO']);

      if($movimentacao_questionario_log->save()){
          return true;
      }else{
          return false;
      }

  }

  public function controle_operacional ($request)
  {


      /*
       *
       * busca a modalidade e cliente exportador pela operação
       * caso a data seja menor que a data de vigencia ele troca o status para operação em analise pois
       * o relatorio ja foi pago e está dentro da vigencia.       *
       *
       */


      $importadores = $this->where('ID_OPER', '=', $request->id_oper)->first();
      $importadores->ID_MPME_FUNDO_GARANTIA = $request->id_mpme_fundo_garantia_operacao;

      $id_modalidade = retornaModalidade($request->id_oper);


      $idMpmeClienteExportador = retornaClienteExportadorPelaOperacao($request->id_oper);


      $vigenciaRelatorioUsuario = MpmeVigenciaRelatorio::where('ID_MPME_CLIENTE_EXPORTADORES', $idMpmeClienteExportador)->where('ID_MODALIDADE', $id_modalidade)->first();

      $dataHoje = Carbon::now();
      if( ($vigenciaRelatorioUsuario != null) && $dataHoje < Carbon::parse($vigenciaRelatorioUsuario->DT_FIM_VIGENCIA)){
          $importadores->ST_OPER = 3; // Operação em analise

      }else{
          $importadores->ST_OPER = $request->st_oper;
      }


      if($importadores->save()){

          $dados = [
              'ID_OPER'         => $request->id_oper,
              'ST_OPER'         => $request->st_oper,
              'DS_OBSERVACAO'   => 'CONTROLE OPERACIONAL PRIMEIRO LANCAMENTO',
          ];

          if($this->registarLogMovimentacaoQuestionario($dados)){
              return true;
          }else{
              return false;
          }

      }else{
          return false;
      }
  }


  public function negar_limite_operacional($request)
  {

      #Recusar a operacao caso algum dos saldos seja insuficiente
      $count_saldo_negativo = 0;

      if ($request->in_saldo_suficiente_exp == "NAO")
      {
          $count_saldo_negativo += 1;
      }

      $arrayInSaldo = $request->in_saldo_suficiente;

      foreach ($arrayInSaldo as $key => $value) {
          if ($arrayInSaldo[$key] == "NAO"){
              $count_saldo_negativo += 1;
          }
      }



      //criar nova notificacao - NÃO HÁ LIMITE DISPONÍVEL
      $notificacao = new MpmeNotificacaoUsuarioRepository();


      if ($count_saldo_negativo > 0)
      {
          $importadores             = new ImportadoresModel();
          $importadores             = $importadores->where('ID_OPER', '=', $request->id_oper)->first();
          $importadores->ST_OPER    = $this::NAO_HA_LIMITE_DISPONIVEL;

          if($importadores->save())
          {
              $dados = [
                  'ID_OPER'         => $request->id_oper,
                  'ST_OPER'         => $this::NAO_HA_LIMITE_DISPONIVEL,
                  'DS_OBSERVACAO'   => 'CANCELANDO A OPERACAO POR FALTA DE LIMITE OPERACIONAL',
              ];

              $notificacao->registrar_notificacao([
                  'id_mpme_tipo_notificacao' => 16,
                  'id_oper' => $request->id_oper,
              ]);

              $mpmeImportadoresRepository = new MpmeImportadoresRepository();
              if($mpmeImportadoresRepository->registarLogMovimentacaoQuestionario($dados)){
                  return true;
              }else{
                  return false;
              }

          }else{
              return false;
          }
      }else{
          return true;
      }

  }

}
