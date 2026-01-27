<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CatalogoPrioridade extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'ordem',
        'ativo',
    ];
}
