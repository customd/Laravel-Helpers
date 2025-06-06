<?php
namespace CustomD\LaravelHelpers\ValueObjects;

use ReflectionClass;
use ReflectionAttribute;
use ReflectionParameter;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use CustomD\LaravelHelpers\ValueObjects\Attributes\MapToCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use CustomD\LaravelHelpers\ValueObjects\Attributes\MakeableObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\ChildValueObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\CollectableValue;

/**
 * @implements Arrayable<string,mixed>
 */
abstract readonly class ValueObject implements Arrayable
{
    /**
     *
     * @param mixed $args
     * @return static
     * @throws BindingResolutionException
     */
    public static function make(...$args): static
    {
        if (array_is_list($args) === false) {
            /** @var Collection<string,mixed> $args*/
            $args = collect($args);
            $map = static::resolveMapToCaseAttribute();
            $args = match ($map) {
                'snake' => $args->mapWithKeys(fn($value, $key) => [str($key)->snake()->toString() => $value]),
                'camel' => $args->mapWithKeys(fn($value, $key) => [str($key)->camel()->toString() => $value]),
                'studly' => $args->mapWithKeys(fn($value, $key) => [str($key)->studly()->toString() => $value]),
                default => $args,
            };

            $args = $args->only(
                static::getConstructorArgs()->map(fn(ReflectionParameter $parameter) => $parameter->getName())
            );
        }

        $mapped = static::resolveChildValueObjects(...$args);
        $mapped = static::resolveMakeableObjects(...$mapped);
        $mapped = static::resolveCollectableValueObjects(...$mapped);

        $instance =  new static(...$mapped); //@phpstan-ignore new.static (this is a valid use case for late static binding)
        $instance->validate();
        return $instance;
    }

    /**
     *
     * @param mixed $args
     * @return ?static
     */
    public static function makeOrNull(...$args): ?static
    {
        try {
            return static::make(...$args);
        } catch (\Throwable $th) {
            return null;
        }
    }

    public static function fromRequest(FormRequest $request, bool $onlyValidated = true): static
    {
        /** @var array<string, mixed> */
        $data = $onlyValidated ? $request->validated() : $request->all();

        return static::make(...$data);
    }

    /**
     * @param array<int|string, mixed> $data
     */
    public static function fromArray(array $data): static
    {
        return static::make(...$data);
    }

    /**
     *
     * @return Collection<int, ReflectionParameter>
     */
    protected static function getConstructorArgs(): Collection
    {
        return collect((new ReflectionClass(static::class))->getConstructor()?->getParameters() ?? []);
    }

    /**
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [];
    }

    protected function validate(): void
    {
        $validator = app('validator')->make($this->toArray(), $this->rules());
        $validator->validate();
    }

    /**
     * @return array<string, mixed|ValueObject|Collection>
     */
    public function toArray(): array
    {

        return static::getConstructorArgs()
            ->map(fn(ReflectionParameter $parameter) => $parameter->getName())
            ->mapWithKeys(fn($property) => [$property => $this->{$property}])
            ->toArray();
    }

    public function toJsonString(): string
    {
        return json_encode($this->toArray(), JSON_THROW_ON_ERROR);
    }


    public function toJsonResource(?string $resource = null): JsonResource
    {
        $resource ??= JsonResource::class;
        return $resource::make($this->toArray());
    }


    /**
     * @param array<string, mixed|ValueObject|Collection> ...$args
     * @return array<string, mixed|ValueObject|Collection>
     */
    protected static function resolveCollectableValueObjects(...$args): array
    {
        static::getConstructorArgs()
            ->filter(fn (ReflectionParameter $parameter):bool => filled($parameter->getAttributes(CollectableValue::class)))
            ->each(function (ReflectionParameter $parameter) use (&$args): void {
                $attributes = collect($parameter->getAttributes(CollectableValue::class))->first();
                $name = $parameter->getName();

                //should not be possible but hey
                if ($attributes === null || ! isset($args[$name]) || ! class_exists($attributes::class)
                    ) {
                    return;
                }
                $attribute = $attributes->getArguments()[0];
                $collectionClass = $attributes->getArguments()[1] ?? Collection::class;
                $arg = $args[$name];
                if ($arg instanceof $collectionClass) {
                    return;
                }
                /** @phpstan-ignore-next-line */
                $args[$name] = (new $collectionClass($arg))->map(fn($item) => $attribute::make(...$item));
            });

        return $args; //@phpstan-ignore-line
    }

    /**
     * @param array<string, mixed|ValueObject|Collection> ...$args
     * @return array<string, mixed|ValueObject|Collection>
     */
    protected static function resolveChildValueObjects(...$args): array
    {
        static::getConstructorArgs()
            ->filter(fn (ReflectionParameter $parameter):bool => filled($parameter->getAttributes(ChildValueObject::class)))
            ->each(function (ReflectionParameter $parameter) use (&$args): void {
                $attributes = collect($parameter->getAttributes(ChildValueObject::class))->first();
                $name = $parameter->getName();

                //should not be possible but hey
                if ($attributes === null || ! isset($args[$name])
                    ) {
                    return;
                }
                $attribute = $attributes->getArguments()[0];
                $arg = $args[$name];
                if ($arg instanceof $attribute) {
                    return;
                }

                if (is_iterable($arg)) {
                    $args[$name] = $attribute::make(...$arg);
                    return;
                }
                $args[$name] = $attribute::make($arg);
            });

        return $args; //@phpstan-ignore-line
    }

    /**
     * @param array<string, mixed|ValueObject|Collection> ...$args
     * @return array<string, mixed|ValueObject|Collection>
     */
    protected static function resolveMakeableObjects(...$args): array
    {
        static::getConstructorArgs()
            ->filter(fn (ReflectionParameter $parameter):bool => filled($parameter->getAttributes(MakeableObject::class)))
            ->each(function (ReflectionParameter $parameter) use (&$args): void {
                $attributes = collect($parameter->getAttributes(MakeableObject::class))->first();
                $name = $parameter->getName();

                //should not be possible but hey
                if ($attributes === null || ! isset($args[$name])
                    ) {
                    return;
                }
                $attribute = $attributes->getArguments()[0];
                $spread = $attributes->getArguments()[1] ?? false;
                $arg = $args[$name];
                if ($arg instanceof $attribute) {
                    return;
                }

                $methodExists = method_exists($attribute, 'make');
                $constructorExists = method_exists($attribute, '__construct');

                if (! $methodExists && ! $constructorExists) {
                    return;
                }
                if ($methodExists) {
                    if (is_iterable($arg) && $spread) {
                        $args[$name] = $attribute::make(...$arg);
                        return;
                    }
                    $args[$name] = $attribute::make($arg);
                    return;
                }

                if (is_iterable($arg) && $spread) {
                    $args[$name] = new $attribute(...$arg);
                    return;
                }
                $args[$name] = new $attribute($arg);
            });

        return $args; //@phpstan-ignore-line
    }


    public static function resolveMapToCaseAttribute(): ?string
    {
        $reflectionClass = new ReflectionClass(static::class);

        return collect($reflectionClass->getAttributes(MapToCase::class))
            ->map(fn (ReflectionAttribute $attribute): string => $attribute->getArguments()[0])
            ->first();
    }

    /**
     * this allows you to create a new valueobject with updated values
     * @param string|array<string,mixed> $key - dot notation accepted, this is the key you wish to override, passing an key=>value array here will set all keys passed
     * @param mixed $value - values to replace when doing single keys.
     */
    public function put(string|array $key, mixed $value = null): static
    {
        $data = $this->toArray();

        if (is_string($key)) {
            data_set($data, $key, $value);
            /** @var array<string,mixed> $data */
            return static::fromArray($data);
        }

        $dots = Arr::dot($key);

        foreach ($dots as $k => $v) {
            data_set($data, $k, $v);
            /** @var array<string,mixed> $data */
        }

        return static::fromArray($data);
    }
}
