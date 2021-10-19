<?php

namespace App\Policies\API;

use App\Enum\AdminRole;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdminPolicy
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
        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $admin
     * @return mixed
     */
    public function view(Admin $user, Admin $admin)
    {
        if ($user->id === $admin->id) {
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
        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $admin
     * @return mixed
     */
    public function update(Admin $user, Admin $admin)
    {
        return $user->role === AdminRole::ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $admin
     * @return mixed
     */
    public function delete(Admin $user, Admin $admin)
    {
        return $user->role === AdminRole::ADMIN and $user->id != $admin->id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $admin
     * @return mixed
     */
    public function restore(Admin $user, Admin $admin)
    {
        return $user->role === AdminRole::ADMIN and $user->id != $admin->id;
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $admin
     * @return mixed
     */
    public function forceDelete(Admin $user, Admin $admin)
    {
        return $user->role === AdminRole::ADMIN and $user->id != $admin->id;
    }

    /**
     * Determine whether the user can change role of admin.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $profile
     *
     * @return bool
     */
    public function changeRole(Admin $user, Admin $admin)
    {
        return $user->role == AdminRole::ADMIN and $user->id != $admin->id;
    }

    /**
     * Determine whether the user can change activity of admin.
     *
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $profile
     *
     * @return bool
     */
    public function changeActivity(Admin $user, Admin $admin)
    {
        return $user->role == AdminRole::ADMIN and $user->id != $admin->id;
    }
}
