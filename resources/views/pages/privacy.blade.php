@extends('layouts.app')

@section('title', __('privacy_title'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-lg font-bold uppercase tracking-widest text-center" style="color: var(--color-gold-400); margin-bottom: 4rem;">{{ __('privacy_title') }}</h1>

        <div class="flex flex-col gap-6 text-xs leading-relaxed" style="color: var(--color-game-muted);">

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">1. {{ __('privacy_data_title') }}</h2>
                <p class="mb-3">{{ __('privacy_data_text') }}</p>
                <ul class="flex flex-col gap-1 list-disc list-inside">
                    <li>{{ __('privacy_data_1') }}</li>
                    <li>{{ __('privacy_data_2') }}</li>
                    <li>{{ __('privacy_data_3') }}</li>
                    <li>{{ __('privacy_data_4') }}</li>
                </ul>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">2. {{ __('privacy_purpose_title') }}</h2>
                <p class="mb-3">{{ __('privacy_purpose_text') }}</p>
                <ul class="flex flex-col gap-1 list-disc list-inside">
                    <li>{{ __('privacy_purpose_1') }}</li>
                    <li>{{ __('privacy_purpose_2') }}</li>
                    <li>{{ __('privacy_purpose_3') }}</li>
                </ul>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">3. {{ __('privacy_storage_title') }}</h2>
                <p>{{ __('privacy_storage_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">4. {{ __('privacy_rights_title') }}</h2>
                <p class="mb-3">{{ __('privacy_rights_text') }}</p>
                <ul class="flex flex-col gap-1 list-disc list-inside">
                    <li>{{ __('privacy_right_1') }}</li>
                    <li>{{ __('privacy_right_2') }}</li>
                    <li>{{ __('privacy_right_3') }}</li>
                    <li>{{ __('privacy_right_4') }}</li>
                </ul>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">5. {{ __('privacy_cookies_title') }}</h2>
                <p>{{ __('privacy_cookies_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">6. {{ __('privacy_contact_title') }}</h2>
                <p>{{ __('privacy_contact_text') }}</p>
            </div>

        </div>
    </div>
@endsection
