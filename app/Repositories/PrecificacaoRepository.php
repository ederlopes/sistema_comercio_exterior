<?php

namespace App\Repositories;
use App\Cotacao;
use App\ImportadoresModel;
use App\MoedaModel;
use App\MpmeFormulaPremio;
use App\MpmePrecoCobertura;
use App\MpmePrecoCoberturaSimulacao;
use App\Pais;
use Carbon\Carbon;
use DB;
use Auth;

class PrecificacaoRepository extends Repository{

    private  $mpme_preco_cobertura;

  public function __construct()
  {
        $this->setModel(ImportadoresModel::class);
  }


  public static function processarCalculadora( $request )
  {
      $TP_CALCULO = $request->tp_calculo;

      switch ($TP_CALCULO)
      {
          case 'minimo':
              $TP_RATING_EXP_PRE = 1;
              $TP_RATING_IMP_POS = 1;
              break;
          case 'maximo':
              $TP_RATING_EXP_PRE = 4;
              $TP_RATING_IMP_POS = 4;
              break;
          case 'analista': //esta opção vai ser alimentada de fato com a funcao buscaBaseDadosCalculadora2
              $TP_RATING_EXP_PRE = '';
              $TP_RATING_IMP_POS = '';
              break;
      }

      $id_oper              = $request->id_oper;
      $id_mpme_proposta     = $request->id_mpme_proposta;

      $dadosAlimentarCalculadora  = (array) self::getDadosCalculadora($id_oper, $id_mpme_proposta)[0];


      if ($TP_CALCULO == 'analista')
      {

          $TP_RATING_EXP_PRE = credScore($dadosAlimentarCalculadora['TP_RATING_EXPORTADOR']);
          $TP_RATING_IMP_POS = credScore($dadosAlimentarCalculadora['TP_RATING_IMPORTADOR']);



          if ( $TP_RATING_EXP_PRE == 5  || $TP_RATING_IMP_POS == 5 )
          {

              $preco_cobetura             = new MpmePrecoCobertura();
              $preco_cobetura_selecionado = $preco_cobetura->where('ID_OPER_FK', '=', $id_oper)
                                                           ->where('ID_MPME_PROPOSTA', '=', $id_mpme_proposta);

              $preco_cobetura_selecionado->PC_COB         = 0;
              $preco_cobetura_selecionado->VL_PC_COB      = 0;
              $preco_cobetura_selecionado->PC_COB         = 0;
              $preco_cobetura_selecionado->PC_COB         = 0;
              $preco_cobetura_selecionado->PC_COB         = 0;
              $preco_cobetura_selecionado->save();

              return response()->json(array('status'=> 'erro',
                  'recarrega' => 'erro',
                  'msg' => 'A'
              ));

          }

          switch ($dadosAlimentarCalculadora['ID_MODALIDADE'])
          {
              case '1':
                  $modalidade_desc 	= utf8_decode('Pré-Embarque');
                  break;
              case '2':
                  $modalidade_desc 	= utf8_decode('Pré+Pós Embarque');
                  break;
              case '3':
                  $modalidade_desc 	= utf8_decode('Pós-Embarque');
                  break;
          }

          $arrayDados 			= array(
                                          'ID_OPER' 				=> $id_oper,
                                          'ID_MPME_PROPOSTA'    	=> $id_mpme_proposta,
                                          'TP_CALCULO' 				=> $TP_CALCULO,
                                          'NO_QUALIDADE_PRODUTO' 	=> utf8_decode($dadosAlimentarCalculadora['NO_QUALIDADE_PRODUTO']),

                                          'PC_PRE_COB_COM' 			=> $dadosAlimentarCalculadora['VA_PERC_COB_COM_PRE']/100,
                                          'PC_PRE_COB_POL' 			=> $dadosAlimentarCalculadora['VA_PERC_COB_POL_PRE']/100,

                                          'PC_POS_COB_COM' 			=> $dadosAlimentarCalculadora['VA_PERC_COB_COM_POS']/100,
                                          'PC_POS_COB_POL' 			=> $dadosAlimentarCalculadora['VA_PERC_COB_POL_POS']/100,

                                          'NU_FATOR_PI' 			=> trim($dadosAlimentarCalculadora['VA_FATOR_AJUSTE']),
                                          'CODIGO_MODAL_CALC' 		=> (int)$dadosAlimentarCalculadora['ID_MODALIDADE'],
                                          'TP_PRODUTO' 				=> $modalidade_desc,
                                          'NU_PRAZO_PRE' 			=> (int)$dadosAlimentarCalculadora['PRAZO_PRE'],
                                          'TP_RATING_EXP_PRE' 		=> $TP_RATING_EXP_PRE, //rating exportador
                                          'NU_RATING_BRA_PRE' 		=> (int)$dadosAlimentarCalculadora['CD_RISCO_EXPORTADOR'],
                                          'NU_PRAZO_POS' 			=> (int)$dadosAlimentarCalculadora['PRAZO_POS'],
                                          'TP_RATING_IMP_POS' 		=> $TP_RATING_IMP_POS, //rating importador
                                          'NU_RATING_PAIS_POS' 		=> (int)$dadosAlimentarCalculadora['CD_RISCO_IMPORTADOR'],
                                          'CD_RISCO_IMPORTADOR' 	=> (int)$dadosAlimentarCalculadora['CD_RISCO_IMPORTADOR'],
                                          'CD_RISCO_EXPORTADOR' 	=> (int)$dadosAlimentarCalculadora['CD_RISCO_EXPORTADOR'],
                                          'PC_ANTECIPADO' 			=> $dadosAlimentarCalculadora['PC_ANTECIPADO'],
                                          'VL_EXP_ANUAL' 			=> $dadosAlimentarCalculadora['VL_EXP_ANUAL'],
                                      );


          if (in_array($dadosAlimentarCalculadora['ST_OPER'], [6,7]) )
          {

              $preco_cobetura                           = new MpmePrecoCobertura();
              $preco_cobetura_selecionado               = $preco_cobetura->where('ID_OPER_FK', '=', $dadosAlimentarCalculadora['ID_OPER'])
                                                                         ->where('ID_MPME_PROPOSTA', '=', $id_mpme_proposta)
                                                                         ->first();

              if ($preco_cobetura_selecionado != NULL)
              {
                  $arrayValoresCobertura["ID_OPER"]              	    = $preco_cobetura_selecionado->ID_OPER_FK;
                  $arrayValoresCobertura["ID_MPME_PROPOSTA"]            = $id_mpme_proposta;
                  $arrayValoresCobertura["PERCENTUAL_CALCULADORA"] 	    = $preco_cobetura_selecionado->PC_COB;
                  $arrayValoresCobertura["VL_COBERTURA_IMP"] 			= $preco_cobetura_selecionado->VL_PC_COB_MAX;
                  $arrayValoresCobertura["VL_COBERTURA_IMP_FORMATDO"] 	= number_format($preco_cobetura_selecionado->VL_PC_COB_MAX, 2, '.', '');
                  $arrayValoresCobertura["PC_COB"] 					    = $preco_cobetura_selecionado->PC_COB;
                  $arrayValoresCobertura["PC_COB_MIN"] 				    = $preco_cobetura_selecionado->PC_COB_MIN;
                  $arrayValoresCobertura["VL_PC_COB"] 				    = $preco_cobetura_selecionado->VL_PC_COB;
                  $arrayValoresCobertura["VL_PC_COB_MIN"] 			    = $preco_cobetura_selecionado->VL_PC_COB_MIN;
                  $arrayValoresCobertura["PC_COB_MAX"] 				    = $preco_cobetura_selecionado->PC_COB_MAX;
                  $arrayValoresCobertura["VL_PC_COB_MAX"] 			    = $preco_cobetura_selecionado->VL_PC_COB_MAX;
              }else{
                  $arrayValoresCobertura["ID_OPER"]              	    = $id_oper;
                  $arrayValoresCobertura["ID_MPME_PROPOSTA"]            = $id_mpme_proposta;
              }


          }else{

              //webservice para calcular EXCEL
              $percentualCalculadora            = buscaPercentualCalculadora2($arrayDados);

              //Calcular valores
              $arrayValoresCobertura		    = calcularValorCobertura($percentualCalculadora, $arrayDados);
              
              self::salvarPrecoCobertura($arrayValoresCobertura);

              self::salvarPrecoCoberturaSimulacaoSite($arrayValoresCobertura, $request, 'PROPOSTA');
          }


          return $arrayValoresCobertura;
      }


  }

