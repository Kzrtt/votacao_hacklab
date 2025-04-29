<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('judges', function (Blueprint $table) {
            $table->string('jdg_email')->unique()->after('jdg_obs');
            $table->string('jdg_password')->after('jdg_email');
        });
    }

    public function down()
    {
        Schema::table('judges', function (Blueprint $table) {
            $table->dropColumn(['jdg_email', 'jdg_password']);
        });
    }
};
