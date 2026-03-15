<?php

namespace App\Http\Controllers\Auth;

use App\Hashing\MysqlPasswordHasher;
use App\Http\Controllers\Controller;
use App\Models\Metin2\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('pages.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'login' => ['required', 'string', 'min:4', 'max:30', 'unique:account.account,login'],
            'email' => ['required', 'email', 'max:100', 'unique:account.account,email'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $hasher = new MysqlPasswordHasher;

        Account::create([
            'login' => $request->login,
            'password' => $hasher->make($request->password),
            'email' => $request->email,
            'social_id' => substr(md5(uniqid()), 0, 13),
            'status' => 'OK',
            'create_time' => now(),
        ]);

        return redirect()->route('login')->with('success', __('register_success'));
    }
}
