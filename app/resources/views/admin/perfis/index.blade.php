<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Administração</p>
                <h2 class="font-semibold text-xl text-slate-900">Perfis e permissões</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 space-y-6">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="border-b px-6 py-4">
                <p class="text-sm text-slate-500">Perfis</p>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                        <tr>
                            <th class="px-3 py-2">Perfil</th>
                            <th class="px-3 py-2">Slug</th>
                            <th class="px-3 py-2">Permissões</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @foreach ($perfis as $perfil)
                            <tr>
                                <td class="px-3 py-2 font-medium">{{ $perfil->nome }}</td>
                                <td class="px-3 py-2 text-xs uppercase tracking-wide text-slate-500">{{ $perfil->slug }}</td>
                                <td class="px-3 py-2">
                                    <form method="POST" action="{{ route('admin.perfis.sync', $perfil) }}">
                                        @csrf
                                        <div class="flex flex-wrap gap-2">
                                            @foreach ($permissoes as $permissao)
                                                <label class="inline-flex items-center gap-1 text-xs px-2 py-1 rounded border {{ $perfil->permissoes->contains($permissao->id) ? 'bg-indigo-50 border-indigo-200' : 'border-slate-200' }}">
                                                    <input type="checkbox" name="permissoes[]" value="{{ $permissao->id }}" class="rounded border-slate-300" @checked($perfil->permissoes->contains($permissao->id))>
                                                    <span>{{ $permissao->chave }}</span>
                                                </label>
                                            @endforeach
                                        </div>
                                        <div class="mt-3">
                                            <button type="submit" class="text-xs px-3 py-2 bg-slate-900 text-white rounded">Salvar</button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="border-b px-6 py-4">
                <p class="text-sm text-slate-500">Catálogo de permissões disponíveis</p>
            </div>
            <div class="p-6">
                <div class="flex flex-wrap gap-2">
                    @foreach ($permissoes as $permissao)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                            {{ $permissao->chave }}
                        </span>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
