<?php
// database/migrations/xxxx_xx_xx_create_judges_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('judges', function (Blueprint $table) {
            $table->bigIncrements('jdg_id');
            $table->string('jdg_name');
            $table->text('jdg_obs')->nullable();
            $table->unsignedBigInteger('event_evt_id');
            $table->timestamp('jdg_created_at')->nullable();
            $table->timestamp('jdg_updated_at')->nullable();

            $table->foreign('event_evt_id')
                  ->references('evt_id')
                  ->on('events')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('judges');
    }
};
