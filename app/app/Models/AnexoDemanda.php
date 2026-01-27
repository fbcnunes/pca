<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnexoDemanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'demanda_id',
        'caminho_arquivo',
        'nome_original',
        'mime',
        'tamanho',
        'criado_por',
    ];
}
