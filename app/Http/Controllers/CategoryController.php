<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();

        return response()->json($categories);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:100',
            'descricao' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['erros' => $validator->errors()], 422);
        }

        $category = new Category();
        $category->nome = $request->nome;
        $category->descricao = $request->descricao;
        $category->save();

        return response()->json($category, 201);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['erro' => 'Categoria não encontrada'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nome' => 'required|string|max:100',
            'descricao' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['erros' => $validator->errors()], 422);
        }

        $category->nome = $request->nome;
        $category->descricao = $request->descricao;
        $category->save();

        return response()->json($category);
    }
}
