<?php

if (! function_exists('execute')) {

    /**
     * Undocumented function
     *
     * @param class-string $classname
     * @param mixed ...$args
     *
     * @return mixed
     */
    function execute(string $classname, ...$args):mixed
    {
        return app($classname)->execute(...$args);
    }
}

if (! function_exists('ddh')) {
    /**
     * dd() with headers
     * @param mixed ...$vars
     * @return void
     */
    function ddh(...$vars)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        $backfiles = debug_backtrace();
        $vars[] = $backfiles[0]['file'] . ':' . $backfiles[0]['line']; // @phpstan-ignore-line
        dd(...$vars);
    }
}

if (! function_exists('dumph')) {
    /**
     * dumph() with headers
     * @param mixed  ...$vars
     * @return void
     */
    function dumph(...$vars)
    {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: *');
        header('Access-Control-Allow-Headers: *');
        $backfiles = debug_backtrace();
        $vars[] = $backfiles[0]['file'] . ':' . $backfiles[0]['line']; // @phpstan-ignore-line
        dump(...$vars);
    }
}

if(!function_exists(from_key_or_model))
{
    /**
     * @template TType
     * @param mixed $value
     * @param class-string $type
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException 
     * @return Illuminate\Database\Eloquent\Model|TType
     */
    function from_key_or_model(mixed $value, string $type)
    {
        if(is_a($var, $type)){
            return $var;
        }
        return $type::whereKey($var)->firstOrFail();
    }
}
