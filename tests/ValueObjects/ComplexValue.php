<?php
declare(strict_types=1);

namespace CustomD\LaravelHelpers\Tests\ValueObjects;

use CustomD\LaravelHelpers\ValueObjects\ValueObject;

class ComplexValue extends ValueObject
{
    public function __construct(
        readonly public string $value,
        readonly public array $address,
        readonly public SimpleValue $simpleValue
    ) {
    }

    public function rules(): array
    {
        return [
            'value'          => ['required', 'string'],
            'address'        => ['required', 'array'],
            'address.street' => ['required', 'string'],
            'simpleValue'    => ['required']
        ];
    }
}
