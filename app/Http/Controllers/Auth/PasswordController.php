<?php

namespace App\Http\Controllers\Auth;

use App\Hashing\MysqlPasswordHasher;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Models\Metin2\Account;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PasswordController extends Controller
{
    public function __construct(private readonly MysqlPasswordHasher $hasher) {}

    public function showForgotForm(): View
    {
        return view('pages.forgot-password');
    }

    public function forgot(ForgotPasswordRequest $request): RedirectResponse
    {
        $account = Account::where('login', $request->login)
            ->where('email', $request->email)
            ->first();

        if (! $account) {
            return back()->withErrors([
                'login' => __('forgot_password_not_found'),
            ])->onlyInput('login');
        }

        $account->password = $this->hasher->make($request->password);
        $account->save();

        return redirect()->route('login')->with('success', __('forgot_password_success'));
    }

    public function showChangeForm(): View
    {
        return view('pages.change-password');
    }

    public function change(ChangePasswordRequest $request): RedirectResponse
    {
        /** @var Account $account */
        $account = Auth::guard('metin2')->user();

        if (! $this->hasher->check($request->current_password, $account->password)) {
            return back()->withErrors([
                'current_password' => __('change_password_wrong_current'),
            ]);
        }

        $account->password = $this->hasher->make($request->password);
        $account->save();

        return back()->with('success', __('change_password_success'));
    }
}
