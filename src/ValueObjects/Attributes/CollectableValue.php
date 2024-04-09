<?php
namespace CustomD\LaravelHelpers\ValueObjects\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class CollectableValue
{

    public function __construct(public string $class)
    {
    }
}
