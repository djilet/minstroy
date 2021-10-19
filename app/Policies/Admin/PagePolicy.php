<?php

namespace App\Policies\Admin;

use App\Enum\AdminRole;
use App\Models\Admin;
use App\Models\Page;
use Illuminate\Auth\Access\HandlesAuthorization;

class PagePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the page.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function view(Admin $user, Page $page)
    {
        return true;
    }

    /**
     * Determine whether the user can create pages.
     *
     * @param  \App\Models\Admin  $user
     * @return mixed
     */
    public function create(Admin $user)
    {
        return AdminRole::greaterThan(AdminRole::Moderator, $user->role);
    }

    /**
     * Determine whether the user can update the page.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function update(Admin $user, Page $page)
    {
        return AdminRole::greaterThan(AdminRole::Moderator, $user->role);
    }

    /**
     * Determine whether the user can delete the page.
     *
     * @param  \App\Models\Admin  $user
     * @param  \App\Models\Page  $page
     * @return mixed
     */
    public function delete(Admin $user, Page $page)
    {
        return AdminRole::greaterThan(AdminRole::Moderator, $user->role);
    }
}
