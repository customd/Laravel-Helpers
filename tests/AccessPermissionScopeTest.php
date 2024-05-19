<?php
namespace CustomD\LaravelHelpers\Tests;

use Orchestra\Testbench\TestCase;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Gate;
use CustomD\LaravelHelpers\Tests\ModelOne;
use CustomD\LaravelHelpers\ServiceProvider;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AccessPermissionScopeTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;
    protected User $other;
    protected array $models;

    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }



    protected function setUp(): void
    {
        parent::setup();

        $this->user = User::forceCreate(['id' => 55]);

        $this->other = User::forceCreate(['id' => 66]);

        $this->models = [
            ModelOne::create(['user_id' => $this->user->id]),
            ModelOne::create(['user_id' => $this->user->id]),
            ModelOne::create(['user_id' => $this->user->id]),
            ModelOne::create(['user_id' => $this->other->id]),
            ModelOne::create(['user_id' => $this->user->id]),
            ModelOne::create(['user_id' => $this->other->id]),

        ];

        Gate::define('model_ones.viewAny', fn() => true);
        Gate::define('model_ones.view', fn() => true);
        Gate::define('model_ones.viewOwn', fn() => true);
    }

    public function test_guest_can_view_none()
    {

        $this->assertStringNotContainsString('"user_id" is null', ModelOne::toRawSql());
        $this->assertEquals(6, ModelOne::count());

        $this->assertStringNotContainsString('"user_id" is null', ModelOne::withoutPermissionCheck()->toRawSql());
        $this->assertEquals(6, ModelOne::withoutPermissionCheck()->count());
    }

    public function test_owner_user_can_view_own()
    {
        $this->actingAs($this->user);
        $this->assertStringContainsString('"user_id" = 55', ModelOne::toRawSql());
        $this->assertEquals(4, ModelOne::count());
    }
    public function test_admin_user_can_view_all()
    {
        $this->actingAs($this->other);
        $this->assertStringContainsString('"user_id" = 66', ModelOne::toRawSql());
        $this->assertEquals(2, ModelOne::count());
    }
}
