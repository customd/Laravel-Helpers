<?php

namespace CustomD\LaravelHelpers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use CustomD\LaravelHelpers\Database\Query\Mixins\NullOrEmptyMixin;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{

    public function register()
    {
        Carbon::mixin(new CdCarbonMixin());
        CarbonImmutable::mixin(new CdCarbonMixin());
    }


    /**
     * boot our macros
     *
     * @return void
     */
    public function boot()
    {
        $this->registerDbMacros();

        \Illuminate\Database\Eloquent\Factories\Factory::macro('randomTestingId', function ($min = 1000, $max = 1000000) {
            /** @var \Illuminate\Database\Eloquent\Factories\Factory $this*/
            return \Illuminate\Support\Facades\App::runningUnitTests() ? $this->faker->unique()->numberBetween($min, $max) : null; //@phpstan-ignore-line
        });

         /** @macro Http */
        Http::macro('enableRecording', function () {
            /** @var  \Illuminate\Http\Client\Factory $this*/
            return $this->record(); //@phpstan-ignore-line
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
    }
}
