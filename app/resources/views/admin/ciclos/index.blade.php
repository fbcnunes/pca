<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Administração</p>
                <h2 class="font-semibold text-xl text-slate-900">Ciclos do PCA</h2>
            </div>
        </div>
    </x-slot>

    <div class="py-8 px-6 space-y-6">
        <div class="bg-white shadow-sm rounded-lg">
            <div class="border-b px-6 py-4">
                <p class="text-sm text-slate-500">Cadastrar novo ciclo</p>
            </div>
            <div class="p-6">
                <form method="POST" action="{{ route('admin.ciclos.store') }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Exercício</label>
                        <input type="number" name="exercicio" class="mt-1 w-full rounded border-slate-300" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Status</label>
                        <select name="status" class="mt-1 w-full rounded border-slate-300" required>
                            @foreach ($statusOptions as $status)
                                <option value="{{ $status }}" @selected(old('status') === $status)>{{ $status }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Data início</label>
                        <input type="date" name="data_inicio" class="mt-1 w-full rounded border-slate-300">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-slate-700">Data fim</label>
                        <input type="date" name="data_fim" class="mt-1 w-full rounded border-slate-300">
                    </div>
                    <div class="md:col-span-2 flex justify-end">
                        <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Salvar</button>
                    </div>
                </form>
            </div>
        </div>

        <div class="bg-white shadow-sm rounded-lg">
            <div class="border-b px-6 py-4">
                <p class="text-sm text-slate-500">Ciclos cadastrados</p>
            </div>
            <div class="p-6 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50 text-left text-xs font-semibold text-slate-600">
                        <tr>
                            <th class="px-3 py-2">Exercício</th>
                            <th class="px-3 py-2">Início</th>
                            <th class="px-3 py-2">Fim</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2 text-center">Ativo</th>
                            <th class="px-3 py-2 text-right">Ações</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 text-sm text-slate-800">
                        @foreach ($ciclos as $ciclo)
                            <tr>
                                <td class="px-3 py-2 font-medium">{{ $ciclo->exercicio }}</td>
                                <td class="px-3 py-2">{{ $ciclo->data_inicio }}</td>
                                <td class="px-3 py-2">{{ $ciclo->data_fim }}</td>
                                <td class="px-3 py-2 capitalize">{{ $ciclo->status }}</td>
                                <td class="px-3 py-2 text-center">
                                    <span class="inline-flex items-center px-2 py-1 text-xs font-semibold rounded-full {{ $ciclo->ativo ? 'bg-emerald-50 text-emerald-700' : 'bg-rose-50 text-rose-700' }}">
                                        {{ $ciclo->ativo ? 'Ativo' : 'Inativo' }}
                                    </span>
                                </td>
                                <td class="px-3 py-2 text-right space-x-2">
                                    <a href="{{ route('admin.ciclos.edit', $ciclo) }}" class="text-sm text-indigo-600 hover:underline">Editar</a>
                                    <form action="{{ route('admin.ciclos.ativar', $ciclo) }}" method="POST" class="inline">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="text-sm text-slate-600 hover:underline">Ativar</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @if ($ciclos->isEmpty())
                    <p class="text-sm text-slate-500">Nenhum ciclo cadastrado.</p>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
