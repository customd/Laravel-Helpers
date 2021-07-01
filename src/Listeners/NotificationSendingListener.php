<?php

namespace CustomD\LaravelHelpers\Listeners;

use Illuminate\Notifications\Events\NotificationSending;

class NotificationSendingListener
{
    public function handle(NotificationSending $event)
    {
        if (method_exists($event->notification, 'blockSend')) {
            return ! $event->notification->blockSend($event->notifiable);
        }

        return true;
    }
}
