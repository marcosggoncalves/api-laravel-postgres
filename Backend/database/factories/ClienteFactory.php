<?php

namespace Database\Factories;

use App\Utils\GerarCNPJ;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ClienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        return [
            'nome' => $this->faker->name,
            'cnpj' => GerarCNPJ::gerar(),
            'data_fundacao' => $this->faker->date,
            'grupo_id' => rand(1, 2)
        ];
    }
}
