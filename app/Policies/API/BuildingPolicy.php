<?php

namespace App\Policies\API;

use App\Enum\AdminRole;
use App\Models\Admin;
use App\Models\Building;
use Illuminate\Auth\Access\HandlesAuthorization;

class BuildingPolicy
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
     * @param \App\Models\Building $building
     * @return mixed
     */
    public function view(Admin $user, Building $building)
    {
        if ($building->getCreatorId()) {
            return in_array($user->role, [AdminRole::ADMIN, AdminRole::USER]);
        }

        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param \App\Models\Admin $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Building $building
     * @return mixed
     */
    public function update(Admin $user, Building $building)
    {
        if ($user->role === AdminRole::USER) {
            return $building->getCreatorId() === $user->id;
        }

        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Building $building
     * @return mixed
     */
    public function delete(Admin $user, Building $building)
    {
        if (!$building->isApproved() && !$building->isPostponed()) {
            if ($building->getCreatorId() === $user->id) {
                return in_array($user->role, [AdminRole::ADMIN, AdminRole::USER]);
            }
        }

        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Building $building
     * @return mixed
     */
    public function restore(Admin $user, Building $building)
    {
        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Building $building
     * @return mixed
     */
    public function forceDelete(Admin $user, Building $building)
    {
        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can approve building.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $profile
     *
     * @return bool
     */
    public function approve(Admin $user)
    {
        return $user->role == AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can reject building.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $profile
     *
     * @return bool
     */
    public function reject(Admin $user)
    {
        return $user->role == AdminRole::ADMIN;
    }
}
