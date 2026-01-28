<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Administração</p>
                <h2 class="font-semibold text-xl text-slate-900">Unidades organizacionais</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 space-y-6 relative">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="border-b px-6 py-4 flex items-center justify-between">
                <p class="text-sm text-slate-500">Unidades organizacionais</p>
                <button type="button" data-modal-open onclick="window.__toggleUnidadesModal?.(true)" class="px-4 py-2 bg-slate-900 text-white rounded">Nova unidade</button>
            </div>
            <div class="p-6 space-y-4">
                <form method="GET" action="{{ route('admin.unidades.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Árvore (a partir de)</label>
                        <select name="arvore_id" class="mt-1 w-full rounded border-slate-300">
                            <option value="">Todas</option>
                            @foreach ($pais as $p)
                                <option value="{{ $p->id }}" @selected(request('arvore_id') == $p->id)>{{ $p->sigla ?? $p->nome }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Tipo</label>
                        <select name="tipo" class="mt-1 w-full rounded border-slate-300">
                            <option value="">Todos</option>
                            @foreach ($tipos as $tipo)
                                <option value="{{ $tipo }}" @selected(request('tipo') === $tipo)>{{ strtoupper($tipo) }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Nome</label>
                        <input type="text" name="nome" value="{{ request('nome') }}" class="mt-1 w-full rounded border-slate-300" placeholder="Buscar por nome">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Sigla</label>
                        <input type="text" name="sigla" value="{{ request('sigla') }}" class="mt-1 w-full rounded border-slate-300" placeholder="Buscar por sigla">
                    </div>
                    <div class="md:col-span-4 flex justify-end gap-2">
                        <a href="{{ route('admin.unidades.index') }}" class="px-4 py-2 text-slate-700 border border-slate-200 rounded">Limpar</a>
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Filtrar</button>
                    </div>
                </form>

                <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                        <tr>
                            <th class="px-3 py-2">Nome</th>
                            <th class="px-3 py-2">Sigla</th>
                            <th class="px-3 py-2">Tipo</th>
                            <th class="px-3 py-2">Pai</th>
                            <th class="px-3 py-2 text-center">Ativo</th>
                            <th class="px-3 py-2 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @foreach ($unidades as $unidade)
                            <tr>
                                <td class="px-3 py-2 font-medium">{{ $unidade->nome }}</td>
                                <td class="px-3 py-2">{{ $unidade->sigla ?? '—' }}</td>
                                <td class="px-3 py-2 uppercase text-xs tracking-wide">{{ $unidade->tipo }}</td>
                                <td class="px-3 py-2">{{ $unidade->parent?->sigla ?? $unidade->parent?->nome ?? 'Raiz' }}</td>
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $unidade->ativo ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $unidade->ativo ? 'Ativa' : 'Inativa' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-right space-x-2">
                                    <a href="{{ route('admin.unidades.edit', $unidade) }}" class="text-sm text-indigo-600 hover:underline">Editar</a>
                                    <form action="{{ route('admin.unidades.toggle', $unidade) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm text-slate-600 hover:underline">
                                            {{ $unidade->ativo ? 'Inativar' : 'Ativar' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.unidades.destroy', $unidade) }}" method="POST" class="inline" onsubmit="return confirm('Excluir esta unidade? Isso só é permitido se não houver vínculos.');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm text-rose-600 hover:underline">Excluir</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($unidades->isEmpty())
                    <p class="text-sm text-slate-500">Nenhuma unidade cadastrada.</p>
                @endif
                </div>
            </div>
        </div>

                {{-- MODAL DENTRO DO CONTAINER - POSICIONADO NO TOPO --}}
        <div class="absolute inset-0 bg-black/50 hidden items-start justify-center z-50 rounded-lg pt-10" data-modal>
            <div class="bg-white rounded-lg shadow-lg w-full max-w-4xl mx-4 max-h-[80vh] overflow-y-auto">
                <div class="px-6 py-4 flex items-center justify-between bg-slate-900 text-slate-100 rounded-t-lg">
                    <div>
                        <p class="text-sm text-slate-300">Cadastrar nova unidade</p>
                        <h3 class="text-lg font-semibold text-white">Nova unidade</h3>
                    </div>
                    <button type="button" class="text-slate-300 hover:text-white" data-modal-close onclick="window.__toggleUnidadesModal?.(false)">✕</button>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('admin.unidades.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @csrf
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Nome</label>
                            <input type="text" name="nome" class="mt-1 w-full rounded border-slate-300" required>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Sigla</label>
                            <input type="text" name="sigla" class="mt-1 w-full rounded border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Código</label>
                            <input type="text" name="codigo" class="mt-1 w-full rounded border-slate-300">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Tipo</label>
                            <select name="tipo" class="mt-1 w-full rounded border-slate-300" required>
                                <option value="secretaria">Secretaria</option>
                                <option value="secretaria_adjunta">Secretaria Adjunta</option>
                                <option value="gabinete">Gabinete</option>
                                <option value="diretoria">Diretoria</option>
                                <option value="coordenadoria">Coordenadoria</option>
                                <option value="gerencia">Gerência</option>
                                <option value="nucleo">Núcleo</option>
                                <option value="consultoria">Consultoria</option>
                                <option value="ouvidoria">Ouvidoria</option>
                                <option value="unidade">Unidade</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700">Unidade Pai</label>
                            <select name="parent_id" class="mt-1 w-full rounded border-slate-300">
                                <option value="">Raiz</option>
                                @foreach ($pais as $p)
                                    <option value="{{ $p->id }}">{{ $p->sigla ?? $p->nome }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="md:col-span-2 flex justify-end gap-2">
                            <button type="button" data-modal-close onclick="window.__toggleUnidadesModal?.(false)" class="px-4 py-2 text-slate-700 border border-slate-200 rounded">Cancelar</button>
                            <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Salvar</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <script>
        (() => {
            const getModal = () => document.querySelector('[data-modal]');

            const show = () => {
                const modal = getModal();
                if (!modal) return;
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            const hide = () => {
                const modal = getModal();
                if (!modal) return;
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            };

            window.__toggleUnidadesModal = (open) => {
                if (open) {
                    show();
                } else {
                    hide();
                }
            };

            document.addEventListener('click', (event) => {
                if (event.target.closest('[data-modal-open]')) {
                    event.preventDefault();
                    show();
                }
                if (event.target.closest('[data-modal-close]')) {
                    event.preventDefault();
                    hide();
                }
                // clique fora não fecha o modal
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    event.preventDefault();
                }
            });
        })();
    </script>
</x-app-layout>
