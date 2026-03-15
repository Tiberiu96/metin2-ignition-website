<?php

namespace App\Http\Controllers\Auth;

use App\Hashing\MysqlPasswordHasher;
use App\Http\Controllers\Controller;
use App\Models\Metin2\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LoginController extends Controller
{
    public function show(): View
    {
        return view('pages.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $account = Account::where('login', $request->login)->first();

        $hasher = new MysqlPasswordHasher;

        if ($account && $hasher->check($request->password, $account->password)) {
            Auth::guard('metin2')->login($account, $request->boolean('remember'));
            $request->session()->regenerate();

            return redirect()->intended(route('home'));
        }

        return back()->withErrors([
            'login' => __('login_failed'),
        ])->onlyInput('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::guard('metin2')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
