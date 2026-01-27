<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('demandas', function (Blueprint $table) {
            $table->foreignId('unidade_envio_id')
                ->nullable()
                ->after('unidade_validacao_id')
                ->constrained('unidades_organizacionais')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('demandas', function (Blueprint $table) {
            $table->dropForeign(['unidade_envio_id']);
            $table->dropColumn('unidade_envio_id');
        });
    }
};
