<?php
namespace CustomD\LaravelHelpers\ValueObjects\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class MapToCase
{

    /**
     *
     * @param string $mapAs (can be snake,camel or studly)
     * @return void
     */
    public function __construct(public string $mapAs = 'camel')
    {
    }
}
