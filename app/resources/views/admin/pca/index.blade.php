<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Administração</p>
                <h2 class="font-semibold text-xl text-slate-900">Consolidação do PCA (DAF)</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 space-y-6">
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
            <div class="bg-white shadow-sm rounded-lg xl:col-span-1">
                <div class="border-b px-6 py-4">
                    <p class="text-sm text-slate-500">Versões do PCA</p>
                </div>
                <div class="p-6 space-y-4">
                    <form method="POST" action="{{ route('admin.pca.versoes.store') }}" class="space-y-3">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Ciclo</label>
                            <select name="ciclo_id" class="mt-1 w-full rounded border-slate-300" required>
                                @if ($cicloAtivo)
                                    <option value="{{ $cicloAtivo->id }}">{{ $cicloAtivo->exercicio }} ({{ $cicloAtivo->status }})</option>
                                @endif
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nome da versão</label>
                            <input type="text" name="nome" class="mt-1 w-full rounded border-slate-300" placeholder="Preliminar v1" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Status</label>
                            <select name="status" class="mt-1 w-full rounded border-slate-300" required>
                                <option value="preliminar">Preliminar</option>
                                <option value="aprovada">Aprovada</option>
                                <option value="substituida">Substituída</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Observação</label>
                            <textarea name="observacao" rows="2" class="mt-1 w-full rounded border-slate-300"></textarea>
                        </div>
                        <label class="flex items-center gap-2 text-sm text-slate-600">
                            <input type="checkbox" name="incluir_todas" value="1" class="rounded border-slate-300">
                            Incluir automaticamente todas as demandas validadas
                        </label>
                        <div class="flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Criar versão</button>
                        </div>
                    </form>

                    <div class="pt-4 border-t space-y-2">
                        @forelse ($versoes as $versao)
                            <a href="{{ route('admin.pca.index', ['versao' => $versao->id]) }}"
                               class="block px-3 py-2 rounded border {{ $versaoSelecionada?->id === $versao->id ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 text-slate-700 hover:bg-slate-50' }}">
                                <div class="text-sm font-semibold">{{ $versao->nome }}</div>
                                <div class="text-xs text-slate-400">Ciclo {{ $versao->ciclo?->exercicio }} • {{ strtoupper($versao->status) }}</div>
                            </a>
                        @empty
                            <p class="text-sm text-slate-500">Nenhuma versão criada.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white shadow-sm rounded-lg xl:col-span-2">
                <div class="border-b px-6 py-4 flex items-center justify-between">
                    <p class="text-sm text-slate-500">Demandas consolidadas</p>
                </div>
                <div class="p-6 space-y-6">
                    @if (! $versaoSelecionada)
                        <p class="text-sm text-slate-500">Selecione uma versão para consolidar.</p>
                    @else
                        <div class="space-y-6">
                            <div>
                                <p class="text-sm font-semibold text-slate-700 mb-3">Consolidadas</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                                            <tr>
                                                <th class="px-3 py-2">Demanda</th>
                                                <th class="px-3 py-2">Unidade</th>
                                                <th class="px-3 py-2 text-right">Valor</th>
                                                <th class="px-3 py-2">Status</th>
                                                <th class="px-3 py-2 text-right">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                                            @forelse ($itensConsolidados as $item)
                                                <tr>
                                                    <td class="px-3 py-2">
                                                        <div class="font-semibold">{{ $item->demanda?->titulo }}</div>
                                                        <div class="text-xs text-slate-500">#{{ $item->demanda_id }}</div>
                                                    </td>
                                                    <td class="px-3 py-2">{{ $item->demanda?->unidade?->sigla ?? $item->demanda?->unidade?->nome }}</td>
                                                    <td class="px-3 py-2 text-right">{{ $item->demanda?->valor_estimado ? 'R$ '.number_format($item->demanda?->valor_estimado, 2, ',', '.') : '—' }}</td>
                                                    <td class="px-3 py-2">{{ $item->demanda?->status?->nome ?? '—' }}</td>
                                                    <td class="px-3 py-2 text-right">
                                                        <form method="POST" action="{{ route('admin.pca.itens.toggle', [$versaoSelecionada->id, $item->demanda_id]) }}">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="px-3 py-1 rounded text-xs bg-rose-50 text-rose-700">
                                                                Devolver
                                                            </button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-3 py-4 text-sm text-slate-500">Nenhuma demanda consolidada.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div>
                                <p class="text-sm font-semibold text-slate-700 mb-3">Validadas</p>
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-slate-200">
                                        <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                                            <tr>
                                                <th class="px-3 py-2">Demanda</th>
                                                <th class="px-3 py-2">Unidade</th>
                                                <th class="px-3 py-2 text-right">Valor</th>
                                                <th class="px-3 py-2">Status</th>
                                                <th class="px-3 py-2 text-right">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                                            @forelse ($demandasValidadas as $demanda)
                                                <tr>
                                                    <td class="px-3 py-2">
                                                        <div class="font-semibold">{{ $demanda->titulo }}</div>
                                                        <div class="text-xs text-slate-500">#{{ $demanda->id }}</div>
                                                    </td>
                                                    <td class="px-3 py-2">{{ $demanda->unidade?->sigla ?? $demanda->unidade?->nome }}</td>
                                                    <td class="px-3 py-2 text-right">{{ $demanda->valor_estimado ? 'R$ '.number_format($demanda->valor_estimado, 2, ',', '.') : '—' }}</td>
                                                    <td class="px-3 py-2">{{ $demanda->status?->nome ?? '—' }}</td>
                                                    <td class="px-3 py-2 text-right">
                                                        <div class="flex justify-end gap-2">
                                                            <a href="{{ route('demandas.edit', ['demanda' => $demanda, 'versao' => $versaoSelecionada?->id]) }}" class="px-3 py-1 rounded text-xs bg-slate-100 text-slate-700">
                                                                Editar
                                                            </a>
                                                            <form method="POST" action="{{ route('admin.pca.itens.toggle', [$versaoSelecionada->id, $demanda->id]) }}">
                                                                @csrf
                                                                @method('PATCH')
                                                                <button type="submit" class="px-3 py-1 rounded text-xs bg-emerald-50 text-emerald-700">
                                                                    Incluir
                                                                </button>
                                                            </form>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="5" class="px-3 py-4 text-sm text-slate-500">Nenhuma demanda validada para o ciclo.</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
