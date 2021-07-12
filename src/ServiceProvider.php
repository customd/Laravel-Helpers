<?php

namespace CustomD\LaravelHelpers;

use Illuminate\Support\Str;
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
        $this->registerStringMacros();
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

    protected function registerStringMacros()
    {
        /** @macro \Illuminate\Support\Str */
        Str::macro('reverse', function ($string, $encoding = null) {
            $chars = mb_str_split($string, 1, $encoding ?? mb_internal_encoding());
            return implode('', array_reverse($chars));
        });
    }
}
