<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Demanda;
use App\Models\PcaItem;
use App\Models\PcaVersao;
use App\Models\StatusDemanda;
use App\Models\CicloPca;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConsolidacaoController extends Controller
{
    public function index(Request $request)
    {
        $cicloAtivo = CicloPca::where('ativo', true)->orderByDesc('exercicio')->first();
        $versoes = PcaVersao::with('ciclo')->orderByDesc('created_at')->get();
        $versaoSelecionada = $request->get('versao')
            ? PcaVersao::with(['itens.demanda.unidade'])->find($request->get('versao'))
            : $versoes->first();

        $demandasValidadas = collect();
        $itensConsolidados = collect();
        if ($versaoSelecionada) {
            $statusIds = $this->statusIdsValidadas();
            $demandasValidadas = Demanda::with(['unidade', 'status', 'ciclo'])
                ->where('ciclo_id', $versaoSelecionada->ciclo_id)
                ->whereIn('status_demanda_id', $statusIds)
                ->orderBy('updated_at', 'desc')
                ->get();

            $itensConsolidados = $versaoSelecionada->itens()
                ->with(['demanda.unidade', 'demanda.status'])
                ->where('incluido', true)
                ->get();
        }

        return view('admin.pca.index', [
            'cicloAtivo' => $cicloAtivo,
            'versoes' => $versoes,
            'versaoSelecionada' => $versaoSelecionada,
            'demandasValidadas' => $demandasValidadas,
            'itensConsolidados' => $itensConsolidados,
        ]);
    }

    public function storeVersao(Request $request)
    {
        $dados = $request->validate([
            'ciclo_id' => 'required|exists:ciclo_pcas,id',
            'nome' => 'required|string|max:255',
            'status' => 'required|string|max:50',
            'observacao' => 'nullable|string',
            'incluir_todas' => 'nullable|boolean',
        ]);

        $versao = PcaVersao::create([
            'ciclo_id' => $dados['ciclo_id'],
            'nome' => $dados['nome'],
            'status' => $dados['status'],
            'observacao' => $dados['observacao'] ?? null,
            'criado_por' => Auth::id(),
        ]);

        if (! empty($dados['incluir_todas'])) {
            $statusIds = $this->statusIdsConsolidacaoBase();
            $demandas = Demanda::where('ciclo_id', $versao->ciclo_id)
                ->whereIn('status_demanda_id', $statusIds)
                ->get();
            foreach ($demandas as $demanda) {
                PcaItem::firstOrCreate([
                    'versao_id' => $versao->id,
                    'demanda_id' => $demanda->id,
                ], [
                    'incluido' => true,
                ]);
                $this->atualizarStatusDemanda($demanda);
            }
        }

        return redirect()->route('admin.pca.index', ['versao' => $versao->id])->with('sucesso', 'Versão criada.');
    }

    public function toggleItem(Request $request, PcaVersao $versao, Demanda $demanda)
    {
        abort_if($demanda->ciclo_id !== $versao->ciclo_id, 422, 'Demanda não pertence ao ciclo da versão.');

        $item = PcaItem::firstOrCreate([
            'versao_id' => $versao->id,
            'demanda_id' => $demanda->id,
        ], [
            'incluido' => true,
        ]);

        $item->update(['incluido' => ! $item->incluido]);
        $this->atualizarStatusDemanda($demanda->fresh(['status']));

        return back()->with('sucesso', 'Item atualizado.');
    }

    public function updateItem(Request $request, PcaItem $item)
    {
        $dados = $request->validate([
            'valor_ajustado' => 'nullable|numeric',
            'observacao' => 'nullable|string',
        ]);

        $item->update($dados);

        return back()->with('sucesso', 'Item atualizado.');
    }

    protected function atualizarStatusDemanda(Demanda $demanda): void
    {
        $statusAtual = $demanda->status?->nome;
        $alterada = in_array($statusAtual, ['Validada c/ alteração', 'Consolidada c/ alteração'], true);
        $temItemIncluido = PcaItem::where('demanda_id', $demanda->id)
            ->where('incluido', true)
            ->exists();

        $novoStatusId = $temItemIncluido
            ? ($alterada ? $this->statusConsolidadaAlteradaId() : $this->statusConsolidadaId())
            : ($alterada ? $this->statusValidadaAlteradaId() : $this->statusValidadaId());

        if ($novoStatusId && $demanda->status_demanda_id !== $novoStatusId) {
            $demanda->update(['status_demanda_id' => $novoStatusId]);
        }
    }

    protected function statusIdsConsolidacao(): array
    {
        return StatusDemanda::whereIn('nome', [
            'Validada',
            'Validada c/ alteração',
            'Consolidada',
            'Consolidada c/ alteração',
        ])->pluck('id')->all();
    }

    protected function statusIdsConsolidacaoBase(): array
    {
        return StatusDemanda::whereIn('nome', [
            'Validada',
            'Validada c/ alteração',
        ])->pluck('id')->all();
    }

    protected function statusIdsValidadas(): array
    {
        return StatusDemanda::whereIn('nome', [
            'Validada',
            'Validada c/ alteração',
        ])->pluck('id')->all();
    }

    protected function statusValidadaId(): ?int
    {
        return StatusDemanda::where('nome', 'Validada')->value('id');
    }

    protected function statusValidadaAlteradaId(): ?int
    {
        return StatusDemanda::where('nome', 'Validada c/ alteração')->value('id');
    }

    protected function statusConsolidadaId(): ?int
    {
        return StatusDemanda::where('nome', 'Consolidada')->value('id');
    }

    protected function statusConsolidadaAlteradaId(): ?int
    {
        return StatusDemanda::where('nome', 'Consolidada c/ alteração')->value('id');
    }
}
