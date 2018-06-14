<?php

namespace App\Providers;

use App\Http\Controllers\Admin\AutotraderImporter;
use App\Models\AutotraderImport;
use App\Models\Permission;
use App\Models\Role;
use App\Models\Setting;
use App\Models\User;
use App\Models\Vehicle;
use App\Policies\AutotraderImporterPolicy;
use App\Policies\PermissionPolicy;
use App\Policies\RolePolicy;
use App\Policies\SettingPolicy;
use App\Policies\UserPolicy;
use App\Policies\VehiclePolicy;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Permission::class => PermissionPolicy::class,
        Vehicle::class => VehiclePolicy::class,
        Setting::class => SettingPolicy::class,
        AutotraderImport::class => AutotraderImporterPolicy::class,
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
