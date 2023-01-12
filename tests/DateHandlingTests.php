<?php

namespace CustomD\LaravelHelpers\Tests;

use Mockery;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use CustomD\LaravelHelpers\ServiceProvider;
use CustomD\LaravelHelpers\Facades\LaravelHelpers;
use CustomD\LaravelHelpers\Tests\ExecutableAction;

class DateHandlingTests extends TestCase
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

    public function setsStartOfDayInUsersTimezone(){

    }

    public function setsViaMiddleware()
    {

    }

}
