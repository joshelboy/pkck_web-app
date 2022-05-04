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
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable();
            $table->string('strava_id')->nullable();
            $table->string('strava_access_token')->nullable();
            $table->string('strava_refresh_token')->nullable();
            $table->string('strava_expires_at')->nullable();
            
            //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('strava_name');
            $table->dropColumn('strava_id');
            $table->dropColumn('strava_access_token');
            $table->dropColumn('strava_refresh_token');
            $table->dropColumn('strava_expires_at');
        });
    }
};
