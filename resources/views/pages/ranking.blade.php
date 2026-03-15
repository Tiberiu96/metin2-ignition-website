@extends('layouts.app')

@section('title', __('ranking_title'))

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-sm font-bold uppercase tracking-widest mb-6" style="color: var(--color-gold-400)">{{ __('ranking_title') }}</h1>

        <div class="rounded overflow-hidden"
             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
            <table class="w-full text-xs">
                <thead>
                    <tr style="background-color: var(--color-game-surface); border-bottom: 1px solid var(--color-game-border); color: var(--color-game-muted);">
                        <th class="px-4 py-3 text-left font-medium w-12">#</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('ranking_player') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('ranking_class') }}</th>
                        <th class="px-4 py-3 text-right font-medium">{{ __('ranking_level') }}</th>
                        <th class="px-4 py-3 text-right font-medium">{{ __('ranking_playtime') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($players ?? [] as $i => $player)
                        <tr style="border-bottom: 1px solid var(--color-game-border);">
                            <td class="px-4 py-3 font-bold" style="color: var(--color-game-muted)">{{ $i + 1 }}</td>
                            <td class="px-4 py-3 font-semibold" style="color: var(--color-game-text)">{{ $player->name }}</td>
                            <td class="px-4 py-3" style="color: var(--color-game-muted)">{{ $player->job_name }}</td>
                            <td class="px-4 py-3 text-right font-bold" style="color: var(--color-gold-400)">{{ $player->level }}</td>
                            <td class="px-4 py-3 text-right" style="color: var(--color-game-muted)">{{ $player->playtime }}h</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-xs" style="color: var(--color-game-muted)">
                                {{ __('ranking_no_players') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
