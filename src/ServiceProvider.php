<?php

namespace CustomD\LaravelHelpers;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Database\Query\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\Support\Stringable;
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

        Str::macro('isEmail', fn(string $value): bool => filter_var($value, FILTER_VALIDATE_EMAIL) ? true : false);
        Stringable::macro('isEmail', function (): bool {
            /** @var Stringable $this */
            return Str::isEmail($this->toString());
        });

        Arr::macro('pushBefore', function (array $existing, $key, $new): array {
            $keys = array_keys($existing);
            $index = array_search($key, $keys);
            $pos = false === $index ? count($existing) : $index;
            return array_merge(array_slice($existing, 0, $pos), $new, array_slice($existing, $pos));
        });

        Arr::macro('pushAfter', function (array $existing, $key, $new): array {
            $keys = array_keys($existing);
            $index = array_search($key, $keys);
            $pos = false === $index ? count($existing) : $index + 1;
            return array_merge(array_slice($existing, 0, $pos), $new, array_slice($existing, $pos));
        });

        Collection::macro('pushBefore', function ($key, $new): Collection {
            /** @var Collection<array-key,mixed> $this */
            $keys = $this->keys()->all();
            $index = array_search($key, $keys);
            $pos = false === $index ? count($this->items) : $index; //@phpstan-ignore property.protected
            $this->items = array_merge(array_slice($this->items, 0, $pos), $new, array_slice($this->items, $pos)); //@phpstan-ignore-line property.protected
            return $this;
        });

        Collection::macro('pushAfter', function ($key, $new): Collection {
             /** @var Collection<array-key,mixed> $this */
            $keys = $this->keys()->all();
            $index = array_search($key, $keys);
            $pos = false === $index ? count($this->items) : $index + 1; //@phpstan-ignore property.protected
            $this->items = array_merge(array_slice($this->items, 0, $pos), $new, array_slice($this->items, $pos)); //@phpstan-ignore-line property.protected
            return $this;
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
