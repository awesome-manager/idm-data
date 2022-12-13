<?php

namespace Database\Seeders;

use App\Models\AccessGroup;
use Illuminate\Database\Seeder;

class AccessGroupsSeeder extends Seeder
{
    private array $data = [
        [
            'code' => 'employees',
            'has_filter' => false,
            'is_active' => true
        ],
        [
            'code' => 'main',
            'has_filter' => false,
            'is_active' => true
        ],
        [
            'code' => 'projects',
            'has_filter' => true,
            'is_active' => true
        ],
    ];

    public function run(): void
    {
        foreach ($this->data as $el) {
            if (!AccessGroup::query()->where('code', $el['code'])->exists()) {
                AccessGroup::query()->create($el);
            }
        }
    }
}