  public static function processarCalculadoraSimulacao( $request )
    {
        $TP_CALCULO = $request->tp_calculo;

        switch ($TP_CALCULO)
        {
            case 'minimo':
                $TP_RATING_EXP_PRE = 1;
                $TP_RATING_IMP_POS = 1;
                break;
            case 'maximo':
                $TP_RATING_EXP_PRE = 4;
                $TP_RATING_IMP_POS = 4;
                break;
            case 'analista': //esta opção vai ser alimentada de fato com a funcao buscaBaseDadosCalculadora2
                $TP_RATING_EXP_PRE = '';
                $TP_RATING_IMP_POS = '';
                break;
        }

        $id_oper                                = $request->id_oper;
        $id_mpme_proposta                       = $request->id_mpme_proposta;
        $id_modalidade                          =  explode("#", $request->id_cliente_exportadores_modalidade)[1];
        $dadosAlimentarCalculadora              = (array) self::getDadosCalculadora($id_oper, $id_mpme_proposta)[0];




            $TP_RATING_EXP_PRE = credScore($dadosAlimentarCalculadora['TP_RATING_EXPORTADOR']);
            $TP_RATING_IMP_POS = credScore($dadosAlimentarCalculadora['TP_RATING_IMPORTADOR']);

            switch ($id_modalidade)
            {
                case '1':
                    $modalidade_desc 	= utf8_decode('Pré-Embarque');
                    break;
                case '2':
                    $modalidade_desc 	= utf8_decode('Pré+Pós Embarque');
                    break;
                case '3':
                    $modalidade_desc 	= utf8_decode('Pós-Embarque');
                    break;
            }

            //SUBISTITUINDO VALORES PELO DO FORM
            $dadosAlimentarCalculadora['CODIGO_MODAL_CALC']             = $id_modalidade;
            $dadosAlimentarCalculadora['ID_MODALIDADE']                 = $id_modalidade;
            $dadosAlimentarCalculadora['NO_MODALIDADE_CALCULADORA']     = $modalidade_desc;

            $arrayDados 			= array(
                'ID_OPER' 				=> $id_oper,
                'ID_MPME_PROPOSTA'    	=> $id_mpme_proposta,
                'TP_CALCULO' 			=> $TP_CALCULO,
                'NO_QUALIDADE_PRODUTO' 	=> utf8_decode($dadosAlimentarCalculadora['NO_QUALIDADE_PRODUTO']),

                'PC_PRE_COB_COM' 		=> $dadosAlimentarCalculadora['VA_PERC_COB_COM_PRE']/100,
                'PC_PRE_COB_POL' 		=> $dadosAlimentarCalculadora['VA_PERC_COB_POL_PRE']/100,

                'PC_POS_COB_COM' 		=> $dadosAlimentarCalculadora['VA_PERC_COB_COM_POS']/100,
                'PC_POS_COB_POL' 		=> $dadosAlimentarCalculadora['VA_PERC_COB_POL_POS']/100,

                'NU_FATOR_PI' 			=> trim($dadosAlimentarCalculadora['VA_FATOR_AJUSTE']),
                'CODIGO_MODAL_CALC' 	=> (int)$dadosAlimentarCalculadora['ID_MODALIDADE'],
                'TP_PRODUTO' 			=> $modalidade_desc,
                'NU_PRAZO_PRE' 			=> (int)$request->nu_prazo_pre,
                'TP_RATING_EXP_PRE' 	=> $TP_RATING_EXP_PRE, //rating exportador
                'NU_RATING_BRA_PRE' 	=> (int)$dadosAlimentarCalculadora['CD_RISCO_EXPORTADOR'],
                'NU_PRAZO_POS' 			=> (int)$request->nu_prazo_pos,
                'TP_RATING_IMP_POS' 	=> $TP_RATING_IMP_POS, //rating importador
                'NU_RATING_PAIS_POS' 	=> (int)$dadosAlimentarCalculadora['CD_RISCO_IMPORTADOR'],
                'CD_RISCO_IMPORTADOR' 	=> (int)$dadosAlimentarCalculadora['CD_RISCO_IMPORTADOR'],
                'CD_RISCO_EXPORTADOR' 	=> (int)$dadosAlimentarCalculadora['CD_RISCO_EXPORTADOR'],
                'PC_ANTECIPADO' 		=> converte_float($request->va_percentual_dw_payment),
                'VL_EXP_ANUAL' 			=> converte_float($request->vl_proposta),
            );



            //webservice para calcular EXCEL
            $percentualCalculadora            = buscaPercentualCalculadora2($arrayDados);

            if ( $percentualCalculadora == "" || $percentualCalculadora == 0)
            {
                return false;
            }


            //Calcular valores
            $arrayValoresCobertura		    = calcularValorCobertura($percentualCalculadora, $arrayDados);

        self::salvarPrecoCoberturaSimulacaoSite($arrayValoresCobertura, $request, 'SIMULACAO_INTERNA');

            return $arrayValoresCobertura;

    }

