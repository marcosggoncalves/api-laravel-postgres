<?php

namespace App\Http\Controllers;

use App\Interfaces\IClientesInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

class ClientesController extends Controller
{
    protected $repository;

    public function __construct(IClientesInterface $repository)
    {
        $this->middleware(
            [
                'auth',
                'verificarNivel'
            ]
        );

        $this->repository = $repository;
    }

    /**
     * @OA\Get(
     *      path="/api/v1/clientes",
     *      operationId="index_clientes",
     *      tags={"Clientes"},
     *      summary="Listar clientes",
     *      description="Retornar todos os cadastros de clientes",
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
            $clientes = $this->repository->getAll();

            return response()->json([
                'status' => true,
                'clientes' => $clientes
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar clientes!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/clientes",
     *   tags={"Clientes"},
     *   summary="Cadastrar novo cliente",
     *   operationId="store_clientes",
     *   security={{"bearerAuth":{}}}, 
     *   @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"nome","cnpj", "data_fundacao", "grupo_id"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *       @OA\Property(property="cnpj", type="string", format="text", example="81.466.424/0001-48"),
     *       @OA\Property(property="data_fundacao", type="string", format="text", example="2022-11-26"),
     *       @OA\Property(property="grupo_id", type="number", format="number", example="1"),
     *    ),
     *  ),
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
                'cnpj' => 'required|string|min:18|max:18|unique:clientes,cnpj',
                'data_fundacao' => 'required',
                'grupo_id' => 'required'
            ]);

            if ($validator->fails()) {
                return  Response([
                    'status' => true,
                    'message' => 'Não foi possivel cadastrar usuário!',
                    'error' => $validator->errors()
                ], 417);
            }

            $cliente = $this->repository->store($request->all());

            return Response([
                'status' => true,
                'message' => 'Cliente cadastrado com sucesso!',
                'cliente' => $cliente
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
     ** path="/api/v1/clientes/{id}",
     *   tags={"Clientes"},
     *   summary="Alterar cadastro do cliente",
     *   operationId="update_clientes",
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
     *       required={"nome","cnpj", "data_fundacao", "grupo_id"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *       @OA\Property(property="cnpj", type="string", format="text", example="81.466.424/0001-48"),
     *       @OA\Property(property="data_fundacao", type="string", format="text", example="2022-11-26"),
     *       @OA\Property(property="grupo_id", type="number", format="number", example="1"),
     *    ),
     *  ),
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
                'cnpj' => 'required|string',
                'data_fundacao' => 'required',
                'grupo_id' => 'required'
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
     ** path="/api/v1/clientes/{id}",
     *   tags={"Clientes"},
     *   summary="Excluir cadastro de um cliente",
     *   operationId="destroy_clientes",
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
}
