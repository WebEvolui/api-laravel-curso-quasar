<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fornecedor;
use Illuminate\Support\Facades\Validator;

class FornecedorController extends Controller
{
    protected $baseValidationRules = [
        'nome' => 'string|max:255',
        'cnpj' => 'string|max:14',
        'endereco' => 'string|max:255',
        'telefone' => 'string|max:20',
        'email' => 'email|max:255',
        'contato' => 'string|max:255'
    ];

    public function index(Request $request)
    {
        return response()->json(
            Fornecedor::when($request->query('cnpj'), fn($query, $cnpj) => $query->where('cnpj', $cnpj))
                ->paginate(config('app.pagination.default'))
        );
    }

    public function show($id)
    {
        $fornecedor = Fornecedor::findOrFail($id);
        return response()->json($fornecedor);
    }

    public function store(Request $request)
    {
        $rules = array_map(function ($rule) {
            return 'required|' . $rule;
        }, $this->baseValidationRules);

        $rules['cnpj'] = $rules['cnpj'] . '|unique:fornecedores';
        $rules['email'] = $rules['email'] . '|unique:fornecedores';

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fornecedor = Fornecedor::create($request->all());
        return response()->json($fornecedor, 201);
    }

    public function update(Request $request, $id)
    {
        $fornecedor = Fornecedor::find($id);

        if (!$fornecedor) {
            return response()->json(['message' => 'Fornecedor não encontrado'], 404);
        }

        $rules = $this->baseValidationRules;
        $rules['cnpj'] = $rules['cnpj'] . '|unique:fornecedores,cnpj,' . $id;
        $rules['email'] = $rules['email'] . '|unique:fornecedores,email,' . $id;

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $fornecedor->update($request->all());
        return response()->json($fornecedor);
    }
}
