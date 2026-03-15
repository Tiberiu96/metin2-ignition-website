@extends('layouts.app')

@section('title', __('news_title'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-sm font-bold uppercase tracking-widest mb-6" style="color: var(--color-gold-400)">{{ __('news_title') }}</h1>

        <div class="flex flex-col gap-4">
            @forelse($news ?? [] as $item)
                <div class="rounded p-5 flex flex-col gap-2"
                     style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
                    <div class="text-[10px]" style="color: var(--color-game-muted)">
                        {{ $item->created_at->translatedFormat('l, j F Y') }}
                    </div>
                    <h2 class="text-sm font-bold" style="color: var(--color-game-text)">{{ $item->title }}</h2>
                    <p class="text-xs leading-relaxed" style="color: var(--color-game-muted)">{{ $item->excerpt }}</p>
                </div>
            @empty
                <div class="text-center text-xs py-12" style="color: var(--color-game-muted)">
                    {{ __('news_no_announcements') }}
                </div>
            @endforelse
        </div>
    </div>
@endsection
