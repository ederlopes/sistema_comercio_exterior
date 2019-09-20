<?php
namespace App\Http\Controllers;

use App\Gecex;
use App\ModalidadeFinanciamento;
use App\Repositories\CadastroRepository;
use App\Repositories\UsersRepository;
use App\User;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{

    public function index()
    {
        // var_dump(Usuario::all());
    }

    // Autentica usando apenas o ID e redireciona para home
    public function loginById(Request $request)
    {
        Auth::loginUsingId($request->ID_USUARIO);
        return redirect(url('home'));
    }

    public function alterarSenha(Request $request, UsersRepository $userRepository)
    {
        $retorno = $userRepository->alterarSenha($request);

        if ($retorno['sucesso'] == false) {
            return response()->json(array(
                'status' => 'erro',
                'recarrega' => 'false',
                'msg' => $retorno['msg'],
            ));
        } else {
            return response()->json(array(
                'status' => 'sucesso',
                'recarrega' => 'url',
                'url' => '/logout',
                'msg' => $retorno['msg'],
            ));
        }
    }

    public function atualizacaoCadastral(Request $request)
    {
        $modalidades = ModalidadeFinanciamento::all();
        $financiadores = User::where('TP_USUARIO', '=', 'B')->whereIn('ID_USUARIO', [16, 1047, 1052, 1058, 1048, 1056, 1050, 1051, 1049, 1059, 1061, 1062, 1040, 1060, 1045, 557, 1039, 1044, 1042, 1065, 1053, 1064, 1055, 1054, 1063, 1041, 1046, 1043])->get();
        $gecexs = Gecex::where('FL_ATIVO', '=', 1)->where('ID_GECEX_BB', '<>', 20)->orderBy('NO_GECEX')->get();

        return view('usuario.atualizacao_cadastro', compact('modalidades', 'financiadores', 'gecexs'));
    }

    public function atualizarCadastro(Request $request)
    {
        try { // try catch para pegar mensagens de erro em algum inser

            /** mensagens personalizadas de erro para o usuario */

            $messages = [

                // Pre embarque
                //'ID_MPME_FINANC_PRE.required' => 'Financiador do pre-embarque não encontrato!',
                'ID_FINANCIADOR_PRE.numeric' => 'Campo de Instituição financeira inválido!',
                'NO_AGENCIA_PRE.string' => 'Campo Agencia inválido!',
                'AG_CEP_PRE.string' => 'Campo CEP inválido!',
                'AG_ENDERECO_PRE.string' => 'Campo endereço inválido!',
                'AG_ESTADO_PRE.max' => 'Erro no campo de estado!',
                'AG_ESTADO_PRE.min' => 'Campo de estado inválido!',
                'AG_CIDADE_PRE.string' => 'Campo cidade inválido!',
                'AG_CNPJ_PRE.string' => 'Campo CNPJ inválido!',
                'AG_INSCR_PRE.string' => 'Campo incrição estadual inválido!',
                'AG_CONTATO_PRE.string' => 'Campo contato do pre-embarque inválido!',
                'AG_CARGO_PRE.string' => 'Campo Cargo do pre-embarque inválido!',
                'AG_TEL_PRE.string' => 'Campo telefone do pre-embarque inválido!',
                'AG_EMAIL_PRE.string' => 'Campo email do pre-embarque inválido!',

                // Pos embarque
                //'ID_MPME_FINANC_POS.required' => 'Financiador do pos-embarque não encontrato!',
                'ID_FINANCIADOR_POS.numeric' => 'Campo de Instituição financeira inválido!',
                'NO_AGENCIA_POS.string' => 'Campo Agencia inválido!',
                'AG_CEP_POS.string' => 'Campo CEP inválido!',
                'AG_ENDERECO_POS.string' => 'Campo endereço inválido!',
                'AG_ESTADO_POS.max' => 'Erro no campo de estado!',
                'AG_ESTADO_POS.min' => 'Campo de estado inválido!',
                'AG_CIDADE_POS.string' => 'Campo cidade inválido!',
                'AG_CNPJ_POS.string' => 'Campo CNPJ inválido!',
                'AG_INSCR_POS.string' => 'Campo incrição estadual inválido!',
                'AG_CONTATO_POS.string' => 'Campo contato do pre-embarque inválido!',
                'AG_CARGO_POS.string' => 'Campo Cargo do pre-embarque inválido!',
                'AG_TEL_POS.string' => 'Campo telefone do pre-embarque inválido!',
                'AG_EMAIL_POS.string' => 'Campo email do pre-embarque inválido!',

            ];

            // Valida os campos conforme tamanho no banco de dados e demais caracteristicas
            $validator = Validator::make($request->all(), [

                //Pre-embarque
                //'ID_MPME_FINANC_PRE' => 'sometimes|required',
                'ID_FINANCIADOR_PRE' => 'sometimes|numeric',
                'NO_AGENCIA_PRE' => 'sometimes|string',
                'AG_CEP_PRE' => 'sometimes|string',
                'AG_ENDERECO_PRE' => 'sometimes|string',
                'AG_ESTADO_PRE' => 'sometimes|max:2',
                'AG_ESTADO_PRE' => 'sometimes|min:2',
                'AG_CIDADE_PRE' => 'sometimes|string',
                'AG_CNPJ_PRE' => 'sometimes|string',
                'AG_INSCR_PRE' => 'sometimes|string',
                'AG_CONTATO_PRE' => 'sometimes|string',
                'AG_TEL_PRE' => 'sometimes|string',
                'AG_EMAIL_PRE' => 'sometimes|string',

                //Pos-embarque
                //'ID_MPME_FINANC_POS' => 'sometimes|required',
                'ID_FINANCIADOR_POS' => 'sometimes|numeric',
                'NO_AGENCIA_POS' => 'sometimes|string',
                'AG_CEP_POS' => 'sometimes|string',
                'AG_ENDERECO_POS' => 'sometimes|string',
                'AG_ESTADO_POS' => 'sometimes|max:2',
                'AG_ESTADO_POS' => 'sometimes|min:2',
                'AG_CIDADE_POS' => 'sometimes|string',
                'AG_CNPJ_POS' => 'sometimes|string',
                'AG_INSCR_POS' => 'sometimes|string',
                'AG_CONTATO_POS' => 'sometimes|string',
                'AG_CARGO_POS' => 'sometimes|string',
                'AG_TEL_POS' => 'sometimes|string',
                'AG_EMAIL_POS' => 'sometimes|string',

            ], $messages);

            /**
             * caso algum dos campos não estejam de acordo com a validação
             * retorna um json com erro e o tipo do erro que será exibido pelo javascript
             *
             */

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'erro',
                    'message' => $validator->errors()->all(),
                    'class_mensagem' => 'error',
                    'header' => 'Erro!',
                ]);

            } else {

                $modalidades = []; // Ira receber todas as modalidades escolhida pelo exportador no cadastro
                $financiamentos = []; // ... financiamentos
                $apenasRecursosProprio = 0; // sera usado para definir se eh ou não apenas recursos proprios

                /**
                 * Remove o caractere # separando as modalidades dos financiamentos
                 * e atribue as variaveis para utilização posterior
                 */

                foreach ($request->id_modalidade as $modalid) {
                    $registro_atual = explode("#", $modalid);
                    array_push($modalidades, $registro_atual[0]);
                    array_push($financiamentos, $registro_atual[1]);
                }

                /**
                 * Caso tenha escolhido apenas recurso proprios na lista, atribue a variavel
                de financiamento apenas a opção de recursos proprios
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

                // Cadastra o exportador
                if (CadastroRepository::AtualizaCadastroExportador($request, $modalidades, $apenasRecursosProprio)) {

                    return response()->json([
                        'status' => 'sucesso',
                        'message' => "Cadastro Atualizado com sucesso!",
                        'class_mensagem' => 'success',
                        'header' => 'Sucesso!',
                    ]);

                } else {

                    return response()->json([
                        'status' => 'erro',
                        'message' => "Ocorreu um erro ao salvar o cadastro!",
                        'class_mensagem' => 'error',
                        'header' => 'Erro!',
                    ]);

                }
            }

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 'erro',
                'message' => $e->getMessage() . " Na Linha - " . $e->getLine(),
                'class_mensagem' => 'error',
                'header' => 'Erro!',
            ]);
        }

    }

}
