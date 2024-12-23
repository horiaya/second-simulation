<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * ログアウト処理
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        // ロールキャッシュをリセット
        app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();

        // セッションを完全に無効化
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login')->with('status', 'ログアウトしました。再度ログインしてください。');
    }
}
