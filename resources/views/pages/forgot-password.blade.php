@extends('layouts.app')

@section('title', __('forgot_password_title'))

@section('content')
    <div class="max-w-sm mx-auto px-4 py-12">
        <div class="rounded p-8 flex flex-col gap-5"
             style="background-color: var(--color-game-panel); border: 1px solid var(--color-game-border);">
            <h1 class="text-sm font-bold uppercase tracking-widest text-center" style="color: var(--color-accent-400)">
                {{ __('forgot_password_title') }}
            </h1>

            <p class="text-[10px] text-center" style="color: var(--color-game-muted)">
                {{ __('forgot_password_desc') }}
            </p>

            @if (session('success'))
                <div class="text-xs text-center px-3 py-2 rounded"
                     style="background-color: rgba(34,197,94,0.1); border: 1px solid rgba(34,197,94,0.2); color: #4ade80;">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.forgot') }}" class="flex flex-col gap-3">
                @csrf
                {{-- Honeypot: hidden from humans, bots fill it --}}
                <div style="position:absolute;left:-9999px;height:0;overflow:hidden;" aria-hidden="true">
                    <input type="text" name="website" value="" tabindex="-1" autocomplete="off">
                </div>

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
                    <label class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('register_email') }}</label>
                    <input type="email" name="email" value="{{ old('email') }}"
                           class="w-full px-3 py-2 text-xs rounded outline-none"
                           style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                    @error('email')
                        <span class="text-[10px] text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('forgot_password_new') }}</label>
                    <input type="password" name="password"
                           class="w-full px-3 py-2 text-xs rounded outline-none"
                           style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                    @error('password')
                        <span class="text-[10px] text-red-400">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex flex-col gap-1">
                    <label class="text-[10px] uppercase tracking-widest" style="color: var(--color-game-muted)">{{ __('register_confirm_password') }}</label>
                    <input type="password" name="password_confirmation"
                           class="w-full px-3 py-2 text-xs rounded outline-none"
                           style="background-color: var(--color-game-bg); border: 1px solid var(--color-game-border); color: var(--color-game-text);">
                </div>

                <button type="submit"
                        class="w-full py-2 mt-2 text-xs font-bold uppercase tracking-widest rounded transition-colors duration-150"
                        style="background-color: var(--color-accent-600); color: #fff;">
                    {{ __('forgot_password_btn') }}
                </button>
            </form>

            <p class="text-center text-[10px]" style="color: var(--color-game-muted)">
                <a href="{{ route('login') }}" class="hover:text-[var(--color-accent-400)] transition-colors" style="color: var(--color-game-text)">{{ __('forgot_password_back_login') }}</a>
            </p>
        </div>
    </div>
@endsection
