<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Minhas demandas</p>
                <h2 class="font-semibold text-xl text-slate-900">Demandas</h2>
            </div>
            <a href="{{ route('demandas.create') }}" class="px-4 py-2 bg-slate-900 text-white rounded">Nova demanda</a>
        </div>
    </x-slot>

    <div class="py-8 px-6">
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-slate-200">
                <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                    <tr>
                        <th class="px-3 py-2">Título</th>
                        <th class="px-3 py-2">Status</th>
                        <th class="px-3 py-2">Ciclo</th>
                        <th class="px-3 py-2">Atualizada em</th>
                        <th class="px-3 py-2 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @forelse ($demandas as $demanda)
                            <tr>
                                <td class="px-3 py-2 font-semibold">{{ $demanda->titulo }}</td>
                                <td class="px-3 py-2">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full bg-slate-100 text-slate-700">
                                        @if ($demanda->status?->nome === 'Enviada para validação' && $demanda->unidadeValidacao)
                                            Enviado para {{ $demanda->unidadeValidacao->sigla }}
                                        @else
                                            {{ $demanda->status?->nome }}
                                        @endif
                                </span>
                            </td>
                            <td class="px-3 py-2">{{ $demanda->ciclo?->exercicio ?? '—' }}</td>
                            <td class="px-3 py-2 text-slate-500 text-xs">{{ $demanda->updated_at->format('d/m/Y H:i') }}</td>
                            @php
                                $editavel = in_array($demanda->status?->nome, ['Rascunho', 'Devolvida para ajustes']);
                            @endphp
                            <td class="px-3 py-2 text-right space-x-2">
                                <a href="{{ route('demandas.show', $demanda) }}" class="text-slate-700 hover:underline">Ver</a>
                                @if ($editavel)
                                    <a href="{{ route('demandas.edit', $demanda) }}" class="text-indigo-600 hover:underline">Editar</a>
                                    <form action="{{ route('demandas.enviar', $demanda) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-slate-700 hover:underline">Enviar</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-3 py-4 text-sm text-slate-500">Nenhuma demanda cadastrada.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