  public static function processarCalculadoraSimulacaoSite( $request )
  {
      $PC_PRE_COB_COM                     = NULL;
      $PC_PRE_COB_POL                     = NULL;
      $PC_POS_COB_COM                     = NULL;
      $PC_POS_COB_POL                     = NULL;
      $dadosAlimentarCalculadora          = [];

      $riscoPaisBrasil                    = new Pais();
      $riscoPaisBrasil                    = $riscoPaisBrasil->getRiscoPaisBrasil();

      $PAIS_RISCO_EXP                     = $riscoPaisBrasil->CD_RISCO;
      $id_modalidade                      = $request->id_modalidade;

      if ($request->id_modalidade != 1)
      {
          $arrayDadosImportador               = explode("#", $request->id_pais_importador);
          $id_pais_importador                 = $arrayDadosImportador[0];
          $risco_importador                   = $arrayDadosImportador[1];
      }else{
          $id_pais_importador                 = 0;
          $risco_importador                   = 0;
      }


      $dadosFinanceiros                   = self::getDadosCalculadoraSimulacao([
                                               'enquadramento_simples'   => $request->enquadrado_simples,
                                               'id_modalidade'           => $id_modalidade,
                                            ]);

      $rsValorRelatorio                   = new Pais();
      $rsValorRelatorio                   = $rsValorRelatorio->getValorRelatorio([28, $id_pais_importador]);

      switch ($id_modalidade)
      {
          case '1':
              $modalidade_desc 	    = utf8_decode('Pré-Embarque');
              $PC_PRE_COB_COM	 	= $dadosFinanceiros[0]->VA_PERCENTUAL_COBERTURA_COMERCIAL;
              $PC_PRE_COB_POL	 	= $dadosFinanceiros[0]->VA_PERCENTUAL_COBERTURA_POLITICA;
              break;
          case '2':
              $modalidade_desc 	    = utf8_decode('Pré+Pós Embarque');

              $PC_PRE_COB_COM	 	= $dadosFinanceiros[0]->VA_PERCENTUAL_COBERTURA_COMERCIAL;
              $PC_PRE_COB_POL	 	= $dadosFinanceiros[0]->VA_PERCENTUAL_COBERTURA_POLITICA;

              $PC_POS_COB_COM	 	= $dadosFinanceiros[1]->VA_PERCENTUAL_COBERTURA_COMERCIAL;
              $PC_POS_COB_POL	 	= $dadosFinanceiros[1]->VA_PERCENTUAL_COBERTURA_POLITICA;

              break;
          case '3':
              $modalidade_desc 	    = utf8_decode('Pós-Embarque');
              $PC_POS_COB_COM	 	= $dadosFinanceiros[0]->VA_PERCENTUAL_COBERTURA_COMERCIAL;
              $PC_POS_COB_POL	 	= $dadosFinanceiros[0]->VA_PERCENTUAL_COBERTURA_POLITICA;

              break;
      }

      //SUBISTITUINDO VALORES PELO DO FORM
      $dadosAlimentarCalculadora['CODIGO_MODAL_CALC']             = $id_modalidade;
      $dadosAlimentarCalculadora['ID_MODALIDADE']                 = $id_modalidade;
      $dadosAlimentarCalculadora['NO_MODALIDADE_CALCULADORA']     = $modalidade_desc;


      $TP_RATING_EXP_PRE                  = 1;
      $TP_RATING_IMP_POS                  = 1;

      $arrayDados 			= array(
            'NO_QUALIDADE_PRODUTO' 	=> utf8_decode('Padrão'),
            'ID_MOEDA' 	            => $request->id_moeda,
            'CODIGO_MODAL_CALC' 	=> $id_modalidade,
            'ID_MPME_MODALIDADE' 	=> $id_modalidade,

            'PC_PRE_COB_COM' 		=> $PC_PRE_COB_COM/100,
            'PC_PRE_COB_POL' 		=> $PC_PRE_COB_POL/100,

            'PC_POS_COB_COM' 		=> $PC_POS_COB_COM/100,
            'PC_POS_COB_POL' 		=> $PC_POS_COB_POL/100,

            'NU_FATOR_PI' 			=> trim($dadosFinanceiros[0]->VA_FATOR_AJUSTE),
            'TP_PRODUTO' 			=> $modalidade_desc,
            'NU_PRAZO_PRE' 			=> (int)$request->nu_prazo_pre,
            'TP_RATING_EXP_PRE' 	=> $TP_RATING_EXP_PRE, //rating exportador
            'NU_RATING_BRA_PRE' 	=> (int)$PAIS_RISCO_EXP,
            'NU_PRAZO_POS' 			=> (int)$request->nu_prazo_pos,
            'TP_RATING_IMP_POS' 	=> $TP_RATING_IMP_POS, //rating importador
            'NU_RATING_PAIS_POS' 	=> (int)$risco_importador,
            'CD_RISCO_IMPORTADOR' 	=> (int)$risco_importador,
            'CD_RISCO_EXPORTADOR' 	=> (int)$PAIS_RISCO_EXP,
            'PC_ANTECIPADO' 		=> converte_float($request->va_percentual_dw_payment),
            'VL_EXP_ANUAL' 			=> converte_float($request->vl_proposta),
            'VALORES_RELATORIOS'    => $rsValorRelatorio
      );

      //webservice para calcular EXCEL
      $percentualCalculadoraMin            = buscaPercentualCalculadora2($arrayDados);

      if ( $percentualCalculadoraMin == "" || $percentualCalculadoraMin == 0)
      {
        return false;
      }

      //Calcular valores - Valor Minimo
      $arrayValoresCoberturaMinimo      = calcularValorCobertura($percentualCalculadoraMin, $arrayDados);

      //Calcular valores - Valor Maximo
      $arrayDados['TP_RATING_EXP_PRE']  = 4;
      $arrayDados['TP_RATING_IMP_POS']  = 4;

      //webservice para calcular EXCEL
      $percentualCalculadoraMax            = buscaPercentualCalculadora2($arrayDados);

      if ( $percentualCalculadoraMax == "" || $percentualCalculadoraMax == 0)
      {
          return false;
      }

      $arrayValoresCoberturaMaximo	    = calcularValorCobertura($percentualCalculadoraMax, $arrayDados);

      $arrayValoresCobertura     =   [
                                          'arrayValoresCoberturaMinimo'  => $arrayValoresCoberturaMinimo,
                                          'arrayValoresCoberturaMaximo'  => $arrayValoresCoberturaMaximo,
                                      ];

      self::salvarPrecoCoberturaSimulacaoSite($arrayValoresCobertura['arrayValoresCoberturaMinimo'], $request, 'SIMULACAO_SITE');

      return $arrayValoresCobertura;

    }

