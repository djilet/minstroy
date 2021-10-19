<?php

namespace App\Policies\API;

use App\Enum\AdminRole;
use App\Models\Admin;
use App\Models\BuildingType;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingTypePolicy
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
     * @param \App\Models\BuildingType $buildingType
     * @return mixed
     */
    public function view(Admin $user, BuildingType $buildingType)
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
        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\BuildingType $buildingType
     * @return mixed
     */
    public function update(Admin $user, BuildingType $buildingType)
    {
        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\BuildingType $buildingType
     * @return mixed
     */
    public function delete(Admin $user, BuildingType $buildingType)
    {
        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\BuildingType $buildingType
     * @return mixed
     */
    public function restore(Admin $user, BuildingType $buildingType)
    {
        return $user->role == AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\BuildingType $buildingType
     * @return mixed
     */
    public function forceDelete(Admin $user, BuildingType $buildingType)
    {
        return $user->role == AdminRole::ADMIN;
    }
}
