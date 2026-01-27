<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Administração</p>
                <h2 class="font-semibold text-xl text-slate-900">{{ isset($usuario) ? 'Editar usuário' : 'Novo usuário' }}</h2>
            </div>
            <a href="{{ route('admin.usuarios.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Voltar</a>
        </div>
    </x-slot>

    @php $model = $usuario ?? null; @endphp

    <div class="py-8 px-6">
        <form method="POST" action="{{ isset($usuario) ? route('admin.usuarios.update', $usuario) : route('admin.usuarios.store') }}" class="bg-white shadow-sm rounded-lg p-6 space-y-6">
            @csrf
            @if(isset($usuario))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Nome</label>
                    <input type="text" name="name" value="{{ old('name', $model?->name) }}" class="mt-1 w-full rounded border-slate-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $model?->email) }}" class="mt-1 w-full rounded border-slate-300" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Senha {{ isset($usuario) ? '(deixe em branco para manter)' : '' }}</label>
                    <input type="password" name="password" class="mt-1 w-full rounded border-slate-300" {{ isset($usuario) ? '' : 'required' }}>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Unidade Organizacional</label>
                    <select name="unidade_id" class="mt-1 w-full rounded border-slate-300" required>
                        <option value="">Selecione</option>
                        @foreach ($unidades as $unidade)
                            <option value="{{ $unidade->id }}" @selected(old('unidade_id', $model?->perfis->first()->pivot->unidade_id ?? null) == $unidade->id)>{{ $unidade->sigla ?? $unidade->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Perfis</label>
                <div class="flex flex-wrap gap-2 mt-2">
                    @foreach ($perfis as $perfil)
                        <label class="inline-flex items-center gap-2 text-sm px-2 py-1 rounded border {{ $model?->perfis->contains($perfil->id) ? 'bg-indigo-50 border-indigo-200' : 'border-slate-200' }}">
                            <input type="checkbox" name="perfis[]" value="{{ $perfil->id }}" class="rounded border-slate-300" @checked(in_array($perfil->id, old('perfis', $model?->perfis->pluck('id')->toArray() ?? [])))>
                            <span>{{ $perfil->nome }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">Salvar</button>
            </div>
        </form>
    </div>
</x-app-layout>
