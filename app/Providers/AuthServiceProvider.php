<?php

namespace App\Providers;

use App\Business\System\Auth as AuthBusiness;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        $this->registerGates();
    }

    public function registerGates()
    {
        $authBusiness = new AuthBusiness;
//        if ($authBusiness->hasPermissionsSchema()) {
//            $authBusiness->registerGates();
//        }

        $authBusiness->registerGates();
    }
}
