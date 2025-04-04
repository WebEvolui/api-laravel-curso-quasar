<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemNotaVenda extends Model
{
    use HasFactory;

    protected $table = 'itens_nota_venda';

    protected $fillable = [
        'nota_venda_id',
        'produto_id',
        'quantidade',
        'preco_unitario',
        'subtotal',
    ];

    public function notaVenda() {
        return $this->belongsTo(NotaVenda::class, 'nota_venda_id');
    }

    public function produto() {
        return $this->belongsTo(Product::class, 'produto_id');
    }
}
