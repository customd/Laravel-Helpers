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

if(!function_exists('from_key_or_model'))
{
    /**
    * @template TType
    * @param string|int|\Illuminate\Database\Eloquent\Model $value
    * @param class-string<TType> $type
    * @throws \Illuminate\Database\Eloquent\ModelNotFoundException 
    * @return \Illuminate\Database\Eloquent\Model|TType
    */
   function from_key_or_model(string|int|\Illuminate\Database\Eloquent\Model $value, string $type)
   {
       if(is_object($value) && is_a($value, $type)){
           return $value;
       }
       return $type::whereKey($value)->firstOrFail();
   }
}
