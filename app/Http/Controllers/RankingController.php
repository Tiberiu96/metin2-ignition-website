<?php

namespace App\Http\Controllers;

use App\Models\Metin2\Player;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class RankingController extends Controller
{
    public function index(): View
    {
        $players = Cache::remember('ranking_top100', 300, function () {
            return Player::query()
                ->select(['player.id', 'player.account_id', 'player.name', 'player.job', 'player.level', 'player.exp', 'player.playtime', 'player_index.empire'])
                ->leftJoin('player_index', 'player_index.id', '=', 'player.account_id')
                ->excludeStaff()
                ->orderByDesc('level')
                ->orderByDesc('exp')
                ->limit(100)
                ->get();
        });

        return view('pages.ranking', compact('players'));
    }
}
