<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-slate-500">Validação de demandas</p>
            <h2 class="font-semibold text-xl text-slate-900">Caixa de validação</h2>
        </div>
    </x-slot>

    @php
        $user = Auth::user();
    @endphp

    <div class="py-8 px-6 space-y-6">
        @if ($demDiretoria->isNotEmpty())
            <div class="bg-white shadow-sm rounded-lg">
                <div class="border-b px-6 py-4">
                    <p class="text-sm text-slate-500">Diretoria / Secretaria Adjunta</p>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                            <tr>
                                <th class="px-3 py-2">Título</th>
                                <th class="px-3 py-2">Unidade</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                            @foreach ($demDiretoria as $demanda)
                                <tr>
                                    <td class="px-3 py-2 font-semibold">{{ $demanda->titulo }}</td>
                                    <td class="px-3 py-2">{{ $demanda->unidade?->sigla ?? $demanda->unidade?->nome }}</td>
                                    <td class="px-3 py-2">
                                        @if ($demanda->status?->nome === 'Enviada para validação' && $demanda->unidadeValidacao)
                                            Enviado para {{ $demanda->unidadeValidacao->sigla }}
                                        @else
                                            {{ $demanda->status?->nome }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        @php
                                            $status = $demanda->status?->nome;
                                            $unidadeValidacaoId = $demanda->unidade_validacao_id;
                                            $precisaCovalidar = $demanda->unidadeValidacao?->tipo === 'gabinete';
                                            $temPermissao = $precisaCovalidar ? $user?->temPermissao('demandas.covalidar') : $user?->temPermissao('demandas.validar');
                                            $podeEditar = $status === 'Enviada para validação'
                                                && $unidadeValidacaoId
                                                && in_array($unidadeValidacaoId, $unidadesUsuario ?? [])
                                                && $temPermissao;
                                        @endphp
                                        @if ($podeEditar)
                                            <a href="{{ route('demandas.edit', $demanda) }}"
                                                class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-800 text-sm rounded border border-slate-200 hover:bg-slate-200">
                                                Editar
                                            </a>
                                        @endif
                                        @if ($podeEditar)
                                            <form action="{{ route('validacao.decidir', $demanda) }}" method="POST" class="inline-flex gap-2 items-center">
                                                @csrf
                                                <input type="hidden" name="decisao" value="aprovado">
                                                <input type="text" name="comentario" placeholder="Comentário" class="rounded border-slate-300 text-sm px-2 py-1" required>
                                                <button type="submit" class="px-3 py-1 bg-emerald-600 text-white text-sm rounded">Aprovar</button>
                                            </form>
                                            <form action="{{ route('validacao.decidir', $demanda) }}" method="POST" class="inline-flex gap-2 items-center mt-2">
                                                @csrf
                                                <input type="hidden" name="decisao" value="devolvido">
                                                <input type="text" name="comentario" placeholder="Comentário" class="rounded border-slate-300 text-sm px-2 py-1" required>
                                                <button type="submit" class="px-3 py-1 bg-rose-600 text-white text-sm rounded">Devolver</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">Aguardando etapa</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($demGabinete->isNotEmpty())
            <div class="bg-white shadow-sm rounded-lg">
                <div class="border-b px-6 py-4">
                    <p class="text-sm text-slate-500">Covalidação do Gabinete</p>
                </div>
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-slate-200">
                        <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                            <tr>
                                <th class="px-3 py-2">Título</th>
                                <th class="px-3 py-2">Unidade</th>
                                <th class="px-3 py-2">Status</th>
                                <th class="px-3 py-2 text-right">Ações</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                            @foreach ($demGabinete as $demanda)
                                <tr>
                                    <td class="px-3 py-2 font-semibold">{{ $demanda->titulo }}</td>
                                    <td class="px-3 py-2">{{ $demanda->unidade?->sigla ?? $demanda->unidade?->nome }}</td>
                                    <td class="px-3 py-2">
                                        @if ($demanda->status?->nome === 'Enviada para validação' && $demanda->unidadeValidacao)
                                            Enviado para {{ $demanda->unidadeValidacao->sigla }}
                                        @else
                                            {{ $demanda->status?->nome }}
                                        @endif
                                    </td>
                                    <td class="px-3 py-2 text-right">
                                        @php
                                            $status = $demanda->status?->nome;
                                            $unidadeValidacaoId = $demanda->unidade_validacao_id;
                                            $precisaCovalidar = $demanda->unidadeValidacao?->tipo === 'gabinete';
                                            $temPermissao = $precisaCovalidar ? $user?->temPermissao('demandas.covalidar') : $user?->temPermissao('demandas.validar');
                                            $podeGabinete = $status === 'Enviada para validação'
                                                && $unidadeValidacaoId
                                                && in_array($unidadeValidacaoId, $unidadesUsuario ?? [])
                                                && $temPermissao;
                                        @endphp
                                        @if ($podeGabinete)
                                            <a href="{{ route('demandas.edit', $demanda) }}"
                                                class="inline-flex items-center px-3 py-1 bg-slate-100 text-slate-800 text-sm rounded border border-slate-200 hover:bg-slate-200">
                                                Editar
                                            </a>
                                        @endif
                                        @if ($podeGabinete)
                                            <form action="{{ route('validacao.decidir', $demanda) }}" method="POST" class="inline-flex gap-2 items-center">
                                                @csrf
                                                <input type="hidden" name="decisao" value="aprovado">
                                                <input type="text" name="comentario" placeholder="Comentário" class="rounded border-slate-300 text-sm px-2 py-1" required>
                                                <button type="submit" class="px-3 py-1 bg-emerald-600 text-white text-sm rounded">Covalidar</button>
                                            </form>
                                            <form action="{{ route('validacao.decidir', $demanda) }}" method="POST" class="inline-flex gap-2 items-center mt-2">
                                                @csrf
                                                <input type="hidden" name="decisao" value="devolvido">
                                                <input type="text" name="comentario" placeholder="Comentário" class="rounded border-slate-300 text-sm px-2 py-1" required>
                                                <button type="submit" class="px-3 py-1 bg-rose-600 text-white text-sm rounded">Devolver</button>
                                            </form>
                                        @else
                                            <span class="text-xs text-slate-400">Aguardando etapa</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

        @if ($demDiretoria->isEmpty() && $demGabinete->isEmpty())
            <p class="text-sm text-slate-500">Nenhuma demanda pendente de sua validação.</p>
        @endif
    </div>
</x-app-layout>
