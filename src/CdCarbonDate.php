<?php
namespace CustomD\LaravelHelpers;

use DateTimeInterface;
use Carbon\CarbonImmutable;
use \Illuminate\Support\Carbon;
use Carbon\Carbon as CarbonCarbon;
use Carbon\CarbonInterface;
use Illuminate\Support\Facades\Date;

class CdCarbonDate
{
    protected string $userTimezone;
    protected string $systemTimezone;
    protected CarbonInterface $carbon;

    public function __construct(?string $userTimezone = null, ?string $systemTimezone = null)
    {
        $this->userTimezone = $userTimezone ?? config('request.user.timezone') ?? config('app.user_timezone') ?? config('app.timezone'); // @phpstan-ignore-line -- will be string
        $this->systemTimezone = $systemTimezone ?? config('app.timezone'); //@phpstan-ignore-line - timezone is string

        $this->carbon = (new CarbonImmutable());

        $this->carbon->setUserTimezone($this->userTimezone)->setSystemTimezone($this->systemTimezone);//@phpstan-ignore-line - timezone is string
    }

    /**
     * @param string $name
     * @param array<mixed,mixed> $arguments
     * @return mixed
     */
    public function __call(string $name, array $arguments): mixed
    {
        return $this->carbon->{$name}(...$arguments);
    }

    /**
     * @param string $name
     * @param array<mixed,mixed> $arguments
     * @return mixed
     */
    public static function __callStatic(string $name, array $arguments): mixed
    {
        $instance = new static(); //@phpstan-ignore-line
        return $instance->{$name}(...$arguments);
    }
}
