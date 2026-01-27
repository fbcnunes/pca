<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Perfil;
use App\Models\Permissao;

class PerfilPermissaoController extends Controller
{
    public function index()
    {
        $perfis = Perfil::with('permissoes')->orderBy('nome')->get();
        $permissoes = Permissao::orderBy('nome')->get();

        return view('admin.perfis.index', compact('perfis', 'permissoes'));
    }

    public function sync(Request $request, Perfil $perfil)
    {
        $dados = $request->validate([
            'permissoes' => 'array',
            'permissoes.*' => 'exists:permissoes,id',
        ]);

        $perfil->permissoes()->sync($dados['permissoes'] ?? []);

        return redirect()->route('admin.perfis.index')->with('sucesso', 'Permissões atualizadas para '.$perfil->nome);
    }
}
