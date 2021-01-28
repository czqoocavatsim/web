<?php

namespace App\Providers;

use App\Models\Training\Application;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-application', function ($user, $application) {
            return $user->id === $application->user_id;
        });

        Gate::define('start-application', function ($user) {
            if (!$user->can('start applications') || Application::where('user_id', $user->id)->where('status', 0)->first())
            {
                return false;
            }
            return true;
        });

        //Grant all privileges to administrators
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Administrator') ? true : null;
        });
    }
}
