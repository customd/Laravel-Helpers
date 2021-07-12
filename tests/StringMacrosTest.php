<?php

namespace CustomD\LaravelHelpers\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use CustomD\LaravelHelpers\ServiceProvider;
use CustomD\LaravelHelpers\Facades\LaravelHelpers;

class StringMacrosTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'laravel-helpers' => LaravelHelpers::class,
        ];
    }

    public function testReverseStringMacro()
    {
        $str = "hello World";
        $rev = 'dlroW olleh';
        $this->assertEquals($rev, Str::reverse($str));

        $encodedStr = 'te reo māori';
        $encodedRev = 'iroām oer et';
        $this->assertEquals($encodedRev, Str::reverse($encodedStr));
    }
}
