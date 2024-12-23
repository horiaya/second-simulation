<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Laravel\Fortify\Events\Registered;
use Illuminate\Auth\Events\Authenticated;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
        Event::listen(Registered::class, function ($event) {
            session(['redirect_after_registration' => '/verify-email']);
        });

        // ログイン後の処理
        Event::listen(Authenticated::class, function ($event) {

            $user = $event->user;
            // 不要なリダイレクト情報を削除
            session()->forget('url.intended');

            if ($user->hasRole('admin')) {
                session(['url.intended' => '/admin/index']);
            } elseif ($user->hasRole('store_owner')) {
                session(['url.intended' => '/store-owner/index']);
            } else {
                session(['url.intended' => '/']);
            }
        });
    }
}
