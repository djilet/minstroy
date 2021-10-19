<?php

namespace App\Providers;

use App\Models\Admin;
use App\Models\Building;
use App\Models\BuildingType;
use App\Models\Region;
use App\Policies\API\AdminPolicy;
use App\Policies\API\BuildingPolicy;
use App\Policies\API\BuildingTypePolicy;
use App\Policies\API\RegionPolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        Admin::class => AdminPolicy::class,
//        old admin policy
//        Menu::class => MenuPolicy::class,
//        Page::class => PagePolicy::class,
        Building::class => BuildingPolicy::class,
        BuildingType::class => BuildingTypePolicy::class,
        Region::class => RegionPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
