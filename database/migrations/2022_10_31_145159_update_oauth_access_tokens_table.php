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
        if (Schema::hasTable('oauth_access_tokens')) {
            Schema::table('oauth_access_tokens', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });

            Schema::table('oauth_access_tokens', function (Blueprint $table) {
                $table->uuid('user_id')->nullable()->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('oauth_access_tokens')) {
            Schema::table('oauth_access_tokens', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });

            Schema::table('oauth_access_tokens', function (Blueprint $table) {
                $table->unsignedBigInteger('user_id')->nullable()->index();
            });
        }
    }
};
