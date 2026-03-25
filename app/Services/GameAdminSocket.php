<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GameAdminSocket
{
    /**
     * Send a raw command to the game server and return the response.
     * Returns false if the connection fails or the response cannot be read.
     */
    public function command(string $command): string|false
    {
        $host = config('game.adminpage_host');
        $port = config('game.adminpage_port');
        $password = config('game.adminpage_password');
        $timeout = config('game.adminpage_timeout', 3);

        $socket = @fsockopen($host, $port, $errno, $errstr, $timeout);

        if (! $socket) {
            Log::error("GameAdminSocket: cannot connect to {$host}:{$port} — {$errstr} ({$errno})");

            return false;
        }

        stream_set_timeout($socket, $timeout);

        // Read initial binary handshake packet sent by game server (0xFD header, 15 bytes)
        fread($socket, 15);

        // Authenticate: HEADER_CG_TEXT (0x40 = '@') prefix required before password
        fwrite($socket, '@'.$password."\n");
        fgets($socket); // reads "UNKNOWN" acknowledgement

        // Send command with '@' prefix, read response
        fwrite($socket, '@'.$command."\n");
        $response = fgets($socket);

        fclose($socket);

        if ($response === false) {
            Log::warning("GameAdminSocket: no response for command [{$command}]");

            return false;
        }

        return trim($response);
    }

    /**
     * Check if the game server is reachable (cached 30s).
     */
    public function isServerUp(): bool
    {
        return Cache::remember('game_server_up', 30, function (): bool {
            $response = $this->command('IS_SERVER_UP');

            return $response !== false;
        });
    }

    /**
     * Get the number of online players (cached 30s).
     */
    public function getUserCount(): int
    {
        return Cache::remember('game_user_count', 30, function (): int {
            $response = $this->command('USER_COUNT');

            if ($response === false) {
                return 0;
            }

            // Response format: "[total] [ch1] [ch2] [ch3] [ch4]", e.g. "1 0 0 1 0"
            $parts = explode(' ', trim($response));

            return (int) ($parts[0] ?? 0);
        });
    }

    /**
     * Set a game event flag via the EVENT command.
     */
    public function setEventFlag(string $flag, int|string $value): bool
    {
        $response = $this->command("EVENT {$flag} {$value}");

        if ($response === false) {
            return false;
        }

        return str_contains($response, 'EVENT FLAG CHANGE');
    }

    /**
     * Broadcast a notice to all online players.
     */
    public function sendNotice(string $message): bool
    {
        $response = $this->command("NOTICE {$message}");

        return $response !== false;
    }

    /**
     * Reload all quests on the game server.
     */
    public function reloadQuests(): bool
    {
        $response = $this->command('RELOAD q');

        return $response !== false;
    }

    /**
     * Disconnect a player by login name.
     */
    public function disconnectPlayer(string $login): bool
    {
        $response = $this->command("DC {$login}");

        return $response !== false;
    }

    /**
     * Invalidate cached server status (call after commands that change server state).
     */
    public function flushStatusCache(): void
    {
        Cache::forget('game_server_up');
        Cache::forget('game_user_count');
    }
}
