<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('coins_title') }} — {{ config('app.name', 'Metin2 Ignition') }}</title>
    <link rel="icon" type="image/png" sizes="32x32" href="/images/favicon.png">
    <meta name="theme-color" content="#080608">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        body { overflow-y: auto; }
        body::-webkit-scrollbar { width: 6px; }
        body::-webkit-scrollbar-track { background: #120a0a; }
        body::-webkit-scrollbar-thumb { background: #3d1e1e; border-radius: 3px; }
        body::-webkit-scrollbar-thumb:hover { background: #5a2e2e; }
    </style>
</head>
<body class="min-h-screen" style="background-color: var(--color-game-bg); color: var(--color-game-text); font-family: var(--font-sans);">

    {{-- Header --}}
    <header class="sticky top-0 z-50 border-b" style="background-color: var(--color-game-surface); border-color: var(--color-game-border);">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('home') }}" class="flex items-center gap-2">
                    <img src="/images/logo.png" alt="Logo" class="w-8 h-8">
                </a>
                <div class="flex items-center gap-2">
                    <svg class="w-4 h-4" style="color: var(--color-game-muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="text-sm font-medium">{{ $account->login }}</span>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8"/>
                    </svg>
                    <span id="coin-balance" class="font-bold text-yellow-400">{{ number_format($coins) }}</span>
                    <span class="text-xs" style="color: var(--color-game-muted);">{{ __('shop_coins') }}</span>
                </div>
                <a href="{{ route('shop.index') }}"
                   class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors"
                   style="background-color: var(--color-game-panel); color: var(--color-game-text); border: 1px solid var(--color-game-border);"
                   onmouseover="this.style.backgroundColor='var(--color-game-border)'"
                   onmouseout="this.style.backgroundColor='var(--color-game-panel)'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    {{ __('coins_back_to_shop') }}
                </a>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="max-w-4xl mx-auto px-4 py-8">

        @if(session('success'))
            <div class="mb-6 px-5 py-3 rounded-lg text-sm font-medium" style="background-color: #1a3a1a; border: 1px solid #2d5a2d; color: #7dcc7d;">
                {{ session('success') }}
            </div>
        @endif

        @if(session('info'))
            <div class="mb-6 px-5 py-3 rounded-lg text-sm font-medium" style="background-color: #2a2a1a; border: 1px solid #5a5a2d; color: #cccc7d;">
                {{ session('info') }}
            </div>
        @endif

        <h1 class="text-xl font-bold mb-2" style="color: var(--color-accent-400);">{{ __('coins_title') }}</h1>

        <p class="text-sm mb-6" style="color: var(--color-game-muted);">{{ __('coins_select_method') }}</p>

        {{-- Payment Method Cards --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
            {{-- Stripe Card --}}
            <button onclick="selectMethod('stripe')" id="method-stripe"
                    class="flex flex-col items-center gap-3 p-6 rounded-xl cursor-pointer transition-all payment-method"
                    style="background: linear-gradient(135deg, #2a1a0a, #1a1008); border: 2px solid var(--color-game-border);">
                <div class="w-16 h-16 flex items-center justify-center rounded-xl" style="background-color: #635bff;">
                    <span class="text-white text-sm font-bold tracking-wider">stripe</span>
                </div>
                <span class="text-sm font-semibold" style="color: var(--color-game-text);">Stripe</span>
            </button>

            {{-- Coupon Card --}}
            <button onclick="selectMethod('coupon')" id="method-coupon"
                    class="flex flex-col items-center gap-3 p-6 rounded-xl cursor-pointer transition-all payment-method"
                    style="background: linear-gradient(135deg, #2a1a0a, #1a1008); border: 2px solid var(--color-game-border);">
                <div class="w-16 h-16 flex items-center justify-center rounded-xl" style="background-color: #d4a017;">
                    <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                    </svg>
                </div>
                <span class="text-sm font-semibold" style="color: var(--color-game-text);">{{ __('coins_coupon') }}</span>
            </button>
        </div>

        {{-- Stripe Packages Section --}}
        <div id="section-stripe" class="hidden">
            <h2 class="text-base font-semibold mb-5" style="color: var(--color-game-text);">{{ __('coins_select_package') }}</h2>

            @if($packages->isEmpty())
                <p class="text-sm" style="color: var(--color-game-muted);">{{ __('coins_no_packages') }}</p>
            @else
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                    @foreach($packages as $package)
                        <div class="flex items-center justify-between gap-4 p-4 rounded-xl"
                             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                            <div class="flex items-center gap-2">
                                <span class="text-lg font-bold text-yellow-400">{{ number_format($package['coins']) }}</span>
                                <svg class="w-5 h-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                    <circle cx="10" cy="10" r="8"/>
                                </svg>
                            </div>
                            <div class="flex flex-col items-end gap-1">
                                @if($package['has_discount'])
                                    <span class="text-xs line-through" style="color: var(--color-game-muted);">
                                        {{ $package['display_price_original']['formatted'] }}
                                    </span>
                                @endif
                                <button onclick="buyPackage({{ $package['id'] }})"
                                        class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer"
                                        style="background-color: var(--color-accent-600); color: #fff; border: 1px solid var(--color-accent-500);"
                                        onmouseover="this.style.backgroundColor='#a01818'"
                                        onmouseout="this.style.backgroundColor='var(--color-accent-600)'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                                    </svg>
                                    {{ $package['display_price']['formatted'] }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>

                @if($packages->first()['display_price']['currency'] !== 'EUR')
                    <p class="text-xs mt-5" style="color: var(--color-game-muted);">
                        {{ __('coins_price_note') }}
                    </p>
                @endif
            @endif
        </div>

        {{-- Coupon Section --}}
        <div id="section-coupon" class="hidden">
            <h2 class="text-base font-semibold mb-4" style="color: var(--color-game-text);">{{ __('coins_coupon') }}</h2>

            <div class="max-w-md">
                <div class="flex gap-3">
                    <div class="flex-1 flex items-center gap-2 px-4 py-2.5 rounded-lg"
                         style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                        <svg class="w-4 h-4 shrink-0" style="color: var(--color-game-muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                        </svg>
                        <input type="text" id="coupon-input" placeholder="{{ __('coins_coupon_placeholder') }}"
                               class="flex-1 bg-transparent text-sm focus:outline-none"
                               style="color: var(--color-game-text);"
                               maxlength="50"
                               onkeydown="if(event.key==='Enter')redeemCoupon()">
                    </div>
                    <button onclick="redeemCoupon()" id="coupon-submit-btn"
                            class="px-5 py-2.5 rounded-lg text-sm font-semibold transition-colors cursor-pointer"
                            style="background-color: var(--color-accent-600); color: #fff; border: 1px solid var(--color-accent-500);"
                            onmouseover="this.style.backgroundColor='#a01818'"
                            onmouseout="this.style.backgroundColor='var(--color-accent-600)'">
                        {{ __('coins_coupon_submit') }}
                    </button>
                </div>
            </div>
        </div>
    </main>

    {{-- Toast Notification --}}
    <div id="toast" class="fixed bottom-6 right-6 z-50 hidden">
        <div id="toast-inner" class="px-5 py-3 rounded-lg shadow-lg text-sm font-medium transition-all duration-300" style="border: 1px solid var(--color-game-border);">
            <span id="toast-message"></span>
        </div>
    </div>

    <script>
        const COINS_CONFIG = {
            couponUrl: '{{ route("coins.coupon.redeem") }}',
            stripeUrl: '{{ route("coins.stripe.checkout") }}',
            csrfToken: '{{ csrf_token() }}',
        };

        let activeMethod = null;

        function showToast(message, type = 'success') {
            const toast = document.getElementById('toast');
            const inner = document.getElementById('toast-inner');
            const msg = document.getElementById('toast-message');

            msg.textContent = message;
            inner.style.backgroundColor = type === 'success' ? '#1a3a1a' : '#3a1a1a';
            inner.style.borderColor = type === 'success' ? '#2d5a2d' : '#5a2d2d';
            inner.style.color = type === 'success' ? '#7dcc7d' : '#cc7d7d';

            toast.classList.remove('hidden');
            setTimeout(() => toast.classList.add('hidden'), 4000);
        }

        function selectMethod(method) {
            activeMethod = method;

            // Update card borders
            document.querySelectorAll('.payment-method').forEach(el => {
                el.style.borderColor = 'var(--color-game-border)';
            });
            document.getElementById('method-' + method).style.borderColor = 'var(--color-accent-400)';

            // Show/hide sections
            document.getElementById('section-stripe').classList.toggle('hidden', method !== 'stripe');
            document.getElementById('section-coupon').classList.toggle('hidden', method !== 'coupon');
        }

        function buyPackage(packageId) {
            fetch(COINS_CONFIG.stripeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': COINS_CONFIG.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ package_id: packageId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.checkout_url) {
                    window.location.href = data.checkout_url;
                } else {
                    showToast(data.message || '{{ __("coins_stripe_error") }}', 'error');
                }
            })
            .catch(() => showToast('{{ __("shop_connection_error") }}', 'error'));
        }

        function redeemCoupon() {
            const input = document.getElementById('coupon-input');
            const code = input.value.trim();
            if (!code) return;

            const btn = document.getElementById('coupon-submit-btn');
            btn.disabled = true;
            btn.style.opacity = '0.5';

            fetch(COINS_CONFIG.couponUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': COINS_CONFIG.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ code: code })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    showToast(data.message, 'success');
                    document.getElementById('coin-balance').textContent = Number(data.coins).toLocaleString();
                    input.value = '';
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(() => showToast('{{ __("shop_connection_error") }}', 'error'))
            .finally(() => {
                btn.disabled = false;
                btn.style.opacity = '1';
            });
        }
    </script>
</body>
</html>
