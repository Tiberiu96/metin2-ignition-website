<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ __('shop_title') }} — {{ config('app.name', 'Metin2 Ignition') }}</title>
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

    {{-- Shop Header --}}
    <header class="sticky top-0 z-50 border-b" style="background-color: var(--color-game-surface); border-color: var(--color-game-border);">
        <div class="max-w-7xl mx-auto px-4 py-3 flex items-center justify-between">
            {{-- Left: Logo + Username --}}
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

            {{-- Right: Coins + Buy button --}}
            <div class="flex items-center gap-3">
                <div class="flex items-center gap-2 px-4 py-2 rounded-lg" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8"/>
                    </svg>
                    <span id="coin-balance" class="font-bold text-yellow-400">{{ number_format($coins) }}</span>
                    <span class="text-xs" style="color: var(--color-game-muted);">{{ __('shop_coins') }}</span>
                </div>
                <button onclick="alert('{{ __('shop_buy_coins_soon') }}')"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer"
                        style="background-color: #1a6b3c; color: #fff; border: 1px solid #23874d;"
                        onmouseover="this.style.backgroundColor='#1f7d46'"
                        onmouseout="this.style.backgroundColor='#1a6b3c'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 100 4 2 2 0 000-4z"/>
                    </svg>
                    {{ __('shop_buy_coins') }}
                </button>
            </div>
        </div>
    </header>

    {{-- Tab Navigation + Search --}}
    <nav class="border-b" style="background-color: var(--color-game-surface); border-color: var(--color-game-border);">
        <div class="max-w-7xl mx-auto px-4 flex items-center justify-between">
            <div class="flex items-center gap-1">
                <a href="{{ route('home') }}"
                   class="flex items-center gap-1 px-4 py-3 text-sm transition-colors"
                   style="color: var(--color-game-muted);"
                   onmouseover="this.style.color='var(--color-game-text)'"
                   onmouseout="this.style.color='var(--color-game-muted)'">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </a>
                <button onclick="filterCategory('all')" id="tab-all"
                        class="shop-tab px-4 py-3 text-sm font-medium transition-colors border-b-2 cursor-pointer"
                        style="color: var(--color-accent-400); border-color: var(--color-accent-400);">
                    {{ __('shop_tab_all') }}
                </button>
                <button onclick="filterCategory('hot')" id="tab-hot"
                        class="shop-tab px-4 py-3 text-sm font-medium transition-colors border-b-2 cursor-pointer"
                        style="color: var(--color-game-muted); border-color: transparent;">
                    {{ __('shop_tab_popular') }}
                </button>
                <button onclick="filterCategory('sale')" id="tab-sale"
                        class="shop-tab px-4 py-3 text-sm font-medium transition-colors border-b-2 cursor-pointer"
                        style="color: var(--color-game-muted); border-color: transparent;">
                    {{ __('shop_tab_on_sale') }}
                </button>
            </div>
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4" style="color: var(--color-game-muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" id="search-input" placeholder="{{ __('shop_search_placeholder') }}"
                       oninput="searchItems(this.value)"
                       class="pl-9 pr-4 py-2 rounded-lg text-sm w-56 focus:outline-none focus:ring-1"
                       style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border); color: var(--color-game-text); --tw-ring-color: var(--color-accent-600);">
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="max-w-7xl mx-auto px-4 py-6">
        <div class="flex gap-6">
            @yield('content')
        </div>
    </main>

    {{-- Purchase Confirmation Modal --}}
    <div id="purchase-modal" class="fixed inset-0 z-[60] hidden">
        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/70" onclick="closePurchaseModal()"></div>
        {{-- Dialog --}}
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full max-w-sm rounded-xl p-6 shadow-2xl"
             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
            <h3 class="text-base font-semibold mb-4" style="color: var(--color-game-text);">{{ __('shop_confirm_purchase') }}</h3>
            <div class="flex items-center gap-3 mb-6 p-4 rounded-lg" style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border);">
                <div class="flex-1 min-w-0">
                    <p id="modal-item-name" class="text-sm font-medium truncate" style="color: var(--color-game-text);"></p>
                </div>
                <div class="flex items-center gap-1.5 shrink-0">
                    <span id="modal-item-price" class="text-sm font-bold text-yellow-400"></span>
                    <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                        <circle cx="10" cy="10" r="8"/>
                    </svg>
                </div>
            </div>
            <div class="flex gap-3 justify-end">
                <button onclick="closePurchaseModal()"
                        class="px-4 py-2 rounded-lg text-sm font-medium transition-colors cursor-pointer"
                        style="background-color: var(--color-game-surface); color: var(--color-game-muted); border: 1px solid var(--color-game-border);"
                        onmouseover="this.style.backgroundColor='var(--color-game-border)'"
                        onmouseout="this.style.backgroundColor='var(--color-game-surface)'">
                    {{ __('shop_cancel') }}
                </button>
                <button id="modal-confirm-btn" onclick="confirmPurchase()"
                        class="px-4 py-2 rounded-lg text-sm font-semibold transition-colors cursor-pointer"
                        style="background-color: #1a6b3c; color: #fff; border: 1px solid #23874d;"
                        onmouseover="this.style.backgroundColor='#1f7d46'"
                        onmouseout="this.style.backgroundColor='#1a6b3c'">
                    {{ __('shop_confirm') }}
                </button>
            </div>
        </div>
    </div>

    {{-- Toast Notification --}}
    <div id="toast" class="fixed bottom-6 right-6 z-50 hidden">
        <div id="toast-inner" class="px-5 py-3 rounded-lg shadow-lg text-sm font-medium transition-all duration-300" style="border: 1px solid var(--color-game-border);">
            <span id="toast-message"></span>
        </div>
    </div>

    <script>
        const SHOP_CONFIG = {
            purchaseUrl: '{{ route("shop.purchase") }}',
            csrfToken: '{{ csrf_token() }}',
            connectionError: '{{ __('shop_connection_error') }}'
        };

        let pendingItemId = null;

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

        function purchaseItem(itemId, itemName, itemPrice) {
            pendingItemId = itemId;
            document.getElementById('modal-item-name').textContent = itemName;
            document.getElementById('modal-item-price').textContent = Number(itemPrice).toLocaleString();
            document.getElementById('purchase-modal').classList.remove('hidden');
        }

        function closePurchaseModal() {
            document.getElementById('purchase-modal').classList.add('hidden');
            pendingItemId = null;
        }

        function confirmPurchase() {
            if (!pendingItemId) return;
            const itemId = pendingItemId;
            closePurchaseModal();

            fetch(SHOP_CONFIG.purchaseUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': SHOP_CONFIG.csrfToken,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ item_id: itemId })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    document.getElementById('coin-balance').textContent = Number(data.coins).toLocaleString();
                    showToast(data.message, 'success');
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(() => showToast(SHOP_CONFIG.connectionError, 'error'));
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') closePurchaseModal();
        });

        function switchTab(activeId) {
            document.querySelectorAll('.shop-tab').forEach(tab => {
                const isActive = tab.id === activeId;
                tab.style.color = isActive ? 'var(--color-accent-400)' : 'var(--color-game-muted)';
                tab.style.borderColor = isActive ? 'var(--color-accent-400)' : 'transparent';
            });
        }

        function searchItems(query) {
            const q = query.toLowerCase().trim();
            document.querySelectorAll('.shop-item-card').forEach(card => {
                const name = (card.dataset.name || '').toLowerCase();
                card.style.display = (!q || name.includes(q)) ? '' : 'none';
            });
        }
    </script>

    @stack('scripts')
</body>
</html>
