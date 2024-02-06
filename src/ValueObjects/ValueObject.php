<?php
namespace CustomD\LaravelHelpers\ValueObjects;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Support\Arrayable;
use ReflectionClass;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Http\FormRequest;
use ReflectionParameter;

/**
 * @implements Arrayable<string,mixed>
 */
abstract class ValueObject implements Arrayable
{
    /**
     *
     * @param mixed $args
     * @return static
     * @throws BindingResolutionException
     */
    public static function make(...$args): static
    {
        $instance =  new static(...$args); //@phpstan-ignore-line -- meant to be static
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

        $args = collect($data)->only(static::getConstructorArgs())->toArray();

        return new static(...$args); //@phpstan-ignore-line -- meant to be static
    }

    /**
     *
     * @return Collection<int, string>
     */
    protected static function getConstructorArgs(): Collection
    {
        return collect((new ReflectionClass(static::class))->getConstructor()?->getParameters() ?? [])
            ->map(fn(ReflectionParameter $parameter) => $parameter->getName());
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

    public function toArray(): array
    {
        return static::getConstructorArgs()
            ->mapWithKeys(fn($property) => [$property => $this->{$property}])
            ->toArray();
    }
}
