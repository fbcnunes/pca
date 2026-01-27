<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StatusDemanda extends Model
{
    use HasFactory;

    protected $table = 'status_demandas';

    protected $fillable = [
        'nome',
        'descricao',
        'ordem',
        'ativo',
    ];
}
