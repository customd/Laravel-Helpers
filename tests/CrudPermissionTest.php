<?php
namespace CustomD\LaravelHelpers\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use CustomD\LaravelHelpers\ServiceProvider;
use CustomD\LaravelHelpers\Tests\ModelOnePolicy;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CrudPermissionTest extends TestCase
{

    public function test_can_permissions()
    {
        $policy = new ModelOnePolicy();
        $user = new User();

        $model = new ModelOne();

        Gate::define('model_ones.viewAny', fn() => true);
        Gate::define('model_ones.view', fn() => true);
        Gate::define('model_ones.fetch', fn() => false);

        $this->assertTrue($policy->viewAny($user)->allowed());
        $this->assertFalse($policy->fetch($user, $model)->allowed());
        $this->assertTrue($policy->view($user, $model)->allowed());
    }
}
