<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-slate-500">Visão geral</p>
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">Dashboard</h2>
        </div>
    </x-slot>

    @php
        $perfis = auth()->user()->perfis()->with('permissoes')->get();
    @endphp

    <div class="py-8 px-6 space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-slate-500 mb-2">Seus perfis</div>
                <ul class="space-y-2">
                    @foreach ($perfis as $perfil)
                        <li class="flex items-start justify-between border border-slate-100 rounded-lg px-3 py-2">
                            <div>
                                <div class="font-semibold">{{ $perfil->nome }}</div>
                                <div class="text-xs text-slate-500">{{ $perfil->descricao }}</div>
                            </div>
                            <span class="text-xs uppercase tracking-wide text-slate-400">{{ $perfil->slug }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="text-sm text-slate-500 mb-2">Permissões</div>
                <div class="flex flex-wrap gap-2">
                    @forelse ($perfis->flatMap->permissoes->unique('chave') as $permissao)
                        <span class="inline-flex items-center px-3 py-1 text-xs font-semibold rounded-full bg-indigo-50 text-indigo-700">
                            {{ $permissao->chave }}
                        </span>
                    @empty
                        <p class="text-sm text-slate-500">Nenhuma permissão atribuída.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
