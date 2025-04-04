<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Fornecedor;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Fornecedor>
 */
class FornecedorFactory extends Factory
{
    protected $model = Fornecedor::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nome' => $this->faker->company(),
            'cnpj' => $this->faker->numerify('########0001##'),
            'endereco' => $this->faker->address(),
            'telefone' => $this->faker->phoneNumber(),
            'email' => $this->faker->companyEmail(),
            'contato' => $this->faker->name(),
        ];
    }
}
