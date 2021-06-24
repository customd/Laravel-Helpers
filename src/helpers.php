<?php

if (! function_exists('execute')) {
    function execute(string $classname, ...$args)
    {
        $args = collect($args)->flatten();
        return resolve($classname)->execute(...$args);
    }
}
