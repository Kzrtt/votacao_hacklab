<?php
// database/migrations/xxxx_xx_xx_create_events_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->bigIncrements('evt_id');
            $table->string('evt_name');
            $table->text('evt_description')->nullable();
            $table->timestamp('evt_created_at')->nullable();
            $table->timestamp('evt_updated_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
