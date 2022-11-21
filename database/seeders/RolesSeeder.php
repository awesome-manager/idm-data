<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesSeeder extends Seeder
{
    private array $data = [
        [
            'name' => 'Администратор',
            'code' => 'admin'
        ],
        [
            'name' => 'Менеджер',
            'code' => 'manager'
        ]
    ];

    public function run(): void
    {
        foreach ($this->data as $el) {
            if (Role::query()->where('code', $el['code'])->doesntExist()) {
                Role::query()->create($el);
            }
        }
    }
}
