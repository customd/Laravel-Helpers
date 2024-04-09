<?php

namespace CustomD\LaravelHelpers\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use CustomD\LaravelHelpers\ServiceProvider;
use CustomD\LaravelHelpers\Facades\LaravelHelpers;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RepositoryTest extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    public function test_repository_gets_model_builder()
    {
        $repository = new ModelOneRepository();
        $this->assertStringContainsString('"deleted_at" is null', $repository->getModel()->toRawSql());

        $this->assertStringNotContainsString('"deleted_at" is null', $repository->withoutScopes(
            fn() => $repository->getModel()->toRawSql()
        ));
    }

    public function test_repository_forwards_calls()
    {
        ModelOne::insert([
            ['name' => 'one', 'deleted_at' => null],
            ['name' => 'two', 'deleted_at' => null],
            ['name' => 'four', 'deleted_at' => null],
            ['name' => 'three', 'deleted_at' => now()],
        ]);

        $repository = new ModelOneRepository();
        $this->assertEquals(3, $repository->count());
        $this->assertEquals(4, $repository->withoutScopes(fn() => $repository->count()));

        $this->assertNull($repository->whereName('three')->first());
        $this->assertInstanceOf(ModelOne::class, $repository->withoutScopes(fn() => $repository->whereName('three')->first()));

        $this->assertNull($repository->findByName('three'));
        $this->assertInstanceOf(ModelOne::class, $repository->findByName('one'));
        $this->assertInstanceOf(ModelOne::class, $repository->withoutScopes(fn() => $repository->findByName('three')));
    }
}
