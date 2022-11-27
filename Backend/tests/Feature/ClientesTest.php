<?php

namespace Tests\Feature;

use App\Utils\GerarCNPJ;
use Tests\TestCase;

class ClientesTest extends TestCase
{
    private $route = 'api/v1/clientes';
    
    private $routeAuth = 'api/v1/gerentes';

    private function cnpj()
    {
        return GerarCNPJ::gerar();
    }

    private function login($auth)
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->post("{$this->routeAuth}/login", $auth);

        return $response;
    }

    public function testTestarAutenticacaoController()
    {
        $response = $this->withHeaders([
            'Accept' => 'application/json',
        ])->get("{$this->route}");

        $response->assertStatus(401);
    }

    public function testListarClientes()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->get("{$this->route}");

        $response->assertStatus(200);
    }

    public function testNovoCadastroCliente()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $cadastro = [
            "nome" => "Marcos",
            "cnpj" => $this->cnpj(),
            "data_fundacao" => "2022-11-26",
            "grupo_id" => 1
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $response->assertStatus(200);
    }

    public function testNovoCadastroClienteCamposVazios()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $cadastroCamposVazios = [
            "nome" => null,
            "cnpj" => null,
            "data_fundacao" => null,
            "grupo_id" => null
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastroCamposVazios);

        $response->assertStatus(417);
    }

    public function testAlterarCadastroCliente()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        /// Criar cadastro para editar
        $cadastro = [
            "nome" => "Marcos",
            "cnpj" => $this->cnpj(),
            "data_fundacao" => "2022-11-26",
            "grupo_id" => 1
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $novoCadastro = $response->decodeResponseJson();

        /// Editar Cadastro criado
        $cadastroEdit = $novoCadastro['cliente'];
        $cadastroEdit['nome'] = "Empresa Teste";

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->put("{$this->route}/{$novoCadastro['cliente']['id']}", $cadastroEdit);

        $response->assertStatus(200);
    }

    public function testExcluirCadastroCliente()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        /// Criar cadastro para editar
        $cadastro = [
            "nome" => "Marcos",
            "cnpj" => $this->cnpj(),
            "data_fundacao" => "2022-11-26",
            "grupo_id" => 2
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $novoCadastro = $response->decodeResponseJson();

        /// Excluir Cadastro criado
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->delete("{$this->route}/{$novoCadastro['cliente']['id']}");


        $response->assertJson([
            "status" => true,
            "message" => "Registro excluido com sucesso!"
        ]);
    }
}
