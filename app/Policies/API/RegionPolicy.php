<?php

namespace App\Policies\API;

use App\Enum\AdminRole;
use App\Models\Admin;
use App\Models\Region;
use Illuminate\Auth\Access\HandlesAuthorization;

class RegionPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param \App\Models\Admin $user
     * @return mixed
     */
    public function viewAny(Admin $user)
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Region $region
     * @return mixed
     */
    public function view(Admin $user, Region $region)
    {
        return true;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\Admin $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return $user->role == AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Region $region
     * @return mixed
     */
    public function update(Admin $user, Region $region)
    {
        return $user->role == AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Region $region
     * @return mixed
     */
    public function delete(Admin $user, Region $region)
    {
        return $user->role == AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Region $region
     * @return mixed
     */
    public function restore(Admin $user, Region $region)
    {
        return $user->role == AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Region $region
     * @return mixed
     */
    public function forceDelete(Admin $user, Region $region)
    {
        return $user->role == AdminRole::ADMIN;
    }
}
