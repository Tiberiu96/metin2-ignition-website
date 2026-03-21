@extends('layouts.app')

@section('title', __('refund_title'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-lg font-bold uppercase tracking-widest text-center" style="color: var(--color-accent-400); margin-bottom: 4rem;">{{ __('refund_title') }}</h1>

        <div class="flex flex-col gap-6 text-xs leading-relaxed" style="color: var(--color-game-muted);">

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">1. {{ __('refund_general_title') }}</h2>
                <p>{{ __('refund_general_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">2. {{ __('refund_exceptions_title') }}</h2>
                <p class="mb-3">{{ __('refund_exceptions_text') }}</p>
                <ul class="flex flex-col gap-1 list-disc list-inside">
                    <li>{{ __('refund_exception_1') }}</li>
                    <li>{{ __('refund_exception_2') }}</li>
                    <li>{{ __('refund_exception_3') }}</li>
                </ul>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">3. {{ __('refund_process_title') }}</h2>
                <p>{{ __('refund_process_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">4. {{ __('refund_contact_title') }}</h2>
                <p>{{ __('refund_contact_text') }}</p>
            </div>

        </div>
    </div>
@endsection
