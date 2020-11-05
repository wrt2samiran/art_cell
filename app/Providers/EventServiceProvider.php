<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

use App\Listeners\User\SendAccountCreatedMailToUser;
use App\Events\User\UserCreated;

use App\Events\Order\SharedService\OrderPlaced as SharedServiceOrderPlaced;
use App\Events\Order\SparePart\OrderPlaced as SparePartOrderPlaced;

use App\Listeners\Order\SharedService\SendMailToAdmin as SendMailToAdminSharedService;
use App\Listeners\Order\SharedService\SendMailToCustomer as SendMailToCustomerSharedService;

use App\Listeners\Order\SparePart\SendMailToAdmin as SendMailToAdminSparePart;
use App\Listeners\Order\SparePart\SendMailToCustomer as SendMailToCustomerSparePart;
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
        UserCreated::class=>[
            SendAccountCreatedMailToUser::class
        ],
        SharedServiceOrderPlaced::class=>[
            SendMailToAdminSharedService::class,
            SendMailToCustomerSharedService::class
        ],
        SparePartOrderPlaced::class=>[
            SendMailToAdminSparePart::class,
            SendMailToCustomerSparePart::class
        ],

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
