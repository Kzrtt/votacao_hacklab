<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            // PK customizado
            $table->bigIncrements('usp_id');

            // campos do model
            $table->string('usp_area');
            $table->string('usp_action');

            // foreign key para users.usr_id
            $table->unsignedBigInteger('users_usr_id');
            $table->foreign('users_usr_id')
                  ->references('usr_id')
                  ->on('users')
                  ->onDelete('cascade');

            // sem timestamps, pois $timestamps = false
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
};
