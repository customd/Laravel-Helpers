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
/*
 * dd() with headers
 */
if (!function_exists('ddh')) {
    function ddh(...$vars){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        $backfiles = debug_backtrace();
        $vars[] = $backfiles[0]['file'] . ':' . $backfiles[0]['line'];
        dd(...$vars);
    }
}

/*
 * dump() with headers
 */
if (!function_exists('dumph')) {
    function dumph(...$vars){
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        $backfiles = debug_backtrace();
        $vars[] = $backfiles[0]['file'] . ':' . $backfiles[0]['line'];
        dump(...$vars);
    }
}


