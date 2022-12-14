<?php

namespace App\IdmData\Passport\UserProviders;

use App\Facades\Repository;
use Illuminate\Contracts\Auth\{Authenticatable, UserProvider};
use Illuminate\Database\Eloquent\Model;

class IdmUserProvider implements UserProvider
{
    public function retrieveById($identifier): ?Model
    {
        return $this->bindAccess(Repository::user()->getById($identifier));
    }

    public function retrieveByCredentials(array $credentials): ?Model
    {
        if (isset($credentials['phone'])) {
            return $this->bindAccess(Repository::user()->getByPhone($credentials['phone']));
        }

        if (isset($credentials['email'])) {
            return $this->bindAccess(Repository::user()->getByEmail($credentials['email']));
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

    private function bindAccess(?Model $user): ?Model
    {
        if (is_null($user)) {
            return null;
        }

        return Repository::user()->bindAccessGroupPages(Repository::user()->bindRoleFilters($user));
    }
}
