<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Metin2\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        $request->validate([
            'pid' => ['required', 'integer'],
            'sas' => ['required', 'string', 'size:32'],
            'ts' => ['required', 'integer'],
            'c' => ['nullable', 'string', 'max:5'],
            'sid' => ['nullable', 'integer'],
        ]);

        $timestamp = $request->integer('ts');

        if (abs(time() - $timestamp) > 60) {
            return redirect()->route('login')
                ->withErrors(['login' => __('login_failed')]);
        }

        $player = Player::find($request->integer('pid'));

        if (! $player) {
            return redirect()->route('login')
                ->withErrors(['login' => __('login_failed')]);
        }

        $account = $player->account;

        if (! $account || $account->status !== 'OK') {
            return redirect()->route('login')
                ->withErrors(['login' => __('login_failed')]);
        }

        $hash1 = md5($player->id . $account->id . $timestamp . config('services.game.sas_key1'));
        $expectedSas = md5($hash1 . config('services.game.sas_key2'));

        if (! hash_equals($expectedSas, $request->input('sas'))) {
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

        return redirect()->route('ishop.browse');
    }
}
