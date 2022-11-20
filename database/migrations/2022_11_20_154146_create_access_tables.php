<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    private array $tables = [
        'access_groups',
        'access_group_page',
        'role_access_group'
    ];

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        foreach ($this->tables as $table) {
            if (Schema::hasTable($table)) {
                continue;
            }

            if (method_exists($this, $method = Str::camel("up_{$table}"))) {
                $this->{$method}();
            }
        }
    }

    private function upAccessGroups(): void
    {
        Schema::create('access_groups', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('code', 100)->unique();
            $table->boolean('has_filter')->default(false);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    private function upAccessGroupPage(): void
    {
        Schema::create('access_group_page', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('access_group_code', 100);
            $table->string('site_page_code', 100);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->unique(['access_group_code', 'site_page_code']);
        });
    }

    private function upRoleAccessGroup(): void
    {
        Schema::create('role_access_group', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('role_code', 100);
            $table->string('access_group_code', 100);

            $table->unique(['role_code', 'access_group_code']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        foreach ($this->tables as $table) {
            Schema::dropIfExists($table);
        }
    }
};
