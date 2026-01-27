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
        Schema::create('unidades_organizacionais', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('sigla')->nullable();
            $table->string('codigo')->nullable();
            $table->string('tipo')->default('unidade'); // unidade, diretoria, secretaria, gabinete
            $table->foreignId('parent_id')->nullable()->constrained('unidades_organizacionais')->nullOnDelete();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('unidades_organizacionais');
    }
};
