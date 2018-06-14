<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SettingPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
        if ($user->hasRole('superadmin')) {
            return true;
        }
    }


    /**
     * Determine if the given user can update settings.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function update(User $user)
    {
        return $user->hasRole('superadmin') ? true : false;
    }
}
