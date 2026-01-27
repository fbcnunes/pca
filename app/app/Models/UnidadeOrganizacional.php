<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnidadeOrganizacional extends Model
{
    use HasFactory;

    protected $table = 'unidades_organizacionais';

    protected $fillable = [
        'nome',
        'sigla',
        'codigo',
        'tipo',
        'parent_id',
        'ativo',
    ];

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    public function descendantsIds(): array
    {
        $ids = [];
        $stack = [$this];
        while ($stack) {
            $current = array_pop($stack);
            $children = $current->children;
            foreach ($children as $child) {
                $ids[] = $child->id;
                $stack[] = $child;
            }
        }
        return $ids;
    }
}
