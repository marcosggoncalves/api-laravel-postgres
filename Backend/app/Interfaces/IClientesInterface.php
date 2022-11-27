<?php

namespace App\Interfaces;

interface IClientesInterface
{
    public function getAll();

    public function store(array $request);

    public function update(int $id, array $request);
    
    public function destroy(int $id);
}
