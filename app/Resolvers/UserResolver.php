<?php

namespace App\Resolvers;

use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Resolvers\UserResolver as AuditingUserResolver;

class UserResolver extends AuditingUserResolver
{
    /**
     * {@inheritdoc}
     */
    public static function resolve()
    {
        $guards = config('audit.user.guards'); // I only removed the default array value
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return Auth::guard($guard)->user();
            }
        }
    }
}

