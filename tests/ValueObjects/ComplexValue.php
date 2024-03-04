<?php
declare(strict_types=1);

namespace CustomD\LaravelHelpers\Tests\ValueObjects;

use Illuminate\Support\Collection;
use CustomD\LaravelHelpers\ValueObjects\ValueObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\MakeableObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\ChildValueObject;
use CustomD\LaravelHelpers\ValueObjects\Attributes\CollectableValue;

class ComplexValue extends ValueObject
{
    public function __construct(
        #[ChildValueObject(StringValue::class)]
        readonly public StringValue $value,
        readonly public array $address,
        #[ChildValueObject(SimpleValue::class)]
        readonly public SimpleValue $simpleValue,
        #[MakeableObject(Constructable::class)]
        readonly public ?Constructable $constructable = null,
        #[CollectableValue(SimpleValue::class)]
        readonly public ?Collection $simpleValues = null,
    ) {
    }

    public function rules(): array
    {
        return [
            'value'          => ['required'],
            'address'        => ['required', 'array'],
            'address.street' => ['required', 'string'],
            'simpleValue'    => ['required']
        ];
    }
}
