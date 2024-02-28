<?php
declare(strict_types=1);

namespace CustomD\LaravelHelpers\Tests\ValueObjects;

class Constructable
{
    public function __construct(
        readonly public array $values,
    ) {
    }


}
