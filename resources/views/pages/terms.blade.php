@extends('layouts.app')

@section('title', __('terms_title'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-lg font-bold uppercase tracking-widest text-center" style="color: var(--color-gold-400); margin-bottom: 4rem;">{{ __('terms_title') }}</h1>

        <div class="flex flex-col gap-6 text-xs leading-relaxed" style="color: var(--color-game-muted);">

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">1. {{ __('terms_acceptance_title') }}</h2>
                <p>{{ __('terms_acceptance_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">2. {{ __('terms_service_title') }}</h2>
                <p>{{ __('terms_service_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">3. {{ __('terms_accounts_title') }}</h2>
                <p>{{ __('terms_accounts_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">4. {{ __('terms_conduct_title') }}</h2>
                <p class="mb-3">{{ __('terms_conduct_text') }}</p>
                <ul class="flex flex-col gap-1 list-disc list-inside">
                    <li>{{ __('terms_conduct_1') }}</li>
                    <li>{{ __('terms_conduct_2') }}</li>
                    <li>{{ __('terms_conduct_3') }}</li>
                    <li>{{ __('terms_conduct_4') }}</li>
                </ul>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">5. {{ __('terms_shop_title') }}</h2>
                <p>{{ __('terms_shop_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">6. {{ __('terms_liability_title') }}</h2>
                <p>{{ __('terms_liability_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">7. {{ __('terms_ip_title') }}</h2>
                <p>{{ __('terms_ip_text') }}</p>
            </div>

        </div>
    </div>
@endsection
