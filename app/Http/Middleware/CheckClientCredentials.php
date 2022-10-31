<?php

namespace App\Http\Middleware;

use App\Exceptions\ClientUnauthorizedException;
use Closure;
use Exception;
use Laravel\Passport\Http\Middleware\CheckClientCredentials as BaseMiddleware;

class CheckClientCredentials extends BaseMiddleware
{
    public function handle($request, Closure $next, ...$scopes)
    {
        try {
            $result = parent::handle($request, $next, ...$scopes);
        } catch (Exception $e) {
            throw new ClientUnauthorizedException('Unauthorized.');
        }

        return $result;
    }

}
