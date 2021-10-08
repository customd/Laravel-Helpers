<?php

namespace CustomD\LaravelHelpers\Listeners;

use Illuminate\Notifications\Events\NotificationSending;
use Illuminate\Support\Facades\Log;

class NotificationSendingListener
{
    public function handle(NotificationSending $event)
    {
        if (method_exists($event->notification, 'blockSending')) {
            Log::debug("this listener is Deprected and will be removed with Laravel 9 release, please see https://laravel-news.com/laravel-8-50-0");
            return ! $event->notification->blockSending($event->notifiable);
        }

        return true;
    }
}
