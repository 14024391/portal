<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AutotraderImporterPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_import_file');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User $user
     * @return mixed
     */
    public function process(User $user)
    {
        return $user->hasPermission('process_import_file');
    }
}
