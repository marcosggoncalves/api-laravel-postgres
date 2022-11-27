<?php

namespace Tests\Unit;

use App\Interfaces\IGruposInterface;
use App\Repositories\GruposRepository;
use App\Utils\Random;
use Tests\TestCase;

class GruposTest extends TestCase
{
    public static function repository(): IGruposInterface
    {
        return new GruposRepository();
    }

    private function nomearGrupo()
    {
        $random = Random::gerar();

        $nomeGrupo = "grupo.unitario-{$random}";

        return [
            'nome' => $nomeGrupo
        ];
    }

    public function testDetalharInformacaoGrupo()
    {
        $grupos = self::repository()->getClientes(1);

        $this->assertEquals($grupos[0], null);
    }

    public function testListarGrupos()
    {
        $novoGrupo = $this->nomearGrupo();
        /// Salvar novo grupo
        self::repository()->store($novoGrupo);
        /// Listar Grupos
        $grupos = self::repository()->getAll();

        $this->assertTrue($grupos[0]->exists);
    }

    public function testNovoGrupo()
    {
        $novoGrupo = $this->nomearGrupo();
        /// Salvar novo grupo
        $salvarGrupo = self::repository()->store($novoGrupo);

        $this->assertEquals($salvarGrupo->nome, $novoGrupo['nome']);
    }

    public function testAlterarGrupo()
    {
        $novoGrupo = $this->nomearGrupo();
        /// Salvar novo grupo  
        $salvarGrupo = self::repository()->store($novoGrupo);
        /// Editar grupo
        $editarGrupo = self::repository()->update($salvarGrupo->id, $novoGrupo);

        $this->assertEquals($editarGrupo, true);
    }

    public function testDeletarGrupo()
    {
        $novoGrupo = $this->nomearGrupo();
        /// Salvar novo grupo  
        $salvarGrupo = self::repository()->store($novoGrupo);
        /// Excluir grupo
        $editarGrupo = self::repository()->destroy($salvarGrupo->id);

        $this->assertEquals($editarGrupo, true);
    }
}
