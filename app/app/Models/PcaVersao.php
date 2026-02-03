<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcaVersao extends Model
{
    use HasFactory;

    protected $table = 'pca_versoes';

    protected $fillable = [
        'ciclo_id',
        'nome',
        'status',
        'observacao',
        'criado_por',
    ];

    public function ciclo()
    {
        return $this->belongsTo(CicloPca::class, 'ciclo_id');
    }

    public function itens()
    {
        return $this->hasMany(PcaItem::class, 'versao_id');
    }
}
