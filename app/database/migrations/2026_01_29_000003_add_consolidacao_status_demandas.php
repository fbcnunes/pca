<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $novos = [
            ['nome' => 'Validada c/ alteração', 'descricao' => 'Validada com ajustes de quantidade/valor', 'ordem' => 8, 'ativo' => true],
            ['nome' => 'Consolidada', 'descricao' => 'Incluída na versão do PCA', 'ordem' => 9, 'ativo' => true],
            ['nome' => 'Consolidada c/ alteração', 'descricao' => 'Incluída na versão do PCA com ajustes', 'ordem' => 10, 'ativo' => true],
        ];

        foreach ($novos as $status) {
            $existe = DB::table('status_demandas')->where('nome', $status['nome'])->exists();
            if (! $existe) {
                DB::table('status_demandas')->insert($status);
            }
        }
    }

    public function down(): void
    {
        DB::table('status_demandas')->whereIn('nome', [
            'Validada c/ alteração',
            'Consolidada',
            'Consolidada c/ alteração',
        ])->delete();
    }
};
