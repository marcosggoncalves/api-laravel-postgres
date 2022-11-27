<?php

namespace App\Http\Controllers;

use App\Interfaces\IGerentesInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Exception;

class GerentesController extends Controller
{
    protected $repository;

    public function __construct(IGerentesInterface $repository)
    {
        $this->middleware(
            ['auth', 'verificarNivel'],
            ['except' => ['login']]
        );

        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/gerentes",
     *      operationId="listar_gerentes",
     *      tags={"Gerentes"},
     *      summary="Listar gerentes",
     *      description="Retornar todos os cadastros de gerentes",
     *      security={{"bearerAuth":{}}},
     *      @OA\Parameter(
     *      name="page",
     *      in="query",
     *       @OA\Schema(
     *           type="number"
     *       )
     *      ), 
     *      @OA\Response(
     *          response=200,
     *          description="Tudo certo!",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *       ),
     *      @OA\Response(
     *          response=401,
     *          description="Não autorizado, login é necessário ou você não tem permissão! ",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      ),
     *      @OA\Response(
     *          response=500,
     *          description="Erro interno!",
     *           @OA\MediaType(
     *              mediaType="application/json",
     *          )
     *      )
     *   )
     */
    public function index()
    {
        try {
            $gerentes = $this->repository->getAll();

            return response()->json([
                'status' => true,
                'gerentes' => $gerentes
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar gerentes!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/gerentes",
     *   tags={"Gerentes"},
     *   summary="Cadastrar Novo Gerente",
     *   operationId="novo_gerente",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"nome","email", "password", "nivel"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234"),
     *       @OA\Property(property="nivel", type="number", format="text", example="1")
     *    ),
     * ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário ou você não tem permissão! "
     *   ),
     *    @OA\Response(
     *      response=417,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|string',
                'password' => 'required|string',
                'email' => 'required|string|email|unique:gerentes,email',
                'nivel' => 'required',
            ]);

            if ($validator->fails()) {
                return  Response([
                    'status' => true,
                    'message' => 'Não foi possivel cadastrar usuário!',
                    'error' => $validator->errors()
                ], 417);
            }

            $gerente = $this->repository->store($request->all());

            return Response([
                'status' => true,
                'message' => 'Usuário cadastrado com sucesso!',
                'gerente' => $gerente
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel realizar cadastro!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Put(
     ** path="/api/v1/gerentes/{id}",
     *   tags={"Gerentes"},
     *   summary="Alterar cadastro do gerente",
     *   operationId="editar_gerente",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"nome","email", "password", "nivel"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234"),
     *       @OA\Property(property="nivel", type="number", format="text", example="1")
     *    ),
     * ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário ou você não tem permissão! "
     *   ),
     *    @OA\Response(
     *      response=417,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function update(Request $request, $id)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nome' => 'required|string',
                'email' => 'required|string|email',
                'nivel' => 'required'
            ]);

            if ($validator->fails()) {
                return  Response([
                    'status' => true,
                    'message' => 'Não foi possivel alterar cadastro!',
                    'error' => $validator->errors()
                ], 417);
            }

            $this->repository->update($id, $request->all());

            return Response([
                'status' => true,
                'message' => 'Cadastro alterado com sucesso!'
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel alterar cadastro!',
                'error' => $e
            ], 500);
        }
    }

    /**
     * @OA\Delete(
     ** path="/api/v1/gerentes/{id}",
     *   tags={"Gerentes"},
     *   summary="Excluir cadastro de um gerente",
     *   operationId="excluir_gerente",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
     *      @OA\Schema(
     *           type="number"
     *      )
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário ou você não tem permissão! "
     *   )
     *)
     **/
    public function destroy($id)
    {
        try {
            $deletarGerente = $this->repository->destroy($id);

            if (!$deletarGerente) {
                return Response()->json([
                    'status' => false,
                    'message' => 'Não foi possivel excluir registro!'
                ], 417);
            }

            return Response()->json([
                'status' => true,
                'message' => 'Registro excluido com sucesso!'
            ]);
        } catch (Exception $e) {
            return Response()->json([
                'status' => false,
                'message' => 'Não foi possivel excluir cadastro!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/gerentes/login",
     *   tags={"Gerentes"},
     *   summary="Efetuar login como gerente",
     *   operationId="login_gerente",
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"email", "password"},
     *       @OA\Property(property="email", type="string", format="text", example="marcoslopesg7@gmail.com"),
     *       @OA\Property(property="password", type="string", format="text", example="1234")
     *    ),
     *   ),
     *   @OA\Response(
     *      response=200,
     *      description="Tudo Certo!",
     *      @OA\MediaType(
     *           mediaType="application/json",
     *      )
     *   ),
     *   @OA\Response(
     *      response=401,
     *      description="Não autorizado, login é necessário ou você não tem permissão! "
     *   ),
     *    @OA\Response(
     *      response=417,
     *      description="Entradas inválidas, campos obrigatórios! "
     *   )
     *)
     **/
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return  Response([
                'status' => true,
                'message' => 'Não foi possivel fazer login!',
                'error' => $validator->errors()
            ], 417);
        }

        $token = Auth::attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);

        if (!$token) {
            return response()->json([
                'status' => false,
                'message' => 'Email/ou Senha inválido(s)!',
            ], 401);
        }

        $usuario = Auth::user();

        return response()->json([
            'status' => true,
            'token' => $token,
            'usuario' => $usuario,
        ]);
    }
}