  public static function getDadosCalculadora( $id_oper, $id_proposta)
  {
      $sql  = "SELECT TOP 1	
                                I.ID_OPER, 
                                I.ST_OPER, 
                                MCE.ID_MPME_CLIENTE,
                                M.ID_MODALIDADE, 
                                I.ID_MPME_CALCULADORA, 
                                QP.NO_QUALIDADE_PRODUTO, 
                                MP.VL_PROPOSTA AS VL_EXP_ANUAL, 
                                M.CODIGO_MODALIDADE AS CODIGO_MODAL_CALC,
                                M.NO_MODALIDADE_CALCULADORA,
                                MRTC_PRE.VA_PERCENTUAL_COBERTURA_POLITICA AS VA_PERC_COB_POL_PRE,
                                MRTC_PRE.VA_PERCENTUAL_COBERTURA_COMERCIAL AS VA_PERC_COB_COM_PRE, 
                                MRTC_POS.VA_PERCENTUAL_COBERTURA_POLITICA AS VA_PERC_COB_POL_POS,
                                MRTC_POS.VA_PERCENTUAL_COBERTURA_COMERCIAL AS VA_PERC_COB_COM_POS,
                                PI.NM_PAIS AS NM_PAIS_IMPORTADOR, PVI.CD_RISCO AS CD_RISCO_IMPORTADOR, 
                                PE.NM_PAIS AS NM_PAIS_EXPORTADOR, PVE.CD_RISCO AS CD_RISCO_EXPORTADOR,
                                CASE WHEN I.ID_TEMPO = '2' THEN 'Não' ELSE 'Sim' END IN_STARTUP,
                                (SELECT TOP 1 PC_JUROS/100 FROM TX_JUROS WHERE CD_TX_JUROS = 'LIBOR' AND NU_PRAZO_FIM = 6) AS TX_LIBOR,
                                (SELECT TOP 1 SIGLA_CLASSIFICACAO_RISCO FROM CLASSIFICACAO_RISCO WHERE IN_MINIMO_CALCULADORA = 'S' AND IN_REGISTRO_ATIVO = 'S') AS RATING_MIN_IMP,
                                (SELECT TOP 1 SIGLA_CLASSIFICACAO_RISCO FROM CLASSIFICACAO_RISCO WHERE IN_MINIMO_CALCULADORA = 'S' AND IN_REGISTRO_ATIVO = 'S') AS RATING_MIN_EXP,
                                (SELECT TOP 1 SIGLA_CLASSIFICACAO_RISCO FROM CLASSIFICACAO_RISCO WHERE IN_MAXIMO_CALCULADORA = 'S' AND IN_REGISTRO_ATIVO = 'S') AS RATING_MAX_IMP,
                                (SELECT TOP 1 SIGLA_CLASSIFICACAO_RISCO FROM CLASSIFICACAO_RISCO WHERE IN_MAXIMO_CALCULADORA = 'S' AND IN_REGISTRO_ATIVO = 'S') AS RATING_MAX_EXP, 
                                (SELECT TOP 1 VA_FATOR_AJUSTE FROM FATOR_AJUSTE WHERE IN_REGISTRO_ATIVO = 'S') AS VA_FATOR_AJUSTE,
                                I.ID_SETOR,
                                (
                                rtrim(cast( isnull( (
                                select TOP 1 CREDIT_SCORE from MPME_CREDIT_SCORE WHERE ID_OPER = I.ID_OPER ORDER BY ID_CREDIT_SCORE DESC
                                ), 0) as char)) 
                                ) AS TP_RATING_IMPORTADOR, 
                                
                                ( 
                                rtrim(cast( isnull( (
                                select TOP 1 CREDIT_SCORE from MPME_CREDIT_SCORE_EXPORTADORES WHERE ID_OPER = I.ID_OPER ORDER BY ID_CREDIT_SCORE_EXPORTADORES DESC
                                ), 0) as char))
                                ) AS TP_RATING_EXPORTADOR,
                                
                                CONVERT(FLOAT, isnull((select TOP 1 MPME_PROPOSTA_APROVACAO.VL_PERC_DOWPAYMENT from MPME_PROPOSTA_APROVACAO
                                INNER JOIN MPME_PROPOSTA ON (MPME_PROPOSTA.ID_MPME_PROPOSTA = MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA)
                                WHERE ID_OPER = I.ID_OPER 
                                AND IN_DECISAO = 1 
                                AND ID_MPME_ALCADA = (select TOP 1 MPME_PROPOSTA_APROVACAO.ID_MPME_ALCADA from MPME_PROPOSTA_APROVACAO
							                            INNER JOIN MPME_PROPOSTA ON (MPME_PROPOSTA.ID_MPME_PROPOSTA = MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA)
							                            WHERE ID_OPER = I.ID_OPER  
							                            AND IN_DECISAO = 1
							                            ORDER BY MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA_APROVACAO DESC
							                          )
                                ORDER BY ID_MPME_PROPOSTA_APROVACAO DESC ),0)) AS PC_ANTECIPADO,
                                
                                CONVERT(FLOAT, isnull((select TOP 1 MPME_PROPOSTA_APROVACAO.NU_PRAZO_PRE from MPME_PROPOSTA_APROVACAO
                                INNER JOIN MPME_PROPOSTA ON (MPME_PROPOSTA.ID_MPME_PROPOSTA = MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA)
                                WHERE ID_OPER = I.ID_OPER 
                                AND IN_DECISAO = 1 
                                AND ID_MPME_ALCADA = (select TOP 1 MPME_PROPOSTA_APROVACAO.ID_MPME_ALCADA from MPME_PROPOSTA_APROVACAO
							                            INNER JOIN MPME_PROPOSTA ON (MPME_PROPOSTA.ID_MPME_PROPOSTA = MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA)
							                            WHERE ID_OPER = I.ID_OPER  
							                            AND IN_DECISAO = 1
							                            ORDER BY MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA_APROVACAO DESC
							                          )
                                ORDER BY ID_MPME_PROPOSTA_APROVACAO DESC ),0)) AS PRAZO_PRE,
                                
                                CONVERT(FLOAT, isnull((select TOP 1 MPME_PROPOSTA_APROVACAO.NU_PRAZO_POS from MPME_PROPOSTA_APROVACAO
                                INNER JOIN MPME_PROPOSTA ON (MPME_PROPOSTA.ID_MPME_PROPOSTA = MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA)
                                WHERE ID_OPER = I.ID_OPER 
                                AND IN_DECISAO = 1 
                                AND ID_MPME_ALCADA = (select TOP 1 MPME_PROPOSTA_APROVACAO.ID_MPME_ALCADA from MPME_PROPOSTA_APROVACAO
							                            INNER JOIN MPME_PROPOSTA ON (MPME_PROPOSTA.ID_MPME_PROPOSTA = MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA)
							                            WHERE ID_OPER = I.ID_OPER  
							                            AND IN_DECISAO = 1
							                            ORDER BY MPME_PROPOSTA_APROVACAO.ID_MPME_PROPOSTA_APROVACAO DESC
							                          )
                                ORDER BY ID_MPME_PROPOSTA_APROVACAO DESC ),0)) AS PRAZO_POS                              

                                
                            FROM MPME_IMPORTADORES I 
                            INNER JOIN OPERACAO_CADASTRO_EXPORTADOR OCE ON (OCE.ID_OPER = I.ID_OPER)
                            INNER JOIN MODALIDADE_REGIME_TRIBUTARIO_COBERTURA MRTC_PRE ON (MRTC_PRE.ID_REGIME_TRIBUTARIO = OCE.ID_REGIME_TRIBUTARIO AND MRTC_PRE.IN_REGISTRO_ATIVO = 'S' AND MRTC_PRE.ID_MODALIDADE = 1 ) --PÓS-EMBARQUE
                            INNER JOIN MODALIDADE_REGIME_TRIBUTARIO_COBERTURA MRTC_POS ON (MRTC_POS.ID_REGIME_TRIBUTARIO = OCE.ID_REGIME_TRIBUTARIO AND MRTC_POS.IN_REGISTRO_ATIVO = 'S' AND MRTC_POS.ID_MODALIDADE = 3 ) --PRÉ-EMBARQUE
                            INNER JOIN QUALIDADE_PRODUTO QP ON (QP.ID_QUALIDADE_PRODUTO = I.ID_QUALIDADE_PRODUTO)
                            INNER JOIN MPME_MERCADORIAS ME ON (ME.ID_OPER = I.ID_OPER)
                            INNER JOIN PAISES PI ON (PI.ID_PAIS = I.ID_PAIS)
                            INNER JOIN PAISES_VAL PVI ON (PVI.ID_PAIS = PI.ID_PAIS AND PVI.DT_FIM_VIG IS NULL) 
                            INNER JOIN USUARIOS U  ON (U.ID_USUARIO = OCE.ID_USUARIO_CAD)
                            INNER JOIN PAISES PE  ON (PE.ID_PAIS = U.ID_PAIS)
                            INNER JOIN PAISES_VAL PVE ON (PVE.ID_PAIS = PE.ID_PAIS AND PVE.DT_FIM_VIG IS NULL) 
                            INNER JOIN MPME_CLIENTE_EXPORTADORES MCE ON (MCE.ID_MPME_CLIENTE_EXPORTADORES = OCE.ID_MPME_CLIENTE_EXPORTADORES)";

                            if ($id_proposta != "")
                            {
                                $sql .= " INNER JOIN MPME_PROPOSTA MP ON (MP.ID_OPER = I.ID_OPER AND MP.ID_MPME_PROPOSTA = $id_proposta)
                                         INNER JOIN CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO CEMF ON (CEMF.ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO = MP.ID_CLIENTE_EXPORTADORES_MODALIDADE_FINANCIAMENTO)
                                         INNER JOIN MODALIDADE_FINANCIAMENTO MF ON (MF.ID_MODALIDADE_FINANCIAMENTO = CEMF.ID_MODALIDADE_FINANCIAMENTO)
                                         INNER JOIN MODALIDADE M ON (M.ID_MODALIDADE = MF.ID_MODALIDADE)";
                            }else{
                                $sql .= " LEFT JOIN MODALIDADE M ON (M.ID_MODALIDADE = OCE.ID_MODALIDADE) 
                                          LEFT JOIN MPME_PROPOSTA MP ON (MP.ID_OPER = I.ID_OPER)";
                            }

      $sql .=  " WHERE I.ID_OPER = $id_oper ORDER BY MP.ID_MPME_PROPOSTA DESC";

      $results = DB::select($sql);
      return $results;
  }

