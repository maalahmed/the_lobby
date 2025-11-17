<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Blade;

class PermissionServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register permission-based gates
        Gate::before(function ($user, $ability) {
            // Super admin has all permissions
            if ($user->hasRole('admin')) {
                return true;
            }
        });

        // Custom Blade directives for permissions
        Blade::if('role', function ($role) {
            return auth()->check() && auth()->user()->hasRole($role);
        });

        Blade::if('anyrole', function (...$roles) {
            return auth()->check() && auth()->user()->hasAnyRole($roles);
        });

        Blade::if('permission', function ($permission) {
            return auth()->check() && auth()->user()->can($permission);
        });

        Blade::if('anypermission', function (...$permissions) {
            foreach ($permissions as $permission) {
                if (auth()->check() && auth()->user()->can($permission)) {
                    return true;
                }
            }
            return false;
        });
    }
}
