<?php

namespace CustomD\LaravelHelpers\Filament\Plugins;


use Filament\Panel;
use Livewire\Livewire;
use Filament\Contracts\Plugin;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\DatePicker;
use Filament\Infolists\Components\TextEntry;
use Filament\Forms\Components\DateTimePicker;
use CustomD\LaravelHelpers\Http\Middleware\UserTimeZone;

class UserTimeZonePlugin implements Plugin
{
    public static function make(): static
    {
        return new static();
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
        DateTimePicker::configureUsing(function (DateTimePicker $dateTimePicker): void {
            $dateTimePicker->timezone(config('request.user.timezone'));
            $dateTimePicker->helperText(fn(DateTimePicker $component) => str("Using the **_{$component->getTimezone()}_** timezone")->markdown()->toHtmlString());
        });

        TextColumn::configureUsing(function (TextColumn $textColumn): void {
            $textColumn->timezone(config('request.user.timezone'));
        });

        TextEntry::configureUsing(function (TextEntry $textEntry): void {
            $textEntry->timezone(config('request.user.timezone'));
        });

    }
}
