<?php
/**
 * Date:    23.01.18
 *
 * @author: dolphin54rus <dolphin54rus@gmail.com>
 */

namespace App\Enum;


class AdminRole extends Enum
{
    //roles for old admin (web)
    const Integrator = 'integrator';
    const Administrator = 'administrator';
    const Moderator = 'moderator';

    //roles for new admin (api)
    const ADMIN = 'admin';
    const USER = 'user';

    const DEFAULT = self::Administrator;


    private static $weight = [
        self::Integrator => 500,
        self::Administrator => 400,
        self::Moderator => 100,
    ];

    public static function getWeight(string $role): int
    {
        return isset(self::$weight[$role]) ? self::$weight[$role] : 0;
    }

    public static function greaterThan(string $role, string $userRole): bool
    {
        return AdminRole::getWeight($role) < AdminRole::getWeight($userRole);
    }

    public static function greaterThanOrEquals(string $role, string $userRole): bool
    {
        return AdminRole::getWeight($role) <= AdminRole::getWeight($userRole);
    }

    public function getIterator()
    {
        $user = \Auth::guard('admin')->user();
        $roles = parent::getIterator();

        foreach ($roles as $index => $role) {
            if (!self::greaterThan($role, $user->role)) {
                unset($roles[$index]);
            }
        }

        return $roles;
    }


}