<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CicloPca;

class CicloController extends Controller
{
    private array $statusOptions = ['Em elaboração', 'Versão preliminar', 'Aprovada', 'Substituída'];

    public function index()
    {
        $ciclos = CicloPca::orderByDesc('exercicio')->get();

        return view('admin.ciclos.index', [
            'ciclos' => $ciclos,
            'statusOptions' => $this->statusOptions,
        ]);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'exercicio' => 'required|digits:4|integer',
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'status' => 'required|in:'.implode(',', $this->statusOptions),
        ]);

        CicloPca::create($dados + ['ativo' => false]);

        return redirect()->route('admin.ciclos.index')->with('sucesso', 'Ciclo criado.');
    }

    public function edit(CicloPca $ciclo)
    {
        return view('admin.ciclos.edit', [
            'ciclo' => $ciclo,
            'statusOptions' => $this->statusOptions,
        ]);
    }

    public function update(Request $request, CicloPca $ciclo)
    {
        $dados = $request->validate([
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
            'status' => 'required|in:'.implode(',', $this->statusOptions),
            'observacao' => 'nullable|string',
        ]);

        $ciclo->update($dados);

        return redirect()->route('admin.ciclos.index')->with('sucesso', 'Ciclo atualizado.');
    }

    public function ativar(CicloPca $ciclo)
    {
        CicloPca::where('id', '!=', $ciclo->id)->update(['ativo' => false]);
        $ciclo->update(['ativo' => true]);

        return redirect()->route('admin.ciclos.index')->with('sucesso', 'Ciclo marcado como ativo.');
    }
}
