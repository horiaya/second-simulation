<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Models\Shop;
use App\Models\User;
use App\Policies\ShopPolicy;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Shop::class => ShopPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // 管理者権限
        Gate::define('isAdmin', function (User $user) {
            return $user->role && $user->role->name === 'admin';
        });

        // 店舗代表者権限
        Gate::define('isRepresentative', function (User $user) {
            return $user->role && $user->role->name === 'representative';
        });
    }
}
