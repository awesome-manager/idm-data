<?php

namespace Database\Seeders;

use App\Models\{AccessGroup, AccessGroupPage};
use Illuminate\Database\Seeder;

class AccessGroupPageSeeder extends Seeder
{
    private array $data = [
        [
            'access_group_code' => 'employees',
            'site_page_code' => 'vacations',
            'is_active' => true
        ],
        [
            'access_group_code' => 'employees',
            'site_page_code' => 'employees',
            'is_active' => true
        ],
        [
            'access_group_code' => 'main',
            'site_page_code' => 'main',
            'is_active' => true
        ],
        [
            'access_group_code' => 'projects',
            'site_page_code' => 'projects',
            'is_active' => true
        ],
        [
            'access_group_code' => 'projects',
            'site_page_code' => 'add_project',
            'is_active' => true
        ],
        [
            'access_group_code' => 'projects',
            'site_page_code' => 'gantt',
            'is_active' => true
        ],
    ];

    public function run(): void
    {
        foreach ($this->data as $el) {
            if (
                AccessGroup::query()->where('code', $el['access_group_code'])->exists() &&
                AccessGroupPage::query()
                    ->where('access_group_code', $el['access_group_code'])
                    ->where('site_page_code', $el['site_page_code'])
                    ->doesntExist()
            ) {
                AccessGroupPage::query()->create($el);
            }
        }
    }
}
