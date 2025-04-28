<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('profile_permissions', function (Blueprint $table) {
            // PK customizado
            $table->bigIncrements('prp_id');

            // campos do model
            $table->string('prp_area');
            $table->string('prp_action');

            // foreign key para profiles.prf_id
            $table->unsignedBigInteger('profiles_prf_id');
            $table->foreign('profiles_prf_id')
                  ->references('prf_id')
                  ->on('profiles')
                  ->onDelete('cascade');

            // sem timestamps, pois $timestamps = false
        });
    }

    public function down()
    {
        Schema::dropIfExists('profile_permissions');
    }
};
