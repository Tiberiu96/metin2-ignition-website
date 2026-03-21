@extends('layouts.app')

@section('content')

    {{-- ═══════════════════════════════════════════════════════════════
         HERO SECTION
    ═══════════════════════════════════════════════════════════════ --}}
    <section class="relative w-full overflow-hidden" style="background-color: #080608; min-height: 280px;">
        {{-- Background image --}}
        <img
            src="/images/background_layout.jpg"
            alt=""
            class="absolute inset-0 w-full h-full object-cover"
            style="object-position: right 15%;"
            onerror="this.style.display='none'"
        >
        {{-- Gradient stanga (desktop) / overlay general (mobil) --}}
        <div class="absolute inset-0 md:hidden" style="background: linear-gradient(to bottom, rgba(0,0,0,0.55) 0%, rgba(0,0,0,0.75) 100%);"></div>
        <div class="absolute inset-0 hidden md:block" style="background: linear-gradient(to right, rgba(0,0,0,0.92) 0%, rgba(0,0,0,0.55) 40%, rgba(0,0,0,0.1) 100%);"></div>
        <div class="absolute inset-0" style="background: linear-gradient(to top, rgba(0,0,0,0.8) 0%, transparent 50%);"></div>
        {{-- Logo --}}
        <img
            src="/images/logo.png"
            alt="{{ config('app.name') }}"
            class="absolute bottom-6 left-1/2 -translate-x-1/2 h-28
                   md:left-16 md:translate-x-0 md:h-52 md:bottom-10
                   object-contain"
            style="filter: drop-shadow(0 0 22px rgba(220,0,0,0.9));"
            onerror="this.style.display='none'"
        >
        {{-- Spacer responsive --}}
        <div class="md:hidden" style="min-height: 280px;"></div>
        <div class="hidden md:block" style="min-height: 520px;"></div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         STATS BAR
    ═══════════════════════════════════════════════════════════════ --}}
    <style>
        .stats-bar { display: flex; flex-wrap: wrap; justify-content: center; align-items: center; gap: 1rem 2.5rem; width: 100%; }
        .stat-item { display: flex; flex-direction: column; align-items: center; text-align: center; gap: 0.2rem; }
        @media (min-width: 640px) { .stats-bar { gap: 1rem 3.5rem; } }
    </style>
    <section style="background-color: var(--color-game-surface); border-bottom: 1px solid var(--color-game-border);">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            @php
                $statItems = [
                    ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',                                                                           'value' => $stats['online'] ?? 0,    'label' => __('stats_players_online')],
                    ['icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z',                                                                                                    'value' => $stats['online_24h'] ?? 0, 'label' => __('stats_players_online_24h')],
                    ['icon' => 'M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0m6 2a9 9 0 11-18 0 9 9 0 0118 0z',             'value' => $stats['accounts'] ?? 0,  'label' => __('stats_accounts')],
                    ['icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',                                                                           'value' => $stats['players'] ?? 0,   'label' => __('stats_players')],
                    ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'value' => $stats['guilds'] ?? 0, 'label' => __('stats_guilds')],
                ];
            @endphp

            <div class="stats-bar">
                @foreach ($statItems as $stat)
                    <div class="stat-item">
                        <svg width="20" height="20" style="color: var(--color-accent-500); flex-shrink: 0;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $stat['icon'] }}"/>
                        </svg>
                        <span style="color: var(--color-game-muted); font-size: 0.7rem; white-space: nowrap;">{{ $stat['label'] }}</span>
                        <span style="color: var(--color-accent-400); font-size: 0.95rem; font-weight: 700;">{{ $stat['value'] }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ═══════════════════════════════════════════════════════════════
         MAIN CONTENT — 3 COLUMNS
    ═══════════════════════════════════════════════════════════════ --}}
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="flex flex-col lg:flex-row gap-5">

            {{-- ── LEFT COLUMN ─────────────────────────────────── --}}
            <div class="w-full lg:w-64 shrink-0 flex flex-col gap-5">

                {{-- Login panel --}}
                <div class="rounded p-4 flex flex-col gap-3"
                     style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    @auth('metin2')
                        <h3 class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-accent-400)">{{ __('panel_welcome') }}</h3>
                        <a href="{{ route('account') }}" class="text-xs font-semibold hover:opacity-80 transition-opacity" style="color: var(--color-game-text)">{{ Auth::guard('metin2')->user()->login }}</a>
                        <a href="{{ route('account') }}"
                           class="w-full py-2 text-xs font-bold uppercase tracking-widest rounded text-center transition-colors duration-150"
                           style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border); color: var(--color-game-muted);">
                            {{ __('account_my_account') }}
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit"
                                    class="w-full py-2 text-xs font-bold uppercase tracking-widest rounded transition-colors duration-150"
                                    style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border); color: var(--color-game-muted);">
                                {{ __('nav_logout') }}
                            </button>
                        </form>
                    @else
                        <h3 class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-accent-400)">{{ __('panel_login_panel') }}</h3>
                        <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-2">
                            @csrf
                            <input type="text" name="login" placeholder="{{ __('panel_username') }}"
                                   class="w-full px-3 py-2 text-xs rounded outline-none"
                                   style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                            <input type="password" name="password" placeholder="{{ __('panel_password') }}"
                                   class="w-full px-3 py-2 text-xs rounded outline-none"
                                   style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                            <button type="submit"
                                    class="w-full py-2 text-xs font-bold uppercase tracking-widest rounded transition-colors duration-150"
                                    style="background-color: var(--color-accent-600); color: #fff;">
                                {{ __('panel_login') }}
                            </button>
                        </form>
                        <div class="flex flex-col gap-1 text-[10px]" style="color: var(--color-game-muted)">
                            <a href="{{ route('password.forgot.form') }}" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('panel_forgot_password') }}</a>
                        </div>
                    @endauth
                </div>

                {{-- Itemshop --}}
                <a href="{{ route('shop.index') }}"
                   class="flex items-center justify-center gap-2 py-3 rounded text-xs font-bold uppercase tracking-widest transition-colors duration-150"
                   style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border); color: var(--color-accent-400);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 11H4L5 9z"/>
                    </svg>
                    {{ __('panel_itemshop') }}
                </a>

                {{-- Ranking mini --}}
                <div class="rounded overflow-hidden"
                     style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <div class="flex items-center justify-between px-4 py-2"
                         style="border-bottom: 1px solid var(--color-game-border);">
                        <h3 class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-accent-400)">{{ __('panel_ranking') }}</h3>
                        <a href="{{ route('ranking') }}"
                           class="text-[10px] transition-colors hover:text-[var(--color-game-text)]"
                           style="color: var(--color-game-muted)">
                            {{ __('panel_see_all') }}
                        </a>
                    </div>
                    @php
                        $homeCrowns = [
                            1 => '/images/crown/gold-crown.png',
                            2 => '/images/crown/silver-crown.png',
                            3 => '/images/crown/bronze-crown.png',
                        ];
                        $homeEmpires = [
                            1 => '/images/empires/empire-red.png',
                            2 => '/images/empires/empire-yellow.png',
                            3 => '/images/empires/empire-blue.png',
                        ];
                    @endphp
                    <table class="w-full text-[10px]">
                        <thead>
                            <tr style="border-bottom: 1px solid var(--color-game-border); color: var(--color-game-muted);">
                                <th class="px-3 py-1.5 text-left font-medium w-8">#</th>
                                <th class="px-3 py-1.5 text-left font-medium">{{ __('ranking_player') }}</th>
                                <th class="px-3 py-1.5 text-right font-medium">Lv</th>
                                <th class="px-3 py-1.5 text-right font-medium w-8"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topPlayers ?? [] as $i => $player)
                                @php $rank = $i + 1; @endphp
                                <tr style="border-bottom: 1px solid var(--color-game-border);">
                                    <td class="px-3 py-1.5 w-8">
                                        @if(isset($homeCrowns[$rank]))
                                            <img src="{{ $homeCrowns[$rank] }}" alt="{{ $rank }}" class="w-4 h-4 object-contain">
                                        @else
                                            <span style="color: var(--color-game-muted)">{{ $rank }}</span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-1.5" style="color: var(--color-game-text)">{{ $player->name }}</td>
                                    <td class="px-3 py-1.5 text-right font-bold" style="color: var(--color-accent-400)">{{ $player->level }}</td>
                                    <td class="px-3 py-1.5 text-right w-8">
                                        @if(isset($homeEmpires[$player->empire ?? 0]))
                                            <img src="{{ $homeEmpires[$player->empire] }}" alt="empire" class="w-4 h-4 object-contain inline-block">
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                @for($i = 1; $i <= 10; $i++)
                                    <tr style="border-bottom: 1px solid var(--color-game-border);">
                                        <td class="px-3 py-1.5" style="color: var(--color-game-muted)">{{ $i }}</td>
                                        <td class="px-3 py-1.5" style="color: var(--color-game-text)">—</td>
                                        <td class="px-3 py-1.5 text-right font-bold" style="color: var(--color-accent-400)">—</td>
                                        <td class="px-3 py-1.5"></td>
                                    </tr>
                                @endfor
                            @endforelse
                        </tbody>
                    </table>
                </div>

            </div>

            {{-- ── CENTER COLUMN ───────────────────────────────── --}}
            <div class="flex-1 min-w-0 flex flex-col gap-5">

                <div class="rounded overflow-hidden"
                     style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <div class="px-4 py-3 flex items-center justify-between"
                         style="border-bottom: 1px solid var(--color-game-border);">
                        <h3 class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-accent-400)">{{ __('panel_community') }}</h3>
                        <a href="#" target="_blank"
                           class="flex items-center gap-1.5 px-3 py-1 rounded text-[10px] font-semibold text-white transition-opacity hover:opacity-80"
                           style="background-color: #5865F2;">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.317 4.37a19.791 19.791 0 00-4.885-1.515.074.074 0 00-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 00-5.487 0 12.64 12.64 0 00-.617-1.25.077.077 0 00-.079-.037A19.736 19.736 0 003.677 4.37a.07.07 0 00-.032.027C.533 9.046-.32 13.58.099 18.057c.002.022.015.043.036.056a19.9 19.9 0 005.993 3.03.078.078 0 00.084-.028c.462-.63.874-1.295 1.226-1.994a.076.076 0 00-.041-.106 13.107 13.107 0 01-1.872-.892.077.077 0 01-.008-.128 10.2 10.2 0 00.372-.292.074.074 0 01.077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 01.078.01c.12.098.246.198.373.292a.077.077 0 01-.006.127 12.299 12.299 0 01-1.873.892.077.077 0 00-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 00.084.028 19.839 19.839 0 006.002-3.03.077.077 0 00.036-.055c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 00-.031-.03z"/>
                            </svg>
                            {{ __('panel_open_discord') }}
                        </a>
                    </div>

                    @php
                        $discordServerId  = config('services.discord.server_id');
                        $discordChannelId = config('services.discord.channel_id');
                    @endphp
                    @if($discordServerId && $discordChannelId)
                        <iframe
                            src="https://e.widgetbot.io/channels/{{ $discordServerId }}/{{ $discordChannelId }}"
                            height="340"
                            width="100%"
                            frameborder="0">
                        </iframe>
                    @else
                        <div class="flex flex-col items-center justify-center gap-3 p-8 text-center"
                             style="min-height: 200px; color: var(--color-game-muted);">
                            <p class="text-xs">Adauga <code>DISCORD_CHANNEL_ID</code> in <code>.env</code></p>
                        </div>
                    @endif
                </div>

            </div>

            {{-- ── RIGHT COLUMN ────────────────────────────────── --}}
            <div class="w-full lg:w-72 shrink-0 flex flex-col gap-5">

                {{-- Announcements --}}
                <div class="rounded overflow-hidden"
                     style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <div class="px-4 py-3" style="border-bottom: 1px solid var(--color-game-border);">
                        <h3 class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-accent-400)">{{ __('panel_announcements') }}</h3>
                    </div>

                    <div class="flex flex-col">
                        @forelse($news ?? [] as $item)
                            <div class="p-4 flex flex-col gap-1.5"
                                 style="border-bottom: 1px solid var(--color-game-border);">
                                <div class="text-[10px]" style="color: var(--color-game-muted)">
                                    {{ $item->created_at->translatedFormat('l, j F') }}
                                </div>
                                <h4 class="text-xs font-semibold leading-snug" style="color: var(--color-game-text)">
                                    {{ $item->title }}
                                </h4>
                                <p class="text-[10px] leading-relaxed line-clamp-3" style="color: var(--color-game-muted)">
                                    {{ $item->excerpt }}
                                </p>
                            </div>
                        @empty
                            @for($i = 0; $i < 3; $i++)
                                <div class="p-4 flex flex-col gap-2"
                                     style="border-bottom: 1px solid var(--color-game-border);">
                                    <div class="h-2 w-1/2 rounded opacity-20"
                                         style="background-color: var(--color-game-muted)"></div>
                                    <div class="h-2.5 w-3/4 rounded opacity-20"
                                         style="background-color: var(--color-game-muted)"></div>
                                    <div class="h-2 w-full rounded opacity-10"
                                         style="background-color: var(--color-game-muted)"></div>
                                </div>
                            @endfor
                        @endforelse
                    </div>

                    <div class="px-4 py-3">
                        <a href="{{ route('news') }}"
                           class="block text-center text-[10px] font-semibold uppercase tracking-widest transition-colors
                                  hover:text-[var(--color-accent-400)]"
                           style="color: var(--color-game-muted)">
                            {{ __('panel_see_all_news') }}
                        </a>
                    </div>
                </div>

                {{-- Server info --}}
                <div class="rounded p-4 flex flex-col gap-3"
                     style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <h3 class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-accent-400)">{{ __('panel_server_info') }}</h3>
                    <div class="flex flex-col gap-2 text-xs">
                        @foreach ([
                            __('panel_game_type')  => 'PVM / PVP',
                            __('panel_exp_rates')  => 'x50',
                            __('panel_drop_rates') => 'x10',
                            __('panel_max_level')  => '120',
                        ] as $label => $value)
                            <div class="flex justify-between">
                                <span style="color: var(--color-game-muted)">{{ $label }}</span>
                                <span style="color: var(--color-game-text)">{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>

        </div>
    </section>

@endsection