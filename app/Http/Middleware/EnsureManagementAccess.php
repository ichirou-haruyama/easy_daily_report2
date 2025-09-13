<?php

declare(strict_types=1);

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * 管理ID認証済みかをセッションで判定し、未認証なら管理ID入力ページへリダイレクトするミドルウェア。
 */
class EnsureManagementAccess
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // セッションに管理ID認証済みフラグがあるか確認
        if (!$request->session()->get('management_authenticated', false)) {
            return redirect()->route('admin.id');
        }

        return $next($request);
    }
}
