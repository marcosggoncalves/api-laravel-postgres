<?php

namespace App\Repositories;

use App\Interfaces\IClientesInterface;
use App\Models\Cliente;

class ClientesRepository implements IClientesInterface
{
    public function getAll()
    {
        return Cliente::orderBy('nome')->paginate(15);
    }

    public function store(array $request)
    {
        return Cliente::create([
            'nome' => $request['nome'],
            'cnpj' => $request['cnpj'],
            'data_fundacao' => $request['data_fundacao'],
            'grupo_id' => $request['grupo_id']
        ]);
    }

    public function update(int $id, array $request)
    {
        $cliente = Cliente::findOrFail($id);
        $cliente->nome = $request['nome'];
        $cliente->cnpj = $request['cnpj'];
        $cliente->data_fundacao = $request['data_fundacao'];
        $cliente->grupo_id = $request['grupo_id'];
        
        return $cliente->save();
    }

    public function destroy(int $id)
    {
        return Cliente::destroy($id);
    }
}
