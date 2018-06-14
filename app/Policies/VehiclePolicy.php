<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Auth\Access\HandlesAuthorization;

class VehiclePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the vehicle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vehicle $vehicle
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->hasPermission('view_vehicle');
    }

    /**
     * Determine whether the user can create vehicles.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasPermission('create_vehicle');
    }

    /**
     * Determine whether the user can update the vehicle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vehicle  $vehicle
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->hasPermission('update_vehicle');
    }

    /**
     * Determine whether the user can delete the vehicle.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Vehicle  $vehicle
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->hasPermission('delete_vehicle');
    }
}
