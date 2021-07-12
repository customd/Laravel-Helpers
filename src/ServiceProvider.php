<?php

namespace CustomD\LaravelHelpers;

use Illuminate\Support\Facades\Event;
use Illuminate\Database\Query\Builder;
use CustomD\LaravelHelpers\Database\Query\Mixins\NullOrEmptyMixin;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    use EventMap;

    public function boot()
    {
        $this->registerEvents();
        $this->registerDbMacros();
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

    protected function registerDbMacros()
    {
        Builder::mixin(new NullOrEmptyMixin());
    }
}
