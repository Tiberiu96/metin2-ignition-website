<?php

namespace App\Http\Controllers\Auth;

use App\Hashing\MysqlPasswordHasher;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\Metin2\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function show(): View
    {
        return view('pages.register');
    }

    public function store(RegisterRequest $request): RedirectResponse
    {

        $hasher = new MysqlPasswordHasher;

        Account::create([
            'login' => $request->login,
            'password' => $hasher->make($request->password),
            'email' => $request->email,
            'social_id' => $request->social_id,
            'status' => 'OK',
            'create_time' => now(),
        ]);

        return redirect()->route('login')->with('success', __('register_success'));
    }
}
