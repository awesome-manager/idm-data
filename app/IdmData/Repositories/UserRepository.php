<?php

namespace App\IdmData\Repositories;

use App\IdmData\Contracts\Repositories\UserRepository as RepositoryContract;
use Awesome\Foundation\Repositories\AbstractRepository;
use Illuminate\Database\Eloquent\{Builder, Model};

class UserRepository extends AbstractRepository implements RepositoryContract
{
    public function getById(string $id): ?Model
    {
        return $this->getUserQuery()->find($id);
    }

    public function getByPhone(string $phone): ?Model
    {
        return $this->getUserQuery()
            ->where('phone', $phone)
            ->first();
    }

    public function getByEmail(string $email): ?Model
    {
        return $this->getUserQuery()
            ->where('email', $email)
            ->first();
    }

    public function bindAccessGroupPages(Model $model): Model
    {
        return $model->load('roles.accessGroups.accessGroupPages:id,access_group_code,site_page_code');
    }

    private function getUserQuery(): Builder
    {
        return $this->getModel()->newQuery()
            ->select(['id', 'name', 'surname', 'second_name', 'phone', 'email'])
            ->where('is_active', true);
    }
}
