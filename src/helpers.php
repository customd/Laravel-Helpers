<?php

if (! function_exists('execute')) {
    function execute(string $classname, ...$args)
    {
        $args = collect($args)->flatten(1);
        return resolve($classname)->execute(...$args);
    }
}
