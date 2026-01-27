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
        Schema::create('validacao_demandas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('demanda_id')->constrained('demandas')->cascadeOnDelete();
            $table->enum('etapa', ['diretoria', 'secretaria_adjunta', 'gabinete']);
            $table->foreignId('decisor_id')->constrained('users')->cascadeOnDelete();
            $table->enum('decisao', ['aprovado', 'devolvido']);
            $table->text('comentario');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('validacao_demandas');
    }
};
