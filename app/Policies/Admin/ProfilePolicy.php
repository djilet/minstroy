<?php

namespace App\Policies\Admin;

use App\Enum\AdminRole;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProfilePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the admin.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Admin  $profile
     * @return mixed
     */
    public function view(Admin $user, Admin $profile)
    {
        return true;
    }

    /**
     * Determine whether the user can create admins.
     *
     * @param  \App\Models\Admin  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return in_array($user->role, [AdminRole::Integrator, AdminRole::Administrator]);
    }

    /**
     * Determine whether the user can update the admin.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Admin  $profile
     * @return mixed
     */
    public function update(Admin $user, Admin $profile)
    {
        return in_array($user->role, [AdminRole::Integrator, AdminRole::Administrator]) or $user->id == $profile->id;
    }

    /**
     * Determine whether the user can delete the admin.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Admin  $profile
     * @return mixed
     */
    public function delete(Admin $user, Admin $profile)
    {
        return $this->deleting($user) and $user->id != $profile->id;
    }

    /**
     * Determine whether the user can delete
     *
     * @param \App\Models\Admin $user
     *
     * @return bool
     */
    public function deleting(Admin $user)
    {
        return in_array($user->role, [AdminRole::Integrator, AdminRole::Administrator]);
    }

    /**
     * Determine whether the user can change role of admin.
     * 
     * @param \App\Models\Admin $user
     * @param \App\Models\Admin $profile
     *
     * @return bool
     */
    public function changeRole(Admin $user, Admin $profile)
    {
        return $user->role == AdminRole::Integrator and $user->id != $profile->id;
    }

}
