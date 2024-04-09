<?php
namespace CustomD\LaravelHelpers\Filament\Plugins;

use Filament\Panel;
use Livewire\Livewire;
use Filament\Contracts\Plugin;
use Filament\Tables\Columns\TextColumn;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use CustomD\LaravelHelpers\Http\Middleware\UserTimeZone;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;

class UserTimeZonePlugin implements Plugin
{
    public static function make(): self
    {
        return new self();
    }

    public function getId(): string
    {
        return 'custom-date-time-input-timezone';
    }

    public function register(Panel $panel): void
    {
        Livewire::addPersistentMiddleware([
            UserTimeZone::class,
        ]);

        $panel->middleware([
            UserTimeZone::class,
        ]);
    }

    public function boot(Panel $panel): void
    {

        $timezone = config('request.user.timezone') ?? config('app.timezone') ?? 'UTC';
        if (is_string($timezone) === false || blank($timezone)) {
            $timezone = 'UTC';
        }

        DateTimePicker::configureUsing(
            fn (DateTimePicker $dateTimePicker): DateTimePicker  => $dateTimePicker->timezone($timezone)
                ->helperText(
                    fn(DateTimePicker $component): Htmlable => new HtmlString("Using the <b><i>{$component->getTimezone()}</i></b> timezone")
                )
        );

        TextColumn::configureUsing(
            fn (TextColumn $textColumn): TextColumn  => $textColumn->timezone($timezone)
        );

        TextEntry::configureUsing(
            fn(TextEntry $textEntry): TextEntry  => $textEntry->timezone($timezone)
        );
    }
}
