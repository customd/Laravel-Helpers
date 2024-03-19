<?php
declare(strict_types=1);

namespace CustomD\LaravelHelpers\Tests\ValueObjects;

use CustomD\LaravelHelpers\ValueObjects\ValueObject;

#[\CustomD\LaravelHelpers\ValueObjects\Attributes\MapToCase('camel')]
final readonly class SimpleValue extends ValueObject
{
    public function __construct(
        readonly public string $value,
        readonly public int $itemCount = 0
    ) {
    }

    public function rules(): array
    {
        return [

            'itemCount' => ["int", "min:10"],

        ];
    }
}
