<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValidacaoDemanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'demanda_id',
        'etapa',
        'decisor_id',
        'decisao',
        'comentario',
    ];
}
