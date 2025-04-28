<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            // PK customizado
            $table->bigIncrements('usr_id');

            // campos do model
            $table->string('usr_email')->unique();
            $table->string('usr_password');
            $table->string('usr_level');
            $table->integer('usr_super');

            // foreign keys
            $table->unsignedBigInteger('persons_pes_id');
            $table->unsignedBigInteger('profiles_prf_id');

            // timestamps customizados
            $table->timestamp('usr_created_at')->nullable();
            $table->timestamp('usr_updated_at')->nullable();

            // constraints
            $table->foreign('persons_pes_id')
                  ->references('pes_id')
                  ->on('persons')
                  ->onDelete('cascade');

            $table->foreign('profiles_prf_id')
                  ->references('prf_id')
                  ->on('profiles')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
};
