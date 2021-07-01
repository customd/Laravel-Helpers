<?php

namespace CustomD\LaravelHelpers;

use Illuminate\Support\Facades\Event;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    use EventMap;

    public function boot()
    {
        $this->registerEvents();
    }

    public function register()
    {
    }

    protected function registerEvents()
    {
        foreach ($this->events as $event => $listeners) {
            foreach (array_unique($listeners) as $listener) {
                Event::listen($event, $listener);
            }
        }
    }
}
