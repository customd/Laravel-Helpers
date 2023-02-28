<?php

namespace CustomD\LaravelHelpers;

use Closure;
use Carbon\Carbon;
use CustomD\LaravelHelpers\Facades\CdCarbonDate;
use Illuminate\Support\Facades\Date;

/** @mixin \Carbon\Carbon */

class CdCarbonMixin
{

    public ?string $userTimezone = null;
    public string $systemTimezone;

    public function __construct()
    {
        $this->systemTimezone = config('app.timezone'); //@phpstan-ignore-line - config value is a string
    }

    public function setUserTimezone(): Closure
    {
        $mixin = $this;

        return static function (string $timezone) use ($mixin) {
            $mixin->userTimezone = $timezone;
        };
    }

    public function getUserTimezone(): Closure
    {
        $mixin = $this;

        return static function () use ($mixin) {
            return $mixin->userTimezone ??  config('request.user.timezone') ?? config('app.user_timezone') ?? config('app.timezone');
        };
    }

    public function setSystemTimezone(): Closure
    {
        $mixin = $this;

        return static function (string $timezone) use ($mixin) {
            $mixin->systemTimezone = $timezone;
        };
    }

    public function getSystemTimezone(): Closure
    {
        $mixin = $this;

        return static function () use ($mixin) {
            return $mixin->systemTimezone;
        };
    }

    public function toUsersTimezone(): Closure
    {
        return static function () {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone());
        };
    }

    public function toSystemTimezone(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersStartOfDay(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->startOfDay()->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersEndOfDay(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->endOfDay()->setTimezone($mixin->systemTimezone);
        };
    }

    public function parseWithTz(): Closure
    {
        return static function ($time) {
             /** @var Carbon $date */
            $date = self::this();
            return self::parse($time, $date->getUserTimezone());
        };
    }
}