<?php

namespace App\IdmData\Repositories;

use App\IdmData\Contracts\Repositories;
use App\IdmData\Contracts\Repositories\Repository as RepositoryContract;

class Repository implements RepositoryContract
{
    public function user(): Repositories\UserRepository
    {
        return app(Repositories\UserRepository::class);
    }
}
