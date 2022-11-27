<?php

namespace App\Interfaces;

interface IGerentesInterface
{
    public function getAll();

    public function store(array $request);

    public function update(int $id, array $request);
    
    public function destroy(int $id);
}
