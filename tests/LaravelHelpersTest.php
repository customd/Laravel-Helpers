<?php

namespace CustomD\LaravelHelpers\Tests;

use Mockery;
use Mockery\MockInterface;
use Orchestra\Testbench\TestCase;
use CustomD\LaravelHelpers\ServiceProvider;
use CustomD\LaravelHelpers\Facades\LaravelHelpers;

class LaravelHelpersTest extends TestCase
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

    public function testExecuteHelperCallsActionWithNoArgs()
    {
        $this->instance(
            ExecutableAction::class,
            Mockery::mock(ExecutableAction::class, function (MockInterface $mock) {
                $mock
                ->shouldReceive('execute')
                ->once()
                ->with()
                ->andReturn(true);
            })
        );

        execute(ExecutableAction::class);
    }

    public function testExecuteHelperCallsActionWithOneArg()
    {
        $this->instance(
            ExecutableAction::class,
            Mockery::mock(ExecutableAction::class, function (MockInterface $mock) {
                $mock
                ->shouldReceive('execute')
                ->once()
                ->with('bar')
                ->andReturn(true);
            })
        );

        execute(ExecutableAction::class, ['bar']);
    }

    public function testExecuteHelperCallsActionWithOneArgAsArray()
    {
        $arg = ['me','my'];
        $this->instance(
            ExecutableAction::class,
            Mockery::mock(ExecutableAction::class, function (MockInterface $mock) use ($arg) {
                $mock
                ->shouldReceive('execute')
                ->once()
                ->with($arg)
                ->andReturn(true);
            })
        );

        execute(ExecutableAction::class, [$arg]);
    }

    public function testExecuteHelperCallsActionWithMultipleArgs()
    {
        $this->instance(
            ExecutableAction::class,
            Mockery::mock(ExecutableAction::class, function (MockInterface $mock) {
                $mock
                ->shouldReceive('execute')
                ->once()
                ->with('bar', 'car', 'bike')
                ->andReturn(true);
            })
        );

        execute(ExecutableAction::class, ['bar', 'car', 'bike']);
    }


    public function testExecuteHelperCallsActionWithArrayAsArgs()
    {

        $arrayArg = ['me','my','mine'];

        $this->instance(
            ExecutableAction::class,
            Mockery::mock(ExecutableAction::class, function (MockInterface $mock) use ($arrayArg) {
                $mock
                ->shouldReceive('execute')
                ->once()
                ->with($arrayArg, 'car', 'bike')
                ->andReturn(true);
            })
        );

        execute(ExecutableAction::class, [$arrayArg, 'car', 'bike']);
    }
}
