<?php

namespace App\Repositories;

use App\Models\Gerente;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\IGerentesInterface;

class GerentesRepository implements IGerentesInterface
{
    public function hashPassword($password)
    {
        return Hash::make($password);
    }

    public function getAll()
    {
        return Gerente::orderBy('nome')->paginate(15);
    }

    public function store(array $request)
    {
        return Gerente::create([
            'nome' => $request['nome'],
            'email' => $request['email'],
            'password' => $this->HashPassword($request['password']),
            'nivel' => $request['nivel']
        ]);
    }

    public function update(int $id, array $request)
    {
        $gerente = Gerente::findOrFail($id);
        $gerente->nome = $request['nome'];
        $gerente->email = $request['email'];
        $gerente->nivel = $request['nivel'];

        if (isset($request['password'])) {
            $gerente->password = $this->HashPassword($request['password']);
        }

        return $gerente->save();
    }

    public function destroy(int $id)
    {
        return Gerente::destroy($id);
    }
}
