<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-slate-50 text-slate-900">
        @php
            $user = Auth::user();
            $cicloAtivo = \App\Models\CicloPca::where('ativo', true)->orderByDesc('exercicio')->first();
        @endphp
        <div class="min-h-screen flex">
            <aside class="hidden md:flex w-64 bg-slate-900 text-slate-100 flex-col">
                <div class="px-6 py-5 border-b border-slate-800">
                    <div class="text-xs uppercase tracking-wide text-slate-400">Sistema PCA</div>
                    <div class="text-xl font-semibold">PCA-SEPLAD</div>
                </div>
                <nav class="flex-1 px-4 py-4 space-y-6">
                    <div>
                        <div class="text-xs uppercase tracking-wide text-slate-500 px-2 mb-2">Visão geral</div>
                        <a href="{{ route('dashboard') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('dashboard') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                            <span class="mr-2">📊</span> Dashboard
                        </a>
                        @if ($user?->temPermissao('demandas.criar'))
                            <a href="{{ route('demandas.index') }}" class="mt-1 flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('demandas.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                <span class="mr-2">📝</span> Demandas
                            </a>
                        @endif
                        @if ($user?->temPermissao('demandas.validar') || $user?->temPermissao('demandas.covalidar'))
                            <a href="{{ route('validacao.index') }}" class="mt-1 flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('validacao.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                <span class="mr-2">✅</span> Validação
                            </a>
                        @endif
                    </div>

                    @if ($user?->temPermissao('usuarios.gerenciar') || $user?->temPermissao('unidades.gerenciar') || $user?->temPermissao('ciclos.gerenciar') || $user?->temPermissao('catalogos.gerenciar') || $user?->temPermissao('pca.consolidar'))
                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500 px-2 mb-2">Administração</div>
                            @if ($user?->temPermissao('unidades.gerenciar'))
                                <a href="{{ route('admin.unidades.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.unidades.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                    <span class="mr-2">🏢</span> Unidades organizacionais
                                </a>
                            @endif
                            @if ($user?->temPermissao('ciclos.gerenciar'))
                                <a href="{{ route('admin.ciclos.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.ciclos.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                    <span class="mr-2">🗓️</span> Ciclos do PCA
                                </a>
                            @endif
                            @if ($user?->temPermissao('pca.consolidar'))
                                <a href="{{ route('admin.pca.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.pca.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                    <span class="mr-2">🧮</span> Consolidação PCA
                                </a>
                            @endif
                            @if ($user?->temPermissao('catalogos.gerenciar'))
                                <div class="pt-1">
                                    <div class="text-xs uppercase tracking-wide text-slate-500 px-2 mb-2">Catálogos</div>
                                    <a href="{{ route('admin.catalogos.tipo', 'categoria') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.catalogos.*') && request()->route('tipo') === 'categoria' ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                        <span class="mr-2">🏷️</span> Categorias
                                    </a>
                                    <a href="{{ route('admin.catalogos.tipo', 'prioridade') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.catalogos.*') && request()->route('tipo') === 'prioridade' ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                        <span class="mr-2">⚡</span> Prioridades
                                    </a>
                                    <a href="{{ route('admin.catalogos.tipo', 'tipo') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.catalogos.*') && request()->route('tipo') === 'tipo' ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                        <span class="mr-2">🧾</span> Tipos de demanda
                                    </a>
                                    <a href="{{ route('admin.catalogos.tipo', 'natureza') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.catalogos.*') && request()->route('tipo') === 'natureza' ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                        <span class="mr-2">🧠</span> Naturezas
                                    </a>
                                </div>
                            @endif
                            @if ($user?->temPermissao('usuarios.gerenciar'))
                                <a href="{{ route('admin.usuarios.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.usuarios.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                    <span class="mr-2">👤</span> Usuários
                                </a>
                                <a href="{{ route('admin.perfis.index') }}" class="flex items-center px-3 py-2 rounded-lg text-sm font-medium {{ request()->routeIs('admin.perfis.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 text-slate-200' }}">
                                    <span class="mr-2">🛡️</span> Perfis e permissões
                                </a>
                            @endif
                        </div>
                    @endif
                </nav>
                <div class="px-6 py-5 border-t border-slate-800 text-sm text-slate-300">
                    <div class="font-semibold">{{ $user?->name }}</div>
                    <div class="text-slate-400">{{ $user?->email }}</div>
                </div>
            </aside>

            <div class="flex-1 flex flex-col min-h-screen">
                <header class="bg-white shadow-sm">
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <div class="text-xs uppercase tracking-wide text-slate-500">Ciclo ativo</div>
                            <div class="font-semibold text-lg">
                                {{ $cicloAtivo?->exercicio ?? 'Sem ciclo ativo' }}
                                @if ($cicloAtivo?->status)
                                    <span class="text-sm text-slate-500 font-normal">({{ $cicloAtivo->status }})</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-3">
                            <a href="{{ route('profile.edit') }}" class="text-sm text-slate-600 hover:text-slate-900">Perfil</a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="text-sm font-semibold text-slate-900 hover:text-rose-600">Sair</button>
                            </form>
                        </div>
                    </div>
                    @isset($header)
                        <div class="border-t border-slate-100 px-6 py-4">
                            {{ $header }}
                        </div>
                    @endisset
                </header>

                <main class="flex-1">
                    @if (session('sucesso'))
                        <div data-flash class="mx-6 mt-4 bg-emerald-50 text-emerald-800 px-4 py-3 rounded border border-emerald-200">
                            {{ session('sucesso') }}
                        </div>
                    @endif
                    @if ($errors->any())
                        <div data-flash class="mx-6 mt-4 bg-rose-50 text-rose-800 px-4 py-3 rounded border border-rose-200">
                            <ul class="list-disc list-inside text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    {{ $slot }}
                </main>
            </div>
        </div>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const flashes = document.querySelectorAll('[data-flash]');
                if (!flashes.length) return;
                setTimeout(() => {
                    flashes.forEach(el => {
                        el.classList.add('hidden');
                    });
                }, 4000);
            });
        </script>
    </body>
</html>
