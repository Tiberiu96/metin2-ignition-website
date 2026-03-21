@extends('layouts.app')

@section('title', __('ranking_title'))

@section('content')
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <h1 class="text-sm font-bold uppercase tracking-widest mb-6" style="color: var(--color-accent-400)">{{ __('ranking_title') }}</h1>

        <div class="rounded overflow-hidden"
             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
            <table class="w-full text-xs">
                <thead>
                    <tr style="background-color: var(--color-game-surface); border-bottom: 1px solid var(--color-game-border); color: var(--color-game-muted);">
                        <th class="px-4 py-3 text-left font-medium w-12">#</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('ranking_player') }}</th>
                        <th class="px-4 py-3 text-left font-medium">{{ __('ranking_class') }}</th>
                        <th class="px-4 py-3 text-right font-medium">{{ __('ranking_level') }}</th>
                        <th class="px-4 py-3 text-right font-medium hidden sm:table-cell">{{ __('ranking_playtime') }}</th>
                        <th class="px-4 py-3 text-right font-medium w-10"></th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $crowns = [
                            1 => '/images/crown/gold-crown.png',
                            2 => '/images/crown/silver-crown.png',
                            3 => '/images/crown/bronze-crown.png',
                        ];
                        $empires = [
                            1 => '/images/empires/empire-red.png',
                            2 => '/images/empires/empire-yellow.png',
                            3 => '/images/empires/empire-blue.png',
                        ];
                    @endphp

                    @forelse($players ?? [] as $i => $player)
                        @php $rank = $i + 1; @endphp
                        <tr style="border-bottom: 1px solid var(--color-game-border); {{ $rank <= 3 ? 'background-color: rgba(255,255,255,0.02);' : '' }}">
                            <td class="px-4 py-3 w-12">
                                @if(isset($crowns[$rank]))
                                    <img src="{{ $crowns[$rank] }}" alt="{{ $rank }}" class="w-6 h-6 object-contain">
                                @else
                                    <span class="font-bold" style="color: var(--color-game-muted)">{{ $rank }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 font-semibold" style="color: var(--color-game-text)">{{ $player->name }}</td>
                            <td class="px-4 py-3" style="color: var(--color-game-muted)">{{ $player->job_name }}</td>
                            <td class="px-4 py-3 text-right font-bold" style="color: var(--color-accent-400)">{{ $player->level }}</td>
                            <td class="px-4 py-3 text-right hidden sm:table-cell" style="color: var(--color-game-muted)">{{ $player->playtime_hours }}h</td>
                            <td class="px-4 py-3 text-right">
                                @if(isset($empires[$player->empire ?? 0]))
                                    <img src="{{ $empires[$player->empire] }}" alt="empire {{ $player->empire }}" class="w-6 h-6 object-contain inline-block">
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-xs" style="color: var(--color-game-muted)">
                                {{ __('ranking_no_players') }}
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
