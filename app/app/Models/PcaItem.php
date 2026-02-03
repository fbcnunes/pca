<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PcaItem extends Model
{
    use HasFactory;

    protected $table = 'pca_itens';

    protected $fillable = [
        'versao_id',
        'demanda_id',
        'incluido',
        'valor_ajustado',
        'observacao',
    ];

    public function versao()
    {
        return $this->belongsTo(PcaVersao::class, 'versao_id');
    }

    public function demanda()
    {
        return $this->belongsTo(Demanda::class, 'demanda_id');
    }
}
