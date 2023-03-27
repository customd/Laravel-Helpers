<?php

namespace CustomD\LaravelHelpers;

use Closure;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use CustomD\LaravelHelpers\CdCarbonDate;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CustomD\LaravelHelpers\Database\Query\Mixins\NullOrEmptyMixin;
use CustomD\LaravelHelpers\Facades\CdCarbonDate as FacadesCdCarbonDate;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        $this->app->bind('cd-carbon-date', function ($app) {
            return new CdCarbonDate();
        });
        Carbon::mixin(new CdCarbonMixin());
    }


    /**
     * boot our macros
     *
     * @return void
     */
    public function boot()
    {
        $this->registerDbMacros();
        $this->registerStringMacros();

        \Illuminate\Database\Eloquent\Factories\Factory::macro('randomTestingId', function ($min = 1000, $max = 1000000) {
            /** @var \Illuminate\Database\Eloquent\Factories\Factory $this*/
            return \Illuminate\Support\Facades\App::runningUnitTests() ? $this->faker->unique()->numberBetween($min, $max) : null; //@phpstan-ignore-line
        });
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

         /** @macro \Illuminate\Database\Query\Builder */
        Builder::macro('iWhere', function ($column, $operator = null, $value = null, $boolean = 'and') {
            /** @var \Illuminate\Database\Query\Builder $this */
            if (is_array($column)) {
                return $this->addArrayOfWheres($column, $boolean, 'iWhere'); //@phpstan-ignore-line
            }

            [$value, $operator] = $this->prepareValueAndOperator(
                $value,
                $operator,
                func_num_args() === 2
            );

            return $this->whereRaw("LOWER({$column}) {$operator} ?", [strtolower($value)], $boolean);
        });
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
