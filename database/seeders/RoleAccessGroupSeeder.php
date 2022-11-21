<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleAccessGroupSeeder extends Seeder
{
    private array $data = [
        [
            'role_code' => 'admin',
            'access_group_code' => 'employees',
        ],
        [
            'role_code' => 'admin',
            'access_group_code' => 'main',
        ],
        [
            'role_code' => 'admin',
            'access_group_code' => 'projects',
        ],
        [
            'role_code' => 'manager',
            'access_group_code' => 'main',
        ],
        [
            'role_code' => 'manager',
            'access_group_code' => 'projects',
        ],
    ];

    public function run(): void
    {
        collect($this->data)->groupBy('role_code')->each(function ($groupedRoleAccessGroups, $roleCode) {
            if (!is_null($role = Role::query()->where('code', $roleCode)->first())) {
                $role->accessGroups()->sync($groupedRoleAccessGroups->pluck('access_group_code')->all());
            }
        });
    }
}
