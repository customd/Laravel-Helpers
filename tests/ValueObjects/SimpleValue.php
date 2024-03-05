<?php
declare(strict_types=1);

namespace CustomD\LaravelHelpers\Tests\ValueObjects;

use CustomD\LaravelHelpers\ValueObjects\ValueObject;

final readonly class SimpleValue extends ValueObject
{
    public function __construct(
        readonly public string $value,
        readonly public int $count = 0
    ) {
    }

    public function rules(): array
    {
        return (new SimpleValueFormRequest())->rules();
    }
}
