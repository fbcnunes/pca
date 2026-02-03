<?php

namespace App\Http\Controllers;

use App\Models\CatalogoCategoria;
use App\Models\CatalogoNatureza;
use App\Models\CatalogoPrioridade;
use App\Models\CatalogoTipoDemanda;
use App\Models\CicloPca;
use App\Models\Demanda;
use App\Models\HistoricoDemanda;
use App\Models\StatusDemanda;
use App\Models\TramitacaoDemanda;
use App\Models\UnidadeOrganizacional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DemandasController extends Controller
{
    public function index()
    {
        $this->exigePermissaoCriar();

        $demandas = Demanda::where('demandante_id', Auth::id())
            ->orderByDesc('created_at')
            ->with(['status', 'unidadeValidacao', 'ciclo'])
            ->get();

        return view('demandas.index', compact('demandas'));
    }

    public function create()
    {
        $this->exigePermissaoCriar();

        return view('demandas.form', array_merge(
            $this->options(),
            ['voltar' => route('demandas.index')]
        ));
    }

    public function store(Request $request)
    {
        $this->exigePermissaoCriar();

        $dados = $this->normalizarValorEstimado($this->validateData($request));
        $dados['demandante_id'] = Auth::id();
        $dados['unidade_id'] = $this->unidadeUsuario();
        $dados['status_demanda_id'] = $this->statusRascunho()->id;
        $dados['unidade_validacao_id'] = null;
        $dados['unidade_envio_id'] = null;

        Demanda::create($dados);

        return redirect()->route('demandas.index')->with('sucesso', 'Demanda salva em rascunho.');
    }

    public function edit(Demanda $demanda)
    {
        $this->autorizar($demanda);

        $usuarioEValidador = $demanda->demandante_id !== Auth::id() && $this->podeValidarDemanda($demanda);
        $somenteAjuste = $this->somenteAjustePermitido($demanda);
        $versaoId = request()->get('versao');
        $usuarioConsolidador = $this->podeConsolidarDemanda($demanda);

        if ($somenteAjuste && $demanda->demandante_id !== Auth::id() && ! $this->podeConsolidarDemanda($demanda)) {
            abort(403);
        }

        $voltar = $usuarioEValidador
            ? route('validacao.index')
            : ($usuarioConsolidador && $versaoId ? route('admin.pca.index', ['versao' => $versaoId]) : route('demandas.index'));

        return view('demandas.form', array_merge(
            $this->options(),
            [
                'demanda' => $demanda,
                'voltar' => $voltar,
                'somenteAjuste' => $somenteAjuste,
                'versao' => $versaoId,
            ]
        ));
    }

    public function update(Request $request, Demanda $demanda)
    {
        $this->autorizar($demanda);
        $usuarioEValidador = $demanda->demandante_id !== Auth::id() && $this->podeValidarDemanda($demanda);
        $somenteAjuste = $this->somenteAjustePermitido($demanda);
        $usuarioConsolidador = $this->podeConsolidarDemanda($demanda);
        $versaoId = $request->get('versao');

        if ($somenteAjuste) {
            abort_if($demanda->demandante_id !== Auth::id() && ! $this->podeConsolidarDemanda($demanda), 403);
            $dados = $this->normalizarValorEstimado($this->validateAjusteData($request));
            $demanda->update($dados);

            if ($demanda->status?->nome === 'Validada') {
                $demanda->update(['status_demanda_id' => $this->statusValidadaAlterada()->id]);
            }
        } else {
            $dados = $this->normalizarValorEstimado($this->validateData($request));
            $dados['unidade_id'] = $demanda->demandante_id === Auth::id() ? $this->unidadeUsuario() : $demanda->unidade_id;
            $demanda->update($dados);
        }

        HistoricoDemanda::create([
            'demanda_id' => $demanda->id,
            'user_id' => Auth::id(),
            'status_anterior_id' => $demanda->status_demanda_id,
            'status_novo_id' => $demanda->status_demanda_id,
            'perfil_decisor' => $this->perfilAtualSlug(),
            'comentario' => 'Alteração de dados da demanda',
        ]);

        if ($usuarioEValidador) {
            return redirect()->route('validacao.index')->with('sucesso', 'Demanda atualizada.');
        }

        if ($usuarioConsolidador && $versaoId) {
            return redirect()->route('admin.pca.index', ['versao' => $versaoId])->with('sucesso', 'Demanda atualizada.');
        }

        return redirect()->route('demandas.index')->with('sucesso', 'Demanda atualizada.');
    }

    public function show(Demanda $demanda)
    {
        $this->autorizar($demanda);
        $demanda->load(['status', 'tipo', 'natureza', 'categoria', 'prioridade', 'ciclo', 'unidade', 'unidadeValidacao']);

        return view('demandas.show', [
            'demanda' => $demanda,
            'unidade' => $demanda->unidade,
            'ciclo' => $demanda->ciclo,
            'status' => $demanda->status,
        ]);
    }

    public function enviar(Demanda $demanda)
    {
        $this->autorizar($demanda);
        $unidadeValidacaoId = $this->unidadePaiId($demanda->unidade_id);
        $novoStatus = $unidadeValidacaoId ? $this->statusEnviada()->id : $this->statusValidada()->id;
        $demanda->update([
            'status_demanda_id' => $novoStatus,
            'unidade_validacao_id' => $unidadeValidacaoId,
            'unidade_envio_id' => $demanda->unidade_id,
        ]);

        TramitacaoDemanda::create([
            'demanda_id' => $demanda->id,
            'unidade_origem_id' => $demanda->unidade_id,
            'unidade_destino_id' => $unidadeValidacaoId,
            'acao' => 'enviar',
            'status_resultante_id' => $novoStatus,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('demandas.index')->with('sucesso', 'Demanda enviada para validação.');
    }

    protected function autorizar(Demanda $demanda): void
    {
        if ($demanda->demandante_id === Auth::id()) {
            return;
        }

        if ($this->podeValidarDemanda($demanda)) {
            return;
        }

        if ($this->podeConsolidarDemanda($demanda)) {
            return;
        }

        abort(403);
    }

    protected function validateData(Request $request): array
    {
        return $request->validate([
            'ciclo_id' => 'required|exists:ciclo_pcas,id',
            'gabinete_obrigatorio' => 'boolean',
            'area_responsavel' => 'nullable|string|max:255',
            'titulo' => 'required|string|max:255',
            'descricao' => 'required|string',
            'tipo_id' => 'required|exists:catalogo_tipo_demandas,id',
            'natureza_id' => 'required|exists:catalogo_naturezas,id',
            'categoria_id' => 'nullable|exists:catalogo_categorias,id',
            'justificativa' => 'required|string',
            'prioridade_id' => 'required|exists:catalogo_prioridades,id',
            'quantidade_estimada' => 'nullable|string|max:255',
            'escopo_basico' => 'nullable|string',
            'mes_necessidade' => 'nullable|in:Janeiro,Fevereiro,Março,Abril,Maio,Junho,Julho,Agosto,Setembro,Outubro,Novembro,Dezembro',
            'justificativa_prazo' => 'nullable|string',
            'valor_estimado' => ['nullable', 'regex:/^(\\d{1,3}(\\.\\d{3})*|\\d+)(,\\d{2})?$/'],
            'fonte_estimativa' => 'nullable|string|max:255',
            'responsavel_nome' => 'required|string|max:255',
            'responsavel_cargo' => 'required|string|max:255',
            'responsavel_contato' => 'required|string|max:255',
        ], [
            'mes_necessidade.in' => 'Selecione um mês válido.',
            'valor_estimado.regex' => 'Informe o valor no formato 0,00 (ex.: 1.234,56).',
        ]);
    }

    protected function validateAjusteData(Request $request): array
    {
        return $request->validate([
            'quantidade_estimada' => 'nullable|string|max:255',
            'valor_estimado' => ['nullable', 'regex:/^(\\d{1,3}(\\.\\d{3})*|\\d+)(,\\d{2})?$/'],
        ], [
            'valor_estimado.regex' => 'Informe o valor no formato 0,00 (ex.: 1.234,56).',
        ]);
    }

    protected function options(): array
    {
        $unidadeId = $this->unidadeUsuario();
        return [
            'ciclos' => CicloPca::orderByDesc('exercicio')->get(),
            'unidade' => UnidadeOrganizacional::find($unidadeId),
            'categorias' => CatalogoCategoria::where('ativo', true)->orderBy('ordem')->get(),
            'prioridades' => CatalogoPrioridade::where('ativo', true)->orderBy('ordem')->get(),
            'tipos' => CatalogoTipoDemanda::where('ativo', true)->orderBy('ordem')->get(),
            'naturezas' => CatalogoNatureza::where('ativo', true)->orderBy('ordem')->get(),
        ];
    }

    protected function statusRascunho(): StatusDemanda
    {
        return StatusDemanda::where('nome', 'Rascunho')->firstOrFail();
    }

    protected function statusEnviada(): StatusDemanda
    {
        return StatusDemanda::where('nome', 'Enviada para validação')->firstOrFail();
    }

    protected function statusValidada(): StatusDemanda
    {
        return StatusDemanda::where('nome', 'Validada')->firstOrFail();
    }

    protected function statusValidadaAlterada(): StatusDemanda
    {
        return StatusDemanda::where('nome', 'Validada c/ alteração')->firstOrFail();
    }

    protected function normalizarValorEstimado(array $dados): array
    {
        if (! array_key_exists('valor_estimado', $dados) || $dados['valor_estimado'] === null || $dados['valor_estimado'] === '') {
            return $dados;
        }

        $valor = str_replace('.', '', $dados['valor_estimado']);
        $valor = str_replace(',', '.', $valor);
        $dados['valor_estimado'] = $valor;

        return $dados;
    }

    protected function unidadePaiId(int $unidadeId): ?int
    {
        return UnidadeOrganizacional::where('id', $unidadeId)->value('parent_id');
    }

    protected function unidadeUsuario(): int
    {
        $unidadeId = Auth::user()?->perfis()
            ->wherePivot('ativo', true)
            ->whereNotNull('perfil_usuario.unidade_id')
            ->value('perfil_usuario.unidade_id');

        abort_if(! $unidadeId, 403, 'Usuário sem unidade vinculada.');

        return $unidadeId;
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

    protected function podeValidarDemanda(Demanda $demanda): bool
    {
        $status = $demanda->status?->nome;
        $temPermValidar = Auth::user()?->temPermissao('demandas.validar');
        $temPermCovalidar = Auth::user()?->temPermissao('demandas.covalidar');
        $unidadesUsuario = $this->unidadesUsuarioIds();
        $unidadeValidacaoId = $demanda->unidade_validacao_id;

        if ($status !== 'Enviada para validação' || ! $unidadeValidacaoId) {
            return false;
        }

        $unidadeValidacao = $demanda->unidadeValidacao ?? UnidadeOrganizacional::find($unidadeValidacaoId);
        $precisaCovalidar = $unidadeValidacao?->tipo === 'gabinete';
        $temPermissaoEtapa = $precisaCovalidar ? $temPermCovalidar : $temPermValidar;

        return $temPermissaoEtapa && in_array($unidadeValidacaoId, $unidadesUsuario);
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

    protected function somenteAjustePermitido(Demanda $demanda): bool
    {
        $status = $demanda->status?->nome;
        return in_array($status, ['Validada', 'Validada c/ alteração']);
    }

    protected function podeConsolidarDemanda(Demanda $demanda): bool
    {
        $status = $demanda->status?->nome;
        return Auth::user()?->temPermissao('pca.consolidar')
            && in_array($status, ['Validada', 'Validada c/ alteração'], true);
    }

    protected function exigePermissaoCriar(): void
    {
        abort_unless(Auth::user()?->temPermissao('demandas.criar'), 403);
    }

    protected function perfilAtualSlug(): ?string
    {
        return Auth::user()?->perfis()->wherePivot('ativo', true)->first()?->slug;
    }
}
