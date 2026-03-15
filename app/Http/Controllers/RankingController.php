<?php

namespace App\Http\Controllers;

use App\Models\Metin2\Player;
use Illuminate\View\View;

class RankingController extends Controller
{
    public function index(): View
    {
        $players = Player::query()
            ->orderByDesc('level')
            ->orderByDesc('exp')
            ->limit(100)
            ->get();

        return view('pages.ranking', compact('players'));
    }
}
