<?php

namespace App\Http\Resources\User;

use Awesome\Foundation\Traits\Resources\Resourceable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Collection;

class UserResource extends JsonResource
{
    use Resourceable;

    public Collection $userRoleFilter;

    public function __construct($resource)
    {
        $this->userRoleFilter = $resource->roleFilters->groupBy('user_role_id');

        parent::__construct($resource);
    }

    public function toArray($request = null): array
    {
        return [
            'id' => $this->string($this->resource->id),
            'name' => $this->string($this->resource->name),
            'surname' => $this->string($this->resource->surname),
            'second_name' => $this->string($this->resource->second_name),
            'phone' => $this->string($this->resource->phone),
            'email' => $this->string($this->resource->email),
            'roles' => $this->resource->roles?->map(fn($role) => $this->prepareRole($role))->all(),
        ];
    }

    private function prepareRole(Model $role): array
    {
        return [
            'id' => $this->string($role->id),
            'name' => $this->string($role->name),
            'code' => $this->string($role->code),
            'filters' => $this->prepareFilter($role->pivot->id),
            'access_groups' => $role->accessGroups->map(fn($accessGroup) => $this->prepareAccessGroup($accessGroup))
        ];
    }

    private function prepareFilter(string $userRoleId): array
    {
        if (is_null($filter = $this->userRoleFilter->get($userRoleId))) {
            return [];
        }

        return $filter->groupBy('entity_type')->map(fn($entityFilter, $entityType) => [
            'entity_type' => $this->string($entityType),
            'entity_ids' => $entityFilter->pluck('entity_id')->all()
        ])->values()->all();
    }

    private function prepareAccessGroup(Model $accessGroup): array
    {
        return [
            'id' => $this->string($accessGroup->id),
            'code' => $this->string($accessGroup->code),
            'has_filter' => $this->bool($accessGroup->has_filter),
            'pages' => $accessGroup->accessGroupPages->pluck('site_page_code')->all()
        ];
    }
}
