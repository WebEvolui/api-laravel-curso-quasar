<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FornecedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\ProdutoController;
use App\Http\Controllers\NotaVendaController;
use App\Http\Controllers\AuthController;

// ---------------------------------------------
// TODAS as rotas ficam em servidor:porta/api
// ---------------------------------------------
// Exemplo: http://localhost:8000/api/categorias
// ---------------------------------------------
Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    Route::get('categorias', [CategoryController::class, 'index']);
    Route::post('categorias', [CategoryController::class, 'store']);
    Route::put('categorias/{id}', [CategoryController::class, 'update']);

    Route::get('fornecedores', [FornecedorController::class, 'index']);
    Route::get('fornecedores/{id}', [FornecedorController::class, 'show']);
    Route::post('fornecedores', [FornecedorController::class, 'store']);
    Route::put('fornecedores/{id}', [FornecedorController::class, 'update']);

    Route::get('clientes', [ClienteController::class, 'index']);
    Route::get('clientes/{id}', [ClienteController::class, 'show']);
    Route::post('clientes', [ClienteController::class, 'store']);
    Route::put('clientes/{id}', [ClienteController::class, 'update']);

    Route::get('produtos', [ProdutoController::class, 'index']);
    Route::get('produtos/{id}', [ProdutoController::class, 'show']);
    Route::post('produtos', [ProdutoController::class, 'store']);
    Route::put('produtos/{id}', [ProdutoController::class, 'update']);

    Route::get('notas-venda', [NotaVendaController::class, 'index']);
    Route::post('notas-venda', [NotaVendaController::class, 'store']);
    Route::put('notas-venda/{id}', [NotaVendaController::class, 'update']);
    Route::get('notas-venda/{id}', [NotaVendaController::class, 'show']);
    Route::delete('notas-venda/{id}', [NotaVendaController::class, 'destroy']);
});
