<?php

namespace App\Policies\Admin;

use App\Enum\AdminRole;
use App\Models\Admin;
use App\Models\Menu;
use Illuminate\Auth\Access\HandlesAuthorization;

class MenuPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the menu.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Menu  $menu
     * @return mixed
     */
    public function view(Admin $user, Menu $menu)
    {
        return true;
    }

    /**
     * Determine whether the user can create menus.
     *
     * @param  \App\Models\Admin  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return $user->role == AdminRole::Integrator;
    }

    /**
     * Determine whether the user can update the menu.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Menu  $menu
     * @return mixed
     */
    public function update(Admin $user, Menu $menu)
    {
        return $user->role == AdminRole::Integrator;
    }

    /**
     * Determine whether the user can delete the menu.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Menu  $menu
     * @return mixed
     */
    public function delete(Admin $user, Menu $menu)
    {
        return $user->role == AdminRole::Integrator;
    }
}
