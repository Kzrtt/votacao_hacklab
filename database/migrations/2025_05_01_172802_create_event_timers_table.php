<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('event_timers', function (Blueprint $table) {
            $table->bigIncrements('etm_id');
            $table->timestamp('etm_started_at')->nullable();
            $table->time('etm_duration')->nullable(); // duração alvo HH:MM:SS
            $table->timestamps('etm_created_at');
            $table->timestamps('etm_updated_at');
            $table->unsignedBigInteger('event_evt_id');
            $table->foreign('event_evt_id')
                  ->references('evt_id')
                  ->on('events');
        });
    }

    public function down()
    {
        Schema::dropIfExists('event_timers');
    }
};
