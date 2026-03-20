<?php

namespace App\Http\Controllers;

use App\Models\Metin2\Account;
use App\Models\Metin2\Guild;
use App\Models\Metin2\Player;
use App\Models\Web\News;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        $stats = Cache::remember('home_stats', 300, function () {
            return [
                'online' => Player::query()->where('last_play', '>=', now()->subMinutes(5))->count(),
                'online_24h' => Player::query()->where('last_play', '>=', now()->subDay())->count(),
                'accounts' => Account::query()->count(),
                'players' => Player::query()->count(),
                'guilds' => Guild::query()->count(),
            ];
        });

        $topPlayers = Cache::remember('home_top_players', 300, function () {
            return Player::query()
                ->select(['player.id', 'player.account_id', 'player.name', 'player.level', 'player.exp', 'player_index.empire'])
                ->leftJoin('player_index', 'player_index.id', '=', 'player.account_id')
                ->orderByDesc('player.level')
                ->orderByDesc('player.exp')
                ->limit(10)
                ->get();
        });

        $news = News::query()
            ->where('is_published', true)
            ->orderByDesc('published_at')
            ->limit(5)
            ->get();

        $discord = $this->fetchDiscordWidget();

        return view('welcome', compact('stats', 'topPlayers', 'news', 'discord'));
    }

    private function fetchDiscordWidget(): ?array
    {
        $serverId = config('services.discord.server_id');

        if (! $serverId) {
            return null;
        }

        try {
            return Cache::remember('discord_widget', 300, function () use ($serverId) {
                $response = Http::timeout(5)->get("https://discord.com/api/guilds/{$serverId}/widget.json");

                if (! $response->ok()) {
                    return null;
                }

                $data = $response->json();

                return [
                    'name' => $data['name'] ?? null,
                    'online' => count($data['members'] ?? []),
                    'instant_invite' => $data['instant_invite'] ?? null,
                ];
            });
        } catch (\Throwable $e) {
            return null;
        }
    }
}