    public static function getDadosCalculadoraSimulacao($where)
    {
        $sqlDadosAdicionais = "SELECT M.NO_MODALIDADE, M.NO_MODALIDADE_CALCULADORA, RT.ID_REGIME_TRIBUTARIO, RT.NO_REGIME_TRIBUTARIO, MRTC.VA_PERCENTUAL_COBERTURA_POLITICA, MRTC.VA_PERCENTUAL_COBERTURA_COMERCIAL,
									(SELECT TOP 1 VA_FATOR_AJUSTE FROM FATOR_AJUSTE WHERE IN_REGISTRO_ATIVO = 'S') AS VA_FATOR_AJUSTE 
									FROM MODALIDADE_REGIME_TRIBUTARIO_COBERTURA MRTC
									INNER JOIN MODALIDADE M ON (M.ID_MODALIDADE = MRTC.ID_MODALIDADE)
									INNER JOIN REGIME_TRIBUTARIO RT ON (RT.ID_REGIME_TRIBUTARIO = MRTC.ID_REGIME_TRIBUTARIO)
									WHERE MRTC.IN_REGISTRO_ATIVO = 'S'";


        $sqlDadosAdicionais .= ( $where['enquadramento_simples'] == 'SIM') ? ' AND RT.ID_REGIME_TRIBUTARIO = 2 ' : ' AND RT.ID_REGIME_TRIBUTARIO = 1 ';
        $sqlDadosAdicionais .= ( $where['id_modalidade'] 		 != 2    ) ? ' AND M.ID_MODALIDADE 		   =  ' .$where['id_modalidade']  : ' ';
        $sqlDadosAdicionais .= 	"ORDER BY M.NO_MODALIDADE";

        $results = DB::select($sqlDadosAdicionais);
        return $results;

    }


