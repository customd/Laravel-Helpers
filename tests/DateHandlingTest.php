<?php

namespace CustomD\LaravelHelpers\Tests;

use Carbon\Carbon;
use CustomD\LaravelHelpers\Facades\CdCarbonDate;
use Mockery;
use Mockery\MockInterface;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;
use CustomD\LaravelHelpers\ServiceProvider;
use CustomD\LaravelHelpers\Facades\LaravelHelpers;
use CustomD\LaravelHelpers\Http\Middleware\UserTimeZone;
use CustomD\LaravelHelpers\Tests\ExecutableAction;

class DateHandlingTest extends TestCase
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

    public function testDatesBasedOnUserAndSystemTimezone()
    {
        Config::set('app.user_timezone', 'Pacific/Auckland');

        $nzDate = '2023-01-05T22:04:00.000Z'; //equiv to 2023-01-06 11:04:00 AM NZ

        Carbon::setTestNow($nzDate);

        $this->assertEquals("Thu Jan 05 2023 22:04:00 GMT+0000", now()->toString());
        $this->assertEquals("Fri Jan 06 2023 11:04:00 GMT+1300", CdCarbonDate::toUsersTimezone(now())->toString());
        $this->assertEquals("Fri Jan 06 2023 11:04:00 GMT+1300", now()->toUsersTimezone()->toString());
        $this->assertEquals("Fri Jan 06 2023 11:04:00 GMT+1300", Carbon::toUsersTimezone()->toString());

        $this->assertEquals("Thu Jan 05 2023 11:00:00 GMT+0000", CdCarbonDate::usersStartOfDay()->toString());
        $this->assertEquals("Thu Jan 05 2023 11:00:00 GMT+0000", Carbon::usersStartOfDay()->toString());
        $this->assertEquals("Thu Jan 05 2023 11:00:00 GMT+0000", now()->usersStartOfDay()->toString());

        $this->assertEquals("Sat Jan 07 2023 10:59:59 GMT+0000", CdCarbonDate::usersEndOfDay('2023-01-07 23:55:00')->toString());
        $this->assertEquals("Sat Jan 07 2023 10:59:59 GMT+0000", Carbon::parseWithTz('2023-01-07 23:55:00')->usersEndOfDay()->toString());

        $this->assertEquals("Sat Dec 31 2022 11:00:00 GMT+0000", CdCarbonDate::usersStartOfDay('2023-01-01T02:04:00.000Z')->toString());
        $this->assertEquals("Sat Dec 31 2022 11:00:00 GMT+0000", Carbon::parseWithTz('2023-01-01T02:04:00.000Z')->usersStartOfDay()->toString());

        $this->assertEquals("Fri Jan 06 2023 10:59:59 GMT+0000", now()->usersEndOfDay()->toString());
    }

    public function testSetsViaMiddleware()
    {
        Config::set('app.user_timezone', 'Pacific/Auckland');
        $request = new Request();
        (new UserTimeZone())->handle($request, function ($request) {
            $this->assertEquals('Pacific/Auckland', Config::get('request.user.timezone'));
        });

        $this->assertEquals('Pacific/Auckland', CdCarbonDate::getUserTimezone());
    }

    public function testSetsViaMiddlewareWithHeader()
    {
        Config::set('app.user_timezone', 'Pacific/Auckland');
        $request = new Request();
        $request->headers->set('X-Timezone', 'Africa/Johannesburg');

        (new UserTimeZone())->handle($request, function ($request) {
                $this->assertEquals('Africa/Johannesburg', Config::get('request.user.timezone'));
        });

        $this->assertEquals('Africa/Johannesburg', CdCarbonDate::getUserTimezone());
    }
}
