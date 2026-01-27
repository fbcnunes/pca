<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tramitacoes_demandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas')->cascadeOnDelete();
            $table->foreignId('unidade_origem_id')->nullable()->constrained('unidades_organizacionais')->nullOnDelete();
            $table->foreignId('unidade_destino_id')->nullable()->constrained('unidades_organizacionais')->nullOnDelete();
            $table->string('acao', 30);
            $table->foreignId('status_resultante_id')->nullable()->constrained('status_demandas')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tramitacoes_demandas');
    }
};
