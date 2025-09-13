<?php

use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use function Livewire\Volt\{layout, state, rules};

/**
 * ログイン画面（Volt Functionalスタイル／marketingレイアウト）
 */
layout('components.layouts.auth.marketing');

// 状態管理
state([
    'email' => '',
    'password' => '',
    'remember' => false,
]);

// バリデーションルール
rules([
    'email' => 'required|string|email',
    'password' => 'required|string',
]);

/**
 * 認証処理
 * @return void
 */
$login = function (): void {
    $this->validate();

    $this->ensureIsNotRateLimited();

    if (!Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
        RateLimiter::hit($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ]);
    }

    RateLimiter::clear($this->throttleKey());
    Session::regenerate();

    // ログイン成功後はLaravelのダッシュボードへリダイレクト
    $this->redirectIntended(default: route('dashboard'), navigate: false);
};

/**
 * レート制限チェック
 * @return void
 */
$ensureIsNotRateLimited = function (): void {
    if (!RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
        return;
    }

    event(new Lockout(request()));

    $seconds = RateLimiter::availableIn($this->throttleKey());

    throw ValidationException::withMessages([
        'email' => __('auth.throttle', [
            'seconds' => $seconds,
            'minutes' => ceil($seconds / 60),
        ]),
    ]);
};

/**
 * スロットルキー生成
 * @return string
 */
$throttleKey = function (): string {
    return Str::transliterate(Str::lower($this->email) . '|' . request()->ip());
};
?>

<section class="auth-card reveal visible">
    <a class="brand auth-brand" href="{{ route('home') }}" aria-label="トップへ" wire:navigate>
        <img src="/images/らくらくポチッと日報.png" alt="らくラクポチッと日報 ロゴ" class="brand-logo" />
        <span class="brand-name">らくラクポチッと日報</span>
    </a>
    <h1 class="auth-title">ログイン</h1>
    <p class="auth-subtitle">ログインIDはメールアドレス、パスワードは管理部発行のものをご利用ください。</p>

    <!-- セッション・ステータスメッセージ -->
    @if (session('status'))
        <p class="auth-message" role="alert" aria-live="polite">{{ session('status') }}</p>
    @endif

    <form id="loginForm" class="auth-form" wire:submit="login" novalidate>
        <div class="form-row">
            <label for="email">メールアドレス</label>
            <input id="email" name="email" type="email" placeholder="example@company.jp" required
                wire:model="email" autocomplete="email" />
            @error('email')
                <p class="auth-message" role="alert">{{ $message }}</p>
            @enderror
        </div>
        <div class="form-row">
            <label for="password">パスワード</label>
            <input id="password" name="password" type="password" placeholder="管理部から発行されたパスワード" required
                wire:model="password" autocomplete="current-password" />
            @error('password')
                <p class="auth-message" role="alert">{{ $message }}</p>
            @enderror
        </div>

        <div class="auth-actions">
            <button type="submit" class="btn btn-primary btn-lg auth-submit">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                ログイン
            </button>
        </div>
    </form>

    <div class="auth-note">
        <h2>テスト用アカウント</h2>
        <ul>
            <li><strong>メール</strong>：test@example.com</li>
            <li><strong>パスワード</strong>：password</li>
        </ul>
    </div>
</section>
