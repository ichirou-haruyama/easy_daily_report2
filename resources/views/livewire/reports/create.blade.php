<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{layout, state, rules, title};

/**
 * 日報入力（新規作成）ページ - Functional コンポーネント
 *
 * 要件:
 * - 工事IDの入力欄
 * - 工事IDが不明な場合のための現場名・件名の検索欄
 * - 工事ID または 検索欄のいずれかが入力されていれば OK ボタンを表示
 */

layout('components.layouts.app');
title('日報入力');

// 状態
state([
    'constructionId' => '',
    'siteName' => '',
    'subject' => '',
]);

// バリデーションルール（リアルタイム用）
rules([
    'constructionId' => ['nullable', 'string', 'max:50'],
    'siteName' => ['nullable', 'string', 'max:100'],
    'subject' => ['nullable', 'string', 'max:100'],
]);
?>

<section class="w-full">
    <div class="container mx-auto p-6 max-w-xl">
        <div
            class="rounded-xl border border-zinc-200/60 bg-white/80 backdrop-blur-md p-6 shadow-sm dark:border-zinc-700/60 dark:bg-zinc-900/80">
            <h1 class="mb-6 text-2xl font-semibold text-zinc-800 dark:text-zinc-100">日報入力</h1>

            <div class="space-y-6">
                <div data-flux-field>
                    <label for="constructionId" data-flux-label
                        class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">工事ID</label>
                    <input id="constructionId" type="text" wire:model.live="constructionId" data-flux-control
                        class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                        placeholder="例: KG-2025-001" />
                    <p class="mt-2 text-xs text-zinc-500">工事IDが分からない場合は、以下の検索欄を使用してください。</p>
                </div>

                <div class="grid grid-cols-1 gap-4">
                    <div data-flux-field>
                        <label for="siteName" data-flux-label
                            class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">現場名で検索</label>
                        <input id="siteName" type="text" wire:model.live="siteName" data-flux-control
                            class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                            placeholder="例: 本社ビル耐震改修工事" />
                    </div>

                    <div data-flux-field>
                        <label for="subject" data-flux-label
                            class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">件名で検索</label>
                        <input id="subject" type="text" wire:model.live="subject" data-flux-control
                            class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                            placeholder="例: 配管更新工事" />
                    </div>
                </div>

                <div class="pt-2">
                    @php
                        $canProceed = filled($constructionId) || filled($siteName) || filled($subject);
                    @endphp

                    @if ($canProceed)
                        <a href="/worktime.html" role="button"
                            class="inline-flex items-center justify-center rounded-md px-5 py-2 text-sm font-medium text-black shadow-sm transition hover:shadow-md focus-visible:outline-none"
                            style="background-color:#9acd32;border:1px solid #9acd32;">OK</a>
                    @else
                        <button type="button"
                            class="inline-flex items-center justify-center rounded-md px-5 py-2 text-sm font-medium text-black shadow-sm transition hover:shadow-md focus-visible:outline-none disabled:opacity-50 disabled:cursor-not-allowed"
                            style="background-color:#9acd32;border:1px solid #9acd32;" disabled>OK</button>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
