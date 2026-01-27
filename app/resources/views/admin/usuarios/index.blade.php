<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Administração</p>
                <h2 class="font-semibold text-xl text-slate-900">Usuários</h2>
            </div>
            <a href="{{ route('admin.usuarios.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded">Novo usuário</a>
        </div>
    </x-slot>

    <div class="py-8 px-6">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                    <tr>
                        <th class="px-3 py-2">Nome</th>
                        <th class="px-3 py-2">E-mail</th>
                        <th class="px-3 py-2">Perfis</th>
                        <th class="px-3 py-2">Unidade</th>
                        <th class="px-3 py-2 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse ($usuarios as $usuario)
                            <tr>
                                <td class="px-3 py-2 font-semibold">{{ $usuario->name }}</td>
                                <td class="px-3 py-2">{{ $usuario->email }}</td>
                                <td class="px-3 py-2">
                                    @foreach ($usuario->perfis as $perfil)
                                        <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                            {{ $perfil->nome }}
                                        </span>
                                    @endforeach
                                </td>
                            <td class="px-3 py-2">
                                @php $unidade = $usuario->perfis->first()?->pivot?->unidade_id; @endphp
                                {{ $unidade ?? '—' }}
                            </td>
                            <td class="px-3 py-2 text-right">
                                <a href="{{ route('admin.usuarios.edit', $usuario) }}" class="text-indigo-600 hover:underline">Editar</a>
                                <form action="{{ route('admin.usuarios.destroy', $usuario) }}" method="POST" class="inline" onsubmit="return confirm('Deseja desativar este usuário?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-rose-600 hover:underline ml-2">Desativar</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td class="px-3 py-4 text-sm text-slate-500" colspan="5">Nenhum usuário cadastrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
