<?php
namespace CustomD\LaravelHelpers\Filament\Plugins;

use Closure;
use Filament\Panel;
use Filament\Contracts\Plugin;
use Illuminate\Support\HtmlString;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Contracts\Support\Htmlable;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use CustomD\LaravelHelpers\Http\Middleware\UserTimeZone;

class UserTimeZonePlugin implements Plugin
{

    protected bool $withDateHelperText = true;

    protected ?Closure $additionalDateConfigs = null;


    public static function make(): self
    {
        return new self();
    }

    public function hideDateHelperText(bool $disabled = true): self
    {
        $this->withDateHelperText = !$disabled;
        return $this;
    }

    public function setAdditionalDateConfigs(Closure $callback): self
    {
        $this->additionalDateConfigs = $callback;
        return $this;
    }

    public function getId(): string
    {
        return 'custom-date-time-input-timezone';
    }

    public function register(Panel $panel): void
    {

        $panel->middleware([
            UserTimeZone::class,
        ], true);
    }

    public function boot(Panel $panel): void
    {


        $timezone = function() {
            $tz = config('request.user.timezone') ?? config('app.timezone') ?? 'UTC';
            if (is_string($tz) === false || blank($tz)) {
                $tz = 'UTC';
            }
            return $tz;
        };


        DateTimePicker::configureUsing(
            function (DateTimePicker $dateTimePicker) use ($timezone): DateTimePicker {
                $dateTimePicker->timezone($timezone)

                    ->helperText(
                        fn(DateTimePicker $component): ?Htmlable => $this->withDateHelperText ? new HtmlString("Using the <b><i>{$component->getTimezone()}</i></b> timezone") : null
                    );

                    if($this->additionalDateConfigs){
                        $call = $this->additionalDateConfigs;
                      $call($dateTimePicker);
                    }

                    return $dateTimePicker;

            } 
        );

        TextColumn::configureUsing(
            fn (TextColumn $textColumn): TextColumn  => $textColumn->timezone($timezone)
        );

        TextEntry::configureUsing(
            fn(TextEntry $textEntry): TextEntry  => $textEntry->timezone($timezone)
        );
    }
}
