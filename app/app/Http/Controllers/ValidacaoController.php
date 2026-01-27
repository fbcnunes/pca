<?php

namespace App\Http\Controllers;

use App\Models\Demanda;
use App\Models\StatusDemanda;
use App\Models\TramitacaoDemanda;
use App\Models\ValidacaoDemanda;
use App\Models\UnidadeOrganizacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ValidacaoController extends Controller
{
    public function index()
    {
        $demDiretoria = collect();
        $demGabinete = collect();

        $unidadesPermitidas = $this->unidadesValidador();
        $unidadesUsuario = $this->unidadesUsuarioIds();

        if (Auth::user()->temPermissao('demandas.validar') && $unidadesPermitidas) {
            $demDiretoria = Demanda::whereIn('unidade_id', $unidadesPermitidas)
                ->with(['status', 'unidade', 'unidadeValidacao'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        if (Auth::user()->temPermissao('demandas.covalidar') && $unidadesPermitidas) {
            $demGabinete = Demanda::whereIn('unidade_id', $unidadesPermitidas)
                ->with(['status', 'unidade', 'unidadeValidacao'])
                ->orderBy('created_at', 'desc')
                ->get();
        }

        return view('validacao.index', [
            'demDiretoria' => $demDiretoria,
            'demGabinete' => $demGabinete,
            'unidadesPermitidas' => $unidadesPermitidas,
            'unidadesUsuario' => $unidadesUsuario,
        ]);
    }

    public function decidir(Request $request, Demanda $demanda)
    {
        $this->autorizarEtapa($demanda);

        $dados = $request->validate([
            'decisao' => 'required|in:aprovado,devolvido',
            'comentario' => 'required|string',
        ]);

        $etapa = $demanda->unidadeValidacao?->sigla ?? 'validacao';

        ValidacaoDemanda::create([
            'demanda_id' => $demanda->id,
            'etapa' => $etapa,
            'decisor_id' => Auth::id(),
            'decisao' => $dados['decisao'],
            'comentario' => $dados['comentario'],
        ]);

        $this->transicionarStatus($demanda, $dados['decisao'], $dados['comentario']);

        return redirect()->route('validacao.index')->with('sucesso', 'Decisão registrada.');
    }

    protected function transicionarStatus(Demanda $demanda, string $decisao, string $comentario): void
    {
        if ($decisao === 'devolvido') {
            $unidadeAtual = $demanda->unidadeValidacao ?? UnidadeOrganizacional::find($demanda->unidade_validacao_id);
            $ultimaRecepcaoValida = $demanda->tramitacoes()
                ->where('unidade_destino_id', $unidadeAtual?->id)
                ->whereIn('acao', ['enviar', 'aprovar'])
                ->latest()
                ->first();
            $destino = $ultimaRecepcaoValida?->unidade_origem_id;

            if ($destino && $destino !== $demanda->unidade_id) {
                $demanda->update([
                    'status_demanda_id' => $this->statusId('Enviada para validação'),
                    'unidade_validacao_id' => $destino,
                ]);
            } else {
                $demanda->update([
                    'status_demanda_id' => $this->statusId('Devolvida para ajustes'),
                    'unidade_validacao_id' => null,
                ]);
            }

            TramitacaoDemanda::create([
                'demanda_id' => $demanda->id,
                'unidade_origem_id' => $unidadeAtual?->id,
                'unidade_destino_id' => $destino,
                'acao' => 'devolver',
                'status_resultante_id' => $demanda->status_demanda_id,
                'user_id' => Auth::id(),
                'comentario' => $comentario,
            ]);
            return;
        }

        $unidadeAtual = $demanda->unidadeValidacao ?? UnidadeOrganizacional::find($demanda->unidade_validacao_id);
        if (! $unidadeAtual) {
            $demanda->update([
                'status_demanda_id' => $this->statusId('Validada'),
                'unidade_validacao_id' => null,
            ]);
            return;
        }

        $pai = $unidadeAtual->parent ?? ($unidadeAtual->parent_id ? UnidadeOrganizacional::find($unidadeAtual->parent_id) : null);
        if (! $pai) {
            $demanda->update([
                'status_demanda_id' => $this->statusId('Validada'),
                'unidade_validacao_id' => null,
            ]);
            TramitacaoDemanda::create([
                'demanda_id' => $demanda->id,
                'unidade_origem_id' => $unidadeAtual->id,
                'unidade_destino_id' => null,
                'acao' => 'aprovar',
                'status_resultante_id' => $demanda->status_demanda_id,
                'user_id' => Auth::id(),
                'comentario' => $comentario,
            ]);
            return;
        }

        if ($pai->tipo === 'secretaria') {
            $demanda->update([
                'status_demanda_id' => $this->statusId('Validada'),
                'unidade_validacao_id' => null,
            ]);
            TramitacaoDemanda::create([
                'demanda_id' => $demanda->id,
                'unidade_origem_id' => $unidadeAtual->id,
                'unidade_destino_id' => null,
                'acao' => 'aprovar',
                'status_resultante_id' => $demanda->status_demanda_id,
                'user_id' => Auth::id(),
                'comentario' => $comentario,
            ]);
            return;
        }

        $demanda->update([
            'status_demanda_id' => $this->statusId('Enviada para validação'),
            'unidade_validacao_id' => $pai->id,
            'unidade_envio_id' => $unidadeAtual->id,
        ]);

        TramitacaoDemanda::create([
            'demanda_id' => $demanda->id,
            'unidade_origem_id' => $unidadeAtual->id,
            'unidade_destino_id' => $pai->id,
            'acao' => 'aprovar',
            'status_resultante_id' => $demanda->status_demanda_id,
            'user_id' => Auth::id(),
            'comentario' => $comentario,
        ]);
    }

    protected function autorizarEtapa(Demanda $demanda): void
    {
        $status = $demanda->status?->nome;
        $permissoes = Auth::user()->perfis()->with('permissoes')->get()->flatMap->permissoes->pluck('chave')->unique();
        $unidadesUsuario = $this->unidadesUsuarioIds();

        if ($status !== 'Enviada para validação' || ! $demanda->unidade_validacao_id) {
            abort(403);
        }

        $unidadeValidacao = $demanda->unidadeValidacao ?? UnidadeOrganizacional::find($demanda->unidade_validacao_id);
        $precisaCovalidar = $unidadeValidacao?->tipo === 'gabinete';
        $permissaoNecessaria = $precisaCovalidar ? 'demandas.covalidar' : 'demandas.validar';

        if (! $permissoes->contains($permissaoNecessaria)) {
            abort(403);
        }

        if ($unidadesUsuario && ! in_array($demanda->unidade_validacao_id, $unidadesUsuario)) {
            abort(403);
        }
    }

    protected function statusIds(array $nomes): array
    {
        return StatusDemanda::whereIn('nome', $nomes)->pluck('id')->toArray();
    }

    protected function statusId(string $nome): int
    {
        return StatusDemanda::where('nome', $nome)->value('id');
    }

    protected function unidadesValidador(): array
    {
        $unidadeIds = Auth::user()?->perfis()
            ->wherePivot('ativo', true)
            ->whereNotNull('perfil_usuario.unidade_id')
            ->pluck('perfil_usuario.unidade_id')
            ->unique()
            ->toArray() ?? [];

        $ids = [];
        foreach ($unidadeIds as $id) {
            $unidade = UnidadeOrganizacional::with('children')->find($id);
            if ($unidade) {
                $ids = array_merge($ids, [$unidade->id], $unidade->descendantsIds());
            }
        }

        return array_values(array_unique($ids));
    }

    protected function unidadesUsuarioIds(): array
    {
        return Auth::user()?->perfis()
            ->wherePivot('ativo', true)
            ->whereNotNull('perfil_usuario.unidade_id')
            ->pluck('perfil_usuario.unidade_id')
            ->unique()
            ->toArray() ?? [];
    }
}
