<nav style="background-color: var(--color-game-surface); border-bottom: 1px solid var(--color-game-border);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            {{-- Logo --}}
            <a href="{{ route('home') }}" class="shrink-0 mr-4">
                <img src="/images/logo.png" alt="{{ config('app.name') }}"
                     class="h-10 w-auto object-contain"
                     style="filter: drop-shadow(0 0 6px rgba(220,0,0,0.5));">
            </a>

            {{-- Nav links --}}
            <div class="hidden md:flex items-center gap-1 flex-1">
                <a href="{{ route('home') }}"
                   class="px-4 py-2 text-xs font-semibold uppercase tracking-widest transition-colors duration-150
                          {{ request()->routeIs('home') ? 'text-[var(--color-accent-400)]' : 'text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]' }}">
                    {{ __('nav_home') }}
                </a>
                <a href="{{ route('register') }}"
                   class="px-4 py-2 text-xs font-semibold uppercase tracking-widest transition-colors duration-150
                          {{ request()->routeIs('register') ? 'text-[var(--color-accent-400)]' : 'text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]' }}">
                    {{ __('nav_registration') }}
                </a>
                <a href="{{ route('download') }}"
                   class="px-4 py-2 text-xs font-semibold uppercase tracking-widest transition-colors duration-150
                          {{ request()->routeIs('download') ? 'text-[var(--color-accent-400)]' : 'text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]' }}">
                    {{ __('nav_download') }}
                </a>
                <a href="{{ route('ranking') }}"
                   class="px-4 py-2 text-xs font-semibold uppercase tracking-widest transition-colors duration-150
                          {{ request()->routeIs('ranking') ? 'text-[var(--color-accent-400)]' : 'text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]' }}">
                    {{ __('nav_ranking') }}
                </a>
                <a href="#" target="_blank"
                   class="px-4 py-2 text-xs font-semibold uppercase tracking-widest text-[var(--color-game-text)] hover:text-[var(--color-accent-400)] transition-colors duration-150">
                    {{ __('nav_discord') }}
                </a>
            </div>

            {{-- Right side: login + language switcher --}}
            <div class="flex items-center gap-3">

                @auth('metin2')
                    <a href="{{ route('account') }}"
                       class="hidden md:inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest transition-colors duration-150
                              {{ request()->routeIs('account') ? '' : 'hover:opacity-80' }}"
                       style="color: var(--color-accent-400)">
                        {{ Auth::guard('metin2')->user()->login }}
                    </a>
                    <form method="POST" action="{{ route('logout') }}" class="hidden md:inline">
                        @csrf
                        <button type="submit"
                                class="px-4 py-1.5 text-xs font-semibold uppercase tracking-widest rounded border transition-colors duration-150
                                       border-[var(--color-game-border)] text-[var(--color-game-muted)]
                                       hover:border-[var(--color-accent-500)] hover:text-[var(--color-accent-400)]">
                            {{ __('nav_logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden md:inline-flex items-center gap-2 px-4 py-1.5 text-xs font-semibold uppercase tracking-widest rounded
                              border border-[var(--color-game-border)] text-[var(--color-game-text)]
                              hover:border-[var(--color-accent-500)] hover:text-[var(--color-accent-400)] transition-colors duration-150">
                        {{ __('nav_login') }}
                    </a>
                @endauth

                {{-- Language switcher --}}
                <div class="relative" id="lang-dropdown-wrapper">
                    @php
                        $languages = [
                            'en' => ['label' => 'EN', 'flag' => '🇬🇧'],
                            'de' => ['label' => 'DE', 'flag' => '🇩🇪'],
                            'hu' => ['label' => 'HU', 'flag' => '🇭🇺'],
                            'fr' => ['label' => 'FR', 'flag' => '🇫🇷'],
                            'cs' => ['label' => 'CZ', 'flag' => '🇨🇿'],
                            'da' => ['label' => 'DK', 'flag' => '🇩🇰'],
                            'es' => ['label' => 'ES', 'flag' => '🇪🇸'],
                            'el' => ['label' => 'GR', 'flag' => '🇬🇷'],
                            'it' => ['label' => 'IT', 'flag' => '🇮🇹'],
                            'nl' => ['label' => 'NL', 'flag' => '🇳🇱'],
                            'pl' => ['label' => 'PL', 'flag' => '🇵🇱'],
                            'pt' => ['label' => 'PT', 'flag' => '🇵🇹'],
                            'ro' => ['label' => 'RO', 'flag' => '🇷🇴'],
                            'ru' => ['label' => 'RU', 'flag' => '🇷🇺'],
                            'tr' => ['label' => 'TR', 'flag' => '🇹🇷'],
                        ];
                        $current = app()->getLocale();
                        $currentLang = $languages[$current] ?? $languages['en'];
                    @endphp

                    <button id="lang-toggle"
                            class="flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold rounded border transition-colors duration-150
                                   border-[var(--color-game-border)] text-[var(--color-game-text)] hover:border-[var(--color-accent-500)]">
                        <span>{{ $currentLang['flag'] }}</span>
                        <span>{{ $currentLang['label'] }}</span>
                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div id="lang-menu"
                         class="hidden absolute right-0 mt-1 z-50 rounded overflow-hidden"
                         style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border); min-width: 130px;">
                        @foreach($languages as $locale => $lang)
                            <a href="?lang={{ $locale }}"
                               class="flex items-center gap-2 px-3 py-2 text-xs transition-colors duration-150
                                      {{ $current === $locale ? 'text-[var(--color-accent-400)]' : 'text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]' }}"
                               style="{{ $current === $locale ? 'background-color: var(--color-game-surface);' : '' }}">
                                <span>{{ $lang['flag'] }}</span>
                                <span>{{ $lang['label'] }}</span>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Mobile hamburger --}}
                <button id="nav-toggle" class="md:hidden p-2 text-[var(--color-game-muted)] hover:text-[var(--color-game-text)]">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Mobile menu --}}
        <div id="nav-mobile" class="hidden md:hidden pb-3">
            <div class="flex flex-col gap-1 pt-2" style="border-top: 1px solid var(--color-game-border);">
                <a href="{{ route('home') }}" class="px-3 py-2 text-xs font-semibold uppercase tracking-widest text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]">{{ __('nav_home') }}</a>
                <a href="{{ route('register') }}" class="px-3 py-2 text-xs font-semibold uppercase tracking-widest text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]">{{ __('nav_registration') }}</a>
                <a href="{{ route('download') }}" class="px-3 py-2 text-xs font-semibold uppercase tracking-widest text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]">{{ __('nav_download') }}</a>
                <a href="{{ route('ranking') }}" class="px-3 py-2 text-xs font-semibold uppercase tracking-widest text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]">{{ __('nav_ranking') }}</a>
                <a href="#" class="px-3 py-2 text-xs font-semibold uppercase tracking-widest text-[var(--color-game-text)] hover:text-[var(--color-accent-400)]">{{ __('nav_discord') }}</a>
                @auth('metin2')
                    <a href="{{ route('account') }}" class="px-3 py-2 text-xs font-semibold uppercase tracking-widest" style="color: var(--color-accent-400)">{{ Auth::guard('metin2')->user()->login }}</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 text-xs font-semibold uppercase tracking-widest text-[var(--color-game-muted)] hover:text-[var(--color-accent-400)]">
                            {{ __('nav_logout') }}
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-2 text-xs font-semibold uppercase tracking-widest text-[var(--color-accent-400)]">{{ __('nav_login') }}</a>
                @endauth
            </div>
        </div>
    </div>
</nav>

<script>
    document.getElementById('nav-toggle').addEventListener('click', function () {
        document.getElementById('nav-mobile').classList.toggle('hidden');
    });
    document.getElementById('lang-toggle').addEventListener('click', function (e) {
        e.stopPropagation();
        document.getElementById('lang-menu').classList.toggle('hidden');
    });
    document.addEventListener('click', function () {
        document.getElementById('lang-menu').classList.add('hidden');
    });
</script>
