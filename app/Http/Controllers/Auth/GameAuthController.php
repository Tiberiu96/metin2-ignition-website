<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Metin2\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class GameAuthController extends Controller
{
    /**
     * Handle in-game browser shop authentication.
     *
     * URL: /ishop?pid={player_id}&c={locale}&sid={server_id}&sas={hash}&ts={unix_timestamp}
     *
     * SAS validation: md5(md5(pid + account_id + timestamp + key1) + key2)
     */
    public function ishop(Request $request): RedirectResponse
    {
        Log::channel('single')->info('[ISHOP] Request received', [
            'ip' => $request->ip(),
            'params' => $request->only(['pid', 'sas', 'ts', 'c', 'sid']),
            'all' => $request->all(),
        ]);

        $validated = $request->validate([
            'pid' => ['required', 'integer'],
            'sas' => ['required', 'string', 'size:32'],
            'ts' => ['required', 'integer'],
            'c' => ['nullable', 'string', 'max:5'],
            'sid' => ['nullable', 'integer'],
        ]);

        Log::channel('single')->info('[ISHOP] Validation passed', $validated);

        $timestamp = $request->integer('ts');
        $serverTime = time();
        $timeDiff = abs($serverTime - $timestamp);

        Log::channel('single')->info('[ISHOP] Timestamp check', [
            'ts_from_client' => $timestamp,
            'server_time' => $serverTime,
            'diff_seconds' => $timeDiff,
            'allowed_window' => 60,
            'valid' => $timeDiff <= 60,
        ]);

        if ($timeDiff > 60) {
            Log::channel('single')->warning('[ISHOP] FAILED: timestamp expired', [
                'diff_seconds' => $timeDiff,
            ]);

            return redirect()->route('login')
                ->withErrors(['login' => __('login_failed')]);
        }

        $pid = $request->integer('pid');
        $player = Player::find($pid);

        Log::channel('single')->info('[ISHOP] Player lookup', [
            'pid' => $pid,
            'found' => $player !== null,
        ]);

        if (! $player) {
            Log::channel('single')->warning('[ISHOP] FAILED: player not found', ['pid' => $pid]);

            return redirect()->route('login')
                ->withErrors(['login' => __('login_failed')]);
        }

        $account = $player->account;

        Log::channel('single')->info('[ISHOP] Account lookup', [
            'account_id' => $account?->id,
            'status' => $account?->status,
            'found' => $account !== null,
        ]);

        if (! $account || $account->status !== 'OK') {
            Log::channel('single')->warning('[ISHOP] FAILED: account missing or not OK', [
                'account_id' => $account?->id,
                'status' => $account?->status,
            ]);

            return redirect()->route('login')
                ->withErrors(['login' => __('login_failed')]);
        }

        $key1Set = ! empty(config('services.game.sas_key1'));
        $key2Set = ! empty(config('services.game.sas_key2'));
        $hash1 = md5($player->id.$account->id.$timestamp.config('services.game.sas_key1'));
        $expectedSas = md5($hash1.config('services.game.sas_key2'));

        Log::channel('single')->info('[ISHOP] SAS verification', [
            'sas_key1_configured' => $key1Set,
            'sas_key2_configured' => $key2Set,
            'hash_input' => $player->id.$account->id.$timestamp.'(key1)',
            'hash1' => $hash1,
            'expected_sas' => $expectedSas,
            'received_sas' => $request->input('sas'),
            'match' => hash_equals($expectedSas, $request->input('sas')),
        ]);

        if (! hash_equals($expectedSas, $request->input('sas'))) {
            Log::channel('single')->warning('[ISHOP] FAILED: SAS hash mismatch', [
                'expected' => $expectedSas,
                'received' => $request->input('sas'),
            ]);

            return redirect()->route('login')
                ->withErrors(['login' => __('login_failed')]);
        }

        Auth::guard('metin2')->login($account);
        $request->session()->regenerate();

        $locale = $request->input('c');
        $supportedLocales = config('app.supported_locales', ['en']);

        if ($locale && in_array($locale, $supportedLocales)) {
            $request->session()->put('locale', $locale);
            app()->setLocale($locale);
        }

        Log::channel('single')->info('[ISHOP] SUCCESS: logged in', [
            'account_id' => $account->id,
            'player_id' => $player->id,
            'locale' => $locale,
        ]);

        return redirect()->route('ishop.browse');
    }
}