    public static  function salvarPrecoCobertura($dados)
    {
        $cotacao = new Cotacao();
        $importadores = new ImportadoresModel();

        $importadores = $importadores->where('ID_OPER', '=', $dados['ID_OPER'])->first();

        $cotacao = $cotacao->where('MOEDA_ID', '=', $importadores->ID_MOEDA)->orderBy('DATA', 'desc')->first(['TAXA_VENDA'])->TAXA_VENDA;

        $preco_cobertura                    = new MpmePrecoCobertura();

        $preco_cobertura_selecionado        = $preco_cobertura->where('ID_OPER', '=', $dados['ID_OPER'])
                                                              ->where('ID_MPME_PROPOSTA', '=', $dados['ID_MPME_PROPOSTA'])
                                                              ->first();

        if ($preco_cobertura_selecionado == NULL)
        {
            $preco_cobertura_selecionado                                = new MpmePrecoCobertura();
            $preco_cobertura_selecionado->ID_OPER                       = $dados['ID_OPER'];
            $preco_cobertura_selecionado->ID_MPME_PROPOSTA              = $dados['ID_MPME_PROPOSTA'];
            $preco_cobertura_selecionado->ID_USUARIO_CAD                = Auth::user()->ID_USUARIO;
            $preco_cobertura_selecionado->DATA_CADASTRO                 = Carbon::now();
        }else{
            $preco_cobertura_selecionado->ID_USUARIO_ALT                = Auth::user()->ID_USUARIO;
            $preco_cobertura_selecionado->DT_ALTERACAO                  = Carbon::now();
        }

        $preco_cobertura_selecionado->CD_RISCO_IMPORTADOR           = $dados['CD_RISCO_IMPORTADOR'];
        $preco_cobertura_selecionado->CD_RISCO_EXPORTADOR           = $dados['CD_RISCO_EXPORTADOR'];

        $preco_cobertura_selecionado->PC_COB_ORIGINAL               = $dados['VL_PERC_RETORNO_CALULADORA1'];
        $preco_cobertura_selecionado->VL_PC_COB_ORIGINAL            = $dados['VL_COBERTURA_RETORNO_CALULADORA1'];

        $preco_cobertura_selecionado->PC_COB_TAXA_CARREGAMENTO      = converte_float($dados['PERCENTUAL_CALCULADORA']);
        $preco_cobertura_selecionado->VL_PC_COB_TAXA_CARREGAMENTO   = converte_float($dados['VL_COBERTURA_IMP']);

        $preco_cobertura_selecionado->VL_PIS                        = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_PIS']);
        $preco_cobertura_selecionado->VL_COFINS                     = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_COFINS']);
        $preco_cobertura_selecionado->VL_ISS                        = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_ISS']);
        $preco_cobertura_selecionado->VL_IOF                        = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_IOF']);
        $preco_cobertura_selecionado->VL_IMPOSTO_INDIRETO           = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_IIN']);
        $preco_cobertura_selecionado->VL_RECEITA_LIQUIDA            = converte_float($dados['DADOS_CARREGAMENTO']['RECEITA_LIQUIDA']);
        $preco_cobertura_selecionado->VL_LUCRO_BRUTO                = converte_float($dados['DADOS_CARREGAMENTO']['LUCRO_BRUTO']);
        $preco_cobertura_selecionado->VL_COMISSAO_CORRETAGEM        = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_CC']);
        $preco_cobertura_selecionado->VL_CORRETAGEM                 = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_COR']);
        $preco_cobertura_selecionado->VL_DESPESA_ADMINISTRATIVA     = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_DA']);
        $preco_cobertura_selecionado->VL_MARGEM_SEGURANCA           = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_MS']);
        $preco_cobertura_selecionado->VL_LUCRO_ANTES_IR_CS          = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_LAIC']);
        $preco_cobertura_selecionado->VL_IMPOSTO_DIRETO             = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_ID']);
        $preco_cobertura_selecionado->VL_IMPOSTO_RENDA              = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_IR']);
        $preco_cobertura_selecionado->VL_CONTRIBUICAO_SINDICAL      = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_CS']);
        $preco_cobertura_selecionado->VL_LUCRO_OPER_LIQUIDO         = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_LOL']);
        $preco_cobertura_selecionado->TAXA_VENDA                    = converte_float($cotacao);


        $retorno = $preco_cobertura_selecionado->save();

        if (!$retorno){
            return false;
        }else{
            return true;
        }
    }



