<?php

namespace App\IdmData\Passport\UserProviders;

use App\IdmData\Contracts\Repositories\UserRepository;
use Illuminate\Contracts\Auth\{Authenticatable, UserProvider};
use Illuminate\Database\Eloquent\Model;

class IdmUserProvider implements UserProvider
{
    public function retrieveById($identifier): ?Model
    {
        return app(UserRepository::class)->getById($identifier);
    }

    public function retrieveByCredentials(array $credentials): ?Model
    {
        if (isset($credentials['phone'])) {
            return app(UserRepository::class)->getByPhone($credentials['phone']);
        }

        if (isset($credentials['email'])) {
            return app(UserRepository::class)->getByEmail($credentials['email']);
        }

        return null;
    }

    public function retrieveByToken($identifier, $token)
    {
        // TODO: Implement retrieveByToken() method.
    }

    public function updateRememberToken(Authenticatable $user, $token)
    {
        // TODO: Implement updateRememberToken() method.
    }

    public function validateCredentials(Authenticatable $user, array $credentials)
    {
        // TODO: Implement validateCredentials() method.
    }
}
