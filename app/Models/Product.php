<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\ItemNotaVenda;

class Product extends Model
{
    use hasFactory;

    protected $table = 'produtos';

    protected $fillable = [
        'nome',
        'descricao',
        'preco',
        'estoque',
        'categoria_id',
    ];

    public function categoria()
    {
        return $this->belongsTo(Category::class);
    }

    public function itensNotaVenda()
    {
        return $this->hasMany(ItemNotaVenda::class);
    }
}
