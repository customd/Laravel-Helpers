<?php
namespace CustomD\LaravelHelpers\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \CustomD\LaravelHelpers\CdCarbonDate setUserTimezone(string $userTimezone)
 * @method static string getUserTimezone()
 * @method static \CustomD\LaravelHelpers\CdCarbonDate setSystemTimezone(string $systemTimezone)
 * @method static string getSystemTimezone()
 * @method static \Illuminate\Support\Carbon usersStartOfDay()
 * @method static \Illuminate\Support\Carbon usersEndOfDay()
 * @method static \Illuminate\Support\Carbon toUsersTimezone()
 * @method static \Illuminate\Support\Carbon toSystemTimezone()
 * @method static \Illuminate\Support\Carbon parse($time = null, $tz = null)
 */
class CdCarbonDate extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'cd-carbon-date';
    }
}
