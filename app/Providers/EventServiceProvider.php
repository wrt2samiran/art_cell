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

use App\Listeners\Order\SharedService\StoreNotifications as StoreSharedServiceOrderNotifications;

use App\Listeners\Order\SparePart\StoreNotifications as StoreSparePartOrderNotifications;

use App\Events\Property\PropertyCreated;
use App\Listeners\Property\StorePropertyCreatedNotifications;

use App\Events\Contract\ContractCreated;
use App\Listeners\Contract\StoreContractCreatedNotifications;


use App\Events\Complaint\ComplaintCreated;
use App\Listeners\Complaint\StoreComplaintCreatedNotifications;

use App\Events\Complaint\NoteAdded;
use App\Listeners\Complaint\StoreComplaintNoteAddedNotifications;

use App\Events\Complaint\StatusUpdated as ComplaintStatusUpdated;
use App\Listeners\Complaint\StoreStatusUpdateNotifications as StoreComplaintStatusUpdateNotifications;

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
            SendMailToCustomerSharedService::class,
            StoreSharedServiceOrderNotifications::class
        ],
        SparePartOrderPlaced::class=>[
            SendMailToAdminSparePart::class,
            SendMailToCustomerSparePart::class,
            StoreSparePartOrderNotifications::class
        ],
        PropertyCreated::class=>[
            StorePropertyCreatedNotifications::class
        ],
        ContractCreated::class=>[
            StoreContractCreatedNotifications::class
        ],
        ComplaintCreated::class=>[
            StoreComplaintCreatedNotifications::class
        ],
        NoteAdded::class=>[
            StoreComplaintNoteAddedNotifications::class
        ],
        ComplaintStatusUpdated::class=>[
            StoreComplaintStatusUpdateNotifications::class
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
