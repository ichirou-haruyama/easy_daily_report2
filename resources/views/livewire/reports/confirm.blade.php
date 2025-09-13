<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{layout, state, title};

/**
 * 日報入力: 入力内容の確認ページ（Functional）
 */

layout('components.layouts.app');
title('入力内容の確認');

state([
    'selected' => [],
]);

$mount = function (): void {
    // セッション優先、無ければクエリから
    $selected = session('selected_construction');
    if (!$selected) {
        $selected = [
            'construction_id' => request()->query('construction_id'),
            'site_name' => request()->query('site_name'),
            'subject' => request()->query('subject'),
            'prime_contractor' => request()->query('prime_contractor'),
        ];
        // どれも無ければ戻す
        $hasAny = collect($selected)->filter(fn($v) => filled($v))->isNotEmpty();
        if (!$hasAny) {
            $this->redirect(route('reports.create'), navigate: true);
            return;
        }
    }
    $this->selected = $selected;
};

$proceed = function (): void {
    // 後続の労務時間入力へ遷移（仮: worktime.html）
    $this->redirect('/worktime.html', navigate: true);
};
?>

<section class="w-full">
    <div class="container mx-auto p-6 max-w-xl">
        <div
            class="rounded-xl border border-zinc-200/60 bg-white/80 backdrop-blur-md p-6 shadow-sm dark:border-zinc-700/60 dark:bg-zinc-900/80">
            <h1 class="mb-6 text-2xl font-semibold text-zinc-800 dark:text-zinc-100">入力内容の確認</h1>

            <div class="space-y-4 text-sm">
                <div class="flex items-center justify-between border-b border-zinc-200/70 dark:border-zinc-700/70 pb-2">
                    <span class="text-zinc-600 dark:text-zinc-300">工事ID</span>
                    <span
                        class="font-medium tabular-nums text-zinc-900 dark:text-zinc-100">{{ $selected['construction_id'] ?? '' }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-zinc-200/70 dark:border-zinc-700/70 pb-2">
                    <span class="text-zinc-600 dark:text-zinc-300">現場名（B列）</span>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $selected['site_name'] ?? '' }}</span>
                </div>
                <div class="flex items-center justify-between border-b border-zinc-200/70 dark:border-zinc-700/70 pb-2">
                    <span class="text-zinc-600 dark:text-zinc-300">件名（C列）</span>
                    <span class="font-medium text-zinc-900 dark:text-zinc-100">{{ $selected['subject'] ?? '' }}</span>
                </div>
                @if (!empty($selected['prime_contractor'] ?? ''))
                    <div
                        class="flex items-center justify-between border-b border-zinc-200/70 dark:border-zinc-700/70 pb-2">
                        <span class="text-zinc-600 dark:text-zinc-300">元請会社</span>
                        <span
                            class="font-medium text-zinc-900 dark:text-zinc-100">{{ $selected['prime_contractor'] }}</span>
                    </div>
                @endif
            </div>

            <div class="mt-6 flex items-center justify-end gap-3">
                <a href="{{ route('reports.create') }}"
                    class="inline-flex items-center justify-center rounded-md px-4 py-2 text-sm font-medium border border-zinc-300 text-zinc-700 hover:bg-zinc-50 dark:border-zinc-700 dark:text-zinc-200 dark:hover:bg-zinc-800">戻る</a>
                <button type="button" wire:click="proceed"
                    class="inline-flex items-center justify-center rounded-md px-5 py-2 text-sm font-medium text-black shadow-sm transition hover:shadow-md focus-visible:outline-none"
                    style="background-color:#9acd32;border:1px solid #9acd32;">労務時間の入力へ</button>
            </div>
        </div>
    </div>
</section>
