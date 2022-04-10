<?php
namespace CustomD\LaravelHelpers\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;

class UserTimeZone
{

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
     * @param \Illuminate\Http\Request $request
     */
    public function setTimeZone(Request $request): void
    {
        if ($request->user() && $request->user()->getAttribute('timezone') !== null) {
            Config::set('request.user.timezone', $request->user()->getAttribute('timezone'));
            return;
        }

        Config::set(
            'request.user.timezone',
            $request->header(
                'x-timezone',
                strval(Config::get('app.user_timezone', Config::get('app.timezone')))
            )
        );
    }
}
