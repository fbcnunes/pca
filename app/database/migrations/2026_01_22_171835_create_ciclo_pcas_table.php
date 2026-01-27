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
        Schema::create('ciclo_pcas', function (Blueprint $table) {
            $table->id();
            $table->year('exercicio');
            $table->date('data_inicio')->nullable();
            $table->date('data_fim')->nullable();
            $table->string('status')->default('planejado');
            $table->text('observacao')->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            $table->unique('exercicio');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclo_pcas');
    }
};
