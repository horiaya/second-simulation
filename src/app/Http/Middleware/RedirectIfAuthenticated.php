<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  ...$guards
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // ロールごとのリダイレクト先をセッションに保存
                if ($user->hasRole('admin')) {
                    session(['url.intended' => '/admin/index']);
                } elseif ($user->hasRole('store-owner')) {
                    session(['url.intended' => '/store-owner/index']);
                } else {
                    session(['url.intended' => '/']);
                }

                // ユーザーをログアウトさせる
                Auth::guard($guard)->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                // セッションのリダイレクト先にリダイレクト
                return redirect(session('url.intended'))->with('status', '再度ログインしてください。');
            }
        }

        return $next($request);
    }
}
