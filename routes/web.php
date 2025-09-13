<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

// 管理ID入力ページ（誰でもアクセス可）
Volt::route('admin', 'admin.id')->name('admin.id');

// 管理画面（管理ID認証が必要）
Route::middleware(['management'])->group(function () {
    Volt::route('management', 'admin.dashboard')->name('management.dashboard');
});

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('profile.edit');
    Volt::route('settings/password', 'settings.password')->name('password.edit');
    Volt::route('settings/appearance', 'settings.appearance')->name('appearance.edit');

    // 日報入力ページ（認証必須）
    Volt::route('reports/create', 'reports.create')->name('reports.create');
    Volt::route('reports/confirm', 'reports.confirm')->name('reports.confirm');

    // 高速料金入力ページ（認証必須）
    Volt::route('tolls', 'tolls.index')->name('tolls.index');
});

require __DIR__ . '/auth.php';
