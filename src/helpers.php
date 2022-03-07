<?php

if (! function_exists('execute')) {
    /**
     * @param string $classname
     * @param mixed ...$args
     *
     * @return mixed
     *
     * @deprecated 07-03-2022 - should use laravel app(XXX::class) rather internally for better inline code
     */
    function execute(string $classname, ...$args):mixed
    {
        return app($classname)->execute(...$args);
    }
}
