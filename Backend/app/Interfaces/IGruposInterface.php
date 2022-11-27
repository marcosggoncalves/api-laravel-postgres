<?php

namespace App\Interfaces;

interface IGruposInterface
{
    public function getAll();

    public function getClientes(int $id);

    public function store(array $request);

    public function update(int $id, array $request);
    
    public function destroy(int $id);
}
