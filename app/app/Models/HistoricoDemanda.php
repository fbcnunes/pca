<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoDemanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'demanda_id',
        'user_id',
        'status_anterior_id',
        'status_novo_id',
        'perfil_decisor',
        'comentario',
    ];
}
