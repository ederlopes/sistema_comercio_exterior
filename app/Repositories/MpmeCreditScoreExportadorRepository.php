<?php
namespace App\Repositories;
use DB;
use App\MpmeArquivo;
use Carbon\Carbon;
use App\MpmeCreditScoreExportadores;
use Auth;
use File;
use Illuminate\Support\Facades\Storage;


class MpmeCreditScoreExportadorRepository extends Repository{

  public function __construct()
    {
        $this->setModel(MpmeCreditScoreExportadores::class);
    }


  public static function salvaCreditStoreExportador($request){




                if(isset($request->parecer_pdf) && $request->parecer_pdf != 0){

                    // Define o valor default para a variável que contém o nome da imagem
                    $nameFile = null;

                    // Verifica se informou o arquivo e se é válido
                    if ($request->hasFile('parecer_pdf') && $request->file('parecer_pdf')->isValid()) {

                        // Define um aleatório para o arquivo baseado no timestamps atual
                        $name = $request->ID_MPME_ALCADA . '.' . $request->ID_OPER; // ID_ALCADA.ID_OPER

                        // Recupera a extensão do arquivo
                        $extension = $request->parecer_pdf->extension();

                        // Define finalmente o nome
                        $nameFile = "{$name}.{$extension}";

                        // Faz o upload:
                        $upload = $request->parecer_pdf->storeAs('public/parecer_tecnico', $nameFile);
                        // Se tiver funcionado o arquivo foi armazenado em storage/app/public/parecer_tecnico/nomedinamicoarquivo.extensao

                        if(isset($request->id_mpme_arquivo) && $request->id_mpme_arquivo != 0){
                            $parecer_pdf = MpmeArquivo::where('ID_MPME_ARQUIVO',$request->id_mpme_arquivo)->where('ID_USUARIO_CAD', Auth::user()->ID_USUARIO)->first();
                        }else{
                            $parecer_pdf =  new MpmeArquivo();
                        }

                        if($parecer_pdf == ""){
                            $parecer_pdf = new MpmeArquivo();
                        }


                        $parecer_pdf->ID_MPME_TIPO_ARQUIVO = 18; // PARECER DA OPERAÇÃO
                        $parecer_pdf->ID_OPER = $request->ID_OPER;
                        $parecer_pdf->NO_DIRETORIO = '/parecer_tecnico';
                        $parecer_pdf->NO_ARQUIVO = $nameFile;
                        $parecer_pdf->NO_EXTENSAO = $extension;
                        $parecer_pdf->DT_CADASTRO = Carbon::now();
                        $parecer_pdf->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;

                        // Verifica se NÃO deu certo o upload (Redireciona de volta)
                        if (!$upload || !$parecer_pdf->save()) {
                            return false;
                        }

                    }else{
                        return false;
                    }

                }




            $creditScore = (isset($request->ID_CREDIT_SCORE_PRE) && trim($request->ID_CREDIT_SCORE_PRE) !="") ? MpmeCreditScoreExportadores::find($request->ID_CREDIT_SCORE_PRE) : new MpmeCreditScoreExportadores();
            $creditScore->ID_OPER       = $request->ID_OPER;
            $creditScore->ID_USUARIO_FK = Auth::user()->ID_USUARIO;
            $creditScore->ID_MPME_ALCADA    = $request->ID_MPME_ALCADA;
            $creditScore->DS_PARECER    = $request->ds_parecer;
            $creditScore->VL_AVAL1      = $request->aval1_pre;
            $creditScore->VL_AVAL2      = 0;
            $creditScore->VL_AVAL3      = $request->aval3_pre;
            $creditScore->VL_AVAL4      = $request->aval4_pre;
            $creditScore->VL_AVAL5      = $request->aval5_pre;
            $creditScore->VL_AVAL6      = $request->aval6_pre;
            $creditScore->VL_AVAL7      = $request->aval7_pre;
            $creditScore->VL_AVAL8      = $request->aval8_pre;
            $creditScore->VL_AVAL9      = (trim($request->aval9_pre) != "") ? $request->aval9_pre : $request->aval92_pre;
            $creditScore->VL_AVAL10     = $request->aval10_pre;
            $creditScore->VL_AVAL11     = $request->aval11_pre;
            $creditScore->VL_AVAL12     = $request->aval12_pre;
            $creditScore->VL_AVAL13     = $request->aval13_pre;
            $creditScore->VL_AVAL14     = $request->aval14_pre;
            $creditScore->DT_REGISTRO_AVALIACAO = date('Y-m-d h:m:s');
            $creditScore->DATA_CADASTRO = date('Y-m-d h:m:s');
            $creditScore->CREDIT_SCORE = $request->nota_credit_score_importador_pre;
            (isset($request->MOTIVO_ALTERACAO_CREDIT_SCORE) && $request->MOTIVO_ALTERACAO_CREDIT_SCORE !="") ?  $creditScore->MOTIVO_ALTERACAO = $request->MOTIVO_ALTERACAO_CREDIT_SCORE: '';


      if(isset($request->parecer_pdf) && $request->parecer_pdf != 0 && $request->parecer_pdf != null) {
              $creditScore->ID_MPME_ARQUIVO = $parecer_pdf->ID_MPME_ARQUIVO;
          }

          if(isset($request->optradio) && $request->optradio == 'manter' && (isset($request->id_mpme_arquivo) && $request->id_mpme_arquivo !="")){

              $delArquivo = MpmeArquivo::where('ID_MPME_ARQUIVO',$request->id_mpme_arquivo)->where('ID_USUARIO_CAD', Auth::user()->ID_USUARIO)->first();

              if($delArquivo != null){
                  $delArquivo->delete();
              }

              $parecer_pdf = new MpmeArquivo();
              $parecer_pdf->ID_MPME_TIPO_ARQUIVO = 18; // PARECER DA OPERAÇÃO
              $parecer_pdf->ID_OPER = $request->ID_OPER;
              $parecer_pdf->NO_DIRETORIO = '/parecer_tecnico';
              $parecer_pdf->NO_ARQUIVO = $request->nome_arquivo;
              $parecer_pdf->NO_EXTENSAO = '.pdf';
              $parecer_pdf->DT_CADASTRO = Carbon::now();
              $parecer_pdf->ID_USUARIO_CAD = Auth::user()->ID_USUARIO;
              if(!$parecer_pdf->save()){
                  DB::rollback();
                  return false ;
              }

              $creditScore->ID_MPME_ARQUIVO = $parecer_pdf->ID_MPME_ARQUIVO;

          }

            if($creditScore->save()){
              return true;
            }else{
              return false;
            }

  }


}
