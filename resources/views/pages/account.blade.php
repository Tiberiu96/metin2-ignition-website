@extends('layouts.app')

@section('title', __('account_title'))

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-10 flex flex-col gap-6">

        {{-- Account details --}}
        <div class="rounded p-6 flex flex-col gap-4"
             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">

            <h1 class="text-sm font-bold uppercase tracking-widest" style="color: var(--color-gold-400)">
                {{ __('account_title') }}
            </h1>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('panel_username') }}</span>
                    <span style="color: var(--color-game-text)">{{ $account->login }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('register_email') }}</span>
                    <span style="color: var(--color-game-text)">{{ $account->email }}</span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('account_status') }}</span>
                    <span class="{{ $account->status === 'OK' ? 'text-green-400' : 'text-red-400' }}">
                        {{ $account->status === 'OK' ? __('account_status_ok') : __('account_status_blocked') }}
                    </span>
                </div>
                <div class="flex flex-col gap-1">
                    <span class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('account_registered') }}</span>
                    <span style="color: var(--color-game-text)">{{ $account->create_time ?? '—' }}</span>
                </div>
            </div>
        </div>

        {{-- Characters --}}
        <div class="rounded overflow-hidden"
             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
            <div class="px-6 py-3" style="border-bottom: 1px solid var(--color-game-border);">
                <h2 class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-gold-400)">
                    {{ __('account_characters') }}
                </h2>
            </div>

            @if($characters->isEmpty())
                <p class="px-6 py-4 text-xs" style="color: var(--color-game-muted)">{{ __('account_no_characters') }}</p>
            @else
                <table class="w-full text-xs">
                    <thead>
                        <tr style="border-bottom: 1px solid var(--color-game-border); color: var(--color-game-muted);">
                            <th class="px-6 py-2 text-left font-medium text-[10px] uppercase tracking-widest">{{ __('ranking_player') }}</th>
                            <th class="px-4 py-2 text-left font-medium text-[10px] uppercase tracking-widest">{{ __('ranking_class') }}</th>
                            <th class="px-4 py-2 text-left font-medium text-[10px] uppercase tracking-widest">{{ __('ranking_level') }}</th>
                            <th class="px-4 py-2 text-left font-medium text-[10px] uppercase tracking-widest hidden sm:table-cell">{{ __('ranking_playtime') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($characters as $char)
                            <tr style="border-bottom: 1px solid var(--color-game-border);">
                                <td class="px-6 py-2 font-semibold" style="color: var(--color-game-text)">{{ $char->name }}</td>
                                <td class="px-4 py-2" style="color: var(--color-game-muted)">{{ $char->job_name }}</td>
                                <td class="px-4 py-2 font-bold" style="color: var(--color-gold-400)">{{ $char->level }}</td>
                                <td class="px-4 py-2 hidden sm:table-cell" style="color: var(--color-game-muted)">{{ $char->playtime_hours }}h</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Change password --}}
        <div id="password" class="rounded p-6 flex flex-col gap-4"
             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">

            <h2 class="text-xs font-bold uppercase tracking-widest" style="color: var(--color-gold-400)">
                {{ __('change_password_title') }}
            </h2>

            @if (session('password_success'))
                <div class="text-xs px-3 py-2 rounded"
                     style="background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); color: #4ade80;">
                    {{ session('password_success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('account.password') }}" class="flex flex-col gap-3 max-w-sm">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('change_password_current') }}</label>
                    <input type="password" name="current_password" autocomplete="current-password"
                           class="w-full px-3 py-2 text-xs rounded outline-none"
                           style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                    @error('current_password')
                        <span class="text-[10px] text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('forgot_password_new') }}</label>
                    <input type="password" name="password" autocomplete="new-password"
                           class="w-full px-3 py-2 text-xs rounded outline-none"
                           style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                    @error('password')
                        <span class="text-[10px] text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('register_confirm_password') }}</label>
                    <input type="password" name="password_confirmation" autocomplete="new-password"
                           class="w-full px-3 py-2 text-xs rounded outline-none"
                           style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                </div>

                <button type="submit"
                        class="w-full py-2 mt-1 text-xs font-bold uppercase tracking-widest rounded transition-colors duration-150"
                        style="background-color: var(--color-gold-600); color: #fff;">
                    {{ __('change_password_btn') }}
                </button>
            </form>
        </div>

    </div>
@endsection
