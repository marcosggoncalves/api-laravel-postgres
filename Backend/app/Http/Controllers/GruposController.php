<?php

namespace App\Http\Controllers;

use App\Interfaces\IGruposInterface;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Exception;

class GruposController extends Controller
{
    protected $repository;

    public function __construct(IGruposInterface $repository)
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
     *      path="/api/v1/grupos",
     *      operationId="listar_grupos",
     *      tags={"Grupos"},
     *      summary="Listar grupos",
     *      description="Retornar todos os grupos cadastrados",
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
            $grupos = $this->repository->getAll();

            return response()->json([
                'status' => true,
                'grupos' => $grupos
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel listar grupos!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Get(
     *      path="/api/v1/grupos/{id}",
     *      operationId="get_grupo",
     *      tags={"Grupos"},
     *      summary="Detalhar informação do grupo  e clientes vinculados",
     *      description="Retornar informações do grupo e clientes vinculados",
     *      security={{"bearerAuth":{}}}, 
     *      @OA\Parameter(
     *      name="id",
     *      in="path",
     *      required=true,
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
    public function show($id)
    {
        try {
            $grupo = $this->repository->getClientes($id);

            return response()->json([
                'status' => true,
                'grupo' => $grupo
            ]);
        } catch (Exception $e) {
            return Response([
                'status' => false,
                'message' => 'Não foi possivel detalhar informação do grupo!',
                'error' => $e
            ], 500);
        }
    }
    /**
     * @OA\Post(
     ** path="/api/v1/grupos",
     *   tags={"Grupos"},
     *   summary="Cadastrar novo grupo",
     *   operationId="novo_grupo",
     *   security={{"bearerAuth":{}}}, 
     * @OA\RequestBody(
     *    required=true,
     *    description="Campos para cadastros",
     *    @OA\JsonContent(
     *       required={"nome"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
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
                'nome' => 'required|string|min:3|unique:grupos,nome'
            ]);

            if ($validator->fails()) {
                return  Response([
                    'status' => true,
                    'message' => 'Não foi possivel cadastrar grupo!',
                    'error' => $validator->errors()
                ], 417);
            }

            $grupo = $this->repository->store($request->all());

            return Response([
                'status' => true,
                'message' => 'Grupo cadastrado com sucesso!',
                'grupo' => $grupo
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
     ** path="/api/v1/grupos/{id}",
     *   tags={"Grupos"},
     *   summary="Alterar cadastro do grupo",
     *   operationId="editar_grupo",
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
     *       required={"nome"},
     *       @OA\Property(property="nome", type="nome", format="text", example="Marcos"),
     *    ),
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
     ** path="/api/v1/grupos/{id}",
     *   tags={"Grupos"},
     *   summary="Excluir cadastro do grupo",
     *   operationId="excluir_grupo",
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
            $deletarGrupo = $this->repository->destroy($id);

            if (!$deletarGrupo) {
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
                'message' => 'Não foi possivel excluir grupo, pode ser que tenha clientes vinculados.',
                'error' => $e
            ], 500);
        }
    }
}
