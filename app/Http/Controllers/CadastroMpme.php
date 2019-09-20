<?php

namespace App\Http\Controllers;

use App\Agenciabb;
use App\ClienteExportadorModalidadeFinanciamento;
use App\ClienteExportadorRegimeTributario;
use App\FinanciamentosModel;
use App\Financpos;
use App\Financpre;
use App\Gecex;
use App\Grupos;
use App\GrupoUso;
use App\ModalidadeFinanciamento;
use App\MoedaModel;
use App\MpmeArquivo;
use App\MpmeCliente;
use App\MpmeClienteExportador;
use App\MpmeFinanceiroExportador;
use App\MpmeQuestionario;
use App\MpmeTipoCliente;
use App\MpmePergunta;
use App\Mpme_Responsav_Assinatura_Cgc;
use App\Notificacoes;
use App\Parametros;
use App\QuadroSocietario;
use App\TempoValidacao;
use App\User;
use App\UsuarioCGCModel;
use App\UsuarioPerfil;
use Carbon\Carbon;
use Exception;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Mail;
use URL;

class CadastroMpme extends Controller
{
    public function index()
    {
        $modalidades = new ModalidadeFinanciamento();
        $modalidades = $modalidades->where('IN_REGISTRO_ATIVO','S')
                                   ->get();

        $moedas = MoedaModel::whereIn('MOEDA_ID', [1, 3, 75])->get();
        $financiadores = User::where('TP_USUARIO', '=', 'B')->whereIn('ID_USUARIO', [16, 1047, 1052, 1058, 1048, 1056, 1050, 1051, 1049, 1059, 1061, 1062, 1040, 1060, 1045, 557, 1039, 1044, 1042, 1065, 1053, 1064, 1055, 1054, 1063, 1041, 1046, 1043])->get();
        $gecexs = Gecex::where('FL_ATIVO', '=', 1)->where('ID_GECEX_BB', '<>', 20)->orderBy('NO_GECEX')->get();

        $perguntas = new MpmePergunta();
        $perguntas = $perguntas->where('IN_ATIVO','S')
                               ->where('IN_ORIGEM','E')
                               ->get();

        return view('cadastro', compact('modalidades', 'moedas', 'financiadores', 'gecexs', 'perguntas'));
    }

    public function buscarcnpj(Request $request)
    {
        $cnpj = User::where('NU_CNPJ', '=', $request->cnpj)->whereIn('FL_ATIVO',[0,1])->orderBy('ID_USUARIO', 'desc')->first();

        if ($cnpj) {
            /*
            $uid = base64_encode($cnpj->ID_USUARIO);
            $data = base64_encode(date('d/m/Y'));
            $link = URL::to('/') . "/atualizadadosexportador/" . $uid . "/" . $data;

            Mail::send('emails.alteracao_cadastro', ['cnpj' => $cnpj, 'link' => $link], function ($message) use ($cnpj) {

                $message->from('mpme@abgf.gov.br', "MPME");

                $message->to(strtolower($cnpj->DE_EMAIL));
                //$message->to("felipe.teodoro@abgf.gov.br");
                $message->subject('Atualize seus dados de MPME!');

            });
            */

            echo '1'; // $cnpj existe

        } else {
            echo '0'; // $cnpj nao existe
        }
    }

    public function buscarusuariopornome(Request $request)
    {
        $usuarioexiste = User::where('CD_LOGIN', '=', strtoupper($request->nomeusuario))->first();

        if ($usuarioexiste) {
            echo '1'; // usuario existe
        } else {
            echo '0'; // nao existe
        }
    }

    public function retornatipofinanciamento(Request $request)
    {
        $financiamentos = FinanciamentosModel::where('ID_MODALIDADE', '=', $request->id_modalidade)->get()->toJson();
        return $financiamentos;

    }

    public function Encripta($info)
    {
        $aux = "";
        $chave = "";

        for ($i = 0; $i <= (strlen($info) - 1); $i++) {$charaux = substr($info, $i, 1);
            $charaux = dechex(ord($charaux));

            if (strlen($charaux) == 1) {$charaux = "0" . $charaux;
            }

            $charaux = $charaux . "F";

            $aux = $aux . $charaux;
        }

        $aux = $aux . $chave;

        return $aux;
    }

    public function Decripta($info)
    {
        $aux = "";
        $i = 0;

        while ($i <= (strlen($info) - 1)) {$charaux = substr($info, $i, 2);

            $charaux = chr(hexdec($charaux));
            $aux = $aux . $charaux;

            $i = $i + 3;
        }

        return $aux;
    }

    public function limpaCPF_CNPJ($valor)
    {
        $valor = trim($valor);
        $valor = str_replace(".", "", $valor);
        $valor = str_replace(",", "", $valor);
        $valor = str_replace("-", "", $valor);
        $valor = str_replace("/", "", $valor);
        return $valor;
    }

    public function atualizacadastro(Request $request)
    {
        //  dd(base64_encode(date("d/m/Y")));
        $data = base64_decode($request->dataexpiracaolink);

        if (date("d/m/Y") > $data) {
            echo "Esse link expirou!!";
        } else {

            $modalidades = ModalidadeFinanciamento::all();
            $usuario = User::where('ID_USUARIO', '=', base64_decode($request->idusuario))->orderBy('ID_USUARIO', 'desc')->first();

            return view('atualizaexportador', compact('usuario', 'modalidades'));
        }
    }

