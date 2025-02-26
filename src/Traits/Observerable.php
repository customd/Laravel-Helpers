<?php

namespace CustomD\LaravelHelpers\Traits;

/**
 * @deprecated -- start using the attribute key
 *
 * @phpstan-ignore trait.unused
 */
trait Observerable
{

    public static function bootObserverable(): void
    {

        $observers = self::$observers ?? [];

        $class = str(class_basename(self::class))->append('Observer')->prepend('\\App\\Observers\\')->__toString();
        if (class_exists($class)) {
            array_unshift($observers, $class);
        }

        foreach (array_unique($observers) as $observer) {
            self::observe($observer);
        }
    }
}
