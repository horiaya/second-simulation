<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, Shop $shop)
    {
        return $user->id === $shop->owner_id;
    }

    public function delete(User $user, Shop $shop)
    {
        return $user->id === $shop->owner_id;
    }
}
