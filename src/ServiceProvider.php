<?php

namespace CustomD\LaravelHelpers;

use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CustomD\LaravelHelpers\Database\Query\Mixins\NullOrEmptyMixin;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{


    /**
     * boot our macros
     *
     * @return void
     */
    public function boot()
    {
        $this->registerDbMacros();
        $this->registerStringMacros();
    }


    protected function registerDbMacros(): void
    {
        Builder::mixin(new NullOrEmptyMixin());

         /** @macro \Illuminate\Database\Eloquent\Relations\Relation */
        Relation::macro(
            'orFail',
            function (?string $error = null) {
                /**
                 * @var \Illuminate\Database\Eloquent\Relations\Relation $this
                 * @phpstan-ignore-next-line
                 */
                return $this->withDefault(
                    fn(Model $relation, Model $parent) => throw new ModelNotFoundException(
                        $error ?? class_basename($relation) . ' relation not mapped to ' . class_basename($parent)
                    )
                );
            }
        );
    }

    protected function registerStringMacros(): void
    {
        /** @macro \Illuminate\Support\Str */
        Str::macro('reverse', function ($string, $encoding = null) {
            $chars = mb_str_split($string, 1, $encoding ?? mb_internal_encoding());
            return implode('', array_reverse($chars));
        });
    }
}
