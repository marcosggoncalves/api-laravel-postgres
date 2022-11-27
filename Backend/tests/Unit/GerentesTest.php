<?php

namespace Tests\Unit;

use App\Interfaces\IGerentesInterface;
use App\Repositories\GerentesRepository;
use App\Utils\Random;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class GerentesTest extends TestCase
{
    public static function repository(): IGerentesInterface
    {
        return new GerentesRepository();
    }

    private function gerarCadastro()
    {
        $random = Random::gerar();

        $cadastro = [
            'nome' => 'Teste',
            'password' => Hash::make('1234'),
            'email' => "unitario.gerente{$random}@gmail.com",
            'nivel' => rand(1, 2),
        ];

        return $cadastro;
    }

    public function testListarGerentes()
    {
        $novoGerente = $this->gerarCadastro();
        /// Salvar novo Gerente
        self::repository()->store($novoGerente);
        /// Listar Gerentes
        $gerentes = self::repository()->getAll();

        $this->assertTrue($gerentes[0]->exists);
    }

    public function testNovoGerente()
    {
        $novoGerente = $this->gerarCadastro();
        /// Salvar novo Gerente
        $salvarGerente = self::repository()->store($novoGerente);

        $this->assertEquals($salvarGerente->nome, $novoGerente['nome']);
    }

    public function testAlterarGerente()
    {
        $novoGerente = $this->gerarCadastro();
        /// Salvar novo Gerente  
        $salvarGerente = self::repository()->store($novoGerente);
        /// Editar Gerente
        $editarGerente = self::repository()->update($salvarGerente->id, $novoGerente);

        $this->assertEquals($editarGerente, true);
    }

    public function testDeletarGerente()
    {
        $novoGerente = $this->gerarCadastro();
        /// Salvar novo Gerente  
        $salvarGerente = self::repository()->store($novoGerente);
        /// Excluir Gerente
        $editarGerente = self::repository()->destroy($salvarGerente->id);

        $this->assertEquals($editarGerente, true);
    }
}
