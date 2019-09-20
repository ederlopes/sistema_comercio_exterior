<?php

namespace App\Http\Controllers;

use App\Repositories\CadastroRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\User;

class CadastroController extends Controller
{
    //

    public function cadastrar(Request $request)
    {
        try { // try catch para pegar mensagens de erro em algum inser

            /** mensagens personalizadas de erro para o usuario */

            $messages = [
                //Dados de acesso
                'NU_CNPJ.required' => 'Voce deve preencher o CNPJ!',
                'NU_CNPJ.min' => 'Confira o número de seu CNPJ',
                'NU_CNPJ.max' => 'Número de CNPJ inválido',
                'NU_CNPJ.unique' => 'Esse CNPJ já está cadastrado em nossa base.',

                'LOGIN.required' => 'Preencha o campo de usuario!',
                'LOGIN.min' => 'O campo de login deve ter no minimo 3 caractéres!',
                'LOGIN.max' => 'O campo de login deve ter no maximo 15 caractéres!',
                'LOGIN.unique' => 'O usuario escolhido ja existe escolha outro usuario!',

                'DS_SENHA.required' => 'Você não digitou uma senha!',
                'DS_SENHA.min' => 'Sua senha deve ter pelo menos 6 caracteres!',
                'DS_SENHA.max' => 'Sua senha deve ter pelo no maximo 10 caractéres!',
                'DS_SENHA.same' => 'Sua confirmação de senha não conferece com a senha!',
                'DS_SENHA_C.required' => 'Você não digitou a confirmação da senha!',
                'DS_SENHA_C.min' => 'A confirmação da senha deve ter pelo menos 6 caracteres!',
                'DS_SENHA_C.max' => 'A confirmação da senha deve ter no máximo 10 caracteres!',
                //Representante legal
                'NM_RESPONSAVEL.required' => 'Voce deve preencher o nome do represetante legal!',
                'NM_RESPONSAVEL.min' => 'O nome do responsável legal deve ter no minimo 1 caractére!',
                'NM_RESPONSAVEL.max' => 'O nome do responsável legal deve ter no máximo 150 caractéres!',

                'EMAIL_RESPONSAVEL.required' => 'Voce deve preencher o e-mail do represetante legal!',

                'CPF_RESPONSAVEL.required' => 'Voce deve preencher o cpf do represetante legal!',
                'CPF_RESPONSAVEL.min' => 'CPF do representante legal é inválido !',
                'CPF_RESPONSAVEL.max' => 'CPF do representante legal é inválido !',
                //Quadro Societario Legal
                'NOME_QUADRO.required' => 'Voce deve preencher o nome do socio',
                'NOME_QUADRO.min' => 'O nome do sócio deve ter pelo menos 1 caractére',
                'NOME_QUADRO.max' => 'O nome do sócio deve ter no máximo 50 caractéres',

                'CPF_QUADRO.required' => 'Voce deve preencher o cpf do socio!',

                'PARTICIPACAO_QUADRO.required' => 'Voce deve preencher a porcentagem de participação do socio!',
                //Dados cadastrais
                'NM_USUARIO.required' => 'Preencha a razão social!',
                'NM_USUARIO.min' => 'O nome da razão social necessita de no minimo 1 caractére',
                'NM_USUARIO.max' => 'O nome da razão social deve ter no máximo 80 caractéres',

                'NM_FANTASIA.required' => 'Preencha o nome fantasia!',
                'NM_FANTASIA.min' => 'O nome fantasia necessita de no minimo 1 caractére',
                'NM_FANTASIA.max' => 'O nome fantasia deve ter no máximo 80 caractéres',

                'simples_nacional.required' => 'Selecione ao menos uma opção no campo simples nacional!',
                'simples_nacional.numeric' => 'A opção ecolhida do simples nacional é inválida!',
                'pergunta.required' => 'O campo referente ao evento e como ficou sabendo da abgf não podem ser vazio!',

                'NU_INSCR_ESTADUAL.required' => 'Preencha a inscrição estadual!',
                'NU_INSCR_ESTADUAL.min' => 'O número de inscrição estadual deve ter pelo menos 1 caractére!',
                'NU_INSCR_ESTADUAL.max' => 'O número de inscrição estadual deve ter no máximo 50 caractéres!',

                'NU_INSCR_MUNICIPAL.required' => 'Preencha a inscrição municipal!',
                'NU_INSCR_MUNICIPAL.min' => 'O número de inscrição municipal deve ter pelo menos 1 caractére!',
                'NU_INSCR_MUNICIPAL.max' => 'O número de inscrição municipal deve ter no máximo 50 caractéres!',

                'CAPITAL_QUADRO.required' => 'Preencha o capital social!',
                'CAPITAL_QUADRO.min' => 'O capital social deve ter pelo menos 4 caractéres!',
                'CAPITAL_QUADRO.min' => 'O capital social deve ter no máximo 50 caractéres!',

                'NU_FUNCIONARIO_EMPRESA.required' => 'Preencha o nº de funcionários de sua empresa!',

                'ID_TEMPO.required' => 'Selecione o tempo de existência da empresa!',
                'ID_TEMPO.numeric' => 'Você selecionou uma opção invalida no tempo de existência da empresa!',
                //Endereço
                'DE_CEP.required' => 'Preencha o CEP!',
                'DE_CEP.min' => 'O cep deve ter no mímimo 1  caractére!',
                'DE_CEP.max' => 'O cep deve ter no máximo 9 caractéres!',

                'CD_UF.required' => 'Preencha o estado da empresa!',
                'CD_UF.max' => 'O estado escolhido é inválido!',

                'DE_CIDADE.required' => 'Preencha a cidade da empresa',
                'DE_CIDADE.min' => 'A cidade deve ter pelo menos 4 caractéres!',
                'DE_CIDADE.max' => 'A cidade deve ter no máximo 50 caractéres!',

                'DE_ENDER.required' => 'Preencha o endereço da empresa!',
                'DE_ENDER.min' => 'O endereço deve ter pelo menos 1 caractéres!',
                'DE_ENDER.max' => 'O endereço deve ter no máximo 50 caractéres!',

                //Contato
                'NM_CONTATO.required' => 'Preencha o nome de contato!',
                'NM_CONTATO.min' => 'O nome de contato deve ter pelo menos 1 caractéres!',
                'NM_CONTATO.max' => 'O nome de contato deve ter no máximo 50 caractéres!',

                'DE_CARGO.required' => 'Preencha o cargo do contato!',
                'DE_CARGO.min' => 'O cargo do contato deve ter pelo menos 1 caractéres!',
                'DE_CARGO.max' => 'O cargo do contato deve ter no máximo 50 caractéres!',

                'DE_TEL.required' => 'Preencha o telefone do contato!',
                'DE_TEL.min' => 'O telefone do contato deve ter pelo menos 8 caractéres!',
                'DE_TEL.max' => 'O telefone do contato deve ter no máximo 30 caractéres!',

                'DE_EMAIL.required' => 'Preencha o e-mail do contato!',
                'DE_EMAIL.min' => 'O e-mail do contato deve ter pelo menos 5 caractéres!',
                'DE_EMAIL.max' => 'O e-mail do contato deve ter no máximo 80 caractéres!',
                'DE_EMAIL.email' => 'O e-mail do contato é inválido!',
                'DE_EMAIL.unique' => 'O e-mail informado ja existe escolha outro email!',

                //Dados financeiros / operacionais
                'id_modalidade.required' => 'Você deve selecionar ao menos uma modalidade!',

                'calendario_fiscal.required' => 'Você deve selecionar o ano das informações !',
                'calendario_fiscal.numeric' => 'Calendario fiscal inválido!',

                'FATURAMENTO_BRUTO_ANUAL.required' => 'O campo Faturamento bruto anual nao pode ser vazio!',
                'FATURAMENTO_BRUTO_ANUAL.min' => 'O faturamento bruto anual deve ter pelo menos 5 caractéres!',
                'FATURAMENTO_BRUTO_ANUAL.max' => 'O faturamento bruto anual deve ter no máximo 50 caractéres!',

                'VL_BRUTO_ANUAL.required' => 'O campo de exportação anual da empresa.!',
                'VL_BRUTO_ANUAL.min' => 'O valor de exportação anual deve ter pelo menos 5 caractéres!',
                'VL_BRUTO_ANUAL.max' => 'O valor de exportação anual deve ter no máximo 50 caractéres!',

                'dre.required' => 'Envie o pdf contendo a DRE.!',
                'dre.mimes' => 'Só é permitido o envio de arquivo .PDF',
                'dre.max' => 'O tamanho maximo do PDF é de 32MB',

                'comprovante_exportacao.required' => 'Envie o pdf contendo o comprovante de exportações.!',
                'comprovante_exportacao.mimes' => 'Só é permitido o envio de arquivo .PDF!',
                'comprovante_exportacao.max' => 'O tamanho maximo do PDF é de 32MB!',

                'CHECK_PROMETE_ENVIO.required' => 'Você deve aceitar o termo que informa que você está ciente que a ABGF poderá solicitar documentos!',
                'CHECK_PROMETE_ENVIO.numeric' => 'O aceite selecionado na opção de aceite da abgf é inválido!.',

                'd_autorizar.required' => 'Você deve aceitar os termos de uso!',
                'd_autorizar.numeric' => 'O aceite no termos de uso é inválido!',

                // Pre embarque
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

                //Dados de acesso
                'NU_CNPJ' => 'required|min:14|max:18',
                'LOGIN' => 'required|min:3|max:15|unique:USUARIOS,CD_LOGIN',
                'DS_SENHA' => 'required|min:6|max:10|same:DS_SENHA_C',
                'DS_SENHA_C' => 'required|min:6|max:10',
                // Representante legal
                'NM_RESPONSAVEL' => 'required|min:1|max:150',
                'EMAIL_RESPONSAVEL' => 'required|min:1|max:150|email',
                'CPF_RESPONSAVEL' => 'required|min:11|max:14',
                //Quadro societário
                'NOME_QUADRO' => 'required|min:1|max:80',
                'NM_FANTASIA' => 'required|min:1|max:80',
                'CPF_QUADRO' => 'required',
                'PARTICIPACAO_QUADRO' => 'required',
                'CAPITAL_QUADRO' => 'required|min:4|max:50',
                //Dados cadastrais
                'NM_USUARIO' => 'required|min:1|max:80',
                'NM_USUARIO' => 'required|min:1|max:80',
                'simples_nacional' => 'required|numeric',
                'pergunta' => 'required',
                'NU_INSCR_ESTADUAL' => 'required|min:1|max:50',
                'NU_INSCR_MUNICIPAL' => 'required|min:1|max:50',
                'ID_TEMPO' => 'required|numeric',
                //Endereço
                'DE_CEP' => 'required|min:1|max:9',
                'CD_UF' => 'required|max:2',
                'DE_CIDADE' => 'required|min:1|max:50',
                'DE_ENDER' => 'required|min:1|max:80',
                //Contato
                'NM_CONTATO' => 'required|min:1|max:50',
                'DE_CARGO' => 'required|min:1|max:50',
                'DE_TEL' => 'required|min:8|max:30',
                'DE_EMAIL' => 'required|min:5|max:80|email|unique:USUARIOS,DE_EMAIL',
                //Dados financeiros / operacionais
                'id_modalidade' => 'required',
                'calendario_fiscal' => 'required|numeric',
                'FATURAMENTO_BRUTO_ANUAL' => 'required|min:1|max:50',
                'VL_BRUTO_ANUAL' => 'required|min:1|max:50',
                'dre' => 'required|mimes:pdf|max:32768',
                'comprovante_exportacao' => 'required|mimes:pdf|max:32768',
                //aceite e termos de uso
                'CHECK_PROMETE_ENVIO' => 'required|numeric',
                'd_autorizar' => 'required|numeric',

                //Pre-embarque
                'ID_FINANCIADOR_PRE' => 'numeric|nullable',
                'NO_AGENCIA_PRE' => 'string|nullable',
                'AG_CEP_PRE' => 'string|nullable',
                'AG_ENDERECO_PRE' => 'string|nullable',
                'AG_ESTADO_PRE' => 'max:2|nullable',
                'AG_ESTADO_PRE' => 'min:2|nullable',
                'AG_CIDADE_PRE' => 'string|nullable',
                'AG_CNPJ_PRE' => 'string|nullable',
                'AG_INSCR_PRE' => 'string|nullable',
                'AG_CONTATO_PRE' => 'string|nullable',
                'AG_CARGO_PRE' => 'string|nullable',
                'AG_TEL_PRE' => 'string|nullable',
                'AG_EMAIL_PRE' => 'string|nullable',

                //Pos-embarque
                'ID_FINANCIADOR_POS' => 'numeric|nullable',
                'NO_AGENCIA_POS' => 'string|nullable',
                'AG_CEP_POS' => 'string|nullable',
                'AG_ENDERECO_POS' => 'string|nullable',
                'AG_ESTADO_POS' => 'max:2|nullable',
                'AG_ESTADO_POS' => 'min:2|nullable',
                'AG_CIDADE_POS' => 'string|nullable',
                'AG_CNPJ_POS' => 'string|nullable',
                'AG_INSCR_POS' => 'string|nullable',
                'AG_CONTATO_POS' => 'string|nullable',
                'AG_CARGO_POS' => 'string|nullable',
                'AG_TEL_POS' => 'string|nullable',
                'AG_EMAIL_POS' => 'string|nullable',

            ], $messages);

            /**
             * caso algum dos campos não estejam de acordo com a validação
             * retorna um json com erro e o tipo do erro que será exibido pelo javascript
             *
             */

           $cnpj_existe = User::where('NU_CNPJ', $request->NU_CNPJ)->whereIn('FL_ATIVO',[0,1])->first();
           if($cnpj_existe != null){
               return response()->json([
                   'status' => 'erro',
                   'message' => 'Esse CNPJ já está cadastrado em nossa base.',
                   'class_mensagem' => 'error',
                   'header' => 'Erro!',
               ]);
           }

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
                if (CadastroRepository::CadastraUsuarioEFazUpload($request, $modalidades, $apenasRecursosProprio)) {

                    // Certifica que se existe agencia do pos embarque e que não é recursos proprios e diferente do banco do brasil
                    // if ((isset($request->ID_FINANCIADOR_POS) && $request->ID_FINANCIADOR_POS != 16 || isset($request->ID_FINANCIADOR_PRE) && $request->ID_FINANCIADOR_PRE != 16)
                    //     && (trim($request->ID_GECEX_POS) == "" || trim($request->ID_GECEX_POS2) == "") && $apenasRecursosProprio != 1) {
                    //     $financiador = CadastroRepository::CadastraOuRetornaFinanciador($request);
                    // }

                    return response()->json([
                        'status' => 'sucesso',
                        'message' => "Cadastro efetuado com sucesso!",
                        'class_mensagem' => 'success',
                        'header' => 'Sucesso!',
                    ]);
                } else {

                    return response()->json([
                        'status' => 'erro',
                        'message' => "Ocorreu um erro ao salvar o responsavel pela assinatura do cgc, tente novamente mais tarde!",
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
