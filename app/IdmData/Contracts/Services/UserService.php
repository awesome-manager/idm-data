<?php

namespace App\IdmData\Contracts\Services;

use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\File\File;

interface UserService
{
    public function updateUserInfo(string $id, array $parameters): bool;

    public function createUserImage(string $id, File $file): ?Model;
}
