<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CatalogoCategoria;
use App\Models\CatalogoNatureza;
use App\Models\CatalogoPrioridade;
use App\Models\CatalogoTipoDemanda;
use Illuminate\Http\Request;

class CatalogoController extends Controller
{
    public function index(string $tipo = null)
    {
        $categorias = CatalogoCategoria::orderBy('ordem')->orderBy('nome')->get();
        $prioridades = CatalogoPrioridade::orderBy('ordem')->orderBy('nome')->get();
        $tipos = CatalogoTipoDemanda::orderBy('ordem')->orderBy('nome')->get();
        $naturezas = CatalogoNatureza::orderBy('ordem')->orderBy('nome')->get();

        return view('admin.catalogos.index', [
            'categorias' => $categorias,
            'prioridades' => $prioridades,
            'tipos' => $tipos,
            'naturezas' => $naturezas,
            'tipoSelecionado' => $tipo,
        ]);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'tipo' => 'required|in:categoria,prioridade,tipo,natureza',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer|min:0',
        ]);

        $mapa = [
            'categoria' => CatalogoCategoria::class,
            'prioridade' => CatalogoPrioridade::class,
            'tipo' => CatalogoTipoDemanda::class,
            'natureza' => CatalogoNatureza::class,
        ];

        $classe = $mapa[$dados['tipo']];
        $classe::create([
            'nome' => $dados['nome'],
            'descricao' => $dados['descricao'] ?? null,
            'ordem' => $dados['ordem'] ?? 0,
            'ativo' => true,
        ]);

        return back()->with('sucesso', 'Item criado.');
    }

    public function toggle(string $tipo, int $id)
    {
        $mapa = [
            'categoria' => CatalogoCategoria::class,
            'prioridade' => CatalogoPrioridade::class,
            'tipo' => CatalogoTipoDemanda::class,
            'natureza' => CatalogoNatureza::class,
        ];

        abort_unless(isset($mapa[$tipo]), 404);

        $classe = $mapa[$tipo];
        $item = $classe::findOrFail($id);
        $item->update(['ativo' => ! $item->ativo]);

        return back()->with('sucesso', 'Status atualizado.');
    }

    public function update(Request $request, string $tipo, int $id)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'ordem' => 'nullable|integer|min:0',
        ]);

        $mapa = [
            'categoria' => CatalogoCategoria::class,
            'prioridade' => CatalogoPrioridade::class,
            'tipo' => CatalogoTipoDemanda::class,
            'natureza' => CatalogoNatureza::class,
        ];

        abort_unless(isset($mapa[$tipo]), 404);

        $classe = $mapa[$tipo];
        $item = $classe::findOrFail($id);
        $item->update($dados);

        return back()->with('sucesso', 'Item atualizado.');
    }

    public function destroy(string $tipo, int $id)
    {
        $mapa = [
            'categoria' => CatalogoCategoria::class,
            'prioridade' => CatalogoPrioridade::class,
            'tipo' => CatalogoTipoDemanda::class,
            'natureza' => CatalogoNatureza::class,
        ];

        abort_unless(isset($mapa[$tipo]), 404);

        $classe = $mapa[$tipo];
        $item = $classe::findOrFail($id);
        $item->delete();

        return back()->with('sucesso', 'Item excluído.');
    }
}
