@extends('layouts.portfolio')

@php
    $profile = $data['profile'];
    $roles = $data['roles'];
    $themeRoles = $data['theme']['roles'];
    $roleKeys = array_keys($roles);
    $defaultRole = $roleKeys[0];
@endphp

@section('content')
<div id="portfolio-app" class="relative min-h-screen overflow-x-hidden" data-active-role="{{ $defaultRole }}">

    {{-- Parallax backdrop layers --}}
    <div class="pointer-events-none fixed inset-0 -z-10 overflow-hidden" aria-hidden="true">
        <div id="bg-grid" class="absolute inset-0 opacity-[0.04] dark:opacity-[0.06]"></div>
        <div id="bg-glow-a" class="absolute -left-1/4 top-0 h-[55vh] w-[55vw] rounded-full blur-[120px] transition-colors duration-700"></div>
        <div id="bg-glow-b" class="absolute -right-1/4 bottom-0 h-[45vh] w-[50vw] rounded-full blur-[100px] transition-colors duration-700"></div>
        <div id="parallax-orb" class="absolute left-1/2 top-[18%] h-64 w-64 -translate-x-1/2 rounded-full blur-3xl opacity-40"></div>
    </div>

    {{-- Header --}}
    <header class="sticky top-0 z-50 border-b border-line/60 bg-surface/80 backdrop-blur-xl">
        <div class="mx-auto flex max-w-6xl items-center justify-between gap-4 px-5 py-4 sm:px-8">
            <a href="#hero" class="group flex items-center gap-3" data-magnetic>
                <span class="relative flex h-11 w-11 items-center justify-center rounded-xl border border-line bg-elevated shadow-sm transition-transform duration-300 group-hover:scale-105">
                    <svg class="h-6 w-6 text-accent" viewBox="0 0 40 40" fill="none" aria-hidden="true">
                        <path d="M8 32V8l12 8 12-8v24" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20 16v16" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"/>
                    </svg>
                </span>
                <span class="hidden sm:block">
                    <span class="block text-sm font-semibold tracking-tight">{{ $profile['name'] }}</span>
                    <span class="block text-xs text-muted">Portfolio</span>
                </span>
            </a>

            <nav class="hidden items-center gap-6 text-sm font-medium text-muted md:flex">
                @foreach($roleKeys as $key)
                    <a href="#role-{{ $roles[$key]['slug'] }}" class="nav-role-link transition-colors hover:text-accent" data-role-nav="{{ $key }}">
                        {{ $roles[$key]['title'] }}
                    </a>
                @endforeach
                <a href="#contact" class="transition-colors hover:text-accent">Kontak</a>
            </nav>

            <div class="flex items-center gap-2">
                <button type="button" id="theme-toggle" class="btn-ghost rounded-full px-3 py-2 text-xs font-medium" aria-label="Toggle light/dark">
                    <span class="dark:hidden">Gelap</span>
                    <span class="hidden dark:inline">Terang</span>
                </button>
            </div>
        </div>
    </header>

    <main>
        {{-- Hero --}}
        <section id="hero" class="relative mx-auto max-w-6xl px-5 pb-16 pt-14 sm:px-8 sm:pt-20">
            <p class="reveal mb-4 text-xs font-semibold uppercase tracking-[0.2em] text-accent">Freelance · Full-stack · Visual</p>
            <h1 class="reveal max-w-3xl text-4xl font-bold tracking-tight sm:text-5xl lg:text-6xl">
                {{ $profile['name'] }}
            </h1>
            <p class="reveal mt-4 max-w-2xl text-lg text-muted sm:text-xl">{{ $profile['tagline'] }}</p>
            <p class="reveal mt-2 text-sm text-muted/80">{{ $profile['headline'] }}</p>

            <div class="reveal mt-10 flex flex-wrap gap-3">
                <a href="#roles" class="btn-primary" data-magnetic>Jelajahi peran</a>
                <a href="#contact" class="btn-ghost" data-magnetic>Hubungi saya</a>
            </div>
        </section>

        {{-- Role cards overview --}}
        <section id="roles" class="mx-auto max-w-6xl px-5 py-8 sm:px-8">
            <div class="reveal mb-10 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                <div>
                    <h2 class="text-2xl font-bold tracking-tight sm:text-3xl">Tiga dimensi keahlian</h2>
                    <p class="mt-2 max-w-xl text-muted">Arahkan kursor atau scroll ke kartu — warna aksen situs beradaptasi. Klik untuk membuka katalog proyek.</p>
                </div>
                <p class="text-xs text-muted">Scroll untuk transisi tema</p>
            </div>

            <div class="grid gap-5 lg:grid-cols-3">
                @foreach($roleKeys as $key)
                    @php
                        $role = $roles[$key];
                        $accent = $themeRoles[$key]['accent'] ?? '#22d3ee';
                    @endphp
                    <article
                        id="role-{{ $role['slug'] }}"
                        class="role-card group relative cursor-pointer overflow-hidden rounded-2xl border border-line bg-elevated/80 p-6 shadow-sm backdrop-blur transition-[transform,box-shadow,border-color] duration-500 hover:-translate-y-1 hover:shadow-xl"
                        data-role-card="{{ $key }}"
                        data-accent="{{ $accent }}"
                        tabindex="0"
                        role="button"
                        aria-expanded="false"
                    >
                        <div class="role-card-glow pointer-events-none absolute -right-8 -top-8 h-32 w-32 rounded-full opacity-0 blur-2xl transition-opacity duration-500 group-hover:opacity-60"></div>
                        <span class="inline-flex rounded-full border border-line px-3 py-1 text-[11px] font-semibold uppercase tracking-wider text-muted">{{ $themeRoles[$key]['label'] }}</span>
                        <h3 class="mt-4 text-xl font-bold">{{ $role['title'] }}</h3>
                        <p class="mt-2 text-sm text-muted">{{ $role['tagline'] }}</p>
                        <p class="mt-4 line-clamp-3 text-sm leading-relaxed text-ink/80">{{ Str::limit($role['description'], 140) }}</p>
                        <span class="mt-6 inline-flex items-center gap-2 text-sm font-semibold text-accent">
                            Buka katalog
                            <svg class="h-4 w-4 transition-transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </span>
                    </article>
                @endforeach
            </div>
        </section>

        {{-- Role detail panels (one visible at a time) --}}
        @foreach($roleKeys as $key)
            @php $role = $roles[$key]; @endphp
            <section
                id="panel-{{ $key }}"
                class="role-panel hidden border-t border-line/60"
                data-role-panel="{{ $key }}"
                aria-hidden="true"
            >
                <div class="mx-auto max-w-6xl px-5 py-14 sm:px-8">
                    <button type="button" class="role-panel-close mb-8 inline-flex items-center gap-2 text-sm font-medium text-muted transition-colors hover:text-accent">
                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        Kembali ke ringkasan
                    </button>

                    <div class="grid gap-10 lg:grid-cols-[1fr_1.2fr]">
                        <div class="reveal-panel">
                            <p class="text-xs font-semibold uppercase tracking-[0.18em] text-accent">{{ $role['title'] }}</p>
                            <h2 class="mt-3 text-3xl font-bold tracking-tight">{{ $role['tagline'] }}</h2>
                            <p class="mt-5 leading-relaxed text-muted">{{ $role['description'] }}</p>
                            <ul class="mt-8 space-y-2">
                                @foreach($role['highlights'] as $item)
                                    <li class="flex items-start gap-2 text-sm">
                                        <span class="mt-1.5 h-1.5 w-1.5 shrink-0 rounded-full bg-accent"></span>
                                        {{ $item }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div>
                            <h3 class="mb-5 text-sm font-semibold uppercase tracking-wider text-muted">Katalog proyek</h3>
                            <div class="catalog-track flex gap-4 overflow-x-auto pb-4 snap-x snap-mandatory scroll-smooth [-ms-overflow-style:none] [scrollbar-width:none] [&::-webkit-scrollbar]:hidden">
                                @foreach($role['catalog'] as $project)
                                    <article class="catalog-card reveal-panel min-w-[280px] max-w-[320px] shrink-0 snap-start rounded-2xl border border-line bg-elevated p-5 shadow-sm transition-transform duration-300 hover:-translate-y-0.5 sm:min-w-[300px]">
                                        <div class="flex items-center justify-between gap-2">
                                            <span class="text-xs text-muted">{{ $project['year'] ?? '' }}</span>
                                            <span class="h-2 w-2 rounded-full bg-accent/80"></span>
                                        </div>
                                        <h4 class="mt-3 text-lg font-bold leading-snug">{{ $project['title'] }}</h4>
                                        <p class="mt-2 text-sm text-muted">{{ $project['short_description'] }}</p>
                                        <div class="mt-4 flex flex-wrap gap-1.5">
                                            @foreach($project['tech_stack'] as $tech)
                                                <span class="rounded-md border border-line bg-surface px-2 py-0.5 text-[10px] font-medium text-muted">{{ $tech }}</span>
                                            @endforeach
                                        </div>
                                        <button
                                            type="button"
                                            class="case-study-open btn-ghost mt-5 w-full justify-center text-sm"
                                            data-project='@json($project)'
                                            data-role-title="{{ $role['title'] }}"
                                        >
                                            View Case Study
                                        </button>
                                    </article>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endforeach

        {{-- Contact --}}
        <section id="contact" class="border-t border-line/60">
            <div class="mx-auto max-w-6xl px-5 py-16 sm:px-8">
                <div class="reveal rounded-2xl border border-line bg-elevated/90 p-8 sm:p-10">
                    <h2 class="text-2xl font-bold">Mari berkolaborasi</h2>
                    <p class="mt-2 text-muted">Tersedia untuk proyek analisis sistem, pengembangan web, dan desain editorial.</p>
                    <div class="mt-6 flex flex-col gap-3 sm:flex-row sm:flex-wrap">
                        <a href="mailto:{{ $profile['email'] }}" class="btn-primary" data-magnetic>{{ $profile['email'] }}</a>
                        @if(!empty($profile['social']['linkedin']))
                            <a href="{{ $profile['social']['linkedin'] }}" target="_blank" rel="noopener" class="btn-ghost" data-magnetic>LinkedIn</a>
                        @endif
                        @if(!empty($profile['social']['github']))
                            <a href="{{ $profile['social']['github'] }}" target="_blank" rel="noopener" class="btn-ghost" data-magnetic>GitHub</a>
                        @endif
                    </div>
                    <p class="mt-6 text-xs text-muted">{{ $profile['location'] }} · © {{ date('Y') }} {{ $profile['name'] }}</p>
                </div>
            </div>
        </section>
    </main>

    {{-- Case study modal --}}
    <div id="case-study-modal" class="fixed inset-0 z-[100] hidden items-end justify-center sm:items-center sm:p-6" aria-hidden="true">
        <div class="case-study-backdrop absolute inset-0 bg-ink/60 backdrop-blur-sm"></div>
        <div class="case-study-dialog relative z-10 max-h-[90vh] w-full max-w-2xl overflow-y-auto rounded-t-2xl border border-line bg-elevated p-6 shadow-2xl sm:rounded-2xl sm:p-8">
            <button type="button" class="case-study-close absolute right-4 top-4 rounded-full border border-line p-2 text-muted hover:text-accent" aria-label="Tutup">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
            <p id="cs-role" class="text-xs font-semibold uppercase tracking-wider text-accent"></p>
            <h3 id="cs-title" class="mt-2 pr-8 text-2xl font-bold"></h3>
            <div id="cs-body" class="mt-6 space-y-4 text-sm leading-relaxed text-muted"></div>
            <a id="cs-link" href="#" target="_blank" rel="noopener" class="btn-primary mt-8 hidden w-full justify-center sm:w-auto">Kunjungi proyek</a>
        </div>
    </div>
</div>

{{-- Theme tokens for JS --}}
<script type="application/json" id="portfolio-theme-data">@json($themeRoles)</script>
@endsection
