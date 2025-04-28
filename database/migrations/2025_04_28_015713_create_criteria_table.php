<?php
// database/migrations/xxxx_xx_xx_create_criteria_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->bigIncrements('crt_id');
            $table->unsignedBigInteger('event_evt_id');
            $table->string('crt_name');
            $table->integer('crt_weight')->default(1);
            $table->timestamp('crt_created_at')->nullable();
            $table->timestamp('crt_updated_at')->nullable();

            $table->foreign('event_evt_id')
                  ->references('evt_id')
                  ->on('events')
                  ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('criteria');
    }
};
