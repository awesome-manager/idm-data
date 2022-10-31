<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $tables = [
        'users',
        'roles',
        'user_role'
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

    private function upUsers()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('surname', 100);
            $table->string('second_name', 100)->nullable();
            $table->string('phone', 20)->unique();
            $table->string('email', 100)->nullable();
            $table->string('password', 255);
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });
    }

    private function upRoles()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name', 100);
            $table->string('code', 100)->unique();
            $table->timestamps();
        });
    }

    private function upUserRole()
    {
        Schema::create('user_role', function (Blueprint $table) {
            $table->uuid('user_id');
            $table->uuid('role_id');

            $table->unique(['user_id', 'role_id']);
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
