@extends('layouts.app')

@section('title', __('login_title'))

@section('content')
    <div class="max-w-sm mx-auto px-4 py-12">
        <div class="rounded p-8 flex flex-col gap-5"
             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
            <h1 class="text-sm font-bold uppercase tracking-widest text-center" style="color: var(--color-gold-400)">
                {{ __('login_title') }}
            </h1>

            @if (session('error'))
                <div class="text-xs text-center text-red-400 px-3 py-2 rounded"
                     style="background-color: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2);">
                    {{ session('error') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" class="flex flex-col gap-3">
                @csrf

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('panel_username') }}</label>
                    <input type="text" name="login" value="{{ old('login') }}" autofocus
                           class="w-full px-3 py-2 text-xs rounded outline-none"
                           style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                    @error('login')
                        <span class="text-[10px] text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('panel_password') }}</label>
                    <input type="password" name="password"
                           class="w-full px-3 py-2 text-xs rounded outline-none"
                           style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                    @error('password')
                        <span class="text-[10px] text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit"
                        class="w-full py-2 mt-2 text-xs font-bold uppercase tracking-widest rounded transition-colors duration-150"
                        style="background-color: var(--color-gold-600); color: #fff;">
                    {{ __('panel_login') }}
                </button>
            </form>

            <p class="text-center text-[10px]" style="color: var(--color-game-muted)">
                {{ __('login_no_account') }}
                <a href="{{ route('register') }}" class="hover:text-[var(--color-gold-400)] transition-colors" style="color: var(--color-game-text)">{{ __('login_register') }}</a>
            </p>
        </div>
    </div>
@endsection
