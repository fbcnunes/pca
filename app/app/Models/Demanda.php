<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demanda extends Model
{
    use HasFactory;

    protected $fillable = [
        'ciclo_id',
        'unidade_id',
        'unidade_validacao_id',
        'unidade_envio_id',
        'demandante_id',
        'status_demanda_id',
        'gabinete_obrigatorio',
        'exercicio',
        'orgao',
        'unidade_administrativa',
        'area_responsavel',
        'titulo',
        'descricao',
        'tipo_id',
        'natureza_id',
        'categoria_id',
        'justificativa',
        'prioridade_id',
        'quantidade_estimada',
        'escopo_basico',
        'mes_necessidade',
        'justificativa_prazo',
        'valor_estimado',
        'fonte_estimativa',
        'responsavel_nome',
        'responsavel_cargo',
        'responsavel_contato',
    ];

    public function status()
    {
        return $this->belongsTo(StatusDemanda::class, 'status_demanda_id');
    }

    public function ciclo()
    {
        return $this->belongsTo(CicloPca::class, 'ciclo_id');
    }

    public function unidade()
    {
        return $this->belongsTo(UnidadeOrganizacional::class, 'unidade_id');
    }

    public function unidadeValidacao()
    {
        return $this->belongsTo(UnidadeOrganizacional::class, 'unidade_validacao_id');
    }

    public function unidadeEnvio()
    {
        return $this->belongsTo(UnidadeOrganizacional::class, 'unidade_envio_id');
    }

    public function tramitacoes()
    {
        return $this->hasMany(TramitacaoDemanda::class, 'demanda_id');
    }

    public function tipo()
    {
        return $this->belongsTo(CatalogoTipoDemanda::class, 'tipo_id');
    }

    public function natureza()
    {
        return $this->belongsTo(CatalogoNatureza::class, 'natureza_id');
    }

    public function categoria()
    {
        return $this->belongsTo(CatalogoCategoria::class, 'categoria_id');
    }

    public function prioridade()
    {
        return $this->belongsTo(CatalogoPrioridade::class, 'prioridade_id');
    }
}
