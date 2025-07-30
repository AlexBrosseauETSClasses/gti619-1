<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSecuritySettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('security_settings', function (Blueprint $table) {
        $table->id();
        $table->integer('min_password_length')->default(8);
        $table->boolean('require_uppercase')->default(true);
        $table->boolean('require_numbers')->default(true);
        $table->boolean('require_special_chars')->default(true);
        $table->integer('password_history_count')->default(3); // ne pas réutiliser les 3 derniers
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
        Schema::dropIfExists('security_settings');
    }
}
