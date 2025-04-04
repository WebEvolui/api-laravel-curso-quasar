<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Client;
use App\Models\Fornecedor;
use App\Models\ItemNotaVenda;
use App\Models\NotaVenda;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (app()->environment('local', 'development')) {
            User::factory()->count(10)->create();

            Client::factory()->count(10)->create();

            Category::factory()->count(5)->create();

            Product::factory()->count(20)->create();

            Fornecedor::factory()->count(5)->create();

            NotaVenda::factory(10)->create()->each(function ($notaVenda) {
                ItemNotaVenda::factory()->count(rand(1, 3))->create(['nota_venda_id' => $notaVenda->id]);
            });
        } else {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@email.com',
                'password' => bcrypt('12345678'),
                'type' => 'admin',
            ]);
        }
    }
}
