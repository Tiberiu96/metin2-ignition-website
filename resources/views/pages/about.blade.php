@extends('layouts.app')

@section('title', __('about_title'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-lg font-bold uppercase tracking-widest text-center" style="color: var(--color-accent-400); margin-bottom: 4rem;">{{ __('about_title') }}</h1>

        <div class="flex flex-col gap-6 text-xs leading-relaxed" style="color: var(--color-game-muted);">

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">{{ __('about_who_title') }}</h2>
                <p>{{ __('about_who_text') }}</p>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">{{ __('about_mission_title') }}</h2>
                <p>{{ __('about_mission_text') }}</p>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                <div class="rounded p-4 text-center" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <p class="text-lg font-bold mb-1" style="color: var(--color-accent-400)">x50</p>
                    <p class="text-[10px] uppercase tracking-widest">{{ __('about_stat_exp') }}</p>
                </div>
                <div class="rounded p-4 text-center" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <p class="text-lg font-bold mb-1" style="color: var(--color-accent-400)">x10</p>
                    <p class="text-[10px] uppercase tracking-widest">{{ __('about_stat_drop') }}</p>
                </div>
                <div class="col-span-2 sm:col-span-1 rounded p-4 text-center" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <p class="text-lg font-bold mb-1" style="color: var(--color-accent-400)">120</p>
                    <p class="text-[10px] uppercase tracking-widest">{{ __('about_stat_level') }}</p>
                </div>
            </div>

            <div class="rounded p-6" style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest mb-3" style="color: var(--color-game-text)">{{ __('about_disclaimer_title') }}</h2>
                <p>{{ __('about_disclaimer_text') }}</p>
            </div>

        </div>
    </div>
@endsection
