<?php

namespace App\Http\Controllers;

use App\Hashing\MysqlPasswordHasher;
use App\Http\Requests\Auth\ChangePasswordRequest;
use App\Models\Metin2\Account;
use App\Models\Metin2\Player;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function __construct(private readonly MysqlPasswordHasher $hasher) {}

    public function show(): View
    {
        /** @var Account $account */
        $account = Auth::guard('metin2')->user();

        $characters = Player::where('account_id', $account->id)
            ->orderBy('level', 'desc')
            ->get(['id', 'name', 'job', 'level', 'playtime', 'last_play']);

        return view('pages.account', compact('account', 'characters'));
    }

    public function changePassword(ChangePasswordRequest $request): RedirectResponse
    {
        /** @var Account $account */
        $account = Auth::guard('metin2')->user();

        if (! $this->hasher->check($request->current_password, $account->password)) {
            return back()->withErrors([
                'current_password' => __('change_password_wrong_current'),
            ])->withFragment('password');
        }

        $account->password = $this->hasher->make($request->password);
        $account->save();

        return redirect()->route('account')->with('password_success', __('change_password_success'))->withFragment('password');
    }
}
