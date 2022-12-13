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

    public function bindRoleFilters(Model $model): Model
    {
        return $model->load([
            'roleFilters:id,user_role_id,entity_type,entity_id'
        ]);
    }

    public function bindAccessGroupPages(Model $model): Model
    {
        return $model->load([
            'roles:id,name,code',
            'roles.accessGroups:id,code,has_filter',
            'roles.accessGroups.accessGroupPages:id,access_group_code,site_page_code'
        ]);
    }

    private function getUserQuery(): Builder
    {
        return $this->getModel()->newQuery()
            ->select(['id', 'name', 'surname', 'second_name', 'phone', 'email'])
            ->where('is_active', true);
    }
}
