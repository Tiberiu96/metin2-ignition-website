<footer style="background-color: var(--color-game-surface); border-top: 1px solid var(--color-game-border);">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex flex-col items-center gap-6">

            {{-- Logo --}}
            <span class="text-2xl font-bold tracking-widest" style="color: var(--color-accent-400);">
                {{ config('app.name', 'IGNITION') }}
            </span>

            {{-- Links --}}
            <div class="grid grid-cols-2 sm:flex sm:flex-wrap justify-center gap-x-6 gap-y-3 text-xs text-center" style="color: var(--color-game-muted);">
                <a href="{{ route('terms') }}" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_terms') }}</a>
                <a href="{{ route('refund') }}" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_refund') }}</a>
                <a href="{{ route('privacy') }}" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_privacy') }}</a>
                <a href="{{ route('contact') }}" class="hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_contact') }}</a>
                <a href="{{ route('about') }}" class="col-span-2 sm:col-span-1 hover:text-[var(--color-game-text)] transition-colors">{{ __('footer_about') }}</a>
            </div>

            {{-- Credit --}}
            <div class="text-xs" style="color: var(--color-game-muted);">
                &copy; {{ date('Y') }} {{ config('app.name') }}. {{ __('footer_rights') }}
            </div>
        </div>
    </div>
</footer>