    public static  function salvarPrecoCoberturaSimulacaoSite($dados, $request, $no_origem)
    {
        $cotacao = new Cotacao();
        $importadores = new ImportadoresModel();
        $preco_cobertura  = new MpmePrecoCoberturaSimulacao();


        if (array_key_exists('ID_OPER', $dados))
        {
            $importadores = $importadores->where('ID_OPER', '=', $dados['ID_OPER'])->first();
            $id_moeda     =  $importadores->ID_MOEDA;
            $id_usuario   = Auth::user()->ID_USUARIO;

        }else{
            $moeda                      = new MoedaModel();

            $id_moeda                    = $moeda->where('SIGLA_MOEDA', '=', $request->id_moeda)->first(['MOEDA_ID'])->MOEDA_ID;
            $dados['ID_OPER']            = 0;
            $dados['ID_MPME_PROPOSTA']   = 0;
            $id_usuario   = 1;
        }

        $cotacao = $cotacao->where('MOEDA_ID', '=', $id_moeda)->orderBy('DATA', 'desc')->first(['TAXA_VENDA'])->TAXA_VENDA;

        $preco_cobertura_selecionado        = $preco_cobertura->where('ID_OPER', '=', $dados['ID_OPER'])
            ->where('ID_MPME_PROPOSTA', '=', $dados['ID_MPME_PROPOSTA'])
            ->first();

        if ($preco_cobertura_selecionado == NULL)
        {
            $preco_cobertura_selecionado                                = new MpmePrecoCoberturaSimulacao();
            $preco_cobertura_selecionado->ID_OPER                       = $dados['ID_OPER'];
            $preco_cobertura_selecionado->ID_MPME_PROPOSTA              = $dados['ID_MPME_PROPOSTA'];
            $preco_cobertura_selecionado->ID_USUARIO_CAD                = $id_usuario;
            $preco_cobertura_selecionado->DATA_CADASTRO                 = Carbon::now();
        }else{
            $preco_cobertura_selecionado->ID_USUARIO_ALT                = $id_usuario;
            $preco_cobertura_selecionado->DT_ALTERACAO                 = Carbon::now();
        }

        $preco_cobertura_selecionado->NO_ORIGEM                     = $no_origem;

        $preco_cobertura_selecionado->CD_RISCO_IMPORTADOR           = $dados['CD_RISCO_IMPORTADOR'];
        $preco_cobertura_selecionado->CD_RISCO_EXPORTADOR           = $dados['CD_RISCO_EXPORTADOR'];

        $preco_cobertura_selecionado->PC_COB_ORIGINAL               = $dados['VL_PERC_RETORNO_CALULADORA1'];
        $preco_cobertura_selecionado->VL_PC_COB_ORIGINAL            = $dados['VL_COBERTURA_RETORNO_CALULADORA1'];

        $preco_cobertura_selecionado->PC_COB_TAXA_CARREGAMENTO      = converte_float($dados['PERCENTUAL_CALCULADORA']);
        $preco_cobertura_selecionado->VL_PC_COB_TAXA_CARREGAMENTO   = converte_float($dados['VL_COBERTURA_IMP']);

        $preco_cobertura_selecionado->VL_PIS                        = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_PIS']);
        $preco_cobertura_selecionado->VL_COFINS                     = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_COFINS']);
        $preco_cobertura_selecionado->VL_ISS                        = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_ISS']);
        $preco_cobertura_selecionado->VL_IOF                        = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_IOF']);
        $preco_cobertura_selecionado->VL_IMPOSTO_INDIRETO           = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_IIN']);
        $preco_cobertura_selecionado->VL_RECEITA_LIQUIDA            = converte_float($dados['DADOS_CARREGAMENTO']['RECEITA_LIQUIDA']);
        $preco_cobertura_selecionado->VL_LUCRO_BRUTO                = converte_float($dados['DADOS_CARREGAMENTO']['LUCRO_BRUTO']);
        $preco_cobertura_selecionado->VL_COMISSAO_CORRETAGEM        = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_CC']);
        $preco_cobertura_selecionado->VL_CORRETAGEM                 = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_COR']);
        $preco_cobertura_selecionado->VL_DESPESA_ADMINISTRATIVA     = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_DA']);
        $preco_cobertura_selecionado->VL_MARGEM_SEGURANCA           = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_MS']);
        $preco_cobertura_selecionado->VL_LUCRO_ANTES_IR_CS          = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_LAIC']);
        $preco_cobertura_selecionado->VL_IMPOSTO_DIRETO             = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_ID']);
        $preco_cobertura_selecionado->VL_IMPOSTO_RENDA              = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_IR']);
        $preco_cobertura_selecionado->VL_CONTRIBUICAO_SINDICAL      = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_CS']);
        $preco_cobertura_selecionado->VL_LUCRO_OPER_LIQUIDO         = converte_float($dados['DADOS_CARREGAMENTO']['VALOR_LOL']);
        $preco_cobertura_selecionado->TAXA_VENDA                    = converte_float($cotacao);
        $preco_cobertura_selecionado->IP                            = $request->ip();

        $retorno = $preco_cobertura_selecionado->save();

        if (!$retorno){
            return false;
        }else{
            return true;
        }
    }