    public function atualizarexportador(Request $request)
    {
        DB::beginTransaction();

        try {

            $FT_ANUAL = $request->FT_ANUAL;
            // $FT_ANUAL3 = str_replace(",", ".", str_replace(".", "", $request->FT_ANUAL3));
            $RE_ANUAL = $request->RE_ANUAL;

            $validator = Validator::make($request->all(), [
                'ID_MODALIDADE' => 'required',
                'FT_ANUAL' => 'required',
                'RE_ANUAL' => 'required',
            ]);

            if ($validator->fails()) {
                echo "erro_validacao";
                exit;
            }

            $modalidades = []; // Ira receber todas as modalidades escolhida pelo exportador no cadastro
            $financiamentos = []; // ... financiamentos

            foreach ($request->ID_MODALIDADE as $modalid) {
                $registro_atual = explode("#", $modalid);
                array_push($modalidades, $registro_atual[0]);
                array_push($financiamentos, $registro_atual[1]);
            }

            /*
             * estamos zerando o array caso o javascript falhe
             * MOTIVO
             * 1 - No cadastro do fornecedor quando eScolher pos-embarque+recursos proprios, somente pode existir esta modalidade na lista;
             * 2 - faz necessario poque existem fluxos de aprovação diferentes no sistema para recursos proprios e outras modalidades;
             * 3 - a funcionalidade de recursos proprios caso o cliente queira juntamente com outra modalidde ficara na tela de cadastro da operacao;
             * estamos garantindo com if embaixo caso o javascript falhe
             */

            if (in_array(4, $financiamentos)) {
                $financiamentos[] = 4; //recursos proprios
                $financiamentos[] = 3; // pos embarque
            }

            $idClienteExportador = MpmeClienteExportador::where('ID_USUARIO', '=', $request->ID_USUARIO)->first()->ID_MPME_CLIENTE_EXPORTADORES;

            if ($idClienteExportador != "") {

                ClienteExportadorModalidadeFinanciamento::where('ID_MPME_CLIENTE_EXPORTADORES', '=', $idClienteExportador)
                    ->where('IN_REGISTRO_ATIVO', '=', 'S')
                    ->update([
                        'DT_FIM_PERIODO' => date('Y-m-d'),
                        'IN_REGISTRO_ATIVO' => 'N',
                    ]);

                $ano_fiscal = (int) $request->calendario_fiscal;
                $data_fechamento = Carbon::create($ano_fiscal + 2, 03, 31);

                ////////////////////////////// Nova estrutura, insere cliente exportador modalidade financiamento //////////////

                foreach ($request->ID_MODALIDADE as $modalid) {
                    $registro_atual_modalidade_financiamento = explode("#", $modalid);
                    $clienteExportadorFinanciamento = new ClienteExportadorModalidadeFinanciamento();
                    $clienteExportadorFinanciamento->ID_MODALIDADE_FINANCIAMENTO = $registro_atual_modalidade_financiamento[2];
                    $clienteExportadorFinanciamento->ID_MPME_CLIENTE_EXPORTADORES = $idClienteExportador;
                    $clienteExportadorFinanciamento->ID_USUARIO_CAD = $request->ID_USUARIO;
                    $clienteExportadorFinanciamento->DT_INI_PERIODO = date('Y-m-d');
                    $clienteExportadorFinanciamento->IN_REGISTRO_ATIVO = 'N';
                    $clienteExportadorFinanciamento->save();
                }
                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                /////////////////////////////////// Financeiro Exportador /////////////////////////////////////////////////////

                $cnpj_usuarios = User::where('ID_USUARIO', '=', $request->ID_USUARIO)->first()->NU_CNPJ;

                $usuarios = User::where('NU_CNPJ', '=', $cnpj_usuarios)->get();
                // Desativa

                MpmeFinanceiroExportador::where('ID_MPME_CLIENTE_EXPORTADORES', '=', $idClienteExportador)
                    ->where('IN_ATIVO', '=', 'S')
                    ->update([
                        'DT_FIM_PERIODO' => date('Y-m-d'),
                        'IN_ATIVO' => 'N',
                    ]);

                MpmeFinanceiroExportador::create([
                    'VL_EXP_BRUTO_ANUAL' => str_replace(',', '.', str_replace('.', '', $FT_ANUAL)),
                    'VL_FAT_BRUTO_ANUAL' => str_replace(',', '.', str_replace('.', '', $RE_ANUAL)),
                    //'VL_EXP_ESTIMADA' => str_replace(',', '.', str_replace('.', '', $FT_ANUAL3)),
                    'IN_APROVADO' => 'S',
                    'IN_ATIVO' => 'S',
                    'DT_ANO_FISCAL' => $ano_fiscal,
                    'ID_MPME_CLIENTE_EXPORTADORES' => $idClienteExportador,
                    'DT_INI_PERIODO' => date('Y-m-d'),
                ]);

                /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

                User::where('ID_USUARIO', '=', $request->ID_USUARIO)
                    ->update([
                        'DT_VALIDADE_CADASTRO' => $data_fechamento,
                        //'VL_LIMITE_MPME' => $FT_ANUAL3,
                        'VL_BRUTO_ANUAL' => $FT_ANUAL,
                        'VL_EXP_BRUTA' => $RE_ANUAL,
                        'IN_NOVA_OPERACAO' => 'S',
                    ]);

            }

            DB::commit();

            echo 'Atualizado com sucesso!';

        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage() . " Na Linha - " . $e->getLine();
            // something went wrong
        }

    }

    /**
     * @param Request $request
     * @param Notificacoes $notificacoes
     */
    public function cadastrar(Request $request, Notificacoes $notificacoes, MpmeQuestionario $mpmeQuestionario)
    {

        DB::beginTransaction();

        try {

            //$cnpj = User::where('NU_CNPJ','=',$request->NU_CNPJ)->orderBy('ID_USUARIO', 'desc')->first();

            //  $email = explode('@',$cnpj->DE_EMAIL);
            $messages = [
                'ID_MODALIDADE.required' => 'Você deve selecionar ao menos uma modalidade!',
                'FT_ANUAL.required' => 'O campo Faturamento nao pode ser vazio!',
                'FT_ANUAL.required' => 'O campo Faturamento nao pode ser vazio!',
                //'FT_ANUAL3.required'        => 'O campo Faturamento nao pode ser vazio!',
                'RE_ANUAL.required' => 'O campo Faturamento nao pode ser vazio!',
                'NU_CNPJ.required' => 'Voce deve preencher o CNPJ!',
                'NU_CNPJ.unique' => 'Esse CNPJ já está cadastrado em nossa base. Enviamos um link de atualização para o e-mail',
                'LOGIN.required' => 'Preencha o campo de usuario!',
                'LOGIN.unique' => 'O usuario escolhido ja existe escolha outro usuario!',
                'DS_SENHA.required' => 'Você não digitou uma senha!',
                'DS_SENHA.min' => 'Sua senha deve ter pelo menos 6 caracteres!',
                'DS_SENHA.same' => 'Sua confirmação de senha não conferece com a senha!',
                'DS_SENHA_C.required' => 'Você não digitou a confirmação da senha!',
                'DS_SENHA_C.required' => 'A confirmação da senha deve ter pelo menos 6 caracteres!',
                'dre.required' => 'Você precisa enviar seu DRE',
                'faturamento.required' => 'Você precisa enviar o Comprovante de Exportação',
                'dre.mimes' => 'Só é permitido o envio de arquivo .PDF',
                'faturamento.mimes' => 'Só é permitido o envio de arquivo .PDF',
                'dre.max' => 'O tamanho maximo do PDF é de 32MB',
                'faturamento.max' => 'O tamanho maximo do PDF é de 32MB',

            ];

            $validator = Validator::make($request->all(), [
                'ID_MODALIDADE' => 'required',
                'FT_ANUAL' => 'required',
                //'FT_ANUAL3'    => 'required',
                'RE_ANUAL' => 'required',
                'NU_CNPJ' => 'required|unique:USUARIOS,NU_CNPJ',
                'LOGIN' => 'required|unique:USUARIOS,CD_LOGIN',
                'DS_SENHA' => 'required|min:6|same:DS_SENHA_C',
                'DS_SENHA_C' => 'required|min:6',
                'dre' => 'required|mimes:pdf|max:32768',
                'faturamento' => 'required|mimes:pdf|max:32768',
            ], $messages);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()->all()]);
            }

            $modalidades = []; // Ira receber todas as modalidades escolhida pelo exportador no cadastro
            $financiamentos = []; // ... financiamentos
            $apenasRecursosProprio = 0; // sera usado para definir se eh ou não apenas recursos proprios

            foreach ($request->ID_MODALIDADE as $modalid) {
                $registro_atual = explode("#", $modalid);
                array_push($modalidades, $registro_atual[0]);
                array_push($financiamentos, $registro_atual[1]);
            }

            /*
             * estamos zerando o array caso o javascript falhe
             * MOTIVO
             * 1 - No cadastro do fornecedor quando escolher pos-embarque+recursos proprios, somente pode existir esta modalidade na lista;
             * 2 - faz necessario poque existem fluxos de aprovação diferentes no sistema para recursos proprios e outras modalidades;
             * 3 - a funcionalidade de recursos proprios caso o cliente queira juntamente com outra modalidde ficara na tela de cadastro da operacao;
             * estamos garantindo com if embaixo caso o javascript falhe
             */

            if (in_array(4, $financiamentos)) {
                //4 recursos proprios
                //$financiamentos[] = 3; // pos embarque

                if (count($financiamentos) == 1) {
                    $financiamentos[] = 4;
                    $apenasRecursosProprio = 1;
                } else {
                    $financiamentos[] = 3;
                }

            }

            $xCD_LOGIN = strtoupper($request->LOGIN);
            $xCD_SENHA = strtoupper($request->DS_SENHA);
            $xCD_SENHA_ENCR = $this->Encripta($xCD_SENHA);
            $TROCA_SENHA = strtoupper($request->DS_SENHA);
