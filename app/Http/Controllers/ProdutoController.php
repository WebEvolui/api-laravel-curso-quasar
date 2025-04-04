<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProdutoController extends Controller
{
    /**
     * Exibe uma lista de produtos.
     */
    public function index(Request $request)
    {
        $perPage = config('app.pagination.default');

        $produtos = Product::with('categoria')
            ->when($request->nome, function ($query, $nome) {
                return $query->where('nome', 'like', '%' . $nome . '%');
            })
            ->paginate($perPage);

        return response()->json($produtos);
    }

    /**
     * Exibe um produto específico.
     */
    public function show($id)
    {
        $produto = Product::with('categoria')->findOrFail($id);
        return response()->json($produto);
    }

    /**
     * Armazena um novo produto.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'preco' => 'required|numeric|min:0',
            'estoque' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categorias,id',
        ]);

        $produto = Product::create($validatedData);
        return response()->json(['message' => 'Produto criado com sucesso', 'produto' => $produto], 201);
    }

    /**
     * Atualiza um produto existente.
     */
    public function update(Request $request, $id)
    {
        $produto = Product::findOrFail($id);

        $validatedData = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'descricao' => 'nullable|string|max:500',
            'preco' => 'sometimes|required|numeric|min:0',
            'estoque' => 'sometimes|required|integer|min:0',
            'categoria_id' => 'sometimes|required|exists:categorias,id',
        ]);

        $produto->update($validatedData);
        return response()->json(['message' => 'Produto atualizado com sucesso', 'produto' => $produto]);
    }
}
