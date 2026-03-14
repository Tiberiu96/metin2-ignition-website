<?php

namespace App\Services;

use App\Models\Metin2\Account;
use App\Models\Metin2\Player;
use Carbon\Carbon;

class PlayerService
{
    public function banAccount(Account $account, Carbon $until): void
    {
        $account->update([
            'status' => 'BLOCK',
            'availdt' => $until,
        ]);
    }

    public function unbanAccount(Account $account): void
    {
        $account->update([
            'status' => 'OK',
            'availdt' => null,
        ]);
    }

    public function teleportPlayer(Player $player, int $mapIndex, int $x, int $y): void
    {
        $player->update([
            'map_index' => $mapIndex,
            'x' => $x,
            'y' => $y,
        ]);
    }

    public function setLevel(Player $player, int $level): void
    {
        $player->update([
            'level' => $level,
        ]);
    }
}
