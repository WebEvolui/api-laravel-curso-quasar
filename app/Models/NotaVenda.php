<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class NotaVenda extends Model
{
    use HasFactory;

    protected $table = 'notas_venda';

    protected $fillable = [
        'cliente_id',
        'data',
        'total',
    ];

    public function cliente() {
        return $this->belongsTo(Client::class);
    }

    public function itens() 
    {
        return $this->hasMany(ItemNotaVenda::class);
    }
}
