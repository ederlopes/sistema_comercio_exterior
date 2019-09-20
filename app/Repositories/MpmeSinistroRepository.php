<?php
namespace App\Repositories;
use App\MpmeRestricaoAbgf;
use DB;
use App\MpmeTempoValidacao;
use Auth;
use App\MpmeSinistro;
use Carbon\Carbon;
use App\MpmeArquivo;
use File;

class MpmeSinistroRepository extends Repository{

    public function __construct()
    {
        $this->setModel(MpmeSinistro::class);
    }



    public static function salvarSinistro($request){

        // Verifica se ja existe sinistro cadastrado, caso haja faz a busca pelo find, caso contrario cria um novo
        $cadSinistro = ($request->id_sinistro != '') ? MpmeSinistro::find($request->id_sinistro) : new MpmeSinistro();

        $cadSinistro->ID_OPER     = $request->ID_OPER;
        $cadSinistro->ID_PROPOSTA = $request->ID_PROPOSTA;
        $cadSinistro->ID_FINANCIADOR_FK = $request->ID_FINANCIADOR;

        /**
         * **************************************** Status ***********************************************************
        **/

        ($request->DT_DAS != '') ? $cadSinistro->ID_MPME_SINISTRO_STATUS = 5 : ''; // Status Em ameaça

        if($request->IN_RENEGOCIACAO ?? '' == 5){
           
            ($request->DT_PAGAMENTO_INDENIZACAO != '') ? $cadSinistro->ID_MPME_SINISTRO_STATUS = 8 : ''; // Idenizada

            switch ($request->ID_REGULACAO_SINISTRO) {
                case 1:
                    $cadSinistro->ID_MPME_SINISTRO_STATUS = 6; // Status Deferir
                    break;
                case 2:
                    $cadSinistro->ID_MPME_SINISTRO_STATUS = 7; // Status Indeferir
                    break;
            }

        }

        (!empty(@array_filter($request->DT_PREVISTA))) ? $cadSinistro->ID_MPME_SINISTRO_STATUS = 9 : ''; // Em recuperação

        $valorPago = 0;
        if($request->VLPAGO ?? '' != ""){
        
            for ($i = 0; $i < count($request->VLPAGO); $i ++) {
                $valorPago = $valorPago + converte_float($request->VLPAGO[$i]); // Soma os valores pagos para validar o status como recuperado.
            }
        }

        $valorPagoEmAtraso = 0;
        if($request->IN_PAGTO_ATRASO ?? '' == 'S') //caso tenha checado pagamento em atraso
        {
           
            for ($i = 0; $i < @count($request->VLPGT); $i ++) {
                $valorPagoEmAtraso = $valorPagoEmAtraso + converte_float($request->VLPGT[$i]); // Soma os valores pagos para validar o status como recuperado.
            }
    
        }

        // Caso tenha checado renegociacao
        if($request->IN_RENEGOCIACAO ?? '' == 'S'){

            // Soma o valor Recuperado
            $valorRecuperadoTotal = 0;
            for ($i = 0; $i < @count($request->VLPGTREC); $i ++) {
                $valorRecuperadoTotal = $valorRecuperadoTotal + converte_float($request->VLPGTREC[$i]); // Soma os valores pagos para validar o status como recuperado.
            }

            $valorRecuperadoTotal = $valorRecuperadoTotal + $valorPago + $valorPagoEmAtraso;

            // dd(($request->VA_REPACTUACAO_RENEGOCIACAO != '') ? converte_float($request->VA_REPACTUACAO_RENEGOCIACAO) : converte_float($request->recuperada));
            $saldoDevedor = 0;

            if ($request->VA_REPACTUACAO_RENEGOCIACAO != '') {
                $saldoDevedor = converte_float($request->VA_REPACTUACAO_RENEGOCIACAO);
            } else {
                $saldoDevedor = converte_float($request->harecuperar ?? '');
            }

            ($valorRecuperadoTotal >= $saldoDevedor) ? $cadSinistro->ID_MPME_SINISTRO_STATUS = 10 : ''; // Recuperada

         }
        /**
         * **************************************** Status ***********************************************************
         */

        $cadSinistro->IN_DAS = ($request->DT_DAS != '') ? 'S' : null;

        ($request->DT_DAS != '') ? $cadSinistro->DT_DAS = Carbon::createFromFormat('d/m/Y', $request->DT_DAS)->toDateString() : '';
        ($request->DT_CANCELAMENTO_DAS != '') ? $cadSinistro->DT_CANCELAMENTO_DAS = Carbon::createFromFormat('d/m/Y', $request->DT_CANCELAMENTO_DAS)->toDateString() : '';
        ($request->ID_MOTIVO_CANCELAMENTO_DAS != '' && $request->ID_MOTIVO_CANCELAMENTO_DAS != 'Selecione') ? $cadSinistro->ID_MOTIVO_CANCELAMENTO_DAS = $request->ID_MOTIVO_CANCELAMENTO_DAS : '';

        $cadSinistro->IN_ENVIO_CARTA_COBRANCA = ($request->DT_ENVIO_CARTA_COBRANCA != '') ? 'S' : null;

        ($request->DT_ENVIO_CARTA_COBRANCA != '') ? $cadSinistro->DT_ENVIO_CARTA_COBRANCA = Carbon::createFromFormat('d/m/Y', $request->DT_ENVIO_CARTA_COBRANCA)->toDateString() : '';

        ($request->DT_ENVIO_PARECER_TECNICO ?? '' != '') ? $cadSinistro->IN_ENVIO_PARECER_TECNICO = ($request->DT_ENVIO_PARECER_TECNICO != '') ? 'S' : null : '';

        ($request->DT_ENVIO_PARECER_TECNICO ?? '' != '') ? $cadSinistro->DT_ENVIO_PARECER_TECNICO = Carbon::createFromFormat('d/m/Y', $request->DT_ENVIO_PARECER_TECNICO)->toDateString() : '';
      
         
       
        if(isset($request->arquivo) )
        {  

        $pasta = public_path('/uploads/abgf/sinistro/' . $request->ID_OPER . '/');

            if (! File::exists($pasta)) {
                File::makeDirectory($pasta, 0777, true, true);
            }

            $arquivo = $request->file('arquivo');
            $novo_nome = $request->ID_OPER . '.' . $arquivo->getClientOriginalExtension();
            $arquivo->move(public_path('/uploads/abgf/sinistro/' . $request->ID_OPER), $novo_nome);

            $uploadDreBanco = new MpmeArquivo();
            $uploadDreBanco->ID_MPME_TIPO_ARQUIVO = 17; // das sinistro
            $uploadDreBanco->NO_DIRETORIO = $pasta;
            $uploadDreBanco->NO_ARQUIVO = $novo_nome;
            $uploadDreBanco->DT_CADASTRO = date('Y-m-d');
            $uploadDreBanco->ID_USUARIO_CAD = Auth::User()->ID_USUARIO;
            $uploadDreBanco->NO_EXTENSAO = $arquivo->getClientOriginalExtension();
            $uploadDreBanco->save();
           

        
        ($request->arquivo ?? '' != '') ? $cadSinistro->NO_ARQ_PARECER_TECNICO_UPLOAD = $novo_nome : '';
        ($request->caminho_arquivo != '') ? $cadSinistro->NO_DIR_PARECER_TECNICO_UPLOAD = $pasta : '';

        }   

        $cadSinistro->IN_ENVIO_DS_PI = ($request->DT_ENVIO_DS_PI != '') ? 'S' : null;

        ($request->DT_ENVIO_DS_PI != '') ? $cadSinistro->DT_ENVIO_DS_PI = Carbon::createFromFormat('d/m/Y', $request->DT_ENVIO_DS_PI)->toDateString() : '';

        ($request->ID_FATO_GERADOR_SINISTRO != '' && $request->ID_FATO_GERADOR_SINISTRO != 'Selecione') ? $cadSinistro->ID_FATO_GERADOR_SINISTRO = $request->ID_FATO_GERADOR_SINISTRO : '';

        $cadSinistro->IN_REGULACAO_SINISTRO = ($request->ID_REGULACAO_SINISTRO != '') ? 'S' : null;

        ($request->ID_REGULACAO_SINISTRO != '' && $request->ID_REGULACAO_SINISTRO != 'Selecione') ? $cadSinistro->ID_REGULACAO_SINISTRO = $request->ID_REGULACAO_SINISTRO : '';

        ($request->DT_REGULACAO_SINISTRO != '') ? $cadSinistro->DT_REGULACAO_SINISTRO = Carbon::createFromFormat('d/m/Y', $request->DT_REGULACAO_SINISTRO)->toDateString() : '';

        ($request->DT_REVOGACAO_SINISTRO != '') ? $cadSinistro->DT_REVOGACAO_SINISTRO = Carbon::createFromFormat('d/m/Y', $request->DT_REVOGACAO_SINISTRO)->toDateString() : '';

        ($request->ID_MOTIVO_REVOGACAO_SINISTRO != '' && $request->ID_MOTIVO_REVOGACAO_SINISTRO != 'Selecione') ? $cadSinistro->ID_MOTIVO_REVOGACAO_SINISTRO = $request->ID_MOTIVO_REVOGACAO_SINISTRO : '';

        $cadSinistro->IN_MOTIVO_NAO_DEFERIMENTO_SINISTRO = ($request->ID_MOTIVO_NAO_DEFERIMENTO_SINISTRO ?? '' != '') ? 'S' : null;

        ($request->ID_MOTIVO_REVOGACAO_SINISTRO != '' && $request->ID_MOTIVO_REVOGACAO_SINISTRO != 'Selecione') ? $cadSinistro->ID_MOTIVO_NAO_DEFERIMENTO_SINISTRO = $request->ID_MOTIVO_NAO_DEFERIMENTO_SINISTRO : '';

        $cadSinistro->IN_CARACTERIZACAO_SINISTRO = ($request->DT_CARACTERIZACAO_SINISTRO != '') ? 'S' : null;

        ($request->DT_CARACTERIZACAO_SINISTRO != '') ? $cadSinistro->DT_CARACTERIZACAO_SINISTRO = Carbon::createFromFormat('d/m/Y', $request->DT_CARACTERIZACAO_SINISTRO)->toDateString() : '';

        $cadSinistro->IN_PAGAMENTO_EM_ATRASO = ($request->IN_PAGAMENTO_EM_ATRASO ?? '' != '') ? 'S' : null;

        $cadSinistro->IN_ENVIO_COMUNICADO_GESTOR = ($request->DT_ENVIO_COMUNICADO_GESTOR != '') ? 'S' : null;

        ($request->DT_ENVIO_COMUNICADO_GESTOR != '') ? $cadSinistro->DT_ENVIO_COMUNICADO_GESTOR = Carbon::createFromFormat('d/m/Y', $request->DT_ENVIO_COMUNICADO_GESTOR)->toDateString() : '';

        $cadSinistro->IN_PAGAMENTO_INDENIZACAO = ($request->DT_PAGAMENTO_INDENIZACAO != '') ? 'S' : null;

        ($request->DT_PAGAMENTO_INDENIZACAO != '') ? $cadSinistro->DT_PAGAMENTO_INDENIZACAO = Carbon::createFromFormat('d/m/Y', $request->DT_PAGAMENTO_INDENIZACAO)->toDateString() : '';
        ($request->VA_PAGAMENTO_INDENIZACAO != '') ? $cadSinistro->VA_PAGAMENTO_INDENIZACAO = converte_float($request->VA_PAGAMENTO_INDENIZACAO) : '';

        $cadSinistro->IN_ASSINATURA_CONTRATO_RENEGOCIACAO = ($request->DT_ASSINATURA_CONTRATO_RENEGOCIACAO != '') ? 'S' : null;

        ($request->DT_ASSINATURA_CONTRATO_RENEGOCIACAO != '') ? $cadSinistro->DT_ASSINATURA_CONTRATO_RENEGOCIACAO = Carbon::createFromFormat('d/m/Y', $request->DT_ASSINATURA_CONTRATO_RENEGOCIACAO)->toDateString() : '';
        ($request->VA_REPACTUACAO_RENEGOCIACAO != '') ? $cadSinistro->VA_REPACTUACAO_RENEGOCIACAO = converte_float($request->VA_REPACTUACAO_RENEGOCIACAO) : '';
        ($request->NU_PARCELA_REPACTUACAO_RENEGOCIACAO != '') ? $cadSinistro->NU_PARCELA_REPACTUACAO_RENEGOCIACAO = trim($request->NU_PARCELA_REPACTUACAO_RENEGOCIACAO, '\n') : '';
        $cadSinistro->ID_USUARIO_ALT = Auth::User()->ID_USUARIO;
        $cadSinistro->DT_ALTERACAO   = Carbon::now();
        $cadSinistro->ID_USUARIO_CAD = Auth::User()->ID_USUARIO;
        $cadSinistro->DATA_CADASTRO  = Carbon::now();
        $cadSinistro->save();
        return $cadSinistro;


    }



}
