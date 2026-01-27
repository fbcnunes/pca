<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('demandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ciclo_id')->constrained('ciclo_pcas')->cascadeOnDelete();
            $table->foreignId('unidade_id')->constrained('unidades_organizacionais')->cascadeOnDelete();
            $table->foreignId('demandante_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('status_demanda_id')->constrained('status_demandas');
            $table->boolean('gabinete_obrigatorio')->default(false);

            // Bloco A – Identificação Institucional
            $table->string('area_responsavel')->nullable();

            // Bloco B – Identificação da Demanda
            $table->string('titulo');
            $table->text('descricao');

            // Bloco C – Classificação
            $table->foreignId('tipo_id')->constrained('catalogo_tipo_demandas');
            $table->foreignId('natureza_id')->constrained('catalogo_naturezas');
            $table->foreignId('categoria_id')->constrained('catalogo_categorias');

            // Bloco D – Justificativa e Interesse Público
            $table->text('justificativa');
            $table->foreignId('prioridade_id')->constrained('catalogo_prioridades');

            // Bloco E – Escopo Preliminar
            $table->string('quantidade_estimada')->nullable();
            $table->text('escopo_basico')->nullable();

            // Bloco F – Prazo
            $table->string('mes_necessidade')->nullable();
            $table->text('justificativa_prazo')->nullable();

            // Bloco G – Estimativa de Valor
            $table->decimal('valor_estimado', 15, 2)->nullable();
            $table->string('fonte_estimativa')->nullable();

            // Bloco H – Responsável
            $table->string('responsavel_nome');
            $table->string('responsavel_cargo');
            $table->string('responsavel_contato');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demandas');
    }
};