//       $TROCA_SENHA = $enc->Encripta($TROCA_SENHA);
            $xNM_USUARIO = $request->NM_USUARIO;
            $xNM_FANTASIA = $request->NM_FANTASIA;
            $NU_FUNCIONARIO_EMPRESA = $request->NU_FUNCIONARIO_EMPRESA;
            $xNU_CNPJ = $request->NU_CNPJ;
            $xNU_INSCR_E = $request->NU_INSCR_ESTADUAL;
            $xDE_EMAIL = strtolower($request->DE_EMAIL);
            $xDE_ENDER = $request->DE_ENDER;
            $xDE_CIDADE = $request->DE_CIDADE;
            $xCD_UF = $request->CD_UF;
            $xDE_CEP = $request->DE_CEP;
            $xNM_CONTATO = $request->NM_CONTATO;
            $xDE_CARGO = $request->DE_CARGO;
            $xNU_DDD = preg_replace('/\(|\)/', '', substr($request->DE_TEL, 0, 4));
            $xDE_TEL = substr($request->DE_TEL, 5, 9);
            $xNU_DDD_FAX = preg_replace('/\(|\)/', '', substr($request->DE_FAX, 0, 4));
            $xDE_FAX = substr($request->DE_FAX, 5, 9);
            $xID_SETOR = $request->ID_SETOR;
            $xID_TEMPO = $request->ID_TEMPO;

            $FT_ANUAL = $request->FT_ANUAL;
            $FT_ANUAL2 = $request->FT_ANUAL2;
            // $FT_ANUAL3 = str_replace(",", ".", str_replace(".", "", $request->FT_ANUAL3));
            $RE_ANUAL = $request->RE_ANUAL;

            $xID_MODALIDADE = $modalidades[0];

            $xID_MOEDA = $request->ID_MOEDA;

            $ID_USUARIO_RESPONSAVEL = $request->ID_USUARIO_RESPONSAVEL;
            $NM_RESPONSAVEL = $request->NM_RESPONSAVEL;
            $CPF_RESPONSAVEL = $request->CPF_RESPONSAVEL;
            $EMAIL_RESPONSAVEL = $request->EMAIL_RESPONSAVEL;

            // Quadro dos socios

            // Dados do Socio

            $quant_socios = $request->quant_socios;

            for ($i = 0; $i <= (count($request->NOME_QUADRO) - 1); $i++) {
                $NOME_QUADRO[$i] = $request->NOME_QUADRO[$i];
                $CPF_QUADRO[$i] = $request->CPF_QUADRO[$i];
                $PARTICIPACAO_QUADRO[$i] = $request->PARTICIPACAO_QUADRO[$i];
            }

            /// Fim Quadro de Socios

            $CAPITAL_QUADRO = $request->CAPITAL_QUADRO;

            $PROEX_PRE = $request->PROEX_PRE;
            $PROEX_POS = $request->PROEX_POS;

            // financiadores
            $ID_FINANCIADOR_PRE = $request->ID_FINANCIADOR_PRE;
            $ID_AGENCIA_PRE = $request->ID_AGENCIA_PRE;
            if ($ID_AGENCIA_PRE == "") {
                $ID_AGENCIA_PRE = "NULL";
            }

            $NO_AGENCIA_PRE = $request->NO_AGENCIA_PRE;
            $AG_ENDERECO_PRE = $request->AG_ENDERECO_PRE;
            $AG_CC_PRE = $request->AG_CC_PRE;
            $AG_CEP_PRE = "-";
            $AG_CIDADE_PRE = $request->AG_CIDADE_PRE;
            $AG_ESTADO_PRE = $request->AG_ESTADO_PRE;
            $AG_CNPJ_PRE = $request->AG_CNPJ_PRE;
            $AG_INSCR_PRE = $request->AG_INSCR_PRE;
            $AG_CONTATO_PRE = $request->AG_CONTATO_PRE;
            $AG_CARGO_PRE = $request->AG_CARGO_PRE;
            $AG_DDD_TEL_PRE = preg_replace('/\(|\)/', '', substr($request->AG_TEL_PRE, 0, 4));
            $AG_TEL_PRE = substr($request->AG_TEL_PRE, 5, 9);
            $AG_DDD_FAX_PRE = preg_replace('/\(|\)/', '', substr($request->AG_FAX_PRE, 0, 4));
            $AG_FAX_PRE = substr($request->AG_FAX_PRE, 5, 9);
            $AG_EMAIL_PRE = $request->AG_EMAIL_PRE;
            $AG_CEP_PRE = $request->AG_CEP_PRE;
            $NU_AG_PRE = $request->NU_AG_PRE;

            // GECEX - POS EMBARQUE
            $ID_GECEX_POS = $request->ID_GECEX_POS;
            $ID_FINANCIADOR_POS = $request->ID_FINANCIADOR_POS;
            $ID_NOME_BANCO_POS_FK = "NULL";

            if (trim($ID_GECEX_POS) != "") {
                $ID_NOME_BANCO_POS_FK = $ID_FINANCIADOR_POS;
                $ID_FINANCIADOR_POS = $ID_GECEX_POS;

            }

            if ($apenasRecursosProprio == 1) { // CASO HAJA RECURSOS PROPRIOS NO SELECT DE MODALIDADE/FINANCIAMENTO, DEFINE A VARIAVEL ID_FINANCIADOR_POS COMO 1000 PARA EXECUTAR OS SQL DE REC. PROPRIO
                $ID_FINANCIADOR_POS = "10000";
            }

            $ID_AGENCIA_POS = $request->ID_AGENCIA_POS;
            if ($ID_AGENCIA_POS == "") {
                $ID_AGENCIA_POS = "NULL";
            }

            $NO_AGENCIA_POS = $request->NO_AGENCIA_POS;
            $AG_ENDERECO_POS = $request->AG_ENDERECO_POS;
            $AG_CC_POS = $request->AG_CC_POS;
            $AG_CIDADE_POS = $request->AG_CIDADE_POS;
            $AG_ESTADO_POS = $request->AG_ESTADO_POS;
            $AG_CEP_POS = "-";
            $AG_CNPJ_POS = $request->AG_CNPJ_POS;
            $AG_INSCR_POS = $request->AG_INSCR_POS;
            $AG_CONTATO_POS = $request->AG_CONTATO_POS;
            $AG_CARGO_POS = $request->AG_CARGO_POS;
            $AG_DDD_TEL_POS = preg_replace('/\(|\)/', '', substr($request->AG_TEL_POS, 0, 4));
            $AG_TEL_POS = substr($request->AG_TEL_POS, 5, 9);
            $AG_DDD_FAX_POS = preg_replace('/\(|\)/', '', substr($request->AG_FAX_POS, 0, 4));
            $AG_FAX_POS = substr($request->AG_FAX_POS, 5, 9);
            $AG_EMAIL_POS = $request->AG_EMAIL_POS;
            $AG_CEP_POS = $request->AG_CEP_POS;
            $NU_AG_POS = $request->NU_AG_POS;

            if (trim($ID_GECEX_POS) == "" && $apenasRecursosProprio != 1) { // se nao haja gecex nao e banco do brasil sendo assim criamos um usuario e senha para essa agencia.

                //fixme: incluir usuários de outros bancos que nao seja BB.. utilizar como usuario(numero do banco + agencia), senha = aleatoria
                // verificar se usuario ja existe, se existir pegar id_usuario para utlizar no id_usuario_financiador_fk, senao, insere na tabela usuario e pegaultimo id para id_usuario_financiador_fk
                //$sqlDadosAgencia = $this->queryExec("SELECT TOP 1 (CONVERT(VARCHAR, ID_USUARIO)+'" . $NO_AGENCIA_POS . "') AS CDLOGIN, NM_USUARIO, CD_SENHA FROM USUARIOS WHERE ID_USUARIO = $ID_FINANCIADOR_POS");

                $sqlDadosAgencia = User::where('ID_USUARIO', '=', $ID_FINANCIADOR_POS)->get();

                $CdLoginNAgencia = $sqlDadosAgencia[0]['ID_USUARIO'] . $NO_AGENCIA_POS;

                ///////////////////////////////////// -- Tabela USUARIOS -- ///////////////////////////////////////////////////////////
                $usuarioexiste = User::where('CD_LOGIN', '=', $CdLoginNAgencia)->first(); //  pesquisa se existe o login do usuario igual gerado acima, caso exista ja existe uma agencia.

                if (count($usuarioexiste) > 1) { // se existir usuario com login gerado acima, ele seta o ID do usuario a variavel;

                    $IdNovaAgencia = $usuarioexiste['ID_USUARIO'];

                } else { // Caso contrario cria o usuario da agencia.

                    $NMUsuario = $sqlDadosAgencia[0]['NM_USUARIO'];
                    $CdSenha = $sqlDadosAgencia[0]['CD_SENHA'];
                    $DT_INCLUSAO = date('Y-m-d H:m:s');

                    if ($ID_FINANCIADOR_POS != "10000" && $apenasRecursosProprio != 1) { // Caso não seja recurso proprio

                        $execSqlNovaAgencia = new user();
                        $execSqlNovaAgencia->CD_LOGIN = $CdLoginNAgencia;
                        $execSqlNovaAgencia->NM_USUARIO = $NMUsuario;
                        $execSqlNovaAgencia->CD_SENHA = $CdSenha;
                        $execSqlNovaAgencia->TP_USUARIO = 'B';
                        $execSqlNovaAgencia->FL_ATIVO = 1;
                        $execSqlNovaAgencia->DE_ENDER = $AG_ENDERECO_POS;
                        $execSqlNovaAgencia->DE_CIDADE = $AG_CIDADE_POS;
                        $execSqlNovaAgencia->CD_UF = $AG_ESTADO_POS;
                        $execSqlNovaAgencia->ID_PAIS = 1;
                        $execSqlNovaAgencia->DE_CEP = $AG_CEP_POS;
                        $execSqlNovaAgencia->NM_CONTATO = $AG_CONTATO_POS;
                        $execSqlNovaAgencia->DE_TEL = $AG_DDD_TEL_POS;
                        $execSqlNovaAgencia->DE_EMAIL = strtolower($AG_EMAIL_POS);
                        $execSqlNovaAgencia->DE_HMPAGE = '';
                        $execSqlNovaAgencia->DE_TITULO = '';
                        $execSqlNovaAgencia->DE_DEPTO = '';
                        $execSqlNovaAgencia->DE_AREA = '';
                        $execSqlNovaAgencia->DE_SEGMENTO = '';
                        $execSqlNovaAgencia->DE_DETALHE = '';
                        $execSqlNovaAgencia->CD_LINGUA = 'P';
                        $execSqlNovaAgencia->DE_COOKIE = '';
                        $execSqlNovaAgencia->NU_CNPJ = $AG_CNPJ_POS;
                        $execSqlNovaAgencia->ID_SETOR = 1;
                        $execSqlNovaAgencia->DE_CARGO = $AG_CARGO_POS;
                        $execSqlNovaAgencia->NU_DDD = $AG_DDD_FAX_POS;
                        $execSqlNovaAgencia->ID_TEMPO = 2;
                        $execSqlNovaAgencia->IC_MPME = 1;
                        $execSqlNovaAgencia->ID_MODALIDADE = 0;
                        $execSqlNovaAgencia->ID_MOEDA = 1;
                        $execSqlNovaAgencia->DE_DDD_FAX = $AG_DDD_FAX_POS;
                        $execSqlNovaAgencia->DE_FAX = $AG_FAX_POS;
                        $execSqlNovaAgencia->VL_BRUTO_ANUAL = null;
                        $execSqlNovaAgencia->VL_ANUAL_EXP = null;
                        $execSqlNovaAgencia->VL_LIMITE_MPME = null;
                        $execSqlNovaAgencia->VL_EXP_BRUTA = null;
                        $execSqlNovaAgencia->VL_ESTIMADO = null;
                        $execSqlNovaAgencia->DE_EMAIL_CONTATO = null;
                        $execSqlNovaAgencia->NU_INSCR_EST = null;
                        $execSqlNovaAgencia->PROEX_PRE = null;
                        $execSqlNovaAgencia->PROEX_POS = null;
                        $execSqlNovaAgencia->IC_VALIDADO_BANCO = 1;
                        $execSqlNovaAgencia->ID_PERFIL = 6;
                        $execSqlNovaAgencia->DT_RECUSA = null;
                        $execSqlNovaAgencia->DS_MOTIVO_RECUSA = null;
                        $execSqlNovaAgencia->DT_FUNDACAO = null;
                        $execSqlNovaAgencia->DT_INCLUSAO = $DT_INCLUSAO;
                        $execSqlNovaAgencia->DS_DIVERGENCIA = null;
                        $execSqlNovaAgencia->ID_ANALISTA_FK = null;
                        $execSqlNovaAgencia->NOME_QUADRO = null;
                        $execSqlNovaAgencia->CPF_CNPJ_QUADRO = null;
                        $execSqlNovaAgencia->PARTICIPACAO_QUADRO = null;
                        $execSqlNovaAgencia->CAPITAL_QUADRO = null;
                        $execSqlNovaAgencia->IC_MOMENTO = 'ANA';
                        $execSqlNovaAgencia->ST_USUARIO = null;
                        $execSqlNovaAgencia->DT_ATZX = null;
                        $execSqlNovaAgencia->NO_FANTASIA = null;
                        $execSqlNovaAgencia->save();
                        // Execulta a query com o sql acima.

                        $sqlNovaAgencia = $execSqlNovaAgencia->ID_USUARIO; // retorna ID_USUARIO inserido.
                    }

                }

                if ($ID_FINANCIADOR_POS != "10000" and !empty($IdNovaAgencia) && $apenasRecursosProprio != 1) {

                    $ID_FINANCIADOR_POS = $IdNovaAgencia;

                }

                UsuarioPerfil::create(
                    [
                        'ID_USUARIO_FK' => $ID_FINANCIADOR_POS,
                        'NU_CHECK' => 28,
                    ]
                );

                UsuarioPerfil::create(
                    [
                        'ID_USUARIO_FK' => $ID_FINANCIADOR_POS,
                        'NU_CHECK' => 29,
                    ]
                );

                UsuarioPerfil::create(
                    [
                        'ID_USUARIO_FK' => $ID_FINANCIADOR_POS,
                        'NU_CHECK' => 30,
                    ]
                );

                UsuarioPerfil::create(
                    [
                        'ID_USUARIO_FK' => $ID_FINANCIADOR_POS,
                        'NU_CHECK' => 40,
                    ]
                );

            }

            $usu_inativo = false;

            /* Sql para Inserir o usuario no banco de dados */
            $xDS_CD_SENHA = $xCD_SENHA_ENCR;
            $DT_INCLUSAO = date('Y-m-d H:m:s');

