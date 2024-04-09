<?php
namespace CustomD\LaravelHelpers\ValueObjects\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class ChildValueObject
{
    public function __construct(public string $class)
    {
    }
}
