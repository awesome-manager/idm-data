<?php

namespace App\Providers;

use App\IdmData\Passport\Guards\TokenGuard;
use App\IdmData\Passport\UserProviders\IdmUserProvider;
use Illuminate\Auth\{AuthManager, RequestGuard};
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\{ClientRepository, Passport, PassportUserProvider, TokenRepository};
use League\OAuth2\Server\ResourceServer;

class AuthServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Passport::tokensCan([
            'private' => 'Authorize microservice',
            'user' => 'User service',
        ]);

        $this->registerGuard();
        $this->registerUserProvider();
    }

    private function registerGuard()
    {
        Auth::resolved(function (AuthManager $auth) {
            $auth->extend('idm', function ($app, $name, array $config) {
                return tap($this->makeGuard($config), function ($guard) {
                    $this->app->refresh('request', $guard, 'setRequest');
                });
            });
        });
    }

    private function registerUserProvider()
    {
        Auth::resolved(function (AuthManager $auth) {
            $auth->provider('idm_users', function () {
                return new IdmUserProvider();
            });
        });
    }

    private function makeGuard(array $config): RequestGuard
    {
        return new RequestGuard(function ($request) use ($config) {
            return (new TokenGuard(
                $this->app->make(ResourceServer::class),
                new PassportUserProvider(Auth::createUserProvider($config['provider']), $config['provider']),
                $this->app->make(TokenRepository::class),
                $this->app->make(ClientRepository::class),
                $this->app->make('encrypter'),
                $request
            ))->user();
        }, $this->app['request']);
    }
}
