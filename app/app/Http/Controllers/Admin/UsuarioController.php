<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Perfil;
use App\Models\UnidadeOrganizacional;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = User::with('perfis')->orderBy('name')->get();

        return view('admin.usuarios.index', compact('usuarios'));
    }

    public function create()
    {
        return view('admin.usuarios.form', $this->options());
    }

    public function store(Request $request)
    {
        $dados = $this->validateData($request, true);

        $user = User::create([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
        ]);

        $this->syncPerfis($user, $dados['perfis'] ?? [], $dados['unidade_id']);

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário criado.');
    }

    public function edit(User $user)
    {
        return view('admin.usuarios.form', array_merge(
            $this->options(),
            ['usuario' => $user]
        ));
    }

    public function update(Request $request, User $user)
    {
        $dados = $this->validateData($request, false, $user->id);

        $user->update([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => isset($dados['password']) ? Hash::make($dados['password']) : $user->password,
        ]);

        $this->syncPerfis($user, $dados['perfis'] ?? [], $dados['unidade_id']);

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário atualizado.');
    }

    public function destroy(User $user)
    {
        $user->perfis()->sync([]);
        $user->delete();

        return redirect()->route('admin.usuarios.index')->with('sucesso', 'Usuário desativado.');
    }

    protected function validateData(Request $request, bool $isCreate, ?int $userId = null): array
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email'.($userId ? ','.$userId : ''),
            'unidade_id' => 'required|exists:unidades_organizacionais,id',
            'perfis' => 'array',
            'perfis.*' => 'exists:perfis,id',
        ];

        if ($isCreate) {
            $rules['password'] = 'required|string|min:6';
        } else {
            $rules['password'] = 'nullable|string|min:6';
        }

        return $request->validate($rules);
    }

    protected function syncPerfis(User $user, array $perfisIds, int $unidadeId): void
    {
        $payload = [];
        foreach ($perfisIds as $perfilId) {
            $payload[$perfilId] = ['unidade_id' => $unidadeId, 'ativo' => true];
        }
        $user->perfis()->sync($payload);
    }

    protected function options(): array
    {
        return [
            'perfis' => Perfil::orderBy('nome')->get(),
            'unidades' => UnidadeOrganizacional::orderBy('nome')->get(),
        ];
    }
}
