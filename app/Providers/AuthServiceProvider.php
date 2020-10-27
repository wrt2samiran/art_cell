<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;
use App\Policies\{PropertyPolicy,RolePolicy,UserPolicy,ContractPolicy};
use App\Models\{User,Role,Property,Contract};
class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
         User::class => UserPolicy::class,
         Property::class => PropertyPolicy::class,
         Role::class => RolePolicy::class,
         Contract::class => ContractPolicy::class
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
