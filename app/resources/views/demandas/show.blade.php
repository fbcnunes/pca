<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-sm text-slate-500">Minhas demandas</p>
                <h2 class="font-semibold text-xl text-slate-900">Detalhe da demanda</h2>
            </div>
            <a href="{{ route('demandas.index') }}" class="text-sm text-slate-600 hover:text-slate-900">Voltar</a>
        </div>
    </x-slot>

    <div class="py-8 px-6 space-y-6">
        <div class="bg-white shadow-sm rounded-lg p-6 space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-sm text-slate-500">Status</div>
                    <div class="text-lg font-semibold">
                        @if ($status?->nome === 'Enviada para validação' && $demanda->unidadeValidacao)
                            Enviado para {{ $demanda->unidadeValidacao->sigla }}
                        @else
                            {{ $status?->nome }}
                        @endif
                    </div>
                </div>
                <div class="text-sm text-slate-500">
                    Ciclo: {{ $ciclo?->exercicio }} ({{ $ciclo?->status }})<br>
                    Unidade: {{ $unidade?->sigla ?? $unidade?->nome }}
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Título</div>
                    <div class="text-sm font-semibold text-slate-900">{{ $demanda->titulo }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Área responsável (quando diferente)</div>
                    <div class="text-sm text-slate-800">{{ $demanda->area_responsavel ?? '—' }}</div>
                </div>
            </div>

            <div>
                <div class="text-xs uppercase tracking-wide text-slate-500">Descrição</div>
                <div class="text-sm text-slate-800 whitespace-pre-line">{{ $demanda->descricao }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Tipo</div>
                    <div class="text-sm text-slate-800">{{ $demanda->tipo?->nome ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Natureza</div>
                    <div class="text-sm text-slate-800">{{ $demanda->natureza?->nome ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Categoria</div>
                    <div class="text-sm text-slate-800">{{ $demanda->categoria?->nome ?? '—' }}</div>
                </div>
            </div>

            <div>
                <div class="text-xs uppercase tracking-wide text-slate-500">Justificativa</div>
                <div class="text-sm text-slate-800 whitespace-pre-line">{{ $demanda->justificativa }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Prioridade</div>
                    <div class="text-sm text-slate-800">{{ $demanda->prioridade?->nome ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Quantidade estimada</div>
                    <div class="text-sm text-slate-800">{{ $demanda->quantidade_estimada ?? '—' }}</div>
                </div>
            </div>

            <div>
                <div class="text-xs uppercase tracking-wide text-slate-500">Escopo básico / Observações</div>
                <div class="text-sm text-slate-800 whitespace-pre-line">{{ $demanda->escopo_basico ?? '—' }}</div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Mês de necessidade</div>
                    <div class="text-sm text-slate-800">{{ $demanda->mes_necessidade ?? '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Justificativa do prazo</div>
                    <div class="text-sm text-slate-800 whitespace-pre-line">{{ $demanda->justificativa_prazo ?? '—' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Valor estimado</div>
                    <div class="text-sm text-slate-800">{{ $demanda->valor_estimado ? 'R$ '.number_format($demanda->valor_estimado, 2, ',', '.') : '—' }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Fonte da estimativa</div>
                    <div class="text-sm text-slate-800">{{ $demanda->fonte_estimativa ?? '—' }}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Responsável</div>
                    <div class="text-sm text-slate-800">{{ $demanda->responsavel_nome }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Cargo/Função</div>
                    <div class="text-sm text-slate-800">{{ $demanda->responsavel_cargo }}</div>
                </div>
                <div>
                    <div class="text-xs uppercase tracking-wide text-slate-500">Contato institucional</div>
                    <div class="text-sm text-slate-800">{{ $demanda->responsavel_contato }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
