<?php

namespace CustomD\LaravelHelpers;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Event;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CustomD\LaravelHelpers\Database\Query\Mixins\NullOrEmptyMixin;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    use EventMap;

    public function boot()
    {
        $this->registerDbMacros();
        $this->registerStringMacros();
    }

    public function register()
    {
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

        /** @macro \Illuminate\Database\Eloquent\Relations\Relation */
        Relation::macro(
            'orFail',
            fn (?string $error = null) => $this->withDefault(
                fn(Model $relation, Model $parent) => throw new ModelNotFoundException(
                    $error ?? class_basename($relation) . ' relation not mapped to ' . class_basename($parent)
                )
            )
        );
    }
}
