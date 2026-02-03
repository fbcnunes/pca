<?php

namespace Database\Seeders;

use App\Models\CatalogoCategoria;
use App\Models\CatalogoNatureza;
use App\Models\CatalogoPrioridade;
use App\Models\CatalogoTipoDemanda;
use App\Models\Perfil;
use App\Models\Permissao;
use App\Models\StatusDemanda;
use App\Models\UnidadeOrganizacional;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $perfis = [
            ['slug' => 'demandante', 'nome' => 'Demandante', 'descricao' => 'Criação e gestão das próprias demandas'],
            ['slug' => 'diretoria', 'nome' => 'Diretoria', 'descricao' => 'Validação técnica/operacional'],
            ['slug' => 'secretaria_adjunta', 'nome' => 'Secretaria Adjunta', 'descricao' => 'Validação estratégica conjunta'],
            ['slug' => 'gabinete', 'nome' => 'Gabinete do Secretário', 'descricao' => 'Covalidação estratégica quando aplicável'],
            ['slug' => 'daf', 'nome' => 'DAF', 'descricao' => 'Consolidação e versões do PCA'],
            ['slug' => 'nplan', 'nome' => 'NPLAN', 'descricao' => 'Acompanhamento e monitoramento'],
            ['slug' => 'administrador', 'nome' => 'Administrador', 'descricao' => 'Governança e parametrização do sistema'],
        ];

        $permissoes = [
            ['chave' => 'usuarios.gerenciar', 'nome' => 'Gerenciar usuários e vínculos'],
            ['chave' => 'ciclos.gerenciar', 'nome' => 'Gerenciar ciclos do PCA'],
            ['chave' => 'catalogos.gerenciar', 'nome' => 'Gerenciar catálogos'],
            ['chave' => 'unidades.gerenciar', 'nome' => 'Gerenciar unidades organizacionais'],
            ['chave' => 'demandas.criar', 'nome' => 'Criar demanda'],
            ['chave' => 'demandas.enviar', 'nome' => 'Enviar demanda'],
            ['chave' => 'demandas.editar', 'nome' => 'Editar demanda em rascunho/devolução'],
            ['chave' => 'demandas.validar', 'nome' => 'Validar demanda (diretoria/secretaria adjunta)'],
            ['chave' => 'demandas.covalidar', 'nome' => 'Covalidar demanda (gabinete)'],
            ['chave' => 'pca.consolidar', 'nome' => 'Consolidar PCA (DAF)'],
            ['chave' => 'pca.versoes', 'nome' => 'Gerir versões do PCA (DAF)'],
            ['chave' => 'pca.acompanhar', 'nome' => 'Acompanhar ciclo (NPLAN)'],
            ['chave' => 'auditoria.ver', 'nome' => 'Visualizar trilha de auditoria'],
        ];

        foreach ($perfis as $perfil) {
            Perfil::firstOrCreate(['slug' => $perfil['slug']], $perfil);
        }

        foreach ($permissoes as $permissao) {
            Permissao::firstOrCreate(['chave' => $permissao['chave']], $permissao);
        }

        $mapaPerfilPermissoes = [
            'administrador' => [
                'usuarios.gerenciar',
                'ciclos.gerenciar',
                'catalogos.gerenciar',
                'unidades.gerenciar',
                'auditoria.ver',
            ],
            'demandante' => ['demandas.criar', 'demandas.enviar', 'demandas.editar'],
            'diretoria' => ['demandas.validar'],
            'secretaria_adjunta' => ['demandas.validar'],
            'gabinete' => ['demandas.covalidar'],
            'daf' => ['pca.consolidar', 'pca.versoes', 'auditoria.ver'],
            'nplan' => ['pca.acompanhar'],
        ];

        foreach ($mapaPerfilPermissoes as $slugPerfil => $chavesPermissoes) {
            $perfil = Perfil::where('slug', $slugPerfil)->first();
            if (! $perfil) {
                continue;
            }

            $idsPermissoes = Permissao::whereIn('chave', $chavesPermissoes)->pluck('id')->toArray();
            $perfil->permissoes()->syncWithoutDetaching($idsPermissoes);
        }

        $unidadeRaiz = UnidadeOrganizacional::firstOrCreate(
            ['sigla' => 'SEPLAD'],
            ['nome' => 'Secretaria de Planejamento e Administração', 'tipo' => 'secretaria', 'ativo' => true]
        );

        $daf = UnidadeOrganizacional::firstOrCreate(
            ['nome' => 'Diretoria de Administração e Finanças'],
            ['sigla' => 'DAF', 'tipo' => 'diretoria', 'parent_id' => $unidadeRaiz->id]
        );

        $nplan = UnidadeOrganizacional::firstOrCreate(
            ['nome' => 'Núcleo de Planejamento'],
            ['sigla' => 'NPLAN', 'tipo' => 'diretoria', 'parent_id' => $unidadeRaiz->id]
        );

        $admin = User::firstOrCreate(
            ['email' => 'admin@pca.local'],
            ['name' => 'Administrador PCA', 'password' => bcrypt('Leao100%')]
        );

        $perfilAdministrador = Perfil::where('slug', 'administrador')->first();
        if ($perfilAdministrador) {
            $admin->perfis()->syncWithoutDetaching([
                $perfilAdministrador->id => ['unidade_id' => $unidadeRaiz->id, 'ativo' => true],
            ]);
        }

        // Usuários de perfis específicos para testes
        $usuariosTeste = [
            'demandante' => ['email' => 'demandante@pca.local', 'name' => 'Demandante PCA'],
            'diretoria' => ['email' => 'diretoria@pca.local', 'name' => 'Diretoria PCA'],
            'secretaria_adjunta' => ['email' => 'sa@pca.local', 'name' => 'Secretaria Adjunta PCA'],
            'gabinete' => ['email' => 'gabinete@pca.local', 'name' => 'Gabinete PCA'],
            'daf' => ['email' => 'daf@pca.local', 'name' => 'DAF PCA'],
            'nplan' => ['email' => 'nplan@pca.local', 'name' => 'NPLAN PCA'],
        ];

        foreach ($usuariosTeste as $slug => $dados) {
            $usuario = User::firstOrCreate(
                ['email' => $dados['email']],
                ['name' => $dados['name'], 'password' => bcrypt('Leao100%')]
            );

            $perfil = Perfil::where('slug', $slug)->first();
            if ($perfil) {
                $usuario->perfis()->syncWithoutDetaching([
                    $perfil->id => ['unidade_id' => $unidadeRaiz->id, 'ativo' => true],
                ]);
            }
        }

        // Catálogos básicos
        CatalogoPrioridade::insert([
            ['nome' => 'Alta', 'descricao' => 'Prioridade alta', 'ordem' => 1, 'ativo' => true],
            ['nome' => 'Média', 'descricao' => 'Prioridade média', 'ordem' => 2, 'ativo' => true],
            ['nome' => 'Baixa', 'descricao' => 'Prioridade baixa', 'ordem' => 3, 'ativo' => true],
        ]);

        CatalogoTipoDemanda::insert([
            ['nome' => 'Compra de bens', 'descricao' => null, 'ordem' => 1, 'ativo' => true],
            ['nome' => 'Serviço', 'descricao' => null, 'ordem' => 2, 'ativo' => true],
            ['nome' => 'Solução de TI', 'descricao' => null, 'ordem' => 3, 'ativo' => true],
        ]);

        CatalogoNatureza::insert([
            ['nome' => 'Nova contratação/aquisição', 'descricao' => null, 'ordem' => 1, 'ativo' => true],
            ['nome' => 'Renovação', 'descricao' => null, 'ordem' => 2, 'ativo' => true],
            ['nome' => 'Continuada', 'descricao' => null, 'ordem' => 3, 'ativo' => true],
        ]);

        CatalogoCategoria::insert([
            ['nome' => 'Infraestrutura', 'descricao' => null, 'ordem' => 1, 'ativo' => true],
            ['nome' => 'Serviços gerais', 'descricao' => null, 'ordem' => 2, 'ativo' => true],
            ['nome' => 'Tecnologia', 'descricao' => null, 'ordem' => 3, 'ativo' => true],
        ]);

        StatusDemanda::insert([
            ['nome' => 'Rascunho', 'descricao' => 'Demanda em edição pelo demandante', 'ordem' => 1, 'ativo' => true],
            ['nome' => 'Enviada para validação', 'descricao' => 'Submetida ao fluxo', 'ordem' => 2, 'ativo' => true],
            ['nome' => 'Em validação na Diretoria', 'descricao' => 'Aguardando diretoria', 'ordem' => 3, 'ativo' => true],
            ['nome' => 'Em validação na Secretaria Adjunta', 'descricao' => 'Aguardando SA', 'ordem' => 4, 'ativo' => true],
            ['nome' => 'Em covalidação no Gabinete', 'descricao' => 'Aguardando Gabinete', 'ordem' => 5, 'ativo' => true],
            ['nome' => 'Devolvida para ajustes', 'descricao' => 'Devolvida com pendências', 'ordem' => 6, 'ativo' => true],
            ['nome' => 'Validada', 'descricao' => 'Apta para consolidação', 'ordem' => 7, 'ativo' => true],
            ['nome' => 'Validada c/ alteração', 'descricao' => 'Validada com ajustes de quantidade/valor', 'ordem' => 8, 'ativo' => true],
            ['nome' => 'Consolidada', 'descricao' => 'Incluída na versão do PCA', 'ordem' => 9, 'ativo' => true],
            ['nome' => 'Consolidada c/ alteração', 'descricao' => 'Incluída na versão do PCA com ajustes', 'ordem' => 10, 'ativo' => true],
        ]);
    }
}
