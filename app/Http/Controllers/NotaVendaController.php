<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NotaVenda;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class NotaVendaController extends Controller
{
    /**
     * Exibe uma lista de notas de venda.
     */
    public function index()
    {
        $perPage = config('app.pagination.default');
        $notasVenda = NotaVenda::with('itens.produto', 'cliente')->paginate($perPage);
        return response()->json($notasVenda);
    }

    /**
     * Exibe uma nota de venda específica.
     */
    public function show($id)
    {
        $notaVenda = NotaVenda::with('itens.produto', 'cliente')->findOrFail($id);
        return response()->json($notaVenda);
    }

    /**
     * Armazena uma nova nota de venda.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'data' => 'required|date',
            'itens' => 'required|array',
            'itens.*.produto_id' => 'required|exists:produtos,id',
            'itens.*.quantidade' => 'required|integer|min:1',
            'itens.*.preco_unitario' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            $total = collect($validatedData['itens'])->sum(function ($item) {
                return $item['quantidade'] * $item['preco_unitario'];
            });

            $notaVenda = NotaVenda::create([
                'cliente_id' => $validatedData['cliente_id'],
                'data' => $validatedData['data'],
                'total' => $total,
            ]);

            foreach ($validatedData['itens'] as $item) {
                $item['subtotal'] = $item['quantidade'] * $item['preco_unitario'];
                $notaVenda->itens()->create($item);

                // Diminuir o estoque do produto
                $produto = Product::findOrFail($item['produto_id']);
                if ($produto->estoque < $item['quantidade']) {
                    abort(400, "Estoque insuficiente para o produto ID {$item['produto_id']}");
                }
                $produto->decrement('estoque', $item['quantidade']);
            }

            DB::commit();

            return response()->json(['message' => 'Nota de venda criada com sucesso', 'nota_venda' => $notaVenda->load('itens.produto')], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Atualiza uma nota de venda existente.
     */
    public function update(Request $request, $id)
    {
        $notaVenda = NotaVenda::findOrFail($id);

        $validatedData = $request->validate([
            'cliente_id' => 'sometimes|required|exists:clientes,id',
            'data' => 'sometimes|required|date',
            'itens' => 'sometimes|array',
            'itens.*.produto_id' => 'required_with:itens|exists:produtos,id',
            'itens.*.quantidade' => 'required_with:itens|integer|min:1',
            'itens.*.preco_unitario' => 'required_with:itens|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            if (isset($validatedData['cliente_id']) || isset($validatedData['data'])) {
                $notaVenda->update([
                    'cliente_id' => $validatedData['cliente_id'] ?? $notaVenda->cliente_id,
                    'data' => $validatedData['data'] ?? $notaVenda->data,
                ]);
            }

            if (isset($validatedData['itens'])) {
                // Restaurar o estoque dos itens antigos
                foreach ($notaVenda->itens as $item) {
                    $produto = Product::findOrFail($item->produto_id);
                    $produto->increment('estoque', $item->quantidade);
                }

                $notaVenda->itens()->delete();

                foreach ($validatedData['itens'] as $item) {
                    $item['subtotal'] = $item['quantidade'] * $item['preco_unitario'];
                    $notaVenda->itens()->create($item);

                    // Diminuir o estoque do produto
                    $produto = Product::findOrFail($item['produto_id']);
                    if ($produto->estoque < $item['quantidade']) {
                        abort(400, "Estoque insuficiente para o produto ID {$item['produto_id']}");
                    }
                    $produto->decrement('estoque', $item['quantidade']);
                }

                $total = collect($validatedData['itens'])->sum(function ($item) {
                    return $item['quantidade'] * $item['preco_unitario'];
                });

                $notaVenda->update(['total' => $total]);
            }

            DB::commit();

            return response()->json(['message' => 'Nota de venda atualizada com sucesso', 'nota_venda' => $notaVenda->load('itens.produto')]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    /**
     * Remove uma nota de venda.
     */
    public function destroy($id)
    {
        DB::beginTransaction();

        try {
            $notaVenda = NotaVenda::findOrFail($id);
            // deletar itens da nota primeiro
            foreach ($notaVenda->itens as $item) {
                $produto = Product::findOrFail($item->produto_id);
                $produto->increment('estoque', $item->quantidade);
            }
            $notaVenda->itens()->delete();
            // deletar a nota de venda
            $notaVenda->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 400);
        }

        return response()->noContent();
    }
}
