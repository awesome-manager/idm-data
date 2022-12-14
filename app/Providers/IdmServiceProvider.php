<?php

namespace App\Providers;

use App\Models;
use App\IdmData\{Contracts, Repositories, Services};
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

class IdmServiceProvider extends ServiceProvider implements DeferrableProvider
{
    public function register()
    {
        $this->registerRepositories();
        $this->registerServices();
    }

    private function registerRepositories()
    {
        $this->app->bind(Contracts\Repositories\UserRepository::class, function () {
            return new Repositories\UserRepository(new Models\User());
        });
    }

    private function registerServices()
    {
        $this->app->bind(Contracts\Services\UserService::class, Services\UserService::class);
    }

    public function provides()
    {
        return [
            Contracts\Repositories\UserRepository::class,

            Contracts\Services\UserService::class,
        ];
    }
}
