<?php

namespace App\Repositories;

use App\Interfaces\IGruposInterface;
use App\Models\Grupo;

class GruposRepository implements IGruposInterface
{
    public function getAll()
    {
        return Grupo::orderBy('nome')->paginate(15);
    }

    public function getClientes($id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->clientes;
        return $grupo;
    }

    public function store(array $request)
    {
        return Grupo::create([
            'nome' => $request['nome']
        ]);
    }

    public function update(int $id, array $request)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->nome = $request['nome'];

        return $grupo->save();
    }

    public function destroy(int $id)
    {
        return Grupo::destroy($id);
    }
}
