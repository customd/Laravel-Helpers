<?php
namespace CustomD\LaravelHelpers\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Guard;

class UserTimeZone {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $this->setTimeZone($request);
        return $next($request);
    }

    /**
     * sets the time zone from request or from the user setting
     * @param Illuminate\Http\Request $request
     */
    public function setTimeZone(Request $request): void
    {
        if($this->user && $this->user->getAttribute('timezone') !== null)
        {
            Config::set('request.user.timezone', $this->user->getAttribute('timezone'));
            return;
        }

        Config::set(
            'request.user.timezone',
            $request->header(
                'x-timezone',
                Config::get('app.user_timezone', Config::get('app.timezone'))
            )
        );

    }
}
