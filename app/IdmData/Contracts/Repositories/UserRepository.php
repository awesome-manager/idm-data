<?php

namespace App\IdmData\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;

interface UserRepository
{
    public function getById(string $id): ?Model;

    public function getByPhone(string $phone): ?Model;

    public function getByEmail(string $email): ?Model;

    public function bindAccessGroupPages(Model $model): Model;
}
