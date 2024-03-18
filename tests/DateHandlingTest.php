<?php

namespace CustomD\LaravelHelpers\Tests;

use Carbon\Carbon;
use Carbon\CarbonImmutable;
use CustomD\LaravelHelpers\CdCarbonDate;
use Mockery;
use Mockery\MockInterface;
use Illuminate\Http\Request;
use Orchestra\Testbench\TestCase;
use Illuminate\Support\Facades\Config;
use CustomD\LaravelHelpers\ServiceProvider;
use CustomD\LaravelHelpers\Facades\LaravelHelpers;
use CustomD\LaravelHelpers\Http\Middleware\UserTimeZone;
use CustomD\LaravelHelpers\Tests\ExecutableAction;
use DateTime;
use Illuminate\Support\Carbon as SupportCarbon;

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
        CarbonImmutable::setTestNow($nzDate);

        //asserting carbon dates
        $this->assertEquals("Thu Jan 05 2023 22:04:00 GMT+0000", now()->toString());
        $this->assertEquals("Fri Jan 06 2023 11:04:00 GMT+1300", now()->toUsersTimezone()->toString());
        $this->assertEquals("Fri Jan 06 2023 11:04:00 GMT+1300", Carbon::toUsersTimezone()->toString());
        $this->assertEquals("Thu Jan 05 2023 11:00:00 GMT+0000", Carbon::usersStartOfDay()->toString());
        $this->assertEquals("Thu Jan 05 2023 11:00:00 GMT+0000", now()->usersStartOfDay()->toString());
        $this->assertEquals("Sat Jan 07 2023 10:59:59 GMT+0000", Carbon::parseWithTz('2023-01-07 23:55:00')->usersEndOfDay()->toString());
        $this->assertEquals("Sat Dec 31 2022 11:00:00 GMT+0000", Carbon::parseWithTz('2023-01-01T02:04:00.000Z')->usersStartOfDay()->toString());
        $this->assertEquals("Fri Jan 06 2023 10:59:59 GMT+0000", now()->usersEndOfDay()->toString());
        $this->assertEquals("Sun Jan 01 2023 11:00:00 GMT+0000", now()->usersStartOfWeek()->toString());
        $this->assertEquals("Sat Dec 31 2022 11:00:00 GMT+0000", now()->usersStartOfWeek(Carbon::SUNDAY)->toString());
        $this->assertEquals("Mon Jan 02 2023 00:00:00 GMT+1300", now()->usersStartOfWeek()->toUsersTimezone()->toString());
        $this->assertEquals("Sun Jan 01 2023 00:00:00 GMT+1300", now()->usersStartOfWeek(Carbon::SUNDAY)->toUsersTimezone()->toString());
        $this->assertEquals("2023-01-08T23:59:59+13:00", now()->usersEndOfWeek()->usersFormat('c'));
        $this->assertEquals("Sun Jan 08 2023 10:59:59 GMT+0000", now()->usersEndOfWeek()->toString());
        $this->assertEquals("Tue Jan 31 2023 10:59:59 GMT+0000", now()->usersEndOfMonth()->toString());
        $this->assertEquals("Sat Dec 31 2022 11:00:00 GMT+0000", now()->usersStartOfMonth()->toString());
        $this->assertEquals("Sunday, 01-Jan-2023 00:00:00 NZDT", now()->usersStartOfMonth()->usersFormat(DateTime::COOKIE));
        $this->assertEquals("Sat Dec 31 2022 11:00:00 GMT+0000", now()->usersStartOfQuarter()->toString());
        $this->assertEquals("Fri Mar 31 2023 10:59:59 GMT+0000", now()->usersEndOfQuarter()->toString());
        $this->assertEquals("Sun Dec 31 2023 10:59:59 GMT+0000", now()->usersEndOfYear()->toString());
        $this->assertEquals("Sat Dec 31 2022 11:00:00 GMT+0000", now()->usersStartOfYear()->toString());
        $this->assertEquals("2023-01-01T00:00:00.000+13:00", now()->usersStartOfQuarter()->usersFormat(DateTime::RFC3339_EXTENDED));

        //make sure the factory returns a new instance each time
        $customdDate1 = CdCarbonDate::toUsersTimezone(now()->toString());
        $customdDate2 = CdCarbonDate::setUserTimezone('Africa/Johannesburg')->toUsersTimezone(now()->toString());

        $this->assertNotEquals($customdDate1->toString(), $customdDate2->toString());

        $this->assertEquals("Fri Jan 06 2023 11:04:00 GMT+1300", CdCarbonDate::toUsersTimezone(now())->toString());
        $this->assertEquals("Thu Jan 05 2023 11:00:00 GMT+0000", CdCarbonDate::usersStartOfDay()->toString());
        $this->assertEquals("Tue Jan 10 2023 10:59:59 GMT+0000", CdCarbonDate::parse('2023-01-09 23:55:00')->usersEndOfDay()->toString());
        $this->assertEquals("Sat Dec 31 2022 11:00:00 GMT+0000", CdCarbonDate::parse('2023-01-01T02:04:00.000Z')->usersStartOfDay()->toString());
    }

    public function testMultipleCarbonInstances()
    {
        Config::set('app.user_timezone', 'Pacific/Auckland');
        $nzDate = '2023-01-05T22:04:00.000Z'; //equiv to 2023-01-06 11:04:00 AM NZ
        Carbon::setTestNow($nzDate);

        $custom = CdCarbonDate::setUserTimezone('Africa/Johannesburg');
        //dd($custom->toUsersTimezone()->toString(), now()->toString(), now()->getUserTimezone());

        $this->assertNotEquals(now()->toUsersTimezone()->toString(), $custom->toUsersTimezone()->toString());
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
