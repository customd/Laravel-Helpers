<?php

namespace CustomD\LaravelHelpers;

use Illuminate\Notifications\Events\NotificationSending;
use CustomD\LaravelHelpers\Listeners\NotificationSendingListener;

trait EventMap
{

    protected $listen = [
        NotificationSending::class => [
            NotificationSendingListener::class,
        ],
    ];
}
