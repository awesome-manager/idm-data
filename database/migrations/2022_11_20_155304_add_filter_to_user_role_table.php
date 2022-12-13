<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_role', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->boolean('full_access')->default(false);
        });

        Schema::create('user_role_filters', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_role_id');
            $table->string('entity_type', 100)->nullable();
            $table->uuid('entity_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_role', function (Blueprint $table) {
            $table->dropColumn(['id', 'full_access']);
        });

        Schema::dropIfExists('user_role_filters');
    }
};
