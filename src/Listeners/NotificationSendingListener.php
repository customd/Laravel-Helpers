<?php

namespace CustomD\LaravelHelpers\Listeners;

use Illuminate\Notifications\Events\NotificationSending;

class NotificationSendingListener
{
    public function handle(NotificationSending $event)
    {
        if (method_exists($event->notification, 'blockSending')) {
            return ! $event->notification->blockSending($event->notifiable);
        }

        return true;
    }
}
