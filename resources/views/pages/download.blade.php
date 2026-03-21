@extends('layouts.app')

@section('title', __('download_title'))

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h1 class="text-sm font-bold uppercase tracking-widest mb-6 text-center" style="color: var(--color-accent-400)">{{ __('download_title') }}</h1>

        <div class="flex flex-col gap-4">

            <div class="rounded p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                 style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <div class="flex flex-col gap-1">
                    <h3 class="text-xs font-bold" style="color: var(--color-game-text)">{{ __('download_full_client') }}</h3>
                    <p class="text-[10px]" style="color: var(--color-game-muted)">{{ __('download_full_client_desc') }}</p>
                    <p class="text-[10px]" style="color: var(--color-game-muted)">{{ __('download_size') }}: ~1.8 GB</p>
                </div>
                <a href="#"
                   class="shrink-0 flex items-center gap-2 px-5 py-2 rounded text-xs font-bold uppercase tracking-widest transition-colors duration-150"
                   style="background-color: var(--color-accent-600); color: #fff;">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    {{ __('download_btn') }}
                </a>
            </div>

            <div class="rounded p-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4"
                 style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                <div class="flex flex-col gap-1">
                    <h3 class="text-xs font-bold" style="color: var(--color-game-text)">{{ __('download_patch_only') }}</h3>
                    <p class="text-[10px]" style="color: var(--color-game-muted)">{{ __('download_patch_only_desc') }}</p>
                    <p class="text-[10px]" style="color: var(--color-game-muted)">{{ __('download_size') }}: ~160 MB</p>
                </div>
                <a href="#"
                   class="shrink-0 flex items-center gap-2 px-5 py-2 rounded text-xs font-bold uppercase tracking-widest transition-colors duration-150"
                   style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                    </svg>
                    {{ __('download_btn') }}
                </a>
            </div>

        </div>

            <div class="rounded p-4 text-[10px] leading-relaxed" style="background-color: var(--color-game-surface); border: 1px solid var(--color-game-border); color: var(--color-game-muted);">
                <p><strong style="color: var(--color-game-text)">{{ __('download_note') }}:</strong> {{ __('download_antivirus_note') }}</p>
            </div>
    </div>
@endsection