//       if($xID_TEMPO == 2){  // Até 3 anos
            //           $xDT_FUNDACAO = date("Y-m-d");
            //       }
            //       if($xID_TEMPO == 1) { // acima de tres anos
            //           $xDT_FUNDACAO = date('Y-m-d', strtotime("-3 year"));
            //       }
            //
            //       $xDT_FUNDACAO = date("Y-m-d", strtotime($xDT_FUNDACAO));// converte a data em formato americano para salvar no banco de dados

            $ano_fiscal = (int) $request->calendario_fiscal;
            $data_fechamento = Carbon::create($ano_fiscal + 2, 03, 31);

            $novoUsuario = new User();
            $novoUsuario->CD_LOGIN = $xCD_LOGIN;
            $novoUsuario->NM_USUARIO = $xNM_USUARIO;
            $novoUsuario->NO_FANTASIA = $xNM_FANTASIA;
            $novoUsuario->TIPO_VALIDACAO = 'c';
            $novoUsuario->CD_SENHA = $xDS_CD_SENHA;
            $novoUsuario->NU_CNPJ = $xNU_CNPJ;
            $novoUsuario->NU_INSCR_EST = $xNU_INSCR_E;
            $novoUsuario->DE_EMAIL = strtolower($xDE_EMAIL);
            $novoUsuario->DE_ENDER = $xDE_ENDER;
            $novoUsuario->ID_PAIS = '28';
            $novoUsuario->DE_CIDADE = $xDE_CIDADE;
            $novoUsuario->CD_UF = $xCD_UF;
            $novoUsuario->DE_CEP = $xDE_CEP;
            $novoUsuario->NM_CONTATO = $xNM_CONTATO;
            $novoUsuario->DE_CARGO = $xDE_CARGO;
            $novoUsuario->NU_DDD = $xNU_DDD;
            $novoUsuario->DE_TEL = $xDE_TEL;
            $novoUsuario->DE_DDD_FAX = $xNU_DDD_FAX;
            $novoUsuario->DE_FAX = $xDE_FAX;
            $novoUsuario->ID_SETOR = $xID_SETOR;
            $novoUsuario->ID_TEMPO = $xID_TEMPO;
            $novoUsuario->FL_ATIVO = 0;
            $novoUsuario->IC_MPME = 1;
            $novoUsuario->ID_MODALIDADE = $xID_MODALIDADE;
            $novoUsuario->ID_MOEDA = $xID_MOEDA;
            $novoUsuario->TP_USUARIO = 'C';
            // $novoUsuario->VL_LIMITE_MPME =  $FT_ANUAL3;
            $novoUsuario->VL_ANUAL_EXP = $FT_ANUAL2;
            $novoUsuario->VL_BRUTO_ANUAL = $FT_ANUAL; // Valor da exportação do ano civil anterior
            $novoUsuario->VL_EXP_BRUTA = $RE_ANUAL; // Faturamento Bruto Anual
            $novoUsuario->PROEX_PRE = $PROEX_PRE;
            $novoUsuario->PROEX_POS = $PROEX_POS;
            $novoUsuario->DT_FUNDACAO = ''; //$xDT_FUNDACAO;
            $novoUsuario->DT_INCLUSAO = $DT_INCLUSAO;
            $novoUsuario->NOME_QUADRO = $NOME_QUADRO[0];
            $novoUsuario->CPF_CNPJ_QUADRO = $CPF_QUADRO[0];
            $novoUsuario->PARTICIPACAO_QUADRO = $PARTICIPACAO_QUADRO[0];
            $novoUsuario->CAPITAL_QUADRO = $CAPITAL_QUADRO;
            $novoUsuario->NU_FUNCIONARIO_EMPRESA = $NU_FUNCIONARIO_EMPRESA;
            $novoUsuario->IN_NOVA_OPERACAO = 'S';
            $novoUsuario->DT_VALIDADE_CADASTRO = $data_fechamento;
            $novoUsuario->ID_PERFIL = 9;
            $novoUsuario->DT_ATZX = null;
            $novoUsuario->save();

            $ID_USUARIOS_INSERIDO = $novoUsuario->ID_USUARIO;

            //faz uploads

            $pasta = public_path('/uploads/abgf/exportador/dre/' . $ID_USUARIOS_INSERIDO . '/');

            if (!File::exists($pasta)) {
                File::makeDirectory($pasta, 0777, true, true);
            }

            $arquivo = $request->file('dre');
            $novo_nome = $ID_USUARIOS_INSERIDO . '.' . $arquivo->getClientOriginalExtension();
            $uploadDRE = $arquivo->move(public_path('/uploads/abgf/exportador/dre/' . $ID_USUARIOS_INSERIDO), $novo_nome);

            $pasta_faturamento = public_path('/uploads/abgf/exportador/faturamento/' . $ID_USUARIOS_INSERIDO . '/');

            if (!File::exists($pasta_faturamento)) {
                File::makeDirectory($pasta_faturamento, 0777, true, true);
            }

            $arquivo_faturamento = $request->file('faturamento');
            $novo_nome_faturamento = $ID_USUARIOS_INSERIDO . '.' . $arquivo->getClientOriginalExtension();
            $uploadFaturamento = $arquivo_faturamento->move(public_path('/uploads/abgf/exportador/faturamento/' . $ID_USUARIOS_INSERIDO), $novo_nome_faturamento);

            if ($uploadDRE && $uploadFaturamento) {

                $uploadDreBanco = new MpmeArquivo();
                $uploadDreBanco->ID_MPME_TIPO_ARQUIVO = 5; // dre e 6 faturamanento;
                $uploadDreBanco->NO_DIRETORIO = $pasta;
                $uploadDreBanco->NO_ARQUIVO = $novo_nome;
                $uploadDreBanco->DT_CADASTRO = date('Y-m-d');
                $uploadDreBanco->ID_USUARIO_CAD = $ID_USUARIOS_INSERIDO;
                $uploadDreBanco->NO_EXTENSAO = $arquivo->getClientOriginalExtension();
                $uploadDreBanco->save();

                $uploadFaturamentoBanco = new MpmeArquivo();
                $uploadFaturamentoBanco->ID_MPME_TIPO_ARQUIVO = 6; // dre e 6 faturamanento;
                $uploadFaturamentoBanco->NO_DIRETORIO = $pasta_faturamento;
                $uploadFaturamentoBanco->NO_ARQUIVO = $novo_nome_faturamento;
                $uploadFaturamentoBanco->DT_CADASTRO = date('Y-m-d');
                $uploadFaturamentoBanco->ID_USUARIO_CAD = $ID_USUARIOS_INSERIDO;
                $uploadFaturamentoBanco->NO_EXTENSAO = $arquivo->getClientOriginalExtension();
                $uploadFaturamentoBanco->save();

            } else {
                DB::rollback();
                echo "Ocorreu um erro ao efetuar o upload, tente novamente mais tarde";
                exit;
            }

            /*
            OBJETIVO: TORNAR O EXPORTADOR ÚNICO, EVITANDO DUPLICIDADE.
            Verifica se o CNPJ do novo usuário já existe na base de dados.
            Se existir, associa este usuário a o já existente.
            Caso não exista, inclui este usuário como novo no novo sistema de tabelas.
             */

            $mpmeCliente = new MpmeCliente();
            $mpmeCliente->NOME_CLIENTE = $xNM_USUARIO;
            $mpmeCliente->NOME_FANTASIA = $xNM_FANTASIA;
            $mpmeCliente->ID_PAIS = 28;
            $mpmeCliente->NOME_CIDADE = $xDE_CIDADE;
            $mpmeCliente->NUMERO_DOC_IDENTIFICACAO = $this->limpaCPF_CNPJ($xNU_CNPJ);
            $mpmeCliente->NUMERO_CNPJ = $this->limpaCPF_CNPJ($xNU_CNPJ);

            if ($mpmeCliente->save()) {

                // Retorna cliente exportador -- Nova Estrutura

                $retornaClienteExportador = new MpmeClienteExportador();
                $retornaClienteExportador->ID_MPME_CLIENTE = $mpmeCliente->ID_MPME_CLIENTE;
                $retornaClienteExportador->ID_USUARIO = $ID_USUARIOS_INSERIDO;
                $retornaClienteExportador->save();

                $idClienteExportador = $retornaClienteExportador->ID_MPME_CLIENTE_EXPORTADORES; // Pega o ID do CLiente Exportador Inserido.

            }

            $mpmeTipoCliente = new MpmeTipoCliente();
            $mpmeTipoCliente->ID_MPME_CLIENTE = $mpmeCliente->ID_MPME_CLIENTE;
            $mpmeTipoCliente->ID_TIPO_CLIENTE = 1;
            $mpmeTipoCliente->DATA_CADASTRO = date('Y-m-d');
            $mpmeTipoCliente->save();

            //////////////////////////////////////////////////////////////

            ///////////////////////////////////// Insere no quadro societario //////////////////////////////////////////////

            // Verifica se existe o socio e o 2º socio, caso exista ele insere os dois socios no banco de dados

            if (count($NOME_QUADRO) > 1) {

                for ($i = 1; $i <= (count($NOME_QUADRO) - 1); $i++) {
                    $cpf01 = trim($CPF_QUADRO[$i]);
                    $cpf01 = str_replace(".", "", $cpf01);
                    $cpf01 = str_replace(",", "", $cpf01);
                    $cpf01 = str_replace("-", "", $cpf01);
                    $cpf01 = str_replace("/", "", $cpf01);
                    $CPFQUADRO01 = $cpf01;

                    // Cria os socios com base na lista de socios relacionados na tela de cadastro
                    QuadroSocietario::create(
                        [
                            'ID_USUARIO' => $ID_USUARIOS_INSERIDO,
                            'NOME_SOCIO' => $NOME_QUADRO[$i],
                            'NU_CPF_CNPJ' => $cpf01,
                            'PC_PARTICIPACAO' => $PARTICIPACAO_QUADRO[$i],

                        ]
                    );

                }

            }

            //////////////////////////////////////// Fim quadro societario /////////////////////////////////////////////////

            ////////////////////////////// Nova estrutura, insere cliente exportador modalidade financiamento //////////////

            foreach ($request->ID_MODALIDADE as $modalid) {
                $registro_atual_modalidade_financiamento = explode("#", $modalid);
                $clienteExportadorFinanciamento = new ClienteExportadorModalidadeFinanciamento();
                $clienteExportadorFinanciamento->ID_MODALIDADE_FINANCIAMENTO = $registro_atual_modalidade_financiamento[2];
                $clienteExportadorFinanciamento->ID_MPME_CLIENTE_EXPORTADORES = $idClienteExportador;
                $clienteExportadorFinanciamento->ID_USUARIO_CAD = $ID_USUARIOS_INSERIDO;
                $clienteExportadorFinanciamento->DT_INI_PERIODO = date('Y-m-d');
                $clienteExportadorFinanciamento->IN_REGISTRO_ATIVO = 'N';
                $clienteExportadorFinanciamento->save();
            }
            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            if ($novoUsuario) {

                // insere registro para relatorio de tempo
                TempoValidacao::create(
                    [
                        'ID_USUARIO_FK' => $ID_USUARIOS_INSERIDO,
                        'ID_TIPO_VALIDACAO_FK' => 1,
                        'DT_VALIDACAO' => date('Y-m-d'),
                    ]
                );

                //Se ID modalidade for 2 e pre embarque
                if ($apenasRecursosProprio != 1 & (in_array(1, $modalidades) || in_array(2, $modalidades))) {
                    if ($ID_AGENCIA_PRE == "NULL") {

                        $agenciaInsererida = Agenciabb::create(
                            [
                                'NU_AGENCIA' => $NU_AG_PRE,
                                'ID_GECEX_FK' => $ID_FINANCIADOR_PRE,
                                'DE_AGENCIA' => $NO_AGENCIA_PRE,
                                'NU_CNPJ_INSCR' => $AG_CNPJ_PRE,
                                'DE_ENDER' => $AG_ENDERECO_PRE,
                                'DE_CIDADE' => $AG_CIDADE_PRE,
                                'CD_UF' => $AG_ESTADO_PRE,
                                'NU_CEP' => $AG_CEP_PRE,
                                'NU_DDD' => $AG_DDD_TEL_PRE,
                                'NU_TEL' => $AG_TEL_PRE,
                                'NU_INSCR_EST' => $AG_INSCR_PRE,
                                'NO_CONTATO' => $AG_CONTATO_PRE,
                                'NU_FAX' => $AG_FAX_PRE,
                                'DS_EMAIL' => $AG_EMAIL_PRE,
                                'ID_BANCO_FK' => $ID_FINANCIADOR_PRE,
                                'IC_ATIVO' => 1,
                            ]
                        );

                        $ID_AGENCIA_PRE = $agenciaInsererida->ID_AGENCIA; // Pega o Ultimo ID da tabela inserido;

                    }

                    Financpre::create(
                        [
                            'ID_USUARIO' => $ID_USUARIOS_INSERIDO,
                            'ID_USUARIO_FINANCIADOR_FK' => ($ID_FINANCIADOR_PRE == 16) ? 1086 : $ID_FINANCIADOR_PRE,
                            'ID_BANCO' => $ID_FINANCIADOR_PRE,
                            'ID_AGENCIA' => $ID_AGENCIA_PRE,
                            'DOMINIO_EMAIL' => '@',
                            'NU_CNPJ' => $AG_CNPJ_PRE,
                            'NU_INSCRICAO' => $AG_INSCR_PRE,
                            'AG_CC' => $AG_CC_PRE,
                            'DS_ENDERECO' => $AG_ENDERECO_PRE,
                            'NO_CIDADE' => $AG_CIDADE_PRE,
                            'NO_ESTADO' => $AG_ESTADO_PRE,
                            'NU_CEP' => $AG_CEP_PRE,
                            'NO_CONTATO' => $AG_CONTATO_PRE,
                            'NU_DDD_TEL' => $AG_DDD_TEL_PRE,
                            'NU_TEL' => $AG_TEL_PRE,
                            'NO_CARGO' => $AG_CARGO_PRE,
                            'NU_DDD_FAX' => $AG_DDD_FAX_PRE,
                            'NU_FAX' => $AG_FAX_PRE,
                            'DS_EMAIL' => $AG_EMAIL_PRE,
                            'IC_PROEX' => $PROEX_PRE,
                            'IC_ATIVO' => 0,
                            'IC_PROPRIO_EXPORTADOR' => 0,
                            'NU_AG_NOVA' => $NU_AG_PRE,
                        ]
                    );
                }

                if ($apenasRecursosProprio != 1 & ($ID_AGENCIA_POS == "NULL" && (in_array(2, $modalidades) || in_array(3, $modalidades)))) { // Se nao houver agencia vinda do post ele cadastrar uma nova agencia.

                    $agenciaInsereridaPos = Agenciabb::create(
                        [
                            'NU_AGENCIA' => $NU_AG_POS,
                            'ID_GECEX_FK' => $ID_FINANCIADOR_POS,
                            'DE_AGENCIA' => $NO_AGENCIA_POS,
                            'NU_CNPJ_INSCR' => $AG_CNPJ_POS,
                            'DE_ENDER' => $AG_ENDERECO_POS,
                            'DE_CIDADE' => $AG_CIDADE_POS,
                            'CD_UF' => $AG_ESTADO_POS,
                            'NU_CEP' => $AG_CEP_POS,
                            'NU_DDD' => $AG_DDD_TEL_POS,
                            'NU_TEL' => $AG_TEL_POS,
                            'NU_INSCR_EST' => $AG_INSCR_POS,
                            'NO_CONTATO' => $AG_CONTATO_POS,
                            'NU_FAX' => $AG_FAX_POS,
                            'DS_EMAIL' => $AG_EMAIL_POS,
                            'ID_BANCO_FK' => $ID_FINANCIADOR_POS,
                            'IC_ATIVO' => 1,
                        ]
                    );

                    $ID_AGENCIA_POS = $agenciaInsereridaPos->ID_AGENCIA; // Pega o Ultimo ID da tabela inserido;

                }

                //Aqui e pos embarque

                if ($ID_FINANCIADOR_POS == "10000" && $apenasRecursosProprio == 1) { // Se for 10000 e recurso proprio

                    Financpos::create(
                        [
                            'ID_USUARIO' => $ID_USUARIOS_INSERIDO,
                            'ID_USUARIO_FINANCIADOR_FK' => $ID_USUARIOS_INSERIDO,
                            'ID_BANCO' => $ID_USUARIOS_INSERIDO,
                            'ID_AGENCIA' => null,
                            'DOMINIO_EMAIL' => '@',
                            'NU_CNPJ' => '-',
                            'NU_INSCRICAO' => '-',
                            'AG_CC' => '-',
                            'DS_ENDERECO' => '-',
                            'NO_CIDADE' => '-',
                            'NO_ESTADO' => '-',
                            'NU_CEP' => '-',
                            'NO_CONTATO' => '-',
                            'NU_DDD_TEL' => '',
                            'NU_TEL' => '',
                            'NO_CARGO' => '-',
                            'NU_DDD_FAX' => '',
                            'NU_FAX' => '',
                            'DS_EMAIL' => '-',
                            'IC_PROEX' => $PROEX_POS,
                            'IC_ATIVO' => 1,
                            'IC_PROPRIO_EXPORTADOR' => 1,
                            'NU_AG_NOVA' => '-',
                        ]
                    );

                } else { // caso nao seja recurso proprio entao:

                    $IDUSUARIOFINAN = $ID_USUARIOS_INSERIDO;
                    if (in_array(2, $modalidades) || in_array(3, $modalidades)) {
                        Financpos::create(
                            [
                                'ID_USUARIO' => $IDUSUARIOFINAN,
                                'ID_USUARIO_FINANCIADOR_FK' => ($ID_FINANCIADOR_POS == 16) ? 1086 : $ID_FINANCIADOR_POS,
                                'ID_BANCO' => $ID_FINANCIADOR_POS,
                                'ID_AGENCIA' => $ID_AGENCIA_POS,
                                'DOMINIO_EMAIL' => '@',
                                'NU_CNPJ' => $AG_CNPJ_POS,
                                'NU_INSCRICAO' => $AG_INSCR_POS,
                                'AG_CC' => $AG_CC_POS,
                                'DS_ENDERECO' => $AG_ENDERECO_POS,
                                'NO_CIDADE' => $AG_CIDADE_POS,
                                'NO_ESTADO' => $AG_ESTADO_POS,
                                'NU_CEP' => $AG_CEP_POS,
                                'NO_CONTATO' => $AG_CONTATO_POS,
                                'NU_DDD_TEL' => $AG_DDD_TEL_POS,
                                'NU_TEL' => $AG_TEL_POS,
                                'NO_CARGO' => $AG_CARGO_POS,
                                'NU_DDD_FAX' => $AG_DDD_FAX_POS,
                                'NU_FAX' => $AG_FAX_POS,
                                'DS_EMAIL' => $AG_EMAIL_POS,
                                'IC_PROEX' => $PROEX_POS,
                                'IC_ATIVO' => 0,
                                'IC_PROPRIO_EXPORTADOR' => 0,
                                'NU_AG_NOVA' => $NU_AG_POS,
                                'ID_NOME_BANCO_POS_FK' => $ID_NOME_BANCO_POS_FK,
                            ]
                        );
                    }

                }

                if (in_array(1, $modalidades) || in_array(2, $modalidades)) {

                    UsuarioCGCModel::create(
                        [
                            'ID_USUARIO_FK' => $ID_USUARIOS_INSERIDO,
                            'ID_FINANCIADOR_FK' => $ID_FINANCIADOR_PRE,
                            'TP_FINANCIADO' => 2,
                            'IC_ATIVO' => 1,

                        ]
                    );

                }

                UsuarioCGCModel::create(
                    [
                        'ID_USUARIO_FK' => $ID_USUARIOS_INSERIDO,
                        'ID_FINANCIADOR_FK' => $ID_FINANCIADOR_POS,
                        'TP_FINANCIADO' => 2,
                        'IC_ATIVO' => 1,

                    ]
                );

                Mpme_Responsav_Assinatura_Cgc::create(
                    [
                        'ID_USUARIO_RESPONSAVEL' => $ID_USUARIOS_INSERIDO,
                        'DATA' => date('Y-m-d'),
                        'NM_RESPONSAVEL' => $NM_RESPONSAVEL,
                        'CPF_RESPONSAVEL' => $CPF_RESPONSAVEL,
                        'EMAIL_RESPONSAVEL' => $EMAIL_RESPONSAVEL,
                    ]
                );

                $msgResp1 = "Cadastro realizado. Aguarde processamento dos seus dados.";
                $wOk = "T";

                // --- ASSOCIA AO GRUPO DE PERFIL DEFAULT --

                $ID_USUARIO = $ID_USUARIOS_INSERIDO;

                if ($ID_FINANCIADOR_POS != "10000" && $apenasRecursosProprio != 1) { // Caso não seja recurso proprio envia o e-mail para o banco

                    $email_para_banco = User::where('ID_USUARIO', '=', $ID_FINANCIADOR_POS)->first(['DE_EMAIL']);

                    $login_do_banco = User::where('ID_USUARIO', '=', $ID_FINANCIADOR_POS)->first(['CD_LOGIN']);

                    $senhaBanco = User::where('ID_USUARIO', '=', $ID_FINANCIADOR_POS)->first(['CD_SENHA']);
                    $senha_do_banco = $this->Decripta($senhaBanco);

                    // echo "<script>alert('".$email_para_banco."')</script>";
                    //$janela = "mail/inclusao.php?EMAIL=".$email_para_banco."&RSOCIAL=".$xNM_USUARIO."&LOGIN=".$login_do_banco."&SENHA=".$senha_do_banco

                    //@$email = new Email();
                    //@$email->enviarEmailInclusao($email_para_banco, $xNM_USUARIO, $login_do_banco, $senha_do_banco);
                }

                /////////////////////////////////// Simples Nacional ///////////////////////////////////////////////////////

                // Caso tenha marcado o check de SIM no Simples nacional

                // Cadastra na Cliente Exportador Regime Trabalho

                $clienteExportadorRegimeTributario = new ClienteExportadorRegimeTributario();
                $clienteExportadorRegimeTributario->ID_MPME_CLIENTE_EXPORTADORES = $idClienteExportador;
                $clienteExportadorRegimeTributario->ID_REGIME_TRIBUTARIO = ($request->simples_nacional == 2) ? 2 : 1;
                $clienteExportadorRegimeTributario->ID_ENQUADRAMENTO_TRIBUTARIO = ($request->simples_nacional == 2) ? $request->ENQUADRAMENTO_TRIBUTARIO : 1;
                $clienteExportadorRegimeTributario->DT_INI_PERIODO = date('Y-m-d h:i:s');
                $clienteExportadorRegimeTributario->ID_USUARIO_CAD = $ID_USUARIOS_INSERIDO;
                $clienteExportadorRegimeTributario->save();

                ///////////////////////////////////////////////////////////////////////////////////////////////////////////

                $NM_GRUPO_DEFAULT = Parametros::where('CD_PARAM', '=', 'GRUPOCLIENTE')->first(['VL_PARAM'])->VL_PARAM;
                $ID_GRUPO = Grupos::where('NM_GRUPO', '=', $NM_GRUPO_DEFAULT)->first(['ID_GRUPO'])->ID_GRUPO;

                if ($ID_GRUPO > 0) {

                    $associarUsuarioAoGrupo = GrupoUso::create(
                        [
                            'ID_GRUPO' => $ID_GRUPO,
                            'ID_USUARIO' => $ID_USUARIO,
                        ]
                    );

                    if (!$associarUsuarioAoGrupo) {
                        DB::rollback();
                        echo "Problemas na associão do cliente a um perfil.";
                    }
                } else {
                    DB::rollback();
                    echo "não existe um perfil default para associar o cliente.";
                }

                // --- GERA NOTIFICACOES DE NOVOS USUARIOS --

                $nm_link = "CADASTRO DE USUÁRIOS";
                $link_notif = "USUARIO1.PHP";
                $link_notif = $link_notif . "?ID_USUARIO=" . $ID_USUARIO;
                $link_notif = $link_notif . "&CD_LOGIN=" . $xCD_LOGIN;
                $link_notif = $link_notif . "&ID_GRUPO=" . $ID_GRUPO;
                $link_notif = $link_notif . "&NM_USUARIO=" . $xNM_USUARIO;
                $link_notif = $link_notif . "&TP_USUARIO=C";
                $link_notif = "javascript:LoadUsu(''" . $link_notif . "'')";

                $DE_TITULO = "NOVO CLIENTE CADASTRADO PELO SITE.";
                $DE_NOTIF = 'UM NOVO CLIENTE FOI CADASTRADO PELO SITE, ASSOCIADO AO GRUPO ' . $NM_GRUPO_DEFAULT . ':<BR>EMPRESA: <B><a href="#" onClick="' . $link_notif . '">' . $xNM_USUARIO . '</a></B><BR>LOGIN: <B><a href="#" onClick="' . $link_notif . '">' . $xCD_LOGIN . '</a></B><BR>CLIQUE NO NOME OU LOGIN PARA APROVAR OS DADOS E LIBERAR O SEU ACESSO AO SITE.';
                $ST_NOTIFUSU = "0";
                $TP_NOTIF = "U";

                if ($ID_USUARIO) { // Insere a notificação sem ID_NOTIF

                    $insereNotificacao = new Notificacoes();
                    $insereNotificacao->ID_STATUS_NOTIFICACAO_FK = 14;
                    $insereNotificacao->ID_USUARIO_FK = $ID_USUARIO;
                    $insereNotificacao->DE_NOTIFICACAO = " / " . $xNM_FANTASIA . " / " . $xNM_USUARIO;
                    $insereNotificacao->DS_LINK = "mpme_dados_cadastrais.php?BASE_ID_USUARIO=" . $ID_USUARIO;
                    $insereNotificacao->IC_ATIVO = 1;
                    if ($apenasRecursosProprio == 1) {
                        $insereNotificacao->TIPO_VALIDACAO = 'A'; // Se for apenas recursos proprio, envia direto para o analista
                    }
                    $insereNotificacao->save();

                    $retornaNotifInserida = new Notificacoes();
                    $retornaNotifInserida = $retornaNotifInserida->orderBy('ID_NOTIFICACAO', 'DESC')->first(['ID_NOTIFICACAO']);

                    $ID_NOTIF = $retornaNotifInserida->ID_NOTIFICACAO; // Pega o Ultimo ID da tabela inserido;

                    if ($insereNotificacao) { // Caso ele consiga inserir a notificação

                        if ($ID_NOTIF > 0) {

                            $link_notif = "mpme_dados_cadastrais.php?BASE_ID_USUARIO=" . $ID_USUARIO . "&ID_NOTIF=" . $ID_NOTIF;
                            $DE_NOTIF = 'UM NOVO CLIENTE FOI CADASTRADO PELO SITE, ASSOCIADO AO GRUPO ' . $NM_GRUPO_DEFAULT . ':<BR>EMPRESA: <B><a href="#" onClick="' . $link_notif . '">' . $xNM_USUARIO . '</a></B><BR>LOGIN: <B><a href="#" onClick="' . $link_notif . '">' . $xCD_LOGIN . '</a></B><BR>CLIQUE NO NOME OU LOGIN PARA APROVAR OS DADOS E LIBERAR O SEU ACESSO AO SITE.';

                            $atualizaNotificacao = Notificacoes::find($ID_NOTIF);

                            $atualizaNotificacao->DS_LINK = $link_notif;
                            $atualizaNotificacao->save();

                        }
                    }
                }

                if ($ID_USUARIO) {

                    $insereNotificacaoCadUsuario = Notificacoes::create(
                        [
                            'ID_STATUS_NOTIFICACAO_FK' => 41,
                            'ID_USUARIO_FK' => $ID_USUARIO,
                            'DE_NOTIFICACAO' => "UM NOVO EXPORTADOR FOI CADASTRADO PELO SITE - " . $xNM_USUARIO,
                            'DS_LINK' => "banco_mpme_dados_cadastrais.php?BASE_ID_USUARIO=" . $ID_USUARIO,
                            'IC_ATIVO' => 1,
                        ]
                    );

                    if ($insereNotificacaoCadUsuario) {

                        $retornaNotifInseridaCadUsuario = new Notificacoes();
                        $retornaNotifInseridaCadUser = $retornaNotifInseridaCadUsuario->orderBy('ID_NOTIFICACAO', 'DESC')->first(['ID_NOTIFICACAO']);

                        $ID_NOTIFXCadUsuario = $retornaNotifInseridaCadUser->ID_NOTIFICACAO; // Pega o Ultimo ID da tabela inserido;

                        if ($ID_NOTIFXCadUsuario > 0) {

                            $link_notifCadUsuario = "banco_mpme_dados_cadastrais.php?BASE_ID_USUARIO=" . $ID_USUARIO . "&ID_NOTIF=" . $ID_NOTIFXCadUsuario;
                            $DE_NOTIFCadUsuario = 'UM NOVO CLIENTE FOI CADASTRADO PELO SITE, ASSOCIADO AO GRUPO ' . $NM_GRUPO_DEFAULT . ':<BR>EMPRESA: <B><a href="#" onClick="' . $link_notifCadUsuario . '">' . $xNM_USUARIO . '</a></B><BR>LOGIN: <B><a href="#" onClick="' . $link_notifCadUsuario . '">' . $xCD_LOGIN . '</a></B><BR>CLIQUE NO NOME OU LOGIN PARA APROVAR OS DADOS E LIBERAR O SEU ACESSO AO SITE.';

                            $atualizaNotificacaoCadUsuario = Notificacoes::find($ID_NOTIFXCadUsuario);
                            $atualizaNotificacaoCadUsuario->DS_LINK = $link_notifCadUsuario;
                            $atualizaNotificacaoCadUsuario->save();

                        }
                    }

                    foreach ($request->pergunta as $pe) {
                        $novo_questionario = new MpmeQuestionario();
                        $novo_questionario->ID_MPME_PERGUNTA_RESPOSTA = $pe['IDRESP'];
                        $novo_questionario->ID_MPME_CLIENTE = $mpmeCliente->ID_MPME_CLIENTE;
                        $novo_questionario->IN_QUESTIONARIO_APLICADO = 'CAD_FORNECEDOR';
                        $novo_questionario->DS_OUTRA_RESPOSTA = ($pe['RESP'] != "") ? $pe['RESP'] : '';
                        $novo_questionario->IN_ATIVO = 'S';
                        $novo_questionario->DATA_CADASTRO = Carbon::now();
                        $novo_questionario->ID_USUARIO = $ID_USUARIOS_INSERIDO;
                        $novo_questionario->save();
                    }

                    $mpme_financeiro_exportador = new MpmeFinanceiroExportador();
                    $mpme_financeiro_exportador->ID_MPME_CLIENTE_EXPORTADORES = $idClienteExportador;
                    $mpme_financeiro_exportador->VL_EXP_BRUTO_ANUAL = str_replace(',', '.', str_replace('.', '', $FT_ANUAL));
                    $mpme_financeiro_exportador->VL_FAT_BRUTO_ANUAL = str_replace(',', '.', str_replace('.', '', $RE_ANUAL));
                    //$mpme_financeiro_exportador->VL_EXP_ESTIMADA = str_replace(',','.',str_replace('.','',$FT_ANUAL3));
                    $mpme_financeiro_exportador->IN_APROVADO = 'N';
                    $mpme_financeiro_exportador->IN_ATIVO = 'S';
                    $mpme_financeiro_exportador->DT_ANO_FISCAL = $ano_fiscal;
                    $mpme_financeiro_exportador->DT_INI_PERIODO = date('Y-m-d');
                    $mpme_financeiro_exportador->save();

                    DB::commit();

                    echo 'Cadastrado com sucesso!';
                }

            }

        } catch (\Exception $e) {
            DB::rollback();
            echo $e->getMessage() . " Na Linha - " . $e->getLine();
            // something went wrong
        }

    }

}
