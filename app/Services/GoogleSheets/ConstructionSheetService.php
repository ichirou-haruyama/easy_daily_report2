<?php

declare(strict_types=1);

namespace App\Services\GoogleSheets;

use Illuminate\Support\Facades\Http;

/**
 * Google スプレッドシート「工事内容管理」からの検索サービス。
 *
 * シートは共有設定で閲覧可能であること（少なくともリンクを知っているユーザーが閲覧可）。
 * API キーやサービスアカウント不要の CSV エクスポート (gviz) を使用します。
 */
final class ConstructionSheetService
{
    /**
     * スプレッドシートから工事情報を検索する。
     *
     * @param string $sheetId Google Spreadsheet ID
     * @param string|null $constructionId 部分一致検索対象の工事ID
     * @param string|null $siteName 部分一致検索対象の現場名
     * @param string|null $subject 部分一致検索対象の工事名/件名
     * @param string $sheetName 既定は「工事内容管理」
     * @return array<int, array<string, string>>
     */
    public function search(
        string $sheetId,
        ?string $constructionId = null,
        ?string $siteName = null,
        ?string $subject = null,
        string $sheetName = '工事内容管理'
    ): array {
        $url = sprintf(
            'https://docs.google.com/spreadsheets/d/%s/gviz/tq?tqx=out:csv&sheet=%s',
            $sheetId,
            rawurlencode($sheetName)
        );

        $response = Http::timeout(15)->retry(2, 200)->get($url);
        if (!$response->ok()) {
            $status = $response->status();
            $snippet = mb_substr(trim($response->body()), 0, 200);
            throw new \RuntimeException("Googleシートからの取得に失敗しました（HTTP ${status}）。${snippet}");
        }

        $csv = $response->body();
        $rows = $this->parseCsv($csv);
        if (count($rows) === 0) {
            return [];
        }

        $headers = array_map(static fn($h) => trim((string) $h), array_shift($rows));

        // ヘッダー名の候補に柔軟対応。解決できなければ A/B/C/D にフォールバック
        $indexMap = $this->resolveHeaderIndexes($headers);
        if (empty($indexMap)) {
            $indexMap = [
                'construction_id' => 0, // A列: ID
                'site_name' => 1,       // B列: 現場名
                'subject' => 2,         // C列: 工事名（件名）
                'prime_contractor' => 3 // D列: 元請（存在すれば）
            ];
        }

        $results = [];
        foreach ($rows as $row) {
            // 行サイズの不足を埋める
            if (count($row) < count($headers)) {
                $row = array_pad($row, count($headers), '');
            }

            $rowId = $this->valueAt($row, $indexMap['id'] ?? null);
            $rowConstructionId = $this->valueAt($row, $indexMap['construction_id'] ?? null) ?: $rowId;
            $rowSiteName = $this->valueAt($row, $indexMap['site_name'] ?? null);
            $rowSubject = $this->valueAt($row, $indexMap['subject'] ?? null);
            $rowPrime = $this->valueAt($row, $indexMap['prime_contractor'] ?? null);

            // ID（工事ID）のみで一致判定（現場名・件名では検索しない）
            if (!$this->idMatches($constructionId, $rowConstructionId)) {
                continue;
            }

            $results[] = [
                'construction_id' => $rowConstructionId,
                'site_name' => $rowSiteName,
                'subject' => $rowSubject,
                'prime_contractor' => $rowPrime,
            ];
        }

        return $results;
    }

    /**
     * CSV文字列を2次元配列へ（ヘッダー含む）。
     *
     * @return array<int, array<int, string>>
     */
    private function parseCsv(string $csv): array
    {
        $lines = preg_split("/(\r\n|\n|\r)/", trim($csv)) ?: [];
        $rows = [];
        foreach ($lines as $line) {
            $rows[] = str_getcsv($line);
        }
        return $rows;
    }

    /**
     * ヘッダー名から列インデックスを推定。
     *
     * @param array<int,string> $headers
     * @return array<string,int>
     */
    private function resolveHeaderIndexes(array $headers): array
    {
        $map = [];
        foreach ($headers as $i => $h) {
            $key = trim($h);
            // 候補にマッチさせる（大小区別なし）
            $lower = mb_strtolower($key);
            if (in_array($lower, ['id', '工事id'], true)) {
                $map['construction_id'] = $i;
                $map['id'] = $i;
            } elseif (in_array($lower, ['現場名', '現場'], true)) {
                $map['site_name'] = $i;
            } elseif (in_array($lower, ['工事名', '件名', '案件名'], true)) {
                $map['subject'] = $i;
            } elseif (in_array($lower, ['元請会社', '元請', '元請名'], true)) {
                $map['prime_contractor'] = $i;
            }
        }
        return $map;
    }

    private function valueAt(array $row, ?int $index): string
    {
        if ($index === null) {
            return '';
        }
        return isset($row[$index]) ? trim((string) $row[$index]) : '';
    }

    /**
     * すべての与件（null/空は無視）に一致するか（AND条件）。
     *
     * @param array<int, array{0:?string,1:string}> $pairs [needle, haystack]
     */
    private function idMatches(?string $needle, string $haystack): bool
    {
        if ($needle === null || $needle === '') {
            return false;
        }
        if ($haystack === '') {
            return false;
        }
        return mb_strtolower(trim($haystack)) === mb_strtolower(trim($needle));
    }

    private function mbStripos(string $haystack, string $needle)
    {
        return mb_stripos($haystack, $needle, 0, 'UTF-8');
    }
}
