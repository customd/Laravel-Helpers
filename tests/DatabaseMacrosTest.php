<?php
namespace CustomD\LaravelHelpers\Tests;

use Orchestra\Testbench\TestCase;
use CustomD\LaravelHelpers\ServiceProvider;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Auth\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DatabaseMacrosTest extends TestCase
{
    use RefreshDatabase;

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__ . '/migrations');
    }

    protected function getEnvironmentSetUp($app)
    {
        # Setup default database to use sqlite :memory:
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

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

    public function test_where_null_or_empty()
    {
        $query = ModelOne::whereNullOrEmpty("age");
        $this->assertStringContainsString('where ("age" = ? or "age" is null)', $query->toSql());
    }

    public function test_or_where_null_or_empty()
    {
        $query = ModelOne::where('x', 'y')->orWhereNullOrEmpty("age");
        $this->assertStringContainsString('or ("age" = ? or "age" is null)', $query->toSql());
    }

    public function test_where_not_null_or_empty()
    {
        $query = ModelOne::whereNotNullOrEmpty("age");
        $this->assertStringContainsString('where ("age" != ? and "age" is not null)', $query->toSql());
    }

    public function test_or_where_not_null_or_empty()
    {
        $query = ModelOne::where('x', 'y')->orWhereNotNullOrEmpty("age");
        $this->assertStringContainsString('or ("age" != ? and "age" is not null)', $query->toSql());
    }

    public function test_case_insensitive_iwhere()
    {
        $query = ModelOne::iWhere('name', 'TestCom')
            ->iWhere(['company' => 'my-comPanbyName'])
            ->orIWhere('country', '!=', 'Nz')
            ->orIWhere([
                'col'  => 'umn',
                'mnot' => 'tonm',
                ['asdf' ,'!=', 'asdf', 'or'],
            ]);
        $this->assertStringContainsString('LOWER(name) = ?', $query->toSql());
        $this->assertStringContainsString('or LOWER(country) != ?', $query->toSql());
        $this->assertStringContainsString('LOWER(company) = ?', $query->toSql());

        $this->assertTrue(str($query->toRawSql())->contains("where LOWER(name) = 'testcom' and (LOWER(company) = 'my-companbyname') or LOWER(country) != 'nz' or (LOWER(col) = 'umn' or LOWER(mnot) = 'tonm' or LOWER(asdf) != 'asdf')"));
    }

    public function test_has_one_nullable()
    {

        $m1 =  ModelOne::create([
        ]);

        $m2 =  ModelTwo::create([
            'model_one_id' => $m1->id
        ]);

        $test = $m1->modelTwoForced;

        $this->assertEquals($m2->id, $test->id);
    }

    public function test_has_one_not_nullable()
    {

        $m1 =  ModelOne::create([
        ]);

        $m2 =  ModelTwo::create([
            'model_one_id' => 0
        ]);

        $test = $m1->modelTwo;
        $this->assertNull($test);

        $this->expectException(ModelNotFoundException::class);

        $test = $m1->modelTwoForced;
    }

    public function test_belongs_to_nullable()
    {

        $m1 =  ModelOne::create([
        ]);

        $m2 =  ModelTwo::create([
            'model_one_id' => $m1->id
        ]);

        $test = ModelTwo::first()->modelOneForced;

        $this->assertEquals($m1->id, $test->id);
    }

    public function test_belongs_to_not_nullable()
    {

        $m1 =  ModelOne::create([
        ]);

        $m2 =  ModelTwo::create([
            'model_one_id' => 0
        ]);

        $test = ModelTwo::first()->modelOne;
        $this->assertNull($test);

        $this->expectException(ModelNotFoundException::class);

        $test = ModelTwo::first()->modelOneForced;
    }
}
