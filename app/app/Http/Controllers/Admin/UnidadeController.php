<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demanda;
use App\Models\UnidadeOrganizacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UnidadeController extends Controller
{
    public function index()
    {
        $query = UnidadeOrganizacional::with('parent');
        $pais = UnidadeOrganizacional::orderBy('nome')->get();
        $tipos = UnidadeOrganizacional::select('tipo')->distinct()->orderBy('tipo')->pluck('tipo');

        $arvoreId = request('arvore_id');
        if ($arvoreId) {
            $raiz = UnidadeOrganizacional::find($arvoreId);
            if ($raiz) {
                $ids = array_merge([$raiz->id], $raiz->descendantsIds());
                $query->whereIn('id', $ids);
            }
        }

        if (request('tipo')) {
            $query->where('tipo', request('tipo'));
        }

        if (request('nome')) {
            $query->where('nome', 'like', '%'.request('nome').'%');
        }

        if (request('sigla')) {
            $query->where('sigla', 'like', '%'.request('sigla').'%');
        }

        $unidades = $query
            ->orderBy('parent_id')
            ->orderBy('nome')
            ->get();

        return view('admin.unidades.index', compact('unidades', 'pais', 'tipos'));
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'nullable|string|max:50',
            'codigo' => 'nullable|string|max:50',
            'tipo' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:unidades_organizacionais,id',
        ]);

        UnidadeOrganizacional::create($dados + ['ativo' => true]);

        return redirect()->route('admin.unidades.index')->with('sucesso', 'Unidade criada.');
    }

    public function edit(UnidadeOrganizacional $unidade)
    {
        $pais = UnidadeOrganizacional::where('id', '!=', $unidade->id)->orderBy('nome')->get();

        return view('admin.unidades.edit', compact('unidade', 'pais'));
    }

    public function update(Request $request, UnidadeOrganizacional $unidade)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:255',
            'sigla' => 'nullable|string|max:50',
            'codigo' => 'nullable|string|max:50',
            'tipo' => 'required|string|max:50',
            'parent_id' => 'nullable|exists:unidades_organizacionais,id',
            'ativo' => 'nullable|boolean',
        ]);

        $unidade->update($dados);

        return redirect()->route('admin.unidades.index')->with('sucesso', 'Unidade atualizada.');
    }

    public function toggle(UnidadeOrganizacional $unidade)
    {
        $unidade->update(['ativo' => ! $unidade->ativo]);

        return redirect()->route('admin.unidades.index')->with('sucesso', 'Status atualizado.');
    }

    public function destroy(UnidadeOrganizacional $unidade)
    {
        $vinculosPerfis = DB::table('perfil_usuario')->where('unidade_id', $unidade->id)->count();
        $filhas = UnidadeOrganizacional::where('parent_id', $unidade->id)->count();
        $demandas = Demanda::where('unidade_id', $unidade->id)->count();

        if ($vinculosPerfis || $filhas || $demandas) {
            return redirect()->route('admin.unidades.index')->withErrors('Unidade não pode ser excluída: possui vínculos ou registros associados.');
        }

        $unidade->delete();

        return redirect()->route('admin.unidades.index')->with('sucesso', 'Unidade excluída.');
    }
}
