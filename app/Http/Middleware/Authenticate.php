<?php

namespace App\Http\Middleware;

use Closure;
use App\Exceptions\UserRequestErrorException;
use App\Exceptions\UserUnauthorizedException;
use Illuminate\Contracts\Auth\Factory as Auth;

class Authenticate
{
    protected $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function handle($request, Closure $next, $guard = null)
    {
        try {
            if ($this->auth->guard($guard)->guest()) {
                throw new UserUnauthorizedException('Unauthorized.');
            }
        } catch (UserRequestErrorException $e) {
            throw $e;
        }

        return $next($request);
    }
}
