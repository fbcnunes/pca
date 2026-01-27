<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Perfil extends Model
{
    use HasFactory;

    protected $table = 'perfis';

    protected $fillable = [
        'nome',
        'slug',
        'descricao',
        'ativo',
    ];

    public function permissoes()
    {
        return $this->belongsToMany(Permissao::class, 'permissao_perfil')->withTimestamps();
    }

    public function usuarios()
    {
        return $this->belongsToMany(User::class, 'perfil_usuario')
            ->withPivot(['unidade_id', 'ativo'])
            ->withTimestamps();
    }
}
