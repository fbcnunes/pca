<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TramitacaoDemanda extends Model
{
    use HasFactory;

    protected $table = 'tramitacoes_demandas';

    protected $fillable = [
        'demanda_id',
        'unidade_origem_id',
        'unidade_destino_id',
        'acao',
        'status_resultante_id',
        'user_id',
        'comentario',
    ];

    public function demanda()
    {
        return $this->belongsTo(Demanda::class, 'demanda_id');
    }

    public function origem()
    {
        return $this->belongsTo(UnidadeOrganizacional::class, 'unidade_origem_id');
    }

    public function destino()
    {
        return $this->belongsTo(UnidadeOrganizacional::class, 'unidade_destino_id');
    }
}
