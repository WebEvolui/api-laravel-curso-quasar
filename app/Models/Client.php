<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;

    protected $table = 'clientes';

    protected $fillable = [
        'nome',
        'cpf_cnpj',
        'endereco',
        'telefone',
        'email',
    ];

    public function notasVenda()
    {
        return $this->hasMany(NotaVenda::class);
    }
}
