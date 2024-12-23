<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Fortify;
use App\Http\Controllers\Auth\CustomRegisteredUserController;
use Illuminate\Auth\Notifications\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Authenticated;
use Illuminate\Support\Facades\Log;



class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
    Fortify::createUsersUsing(CreateNewUser::class);
        // Fortifyの登録ルートを無効化
        Fortify::ignoreRoutes();

        Fortify::registerView(function () {
            return view('auth.register');
        });

        Fortify::redirects('register', function () {
        $user = auth()->user();
        if ($user && $user->hasRole('store_owner')) {
            return '/store-owner/verify-email';
        } else {
            return '/verify-email';
        }
    });

        // メール認証ビュー
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email')->with('status', 'メールアドレスが正常に確認されました！');
        });
        Fortify::redirects('verify-email', '/thanks');

        Fortify::loginView(function () {
        if (Auth::check()) {
            Auth::logout();
            session()->invalidate();
            session()->regenerateToken();

            session()->flash('status', 'ログアウトしました。再度ログインしてください。');
        }
            return view('auth.login');
        });

        RateLimiter::for('login', function (Request $request) {
            $email = (string) $request->email;
            return Limit::perMinute(10)->by($email . $request->ip());
        });


        Fortify::authenticateUsing(function (Request $request) {
            session()->forget('url.intended');

            $user = User::where('email', $request->email)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if (isset($user->is_active) && !$user->is_active) {
                    return null;
                }
                app(\Spatie\Permission\PermissionRegistrar::class)->forgetCachedPermissions();
                $request->session()->regenerate();

                return $user;
            }
            return null;
        });
    }
}
