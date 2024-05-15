<?php
namespace CustomD\LaravelHelpers\ValueObjects;

use ReflectionClass;
use ReflectionParameter;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Resources\Json\JsonResource;
use CustomD\LaravelHelpers\ValueObjects\Attributes\MapToCase;
use Illuminate\Contracts\Container\BindingResolutionException;
use CustomD\LaravelHelpers\ValueObjects\Attributes\MakeableObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\ChildValueObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\CollectableValue;
use ReflectionAttribute;

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

        $map = static::resolveMapToCaseAttribute();

        $args = collect($args);
        $args = match ($map) {
            'snake' => $args->mapWithKeys(fn($value, $key) => [str($key)->snake()->toString() => $value]),
            'camel' => $args->mapWithKeys(fn($value, $key) => [str($key)->camel()->toString() => $value]),
            'studly' => $args->mapWithKeys(fn($value, $key) => [str($key)->studly()->toString() => $value]),
            default => $args,
        };

        $args = $args->only(
            static::getConstructorArgs()->map(fn(ReflectionParameter $parameter) => $parameter->getName())
        )->toArray();

        $mapped = static::resolveChildValueObjects(...$args);
        $mapped = static::resolveMakeableObjects(...$mapped);
        $mapped = static::resolveCollectableValueObjects(...$mapped);

        $instance =  new static(...$mapped); //@phpstan-ignore-line -- meant to be static
        $instance->validate();
        return $instance;
    }

    /**
     *
     * @param mixed $args
     * @return ?static
     * @throws BindingResolutionException
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
        return static::make(...$data); //@phpstan-ignore-line -- meant to be static
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
                $arg = $args[$name];
                if ($arg instanceof Collection) {
                    return;
                }
                /** @phpstan-ignore-next-line */
                $args[$name] = collect($arg)->map(fn($item) => $attribute::make(...$item));
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
}
