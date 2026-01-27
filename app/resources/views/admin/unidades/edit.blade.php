<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-sm text-slate-500">Administração</p>
            <h2 class="font-semibold text-xl text-slate-900">Editar unidade</h2>
            <p class="text-sm text-slate-500">{{ $unidade->nome }}</p>
        </div>
    </x-slot>

    <div class="py-8 px-6">
        <div class="bg-white shadow-sm rounded-lg p-6">
            <form method="POST" action="{{ route('admin.unidades.update', $unidade) }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nome</label>
                    <input type="text" name="nome" value="{{ $unidade->nome }}" class="mt-1 w-full rounded border-slate-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Sigla</label>
                    <input type="text" name="sigla" value="{{ $unidade->sigla }}" class="mt-1 w-full rounded border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Código</label>
                    <input type="text" name="codigo" value="{{ $unidade->codigo }}" class="mt-1 w-full rounded border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tipo</label>
                    <select name="tipo" class="mt-1 w-full rounded border-slate-300" required>
                        @foreach (['unidade','diretoria','secretaria','gabinete'] as $tipo)
                            <option value="{{ $tipo }}" @selected($unidade->tipo === $tipo)>{{ ucfirst($tipo) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Unidade Pai</label>
                    <select name="parent_id" class="mt-1 w-full rounded border-slate-300">
                        <option value="">Raiz</option>
                        @foreach ($pais as $p)
                            <option value="{{ $p->id }}" @selected($unidade->parent_id === $p->id)>{{ $p->sigla ?? $p->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" name="ativo" value="1" @checked($unidade->ativo) class="rounded border-slate-300">
                    <label class="text-sm text-slate-700">Ativa</label>
                </div>
                <div class="md:col-span-2 flex justify-end gap-3">
                    <a href="{{ route('admin.unidades.index') }}" class="px-4 py-2 text-slate-600">Cancelar</a>
                    <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
