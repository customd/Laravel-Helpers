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

        return function (string $timezone) use ($mixin) {
            $mixin->userTimezone = $timezone;
            return $this;
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

        return function (string $timezone) use ($mixin) {
            $mixin->systemTimezone = $timezone;
            return $this;
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

    public function usersStartOfWeek(): Closure
    {
        $mixin = $this;
        return static function (?int $day = null) use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->startOfWeek($day)->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersEndOfWeek(): Closure
    {
        $mixin = $this;
        return static function (?int $day = null) use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->endOfWeek($day)->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersStartOfMonth(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->startOfMonth()->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersEndOfMonth(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->endOfMonth()->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersStartOfQuarter(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->startOfQuarter()->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersEndOfQuarter(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->endOfQuarter()->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersStartOfYear(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->startOfYear()->setTimezone($mixin->systemTimezone);
        };
    }

    public function usersEndOfYear(): Closure
    {
        $mixin = $this;
        return static function () use ($mixin) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->endOfYear()->setTimezone($mixin->systemTimezone);
        };
    }

    public function parseWithTz(): Closure
    {
        return static function ($time) {
             /** @var Carbon $date */
            $date = self::this();
            return $date->parse($time, $date->getUserTimezone());
        };
    }

    public function usersFormat(): Closure
    {
        return static function ($format) {
            /** @var Carbon $date */
            $date = self::this();
            return $date->setTimezone($date->getUserTimezone())->format($format);
        };
    }
}
