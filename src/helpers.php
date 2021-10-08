<?php

if (! function_exists('execute')) {
    function execute(string $classname, ...$args)
    {
        return resolve($classname)->execute(...$args);
    }
}
