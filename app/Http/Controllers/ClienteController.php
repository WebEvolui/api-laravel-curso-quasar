<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;

class ClienteController extends Controller
{
    /**
     * Exibe uma lista de clientes.
     */
    public function index(Request $request)
    {
        $perPage = config('app.pagination.default');

        $clientes = Client::when($request->nome, function ($query, $nome) {
                return $query->where('nome', 'like', '%' . $nome . '%');
            })
            ->when($request->cpf_cnpj, function ($query, $cpfCnpj) {
                return $query->where('cpf_cnpj', $cpfCnpj);
            })
            ->paginate($perPage);

        return response()->json($clientes);
    }

    /**
     * Exibe um cliente específico.
     */
    public function show($id)
    {
        $cliente = Client::findOrFail($id);
        return response()->json($cliente);
    }

    /**
     * Armazena um novo cliente.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nome' => 'required|string|max:255',
            'cpf_cnpj' => 'required|string|max:20|unique:clientes,cpf_cnpj',
            'endereco' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'required|email|unique:clientes,email',
        ]);

        $cliente = Client::create($validatedData);
        return response()->json(['message' => 'Cliente criado com sucesso', 'cliente' => $cliente], 201);
    }

    /**
     * Atualiza um cliente existente.
     */
    public function update(Request $request, $id)
    {
        $cliente = Client::findOrFail($id);

        $validatedData = $request->validate([
            'nome' => 'sometimes|required|string|max:255',
            'cpf_cnpj' => 'sometimes|required|string|max:20|unique:clientes,cpf_cnpj,' . $cliente->id,
            'endereco' => 'nullable|string|max:255',
            'telefone' => 'nullable|string|max:20',
            'email' => 'sometimes|required|email|unique:clientes,email,' . $cliente->id,
        ]);

        $cliente->update($validatedData);
        return response()->json(['message' => 'Cliente atualizado com sucesso', 'cliente' => $cliente]);
    }
}
