@extends('shop.layout')

@section('content')
    {{-- Category Sidebar --}}
    <nav class="hidden md:block w-52 shrink-0">
        <div class="sticky top-28 rounded-lg overflow-hidden" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
            <div class="py-1">
                <a href="#all" onclick="filterCategory('all')" id="cat-all"
                   class="category-link flex items-center gap-2 px-4 py-2.5 text-sm transition-colors active cursor-pointer"
                   style="color: var(--color-accent-400); background-color: var(--color-game-surface);">
                    <svg class="w-4 h-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    {{ __('shop_all_items') }}
                </a>
                @foreach($categories as $category)
                    <a href="#{{ $category->slug }}" onclick="filterCategory('{{ $category->slug }}')" id="cat-{{ $category->slug }}"
                       class="category-link flex items-center gap-2 px-4 py-2.5 text-sm transition-colors cursor-pointer"
                       style="color: var(--color-game-text);"
                       onmouseover="if(!this.classList.contains('active'))this.style.backgroundColor='var(--color-game-surface)'"
                       onmouseout="if(!this.classList.contains('active'))this.style.backgroundColor='transparent'">
                        @if($category->icon)
                            <span class="w-4 text-center shrink-0">{{ $category->icon }}</span>
                        @else
                            <svg class="w-4 h-4 shrink-0" style="color: var(--color-game-muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        @endif
                        {{ $category->name }}
                        <span class="ml-auto text-xs" style="color: var(--color-game-muted);">{{ $category->items->count() }}</span>
                    </a>
                @endforeach
            </div>
        </div>
    </nav>

    {{-- Mobile Category Select --}}
    <div class="md:hidden w-full mb-4">
        <select id="mobile-category" onchange="filterCategory(this.value)"
                class="w-full rounded-lg px-3 py-2 text-sm"
                style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
            <option value="all">{{ __('shop_all_items') }}</option>
            @foreach($categories as $category)
                <option value="{{ $category->slug }}">{{ $category->name }} ({{ $category->items->count() }})</option>
            @endforeach
        </select>
    </div>

    {{-- Items Grid --}}
    <div class="flex-1 min-w-0">
        {{-- Hot Items Section --}}
        @if($hotItems->isNotEmpty())
            <section class="mb-6 hot-section" id="section-hot">
                <h2 class="text-sm font-bold uppercase tracking-widest mb-3 flex items-center gap-2" style="color: var(--color-accent-400);">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('shop_popular_items') }}
                </h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                    @foreach($hotItems as $item)
                        <div class="shop-item-card relative flex items-center gap-4 rounded-lg p-4 transition-all duration-200 group"
                             data-name="{{ $item->name }}"
                             data-hot="true"
                             data-sale="{{ ($item->price_original && $item->price_original > $item->price) ? 'true' : 'false' }}"
                             data-category="{{ $item->category?->slug }}"
                             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);"
                             onmouseover="this.style.borderColor='var(--color-game-border-light)'"
                             onmouseout="this.style.borderColor='var(--color-game-border)'">
                            {{-- HOT badge --}}
                            <div class="absolute top-2 right-2 px-1.5 py-0.5 rounded text-[9px] font-bold uppercase" style="background-color: var(--color-accent-600); color: #fff;">
                                {{ __('shop_badge_hot') }}
                            </div>
                            {{-- Icon --}}
                            <div class="shrink-0 w-14 h-14 rounded-lg flex items-center justify-center" style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border);">
                                @if($item->icon_url)
                                    <img src="{{ $item->icon_url }}" alt="{{ $item->name }}" class="w-10 h-10 object-contain">
                                @else
                                    <svg class="w-7 h-7" style="color: var(--color-game-muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                @endif
                            </div>
                            {{-- Info --}}
                            <div class="flex-1 min-w-0 pr-6">
                                <h4 class="text-sm font-semibold truncate">{{ $item->name }}</h4>
                                @if($item->description)
                                    <p class="text-xs mt-0.5 line-clamp-2" style="color: var(--color-game-muted);">{{ $item->description }}</p>
                                @endif
                            </div>
                            {{-- Price + Buy --}}
                            <div class="shrink-0 flex flex-col items-end gap-2">
                                <div class="flex items-center gap-1.5 px-3 py-1 rounded-md" style="background-color: rgba(0,0,0,0.3);">
                                    @if($item->price_original && $item->price_original > $item->price)
                                        <span class="text-xs line-through" style="color: var(--color-game-muted);">{{ number_format($item->price_original) }}</span>
                                    @endif
                                    <span class="text-sm font-bold text-yellow-400">{{ number_format($item->price) }}</span>
                                    <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="8"/>
                                    </svg>
                                </div>
                                <button onclick="purchaseItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})"
                                        class="px-4 py-1 rounded text-xs font-semibold uppercase tracking-wide transition-colors cursor-pointer"
                                        style="background-color: #1a6b3c; color: #fff; border: 1px solid #23874d;"
                                        onmouseover="this.style.backgroundColor='#1f7d46'"
                                        onmouseout="this.style.backgroundColor='#1a6b3c'">
                                    {{ __('shop_buy') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif

        {{-- Category Sections --}}
        @foreach($categories as $category)
            <div class="category-section mb-6" data-category="{{ $category->slug }}">
                <h3 class="text-sm font-bold uppercase tracking-widest mb-3 pb-2 flex items-center gap-2" style="color: var(--color-accent-400); border-bottom: 1px solid var(--color-game-border);">
                    @if($category->icon)
                        <span>{{ $category->icon }}</span>
                    @endif
                    {{ $category->name }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                    @foreach($category->items as $item)
                        <div class="shop-item-card flex items-center gap-4 rounded-lg p-4 transition-all duration-200 group"
                             data-name="{{ $item->name }}"
                             data-hot="{{ $item->is_hot ? 'true' : 'false' }}"
                             data-sale="{{ ($item->price_original && $item->price_original > $item->price) ? 'true' : 'false' }}"
                             data-category="{{ $category->slug }}"
                             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);"
                             onmouseover="this.style.borderColor='var(--color-game-border-light)'"
                             onmouseout="this.style.borderColor='var(--color-game-border)'">
                            {{-- Icon --}}
                            <div class="shrink-0 w-14 h-14 rounded-lg flex items-center justify-center" style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border);">
                                @if($item->icon_url)
                                    <img src="{{ $item->icon_url }}" alt="{{ $item->name }}" class="w-10 h-10 object-contain">
                                @else
                                    <svg class="w-7 h-7" style="color: var(--color-game-muted);" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                    </svg>
                                @endif
                            </div>
                            {{-- Info --}}
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold truncate">{{ $item->name }}</h4>
                                @if($item->description)
                                    <p class="text-xs mt-0.5 line-clamp-2" style="color: var(--color-game-muted);">{{ $item->description }}</p>
                                @endif
                                @if($item->count > 1)
                                    <span class="text-[10px] mt-0.5 inline-block" style="color: var(--color-game-muted);">x{{ $item->count }}</span>
                                @endif
                            </div>
                            {{-- Price + Buy --}}
                            <div class="shrink-0 flex flex-col items-end gap-2">
                                <div class="flex items-center gap-1.5 px-3 py-1 rounded-md" style="background-color: rgba(0,0,0,0.3);">
                                    @if($item->price_original && $item->price_original > $item->price)
                                        <span class="text-xs line-through" style="color: var(--color-game-muted);">{{ number_format($item->price_original) }}</span>
                                    @endif
                                    <span class="text-sm font-bold text-yellow-400">{{ number_format($item->price) }}</span>
                                    <svg class="w-3.5 h-3.5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                        <circle cx="10" cy="10" r="8"/>
                                    </svg>
                                </div>
                                <button onclick="purchaseItem({{ $item->id }}, '{{ addslashes($item->name) }}', {{ $item->price }})"
                                        class="px-4 py-1 rounded text-xs font-semibold uppercase tracking-wide transition-colors cursor-pointer"
                                        style="background-color: #1a6b3c; color: #fff; border: 1px solid #23874d;"
                                        onmouseover="this.style.backgroundColor='#1f7d46'"
                                        onmouseout="this.style.backgroundColor='#1a6b3c'">
                                    {{ __('shop_buy') }}
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach

        @if($categories->isEmpty() || $categories->every(fn($c) => $c->items->isEmpty()))
            <div class="text-center py-20" style="color: var(--color-game-muted);">
                <svg class="w-16 h-16 mx-auto mb-4 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <p class="text-sm">{{ __('shop_no_items') }}</p>
                <p class="text-xs mt-1">{{ __('shop_check_back') }}</p>
            </div>
        @endif
    </div>
@endsection

@push('scripts')
<script>
    function filterCategory(slug) {
        const sections = document.querySelectorAll('.category-section');
        const hotSection = document.getElementById('section-hot');
        const links = document.querySelectorAll('.category-link');
        const mobileSelect = document.getElementById('mobile-category');
        const cards = document.querySelectorAll('.shop-item-card');

        // Handle tab-based filters
        if (slug === 'hot') {
            switchTab('tab-hot');
            sections.forEach(s => s.style.display = 'none');
            if (hotSection) hotSection.style.display = '';
            cards.forEach(card => {
                if (!card.closest('.hot-section')) {
                    card.style.display = card.dataset.hot === 'true' ? '' : 'none';
                }
            });
            // Reset sidebar active
            links.forEach(link => {
                link.classList.remove('active');
                link.style.color = 'var(--color-game-text)';
                link.style.backgroundColor = 'transparent';
            });
            return;
        }

        if (slug === 'sale') {
            switchTab('tab-sale');
            sections.forEach(s => s.style.display = '');
            if (hotSection) hotSection.style.display = 'none';
            cards.forEach(card => {
                card.style.display = card.dataset.sale === 'true' ? '' : 'none';
            });
            links.forEach(link => {
                link.classList.remove('active');
                link.style.color = 'var(--color-game-text)';
                link.style.backgroundColor = 'transparent';
            });
            return;
        }

        // Category or all
        switchTab('tab-all');
        if (hotSection) hotSection.style.display = (slug === 'all') ? '' : 'none';

        sections.forEach(section => {
            section.style.display = (slug === 'all' || section.dataset.category === slug) ? '' : 'none';
        });

        // Reset card visibility from tab filters
        cards.forEach(card => card.style.display = '');

        links.forEach(link => {
            const isActive = link.id === 'cat-' + slug;
            link.classList.toggle('active', isActive);
            link.style.color = isActive ? 'var(--color-accent-400)' : 'var(--color-game-text)';
            link.style.backgroundColor = isActive ? 'var(--color-game-surface)' : 'transparent';
        });

        if (mobileSelect) {
            mobileSelect.value = slug;
        }
    }
</script>
@endpush
