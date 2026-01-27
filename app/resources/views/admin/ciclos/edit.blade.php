<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-slate-500">Administração</p>
            <h2 class="font-semibold text-xl text-slate-900">Editar ciclo</h2>
            <p class="text-sm text-slate-500">Exercício {{ $ciclo->exercicio }}</p>
        </div>
    </x-slot>

    <div class="py-8 px-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('admin.ciclos.update', $ciclo) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700">Data início</label>
                    <input type="date" name="data_inicio" value="{{ $ciclo->data_inicio }}" class="mt-1 w-full rounded border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Data fim</label>
                    <input type="date" name="data_fim" value="{{ $ciclo->data_fim }}" class="mt-1 w-full rounded border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Status</label>
                    <select name="status" class="mt-1 w-full rounded border-slate-300" required>
                        @foreach ($statusOptions as $status)
                            <option value="{{ $status }}" @selected(old('status', $ciclo->status) === $status)>{{ $status }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-slate-700">Observação</label>
                    <textarea name="observacao" class="mt-1 w-full rounded border-slate-300" rows="3">{{ $ciclo->observacao }}</textarea>
                </div>
                <div class="md:col-span-2 flex justify-end gap-3">
                    <a href="{{ route('admin.ciclos.index') }}" class="px-4 py-2 text-slate-600">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
