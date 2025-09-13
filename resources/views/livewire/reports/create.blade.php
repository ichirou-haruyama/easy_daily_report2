<?php

use App\Services\GoogleSheets\ConstructionSheetService;
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
    // 検索関連
    'sheetId' => '1BtNQi0bVLbr3V_iOPZT-b0txntw433vfnDKjYx2Yx4s',
    'searchResults' => [],
    'selectedIndex' => null,
    'isLoading' => false,
    'error' => '',
]);

// バリデーションルール（リアルタイム用）
rules([
    'constructionId' => ['nullable', 'string', 'max:50'],
    'siteName' => ['nullable', 'string', 'max:100'],
    'subject' => ['nullable', 'string', 'max:100'],
]);

// 検索アクション
$search = function (ConstructionSheetService $service): void {
    $this->error = '';
    $this->searchResults = [];
    $this->selectedIndex = null;

    if (!filled($this->constructionId)) {
        $this->error = '工事IDを入力してください。';
        return;
    }

    $this->isLoading = true;
    try {
        $results = $service->search($this->sheetId, $this->constructionId ?: null);

        $this->searchResults = $results;
        if (count($results) === 0) {
            $this->error = '一致する工事IDが見つかりませんでした。';
        }
    } catch (\Throwable $e) {
        $this->error = '検索中にエラーが発生しました: ' . $e->getMessage();
        report($e);
    } finally {
        $this->isLoading = false;
    }
};

// 検索結果の選択
$selectResult = function (int $index): void {
    if (!isset($this->searchResults[$index])) {
        return;
    }
    $this->selectedIndex = $index;
};

// 確定して遷移
$confirm = function (): void {
    if ($this->selectedIndex !== null && isset($this->searchResults[$this->selectedIndex])) {
        $selected = $this->searchResults[$this->selectedIndex];
        $this->constructionId = $selected['construction_id'] ?? $this->constructionId;
        $this->siteName = $selected['site_name'] ?? $this->siteName;
        $this->subject = $selected['subject'] ?? $this->subject;

        // 必要に応じてセッション等に保持
        session()->put('selected_construction', $selected);
        // クエリ引数としても渡す（セッションが利用できない環境へのフォールバック）
        $params = [
            'construction_id' => $selected['construction_id'] ?? null,
            'site_name' => $selected['site_name'] ?? null,
            'subject' => $selected['subject'] ?? null,
            'prime_contractor' => $selected['prime_contractor'] ?? null,
        ];
        $this->redirect(route('reports.confirm', array_filter($params, fn($v) => filled($v))), navigate: true);
        return;
    }
    $this->redirect(route('reports.confirm'), navigate: true);
};
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
                            class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">現場名（検索結果に表示）</label>
                        <input id="siteName" type="text" wire:model.live="siteName" data-flux-control
                            class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                            placeholder="検索結果の現場名が表示されます" readonly />
                    </div>

                    <div data-flux-field>
                        <label for="subject" data-flux-label
                            class="block text-sm font-medium text-zinc-700 dark:text-zinc-300">件名（検索結果に表示）</label>
                        <input id="subject" type="text" wire:model.live="subject" data-flux-control
                            class="w-full rounded-lg border border-zinc-300/70 bg-white/90 px-3 py-2 text-zinc-900 placeholder-zinc-400 shadow-xs focus:outline-none dark:border-zinc-700/70 dark:bg-zinc-900/90 dark:text-zinc-100"
                            placeholder="検索結果の件名が表示されます" readonly />
                    </div>
                </div>

                <!-- 検索結果表示 -->
                @if (!empty($searchResults))
                    <div class="mt-2 rounded-lg border border-zinc-200/70 dark:border-zinc-700/70 overflow-hidden">
                        <div
                            class="bg-zinc-50 dark:bg-zinc-800 px-4 py-2 text-sm font-medium text-zinc-700 dark:text-zinc-200">
                            検索結果
                        </div>
                        <div class="divide-y divide-zinc-200/70 dark:divide-zinc-700/70">
                            @foreach ($searchResults as $i => $row)
                                <div class="px-4 py-3 flex items-start gap-3" wire:key="result-{{ $i }}">
                                    <div class="grow text-sm text-zinc-800 dark:text-zinc-100">
                                        <div class="font-medium">工事ID: <span
                                                class="tabular-nums">{{ $row['construction_id'] ?? '' }}</span></div>
                                        <div class="mt-0.5 text-zinc-600 dark:text-zinc-300">現場名:
                                            {{ $row['site_name'] ?? '' }}</div>
                                        <div class="mt-0.5 text-zinc-600 dark:text-zinc-300">件名:
                                            {{ $row['subject'] ?? '' }}</div>
                                        @if (!empty($row['prime_contractor'] ?? ''))
                                            <div class="mt-0.5 text-zinc-600 dark:text-zinc-300">元請会社:
                                                {{ $row['prime_contractor'] }}</div>
                                        @endif
                                    </div>
                                    <div>
                                        <button type="button" wire:click="selectResult({{ $i }})"
                                            class="inline-flex items-center justify-center rounded-md px-3 py-1.5 text-xs font-medium text-white shadow-sm transition hover:shadow-md focus-visible:outline-none"
                                            style="background-color:#1e90ff;border:1px solid #1e90ff;">
                                            @if ($selectedIndex === $i)
                                                選択中
                                            @else
                                                選択
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if (filled($error))
                    <p class="mt-2 text-sm text-red-600">{{ $error }}</p>
                @endif

                <div class="pt-2 flex items-center gap-3">
                    @php
                        $canProceed = filled($constructionId);
                    @endphp

                    @if ($canProceed)
                        <button type="button" wire:click="search" wire:loading.attr="disabled" wire:target="search"
                            class="inline-flex items-center justify-center rounded-md px-5 py-2 text-sm font-medium text-white shadow-sm transition hover:shadow-md focus-visible:outline-none"
                            style="background-color:#1e90ff;border:1px solid #1e90ff;">
                            <span wire:loading.remove wire:target="search">検索</span>
                            <span wire:loading wire:target="search">検索中...</span>
                        </button>

                        @if ($selectedIndex !== null)
                            <button type="button" wire:click="confirm"
                                class="inline-flex items-center justify-center rounded-md px-5 py-2 text-sm font-medium text-black shadow-sm transition hover:shadow-md focus-visible:outline-none"
                                style="background-color:#9acd32;border:1px solid #9acd32;">OK</button>
                        @else
                            <a href="/worktime.html" role="button"
                                class="inline-flex items-center justify-center rounded-md px-5 py-2 text-sm font-medium text-black shadow-sm transition hover:shadow-md focus-visible:outline-none"
                                style="background-color:#9acd32;border:1px solid #9acd32;">OK</a>
                        @endif
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
