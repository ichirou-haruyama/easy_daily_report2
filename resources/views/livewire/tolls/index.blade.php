<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{layout, state, rules, title};

/**
 * 高速料金入力ページ - Functional コンポーネント
 *
 * 要件:
 * - 一番上に「高速道路未使用」のチェックボックスを表示
 * - 行き/帰りそれぞれで「入場IC名」「退場IC名」を入力
 * - 入退場ICが両方入力されたら、料金入力カラム（円・カンマ表示）を表示
 * - 「高速道路未使用」がチェックされている場合は、入力が空でも OK ボタンを表示
 */

layout('components.layouts.app');
title('高速料金入力');

// 状態
state([
    'skipHighway' => false,

    // 行き
    'goEntryIC' => '',
    'goExitIC' => '',
    'goFare' => '', // 数字文字列（表示は別途フォーマット）

    // 帰り
    'returnEntryIC' => '',
    'returnExitIC' => '',
    'returnFare' => '', // 数字文字列（表示は別途フォーマット）
]);

// リアルタイムバリデーション（形式のみ軽くチェック）
rules([
    'skipHighway' => ['boolean'],
    'goEntryIC' => ['nullable', 'string', 'max:100'],
    'goExitIC' => ['nullable', 'string', 'max:100'],
    'goFare' => ['nullable', 'string', 'max:12'],
    'returnEntryIC' => ['nullable', 'string', 'max:100'],
    'returnExitIC' => ['nullable', 'string', 'max:100'],
    'returnFare' => ['nullable', 'string', 'max:12'],
]);
?>

<section class="w-full">
    <div class="container mx-auto p-6 max-w-xl">
        <div
            class="rounded-xl border border-zinc-200/60 bg-white/80 backdrop-blur-md p-6 shadow-sm dark:border-zinc-700/60 dark:bg-zinc-900/80">
            <h1 class="mb-6 text-2xl font-semibold text-zinc-800 dark:text-zinc-100">高速料金入力</h1>

            <div class="space-y-8">
                <!-- 高速道路未使用 -->
                <div class="flex items-center gap-3">
                    <input id="skipHighway" type="checkbox" wire:model.live="skipHighway"
                        class="h-8 w-8 rounded border-zinc-300 text-blue-600 focus:ring-blue-500 dark:border-zinc-700 dark:bg-zinc-900" />
                    <label for="skipHighway"
                        class="text-sm font-medium text-zinc-800 dark:text-zinc-200">高速道路未使用</label>
                </div>

                <!-- 行き -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">行き</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div data-flux-field>
                            <label for="goEntryIC" data-flux-label
                                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">入場IC名（行き）</label>
                            <input id="goEntryIC" type="text" wire:model.live="goEntryIC" data-flux-control
                                class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                                placeholder="例: 東京IC" />
                        </div>
                        <div data-flux-field>
                            <label for="goExitIC" data-flux-label
                                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">退場IC名（行き）</label>
                            <input id="goExitIC" type="text" wire:model.live="goExitIC" data-flux-control
                                class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                                placeholder="例: 名古屋IC" />
                        </div>
                    </div>

                    @php
                        $showGoFare = filled($goEntryIC) && filled($goExitIC);
                        $goFareNumeric = (int) preg_replace('/\D/', '', (string) $goFare);
                    @endphp
                    @if ($showGoFare)
                        <div class="grid grid-cols-1 gap-2">
                            <div data-flux-field>
                                <label for="goFare" data-flux-label
                                    class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">料金（行き）</label>
                                <div class="relative">
                                    <input id="goFare" type="text" inputmode="numeric" wire:model.live="goFare"
                                        data-flux-control
                                        class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 pr-12 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                                        placeholder="例: 2500" />
                                    <span
                                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-zinc-600 dark:text-zinc-400">円</span>
                                </div>
                                <p class="mt-1 text-xs text-zinc-500">表示: {{ number_format($goFareNumeric) }} 円</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- 帰り -->
                <div class="space-y-4">
                    <h2 class="text-lg font-semibold text-zinc-800 dark:text-zinc-100">帰り</h2>
                    <div class="grid grid-cols-1 gap-4">
                        <div data-flux-field>
                            <label for="returnEntryIC" data-flux-label
                                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">入場IC名（帰り）</label>
                            <input id="returnEntryIC" type="text" wire:model.live="returnEntryIC" data-flux-control
                                class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                                placeholder="例: 名古屋IC" />
                        </div>
                        <div data-flux-field>
                            <label for="returnExitIC" data-flux-label
                                class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">退場IC名（帰り）</label>
                            <input id="returnExitIC" type="text" wire:model.live="returnExitIC" data-flux-control
                                class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                                placeholder="例: 東京IC" />
                        </div>
                    </div>

                    @php
                        $showReturnFare = filled($returnEntryIC) && filled($returnExitIC);
                        $returnFareNumeric = (int) preg_replace('/\D/', '', (string) $returnFare);
                    @endphp
                    @if ($showReturnFare)
                        <div class="grid grid-cols-1 gap-2">
                            <div data-flux-field>
                                <label for="returnFare" data-flux-label
                                    class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">料金（帰り）</label>
                                <div class="relative">
                                    <input id="returnFare" type="text" inputmode="numeric"
                                        wire:model.live="returnFare" data-flux-control
                                        class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 pr-12 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                                        placeholder="例: 2500" />
                                    <span
                                        class="pointer-events-none absolute inset-y-0 right-3 flex items-center text-sm text-zinc-600 dark:text-zinc-400">円</span>
                                </div>
                                <p class="mt-1 text-xs text-zinc-500">表示: {{ number_format($returnFareNumeric) }} 円</p>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- OK ボタン -->
                <div class="pt-2">
                    @php
                        $hasGo = filled($goEntryIC) && filled($goExitIC);
                        $hasReturn = filled($returnEntryIC) && filled($returnExitIC);
                        $canProceed = $skipHighway || $hasGo || $hasReturn;
                    @endphp

                    @if ($canProceed)
                        <a id="tollsOk" href="/summary.html" role="button"
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const byId = (id) => document.getElementById(id);
        const ok = byId('tollsOk');
        if (!ok) return;
        const num = (v) => {
            const n = String(v || '').replace(/\D/g, '');
            return n ? parseInt(n, 10) : 0;
        };
        ok.addEventListener('click', (ev) => {
            try {
                const data = {
                    skipHighway: !!byId('skipHighway')?.checked,
                    goEntryIC: String(byId('goEntryIC')?.value || ''),
                    goExitIC: String(byId('goExitIC')?.value || ''),
                    goFare: num(byId('goFare')?.value || ''),
                    returnEntryIC: String(byId('returnEntryIC')?.value || ''),
                    returnExitIC: String(byId('returnExitIC')?.value || ''),
                    returnFare: num(byId('returnFare')?.value || ''),
                };
                localStorage.setItem('tolls', JSON.stringify(data));
            } catch (e) {}
            // 念のためデフォルト遷移の前に保存を完了させる
            ev.preventDefault();
            window.location.href = '/summary.html';
        });
    });
</script>
