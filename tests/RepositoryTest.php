<?php

namespace CustomD\LaravelHelpers\Tests;

use Illuminate\Support\Str;
use Orchestra\Testbench\TestCase;
use CustomD\LaravelHelpers\ServiceProvider;
use CustomD\LaravelHelpers\Facades\LaravelHelpers;

class RepositoryTest extends TestCase
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

    public function test_repository_gets_model_builder()
    {
        $repository = new ModelOneRepository();
        $this->assertStringContainsString("`deleted_at` is null", $repository->getModel()->toRawSql());

        $this->assertStringNotContainsString("`deleted_at` is null", $repository->withoutScopes(
            fn() => $repository->getModel()->toRawSql()
        ));
    }
}
