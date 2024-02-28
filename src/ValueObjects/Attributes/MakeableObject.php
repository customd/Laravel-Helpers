<?php
namespace CustomD\LaravelHelpers\ValueObjects\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PARAMETER)]
class MakeableObject
{

    public function __construct(public string $class, public bool $spread = false)
    {
    }
}
