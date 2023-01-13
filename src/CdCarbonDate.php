<?php
namespace CustomD\LaravelHelpers;

use \Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Date;
use DateTimeInterface;

class CdCarbonDate
{
    protected string $userTimezone;
    protected string $systemTimezone;

    public function __construct(?string $userTimezone = null, ?string $systemTimezone = null)
    {
        $this->userTimezone = $userTimezone ?? config('request.user.timezone') ?? config('app.user_timezone') ?? config('app.timezone'); // @phpstan-ignore-line -- will be string
        $this->systemTimezone = $systemTimezone ?? config('app.timezone'); //@phpstan-ignore-line - timezone is string
    }

    public function setUserTimezone(string $userTimezone): static
    {
        $this->userTimezone = $userTimezone;
        return $this;
    }

    public function getUserTimezone(): string
    {
        return $this->userTimezone;
    }

    public function setSystemTimezone(string $systemTimezone): static
    {
        $this->systemTimezone = $systemTimezone;
        return $this;
    }

    public function getSystemTimezone(): string
    {
        return $this->systemTimezone;
    }

    public function usersStartOfDay(DateTimeInterface|string|null $date = null): Carbon
    {
        return Date::parse($date ?? now(), $this->userTimezone)->setTimezone($this->userTimezone)->startOfDay()->setTimezone($this->systemTimezone);
    }

    public function usersEndOfDay(DateTimeInterface|string|null $date = null): Carbon
    {
        return Date::parse($date ?? now(), $this->userTimezone)->setTimezone($this->userTimezone)->endOfDay()->setTimezone($this->systemTimezone);
    }

    public function toUsersTimezone(DateTimeInterface|string|null $date = null): Carbon
    {
        return Date::parse($date ?? now(), $this->systemTimezone)->setTimezone($this->userTimezone);
    }

    public function toSystemTimezone(DateTimeInterface|string|null $date = null): Carbon
    {
        return Date::parse($date ?? now(), $this->systemTimezone)->setTimezone($this->systemTimezone);
    }
}
