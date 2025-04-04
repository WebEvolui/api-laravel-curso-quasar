<?php

namespace Database\Factories;

use App\Models\ItemNotaVenda;
use App\Models\NotaVenda;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ItemNotaVenda>
 */
class ItemNotaVendaFactory extends Factory
{
    protected $model = ItemNotaVenda::class;
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $quantidade = $this->faker->numberBetween(1, 5);
        $precoUnitario = $this->faker->randomFloat(2, 1, 500);

        return [
            'nota_venda_id' => NotaVenda::factory(),
            'produto_id' => Product::factory(),
            'quantidade' => $quantidade,
            'preco_unitario' => $precoUnitario,
            'subtotal' => $quantidade * $precoUnitario,
        ];
    }
}
