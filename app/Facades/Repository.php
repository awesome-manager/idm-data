<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\IdmData\Contracts\Repositories;
use App\IdmData\Contracts\Repositories\Repository as RepositoryContract;

/**
 * @method static Repositories\UserRepository user()
 */
class Repository extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return RepositoryContract::class;
    }
}
