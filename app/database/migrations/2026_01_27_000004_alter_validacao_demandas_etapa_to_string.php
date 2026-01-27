<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE validacao_demandas MODIFY etapa VARCHAR(50) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE validacao_demandas MODIFY etapa ENUM('diretoria','secretaria_adjunta','gabinete') NOT NULL");
    }
};
