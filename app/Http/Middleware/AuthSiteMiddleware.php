<?php
namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Contracts\Auth\Guard;

class AuthSiteMiddleware
{

    protected $auth;

    /**
     * Create a new filter instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param \Illuminate\Http\Request $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login');
        }
    }

    public function handle($request, Closure $next)
    {
        if ($this->auth->guest()) {
            return redirect()->guest('login');
        }

        return $next($request);
    }

}
