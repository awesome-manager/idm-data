<?php

namespace App\IdmData\Repositories;

use App\IdmData\Contracts\Repositories\UserRepository as RepositoryContract;
use Illuminate\Database\Eloquent\Model;

class UserRepository extends AbstractRepository implements RepositoryContract
{
    public function getById(string $id): ?Model
    {
        return $this->getModel()->newQuery()
            ->where('is_active', true)
            ->with('roles:id,code,name')
            ->find($id, ['id', 'name', 'surname', 'second_name', 'phone', 'email']);
    }

    public function getByPhone(string $phone): ?Model
    {
        return $this->getModel()->newQuery()
            ->select(['id', 'name', 'surname', 'second_name', 'phone', 'email'])
            ->where('is_active', true)
            ->where('phone', $phone)
            ->with('roles:id,code,name')
            ->first();
    }

    public function getByEmail(string $email): ?Model
    {
        return $this->getModel()->newQuery()
            ->select(['id', 'name', 'surname', 'second_name', 'phone', 'email'])
            ->where('is_active', true)
            ->where('email', $email)
            ->with('roles:id,code,name')
            ->first();
    }
}