    public static function calcular_taxas_precificacao( $VL_PREMIO_PURO )
    {
        $VL_PREMIO_PURO    = $VL_PREMIO_PURO*0.8; //regra é usado somente 80% do valor que vem da calculadora MD
        
        $mpmeFormulaPremio = new MpmeFormulaPremio();
        $taxas             = $mpmeFormulaPremio->getFormulasPremioAtiva();

        foreach ($taxas as $taxa)
        {
            try{
                eval('$calculo["'.$taxa->NO_CODIGO.'"] = '.$taxa->NO_FORMULA.';');
            } catch (\Exception $e) {
                echo 'Erro ao processar fórmula: '. $taxa->NO_FORMULA;
                return false;
            }
        }

       $arrayDadosCarragamento = [
                                    "PC"                => $calculo['PC'],
                                    "VALOR_PIS"         => $calculo['VALOR_PIS'],
                                    "VALOR_COFINS"      => $calculo['VALOR_COFINS'],
                                    "VALOR_ISS"         => $calculo['VALOR_ISS'],
                                    "VALOR_IOF"         => $calculo['VALOR_IOF'],
                                    "VALOR_IIN"         => $calculo['VALOR_IIN'],
                                    "RECEITA_LIQUIDA"   => $calculo['RECEITA_LIQUIDA'],
                                    "LUCRO_BRUTO"       => $calculo['LUCRO_BRUTO'],
                                    "VALOR_CC"          => $calculo['VALOR_CC'],
                                    "VALOR_COR"         => $calculo['VALOR_COR'],
                                    "VALOR_DA"          => $calculo['VALOR_DA'],
                                    "VALOR_MS"          => $calculo['VALOR_MS'],
                                    "VALOR_LAIC"        => $calculo['VALOR_LAIC'],
                                    "VALOR_ID"          => $calculo['VALOR_ID'],
                                    "VALOR_IR"          => $calculo['VALOR_IR'],
                                    "VALOR_CS"          => $calculo['VALOR_CS'],
                                    "VALOR_LOL"         => $calculo['VALOR_LOL'],
                                    "TOTAL"             => round($calculo['PC'], 2),
                                    "AUMENTO_PERC"      => $calculo['AUMENTO_PERC'],
                                 ];

        return $arrayDadosCarragamento;
    }

}
