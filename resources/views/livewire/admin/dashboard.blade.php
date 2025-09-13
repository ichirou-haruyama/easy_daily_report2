<?php

use Livewire\Volt\Component;
use function Livewire\Volt\{layout};

/**
 * 管理画面トップ（Functional）
 */
layout('components.layouts.app');
?>

<section class="w-full">
    <div class="container mx-auto p-6">
        <h1 class="text-lg font-semibold mb-4">管理画面</h1>

        <div class="space-y-3">
            <p class="text-sm text-zinc-600 dark:text-zinc-400">管理専用機能のダッシュボードです。</p>
            <ul class="list-disc ms-4 text-sm text-zinc-700 dark:text-zinc-300">
                <li>ユーザー管理（今後実装）</li>
                <li>レポート出力（今後実装）</li>
            </ul>
        </div>
    </div>
</section>
