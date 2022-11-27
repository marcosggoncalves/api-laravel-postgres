<?php

namespace Tests\Feature;

use App\Utils\Random;
use Tests\TestCase;

class GruposTest extends TestCase
{
    private $route = 'api/v1/grupos';

    private $routeAuth = 'api/v1/gerentes';

    private function rand()
    {
        return Random::gerar();
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

    public function testListarGrupos()
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

    public function testDetalharInformacoesGrupoEClientes()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->get("{$this->route}/1");

        $response->assertStatus(200);
    }

    public function testNovoCadastroGrupoGerenteNivelUmSemPermissao()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", []);

        $response->assertStatus(401);
    }

    public function testNovoCadastroGrupoGerenteNivelDoisComPermissao()
    {
        $auth = [
            'email' => 'marcoslopes5687@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $nomeGrupo = "Grupo-{$this->rand()}";

        $cadastro = [
            'nome' => $nomeGrupo
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $response->assertStatus(200);
    }

    public function testNovoCadastroGrupoCamposVaziosGerenteNivelDoisComPermissao()
    {
        $auth = [
            'email' => 'marcoslopes5687@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $cadastroCamposVazios = [
            'nome' => null
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastroCamposVazios);

        $response->assertStatus(417);
    }

    public function testAlterarCadastroGrupoGerenteNivelDoisComPermissao()
    {
        $auth = [
            'email' => 'marcoslopes5687@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $nomeGrupo = "Grupo-1{$this->rand()}";

        /// Criar novo cadastro 
        $cadastro = [
            'nome' => $nomeGrupo
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->post("{$this->route}", $cadastro);

        $novoCadastro = $response->decodeResponseJson();

        /// Editar Cadastro criado
        $cadastroEdit = [
            'nome' => $nomeGrupo
        ];

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->put("{$this->route}/{$novoCadastro['grupo']['id']}", $cadastroEdit);

        $response->assertStatus(200);
    }

    public function testAlterarCadastroGrupoGerenteNivelUmSemPermissao()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->put("{$this->route}/100", []);

        $response->assertStatus(401);
    }

    public function testExcluirCadastroGrupoGerenteNivelDoisComPermissao()
    {
        $auth = [
            'email' => 'marcoslopes5687@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        $nomeGrupo = "Grupo-2{$this->rand()}";

        /// Criar novo cadastro 
        $cadastro = [
            'nome' => $nomeGrupo
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
        ])->delete("{$this->route}/{$novoCadastro['grupo']['id']}");

        $response->assertJson([
            "status" => true,
            "message" => "Registro excluido com sucesso!"
        ]);
    }

    public function testExcluirCadastroGrupoGerenteNivelUmSemPermissao()
    {
        $auth = [
            'email' => 'marcoslopesg7@gmail.com',
            'password' => '1234'
        ];

        $login = $this->login($auth)->getData();

        /// Excluir Cadastro criado
        $response = $this->withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer {$login->token}"
        ])->delete("{$this->route}/100");

        $response->assertStatus(401);
    }
}
