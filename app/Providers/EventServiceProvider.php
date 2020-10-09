<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Events\ServiceProvider\ServiceProviderCreated;
use App\Listeners\ServiceProvider\SendAccountCreatedMailToUser;
use App\Events\PropertyOwner\PropertyOwnerCreated;
use App\Listeners\PropertyOwner\SendAccountCreatedMailToUser as PropertyOwnerSendAccountCreatedMailToUser;
use App\Events\PropertyManager\PropertyManagerCreated;
use App\Listeners\PropertyManager\SendAccountCreatedMailToUser as PropertyManagerSendAccountCreatedMailToUser;
use App\Listeners\User\SendAccountCreatedMailToUser as UserSendAccountCreatedMailToUser;

use App\Events\User\UserCreated;
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        ServiceProviderCreated::class=>[
            SendAccountCreatedMailToUser::class
        ],
        PropertyOwnerCreated::class=>[
            PropertyOwnerSendAccountCreatedMailToUser::class
        ],
        PropertyManagerCreated::class=>[
            PropertyManagerSendAccountCreatedMailToUser::class
        ],
        UserCreated::class=>[
            UserSendAccountCreatedMailToUser::class
        ]

    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
