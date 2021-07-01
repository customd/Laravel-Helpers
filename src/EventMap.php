<?php

namespace CustomD\LaravelHelpers;

use Illuminate\Notifications\Events\NotificationSending;
use CustomD\LaravelHelpers\Listeners\NotificationSendingListener;

trait EventMap
{

    protected $events = [
        NotificationSending::class => [
            NotificationSendingListener::class,
        ],
    ];
}
