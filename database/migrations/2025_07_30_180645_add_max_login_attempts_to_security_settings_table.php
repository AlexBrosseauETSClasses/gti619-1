<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxLoginAttemptsToSecuritySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('security_settings', function (Blueprint $table) {
        $table->integer('max_login_attempts')->default(5);
    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('security_settings', function (Blueprint $table) {
        $table->dropColumn('max_login_attempts');
    });
    }
}
