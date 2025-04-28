<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('action_logs', function (Blueprint $table) {
            // PK customizado
            $table->bigIncrements('alg_id');

            // campos do model
            $table->string('alg_model');
            $table->string('alg_action');
            $table->text('alg_description')->nullable();
            $table->text('alg_object')->nullable();

            // data e hora separados
            $table->date('alg_date');
            $table->time('alg_time');

            // referência ao registro afetado
            $table->unsignedBigInteger('alg_model_id')->nullable();

            // FK para users
            $table->unsignedBigInteger('users_usr_id');
            $table->foreign('users_usr_id')
                  ->references('usr_id')
                  ->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('action_logs');
    }
};
