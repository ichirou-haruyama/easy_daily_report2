<?php

use App\Models\User;
use Illuminate\Support\Facades\Session;
use Livewire\Volt\Component;
use function Livewire\Volt\{layout, state, rules};

/**
 * 管理ID入力ページ（Functional）
 */
layout('components.layouts.auth');

state([
    'management_id' => '',
]);

rules([
    'management_id' => 'required|string',
]);

$submit = function (): void {
    $this->validate();

    $exists = User::query()->where('management_id', $this->management_id)->exists();

    if (!$exists) {
        $this->addError('management_id', __('指定の管理IDが見つかりません。'));
        return;
    }

    // 認証済みフラグをセッションに保存
    Session::put('management_authenticated', true);

    // 管理画面へ
    $this->redirectIntended(default: route('management.dashboard', absolute: false), navigate: true);
};
?>

<div class="flex flex-col gap-6">
    <x-auth-header :title="__('管理IDの入力')" :description="__('管理画面に入るために管理IDを入力してください')" />

    <form wire:submit="submit" class="flex flex-col gap-6">
        <flux:input wire:model="management_id" :label="__('管理ID')" type="text" required autofocus />

        <div class="flex items-center justify-end">
            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('管理画面へ進む') }}
            </flux:button>
        </div>
    </form>
</div>
