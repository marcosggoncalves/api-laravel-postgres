<?php

namespace Tests\Unit;

use App\Interfaces\IClientesInterface;
use App\Repositories\ClientesRepository;
use App\Utils\GerarCNPJ;
use Tests\TestCase;

class ClientesTest extends TestCase
{
    public static function repository(): IClientesInterface
    {
        return new ClientesRepository();
    }

    private function gerarCadastro()
    {
        $cnpj = GerarCNPJ::gerar();

        $cadastro = [
            "nome" => "Teste.Unitario",
            "cnpj" => $cnpj,
            "data_fundacao" => "2022-11-26",
            "grupo_id" => rand(1, 2)
        ];

        return $cadastro;
    }

    public function testListarClientes()
    {
        $novoCliente = $this->gerarCadastro();
        /// Salvar novo Cliente
        self::repository()->store($novoCliente);
        /// Listar Clientes
        $clientes = self::repository()->getAll();

        $this->assertTrue($clientes[0]->exists);
    }

    public function testNovoCliente()
    {
        $novoCliente = $this->gerarCadastro();
        /// Salvar novo Cliente
        $salvarCliente = self::repository()->store($novoCliente);

        $this->assertEquals($salvarCliente->nome, $novoCliente['nome']);
    }

    public function testAlterarCliente()
    {
        $novoCliente = $this->gerarCadastro();
        /// Salvar novo Cliente  
        $salvarCliente = self::repository()->store($novoCliente);
        /// Editar Cliente
        $editarCliente = self::repository()->update($salvarCliente->id, $novoCliente);

        $this->assertEquals($editarCliente, true);
    }

    public function testDeletarCliente()
    {
        $novoCliente = $this->gerarCadastro();
        /// Salvar novo Cliente  
        $salvarCliente = self::repository()->store($novoCliente);
        /// Excluir Cliente
        $editarCliente = self::repository()->destroy($salvarCliente->id);

        $this->assertEquals($editarCliente, true);
    }
}
