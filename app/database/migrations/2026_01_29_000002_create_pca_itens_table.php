<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pca_itens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('versao_id')->constrained('pca_versoes')->cascadeOnDelete();
            $table->foreignId('demanda_id')->constrained('demandas')->cascadeOnDelete();
            $table->boolean('incluido')->default(true);
            $table->decimal('valor_ajustado', 15, 2)->nullable();
            $table->text('observacao')->nullable();
            $table->timestamps();

            $table->unique(['versao_id', 'demanda_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pca_itens');
    }
};
