<?php
declare(strict_types=1);

namespace CustomD\LaravelHelpers\Tests\ValueObjects;

use CustomD\LaravelHelpers\ValueObjects\ValueObject;
use Stringable;

class StringValue extends ValueObject implements Stringable
{
    public function __construct(
        readonly public string $value,
    ) {
    }

    public function __toString(): string
    {
        return $this->value;
    }
}
