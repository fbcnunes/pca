<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Minhas demandas</p>
                <h2 class="font-semibold text-xl text-slate-900">{{ isset($demanda) ? 'Editar demanda' : 'Nova demanda' }}</h2>
            </div>
            <a href="{{ $voltar ?? route('demandas.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Voltar</a>
        </div>
    </x-slot>

    @php
        $model = $demanda ?? null;
        $meses = ['Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho', 'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'];
    @endphp

    <div class="py-8 px-6">
        <form method="POST" action="{{ isset($demanda) ? route('demandas.update', $demanda) : route('demandas.store') }}" class="bg-white shadow-sm rounded-lg p-6 space-y-6">
            @csrf
            @if (isset($demanda))
                @method('PUT')
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Ciclo</label>
                    <select name="ciclo_id" class="mt-1 w-full rounded border-slate-300" required>
                        <option value="">Selecione</option>
                        @foreach ($ciclos as $ciclo)
                            <option value="{{ $ciclo->id }}" @selected(old('ciclo_id', $model?->ciclo_id) == $ciclo->id)>{{ $ciclo->exercicio }} ({{ $ciclo->status }})</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Unidade Organizacional</label>
                    <input type="hidden" name="unidade_id" value="{{ $unidade?->id }}">
                    <input type="text" class="mt-1 w-full rounded border-slate-300 bg-slate-100" value="{{ $unidade?->sigla ?? $unidade?->nome }}" disabled>
                    <p class="text-xs text-slate-500 mt-1">Vínculo do usuário</p>
                </div>
                <div class="flex items-center gap-2 mt-6">
                    <input type="checkbox" name="gabinete_obrigatorio" value="1" @checked(old('gabinete_obrigatorio', $model?->gabinete_obrigatorio)) class="rounded border-slate-300">
                    <label class="text-sm text-slate-700">Covalidação do Gabinete obrigatória</label>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Área responsável (quando diferente da unidade)</label>
                    <input type="text" name="area_responsavel" value="{{ old('area_responsavel', $model?->area_responsavel) }}" class="mt-1 w-full rounded border-slate-300">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Título</label>
                <input type="text" name="titulo" value="{{ old('titulo', $model?->titulo) }}" class="mt-1 w-full rounded border-slate-300" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Descrição</label>
                <textarea name="descricao" rows="3" class="mt-1 w-full rounded border-slate-300" required>{{ old('descricao', $model?->descricao) }}</textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Tipo</label>
                    <select name="tipo_id" class="mt-1 w-full rounded border-slate-300" required>
                        <option value="">Selecione</option>
                        @foreach ($tipos as $tipo)
                            <option value="{{ $tipo->id }}" @selected(old('tipo_id', $model?->tipo_id) == $tipo->id)>{{ $tipo->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Natureza</label>
                    <select name="natureza_id" class="mt-1 w-full rounded border-slate-300" required>
                        <option value="">Selecione</option>
                        @foreach ($naturezas as $natureza)
                            <option value="{{ $natureza->id }}" @selected(old('natureza_id', $model?->natureza_id) == $natureza->id)>{{ $natureza->nome }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Categoria</label>
                    <select name="categoria_id" class="mt-1 w-full rounded border-slate-300">
                        <option value="">Selecione</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" @selected(old('categoria_id', $model?->categoria_id) == $categoria->id)>{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-slate-700">Justificativa</label>
                <textarea name="justificativa" rows="3" class="mt-1 w-full rounded border-slate-300" required>{{ old('justificativa', $model?->justificativa) }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-slate-700">Prioridade</label>
                <select name="prioridade_id" class="mt-1 w-full rounded border-slate-300" required>
                    <option value="">Selecione</option>
                    @foreach ($prioridades as $prioridade)
                        <option value="{{ $prioridade->id }}" @selected(old('prioridade_id', $model?->prioridade_id) == $prioridade->id)>{{ $prioridade->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Quantidade estimada</label>
                    <input type="text" name="quantidade_estimada" value="{{ old('quantidade_estimada', $model?->quantidade_estimada) }}" class="mt-1 w-full rounded border-slate-300">
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Escopo básico / Observações</label>
                    <textarea name="escopo_basico" rows="2" class="mt-1 w-full rounded border-slate-300">{{ old('escopo_basico', $model?->escopo_basico) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Mês de necessidade</label>
                    <select name="mes_necessidade" class="mt-1 w-full rounded border-slate-300">
                        <option value="">Selecione</option>
                        @foreach ($meses as $mes)
                            <option value="{{ $mes }}" @selected(old('mes_necessidade', $model?->mes_necessidade) === $mes)>{{ $mes }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Justificativa do prazo</label>
                    <textarea name="justificativa_prazo" rows="2" class="mt-1 w-full rounded border-slate-300">{{ old('justificativa_prazo', $model?->justificativa_prazo) }}</textarea>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Valor estimado</label>
                    <input type="text" name="valor_estimado" value="{{ old('valor_estimado', $model?->valor_estimado) }}" class="mt-1 w-full rounded border-slate-300" placeholder="0,00" data-currency>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Fonte da estimativa</label>
                    <input type="text" name="fonte_estimativa" value="{{ old('fonte_estimativa', $model?->fonte_estimativa) }}" class="mt-1 w-full rounded border-slate-300">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-slate-700">Responsável</label>
                    <input type="text" name="responsavel_nome" value="{{ old('responsavel_nome', $model?->responsavel_nome) }}" class="mt-1 w-full rounded border-slate-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Cargo/Função</label>
                    <input type="text" name="responsavel_cargo" value="{{ old('responsavel_cargo', $model?->responsavel_cargo) }}" class="mt-1 w-full rounded border-slate-300" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-slate-700">Contato institucional</label>
                    <input type="text" name="responsavel_contato" value="{{ old('responsavel_contato', $model?->responsavel_contato) }}" class="mt-1 w-full rounded border-slate-300" required>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <button type="submit" class="px-4 py-2 bg-slate-900 text-white rounded">
                    {{ isset($demanda) ? 'Salvar' : 'Salvar rascunho' }}
                </button>
            </div>
        </form>
    </div>
    <script>
        const formatCurrency = (value) => {
            const digits = value.replace(/\D/g, '');
            if (!digits) return '';
            const number = parseInt(digits, 10);
            return (number / 100).toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        };

        document.addEventListener('DOMContentLoaded', () => {
            const input = document.querySelector('[data-currency]');
            if (!input) return;
            input.value = formatCurrency(input.value);
            input.addEventListener('input', (event) => {
                const cursorEnd = event.target.value.length;
                event.target.value = formatCurrency(event.target.value);
                event.target.setSelectionRange(cursorEnd, cursorEnd);
            });
        });
    </script>
</x-app-layout>
