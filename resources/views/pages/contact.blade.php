@extends('layouts.app')

@section('title', __('contact_title'))

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-lg font-bold uppercase tracking-widest text-center" style="color: var(--color-accent-400); margin-bottom: 4rem;">{{ __('contact_title') }}</h1>

        <div class="flex flex-col gap-4">

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <p class="text-xs leading-relaxed mb-6" style="color: var(--color-game-muted)">{{ __('contact_intro') }}</p>

                <div class="flex flex-col gap-4">

                    <div class="flex items-center gap-3">
                        <div class="shrink-0 w-8 h-8 rounded flex items-center justify-center" style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border);">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--color-accent-400)">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest mb-0.5" style="color: var(--color-game-muted)">{{ __('contact_email_label') }}</p>
                            <a href="mailto:contact@{{ config('app.name') | lower }}.com" class="text-xs hover:underline" style="color: var(--color-game-text)">
                                contact@metin2ignition.com
                            </a>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <div class="shrink-0 w-8 h-8 rounded flex items-center justify-center" style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border);">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor" style="color: var(--color-accent-400)">
                                <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03z"/>
                            </svg>
                        </div>
                        <div>
                            <p class="text-[10px] uppercase tracking-widest mb-0.5" style="color: var(--color-game-muted)">Discord</p>
                            <a href="#" class="text-xs hover:underline" style="color: var(--color-game-text)">
                                {{ __('contact_discord_label') }}
                            </a>
                        </div>
                    </div>

                </div>
            </div>

            <div class="rounded p-6 text-xs" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border); color: var(--color-game-muted);">
                <p>{{ __('contact_response_time') }}</p>
            </div>

        </div>
    </div>
@endsection
