<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CicloPca extends Model
{
    use HasFactory;

    protected $fillable = [
        'exercicio',
        'data_inicio',
        'data_fim',
        'status',
        'observacao',
        'ativo',
    ];
}
