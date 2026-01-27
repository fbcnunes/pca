<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Administração</p>
                <h2 class="font-semibold text-xl text-slate-900">Catálogos institucionais</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 space-y-6">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            @php
                $blocos = [
                    ['titulo' => 'Categorias', 'tipo' => 'categoria', 'itens' => $categorias],
                    ['titulo' => 'Prioridades', 'tipo' => 'prioridade', 'itens' => $prioridades],
                    ['titulo' => 'Tipos de demanda', 'tipo' => 'tipo', 'itens' => $tipos],
                    ['titulo' => 'Naturezas', 'tipo' => 'natureza', 'itens' => $naturezas],
                ];
            @endphp

            @foreach ($blocos as $bloco)
                <div class="bg-white shadow-sm rounded-lg">
                    <div class="border-b px-6 py-4 flex items-center justify-between">
                        <p class="text-sm text-slate-500">{{ $bloco['titulo'] }}</p>
                    </div>
                    <div class="p-6 space-y-4">
                        <form method="POST" action="{{ route('admin.catalogos.store') }}" class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @csrf
                            <input type="hidden" name="tipo" value="{{ $bloco['tipo'] }}">
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700">Nome</label>
                                <input type="text" name="nome" class="mt-1 w-full rounded border-slate-300" required>
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-sm font-medium text-slate-700">Descrição</label>
                                <textarea name="descricao" rows="2" class="mt-1 w-full rounded border-slate-300"></textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-slate-700">Ordem</label>
                                <input type="number" name="ordem" value="0" class="mt-1 w-full rounded border-slate-300">
                            </div>
                            <div class="self-end">
                                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Salvar</button>
                            </div>
                        </form>

                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-slate-200">
                                <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                                    <tr>
                                        <th class="px-3 py-2">Nome</th>
                                        <th class="px-3 py-2">Ordem</th>
                                        <th class="px-3 py-2 text-center">Ativo</th>
                                        <th class="px-3 py-2 text-right">Ações</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                                    @forelse ($bloco['itens'] as $item)
                                        <tr>
                                            <td class="px-3 py-2">
                                                <div class="font-semibold">{{ $item->nome }}</div>
                                                <div class="text-xs text-slate-500">{{ $item->descricao }}</div>
                                            </td>
                                            <td class="px-3 py-2">{{ $item->ordem }}</td>
                                            <td class="px-3 py-2 text-center">
                                                <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $item->ativo ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                                    {{ $item->ativo ? 'Ativo' : 'Inativo' }}
                                                </span>
                                            </td>
                                            <td class="px-3 py-2 text-right">
                                                <form action="{{ route('admin.catalogos.toggle', [$bloco['tipo'], $item->id]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="text-sm text-slate-600 hover:underline">
                                                        {{ $item->ativo ? 'Inativar' : 'Ativar' }}
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td class="px-3 py-2 text-sm text-slate-500" colspan="4">Nenhum item cadastrado.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>
