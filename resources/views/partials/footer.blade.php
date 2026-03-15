<footer style="background-color: var(--color-game-surface); border-top: 1px solid var(--color-game-border);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">

            {{-- Logo --}}
            <div class="flex-shrink-0">
                <span class="text-2xl font-bold tracking-widest" style="color: var(--color-gold-400);">
                    {{ config('app.name', 'IGNITION') }}
                </span>
            </div>

            {{-- Links --}}
            <div class="flex flex-wrap justify-center gap-x-6 gap-y-2 text-xs" style="color: var(--color-game-muted);">
                <a href="#" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_terms') }}</a>
                <a href="#" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_refund') }}</a>
                <a href="#" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_privacy') }}</a>
                <a href="#" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_contact') }}</a>
                <a href="#" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_about') }}</a>
            </div>

            {{-- Credit --}}
            <div class="text-xs" style="color: var(--color-game-muted);">
                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('footer_rights') }}
            </div>
        </div>
    </div>
</footer>
