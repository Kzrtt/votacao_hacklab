<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCascadeToEventTimersFk extends Migration
{
    public function up()
    {
        Schema::table('event_timers', function (Blueprint $table) {
            // 1) solta a FK antiga
            $table->dropForeign(['event_evt_id']);
            // 2) cria novamente COM cascade
            $table
                ->foreign('event_evt_id')
                ->references('evt_id')
                ->on('events')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('event_timers', function (Blueprint $table) {
            $table->dropForeign(['event_evt_id']);
            $table
                ->foreign('event_evt_id')
                ->references('evt_id')
                ->on('events');
        });
    }
}
