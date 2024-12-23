<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;


class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //役割（Role）を作成
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $storeOwnerRole = Role::firstOrCreate(['name' => 'store_owner', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // 必要に応じて権限を作成
        $manageUsersPermission = Permission::firstOrCreate(['name' => 'manage users', 'guard_name' => 'web']);
        $manageStoresPermission = Permission::firstOrCreate(['name' => 'manage stores', 'guard_name' => 'web']);

        // 権限をロールに割り当て
        $adminRole->givePermissionTo($manageUsersPermission);
        $storeOwnerRole->givePermissionTo($manageStoresPermission);

        //初期管理者を作成
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'), // パスワードを "password" にリセット
            ]
        );

        //管理者ロールを付与
        if (!$adminUser->hasRole('admin')) {
            $adminUser->assignRole($adminRole);
        }
    }
}
